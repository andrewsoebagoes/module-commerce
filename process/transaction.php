<?php

use Core\Page;
use Core\Database;
use Core\Request;
use Modules\Crud\Libraries\Repositories\CrudRepository;

$success_msg = get_flash_msg('success');
$transaction_success = get_flash_msg('transaction_success');
$error_msg   = get_flash_msg('error');

// get products
$db = new Database;

$db->query  = "SELECT
    products.item_id id_product,
    products.price,
    products.sku,
    inventory_items.name AS product_name
    FROM products
    JOIN inventory_items ON inventory_items.id = products.item_id
    WHERE products.status = 'Ada'
    AND products.sku > 0";

$products = $db->exec('all');

$db->query = "SELECT users.* FROM users JOIN user_roles ON user_roles.user_id = users.id AND user_roles.role_id = " . env('CUSTOMER_ROLE_ID')  . " ";
$customer  = $db->exec('all');

if (Request::isMethod('POST')) {

    extract($_POST);

    $is_customer = get_role(auth()->id)->role_id == env('CUSTOMER_ROLE_ID');

    if ($is_customer) {
        $ongkir   = explode('-', $ongkir);
        $provinsiTujuan = explode('-', $provinsiTujuan);
        $kabupatenTujuan = explode('-', $kabupatenTujuan);

        $user_id = $customer_user_id;
    }

    // echo '<pre>';
    // print_r($_POST);
    // die();

    if ($user_id == 0) {
        $username = strtolower($new_customer_name);
        $username = str_replace(' ', '', $username);

        $dataUser = $db->insert('users', [
            'name'      => $new_customer_name,
            'username'  => $username,
            'password'  => md5('123')
        ]);

        $user_id = $dataUser->id;

        $db->insert('user_roles', [
            'user_id'   => $dataUser->id,
            'role_id'   => 3
        ]);
    }

    $dataInvoices = [
        'code'              => $kode_transaksi,
        'user_id'           => $user_id ?? $customer_user_id,
        'status'            => !$is_customer ? 'Finished' : (isset($preorder) ? 'Pre Order' :  'Waiting for Payment'),
        'notes'             => '',
        'total_amount'      => isset($ongkir) ? $total + $ongkir[1] : $total,
        'payment_receive'   => isset($bayar) ? $bayar : 0,
        'payment_return'    => isset($bayar) ? $bayar - $total : 0,
        'created_at'        => date('Y-m-d H:i:s'),
        'created_by'        => auth()->id,
        // 'organization_id'   => 1
    ];

    $data = $db->insert('invoices', $dataInvoices);
    $lastInvoiceId = $data->id;

    if ($is_customer) {
        $dataShippings = [
            'invoice_id'    => $lastInvoiceId,
            'country'       => 'Indonesia',
            'province'      => $provinsiTujuan[1],
            'city'          => $kabupatenTujuan[1],
            'address'       => $address,
            'courier'       => $ekspedisi,
            'notes'         => $notes
        ];
        $shippings = $db->insert('shippings', $dataShippings);
    }

    $jml_barang = count($id_product);
    $grand_total = 0;
    for ($i = 0; $i < $jml_barang; $i++) {

        $db->query  = "SELECT
            products.item_id id_product,
            products.price,
            products.sku,
            inventory_items.name AS product_name
            FROM products
            JOIN inventory_items ON inventory_items.id = products.item_id
            WHERE products.item_id = {$id_product[$i]}";

        $product = $db->exec('single');

        $db->query  = "SELECT
                            discounts.id AS discount_id,
                            discounts.code AS discount_code,
                            discounts.name AS discount_name,
                            discounts.discount_type,
                            discounts.discount_value,
                            discounts.record_type,
                            discounts.status AS discount_status,
                            product_discount.status AS product_discount_status,
                            discount_applicables.user_id,
                            users.id AS user_id
                        FROM
                            product_discount
                            LEFT JOIN discounts ON product_discount.discount_id = discounts.id
                            LEFT JOIN discount_applicables ON discounts.id = discount_applicables.discount_id
                            LEFT JOIN users ON discount_applicables.user_id = users.id
                        WHERE
                            product_discount.product_id = {$id_product[$i]}
                            AND product_discount.status = 'PUBLISH'
                            AND discounts.status = 'Aktif'
                            AND discount_applicables.user_id = $user_id;
                        ";

        $discount = $db->exec('single');
        // echo '<pre>';
        // print_r($discount);
        // die();

        $discount_value = 0;

        if ($discount && $discount->product_discount_status == 'PUBLISH') {
            $discount_value += $discount->discount_value;
        }
        
        if ($discount && $discount->user_id == $user_id) {
            $discount_value += $discount->discount_value;
        }

        $total_price = $total_barang[$i] - $discount_value;
        
        $invoice_items = $db->insert('invoice_items', [
            'invoice_id'            => $lastInvoiceId,
            'item_id'               => $id_product[$i],
            'item_type'             => 'products',
            'discount_id'           => $discount ? $discount->discount_id : 0,
            'item_snapshot'         => json_encode($product),
            'discount_snapshot'     => json_encode($discount),
            'quantity'              => $jumlah_barang[$i],
            'item_price'            => $price[$i],
            'discount_price'        => $discount_value,
            'total_price'           => $total_price
        ]);

        $db->query = ("UPDATE products
                    SET sku = $product->sku - $jumlah_barang[$i]
                    WHERE products.item_id = {$id_product[$i]}
                    ");
        $db->exec();

        $grand_total += $invoice_items->total_price;
    }

    if ($is_customer) {
        $dataShippings['price'] = $ongkir[1];
        $db->insert('invoice_items', [
            'invoice_id'    => $lastInvoiceId,
            'quantity'  => 1,
            'item_type' => 'shippings',
            'item_id'   => $shippings->id,
            'item_snapshot' => json_encode($dataShippings),
            'item_price'    => $ongkir[1],
            'discount_price' => 0,
            'total_price'   => $ongkir[1]
        ]);

        $grand_total += $ongkir[1];
    }



    $db->update('invoices', [
        'total_amount' => $grand_total,
    ], ['id' => $lastInvoiceId]);



    // Set flash message
    set_flash_msg(['success' => "Transaksi berhasil ditambahkan"]);


    header('Location: /commerce/data-transaction');

    die();
}




Page::pushHead('<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />');
Page::pushHead("<script>
tinymce.init({
  selector: 'textarea:not(.select2-search__field)',
  relative_urls : false,
  remove_script_host : false,
  convert_urls : true,
  plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
  toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
});
</script>");

Page::pushHead('<style>.select2,.select2-selection{height:38px!important;} .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:38px!important;}.select2-selection__arrow{height:34px!important;}</style>');
Page::pushFoot('<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>');
Page::pushHead("<link rel='stylesheet' href='" . asset('assets/commerce/css/style.css') . "'>");
Page::pushFoot("<script src='" . asset('assets/crud/js/crud.js') . "'></script>");
Page::pushFoot("<script src='" . asset('assets/commerce/js/script.js') . "'></script>");
Page::pushFoot("<script src='" . asset('assets/commerce/js/sweetalert.min.js') . "'></script>");
Page::pushFoot("<script src='" . asset('assets/commerce/js/jquery.form-validator.min.js') . "'></script>");


// Page::pushHook();

return view('commerce/views/transaction', compact('success_msg', 'error_msg', 'products', 'transaction_success', 'customer'));
