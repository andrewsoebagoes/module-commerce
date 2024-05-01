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
    AND products.sku != 0";

$products = $db->exec('all');

$db->query = "SELECT users.* FROM users JOIN user_roles ON user_roles.user_id = users.id AND user_roles.role_id = " . env('CUSTOMER_ROLE_ID')  . " ";
$customer  = $db->exec('all');

if (Request::isMethod('POST')) {

    extract($_POST);
    echo '<pre>';
    print_r($_POST);
    die();

    $username = strtolower($new_customer_name);
    $username = str_replace(' ', '', $username);

    if($user_id == 0){
        $dataUser = $db->insert('users', [
            'name'      => $new_customer_name,
            'username'  => $username,
            'password'  => md5('123')
        ]);

        $db->insert('user_roles', [
            'user_id'   => $dataUser->id,
            'role_id'   => 3
        ]);
    }
    
    $dataInvoices = [
        'code'              => $kode_transaksi,
        'user_id'           => $user_id == 0 ? $dataUser->id : $user_id,
        'status'            => 'Transaksi',
        'notes'             => '',
        'total_amount'      => $total,
        'payment_receive'   => $bayar,
        'payment_return'    => $bayar - $total,
        'created_at'        => date('Y-m-d H:i:s'),
        'created_by'        => auth()->name,
        // 'organization_id'   => 1
    ];

    $data = $db->insert('invoices', $dataInvoices);
    $lastInvoiceId = $data->id;

    $jml_barang = count($id_product);
    for ($i = 0; $i < $jml_barang; $i++) {
        
        $db->query  = "SELECT
            products.id id_product,
            products.price,
            products.sku,
            inventory_items.name AS product_name
            FROM products
            JOIN inventory_items ON inventory_items.id = products.item_id
            WHERE products.id = {$id_product[$i]}";

        $product = $db->exec('single');

        $db->insert('invoice_items', [
            'invoice_id'        => $lastInvoiceId,
            'item_id'           => $id_product[$i],
            'item_type'         => 'products',
            // 'discount_id'       => 0,
            'item_snapshot'     => json_encode($product),
            'discount_snapshot' => '[]',
            'quantity'          => $jumlah_barang[$i],
            'item_price'        => $price[$i],
            'discount_price'    => 0,
            'total_price'       => $total_barang[$i]
        ]);    

        $db->query = ("UPDATE products
                    SET sku = $product->sku - $jumlah_barang[$i]
                    WHERE products.id = {$id_product[$i]}
                    ");
        $db->exec();
    }

    // Set flash message
    set_flash_msg(['success' => "Transaksi berhasil ditambahkan"]);


    header('Location: /commerce/data-transaction');

    die();
}




Page::pushHead('<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />');
Page::pushHead('<script src="https://cdn.tiny.cloud/1/rsb9a1wqmvtlmij61ssaqj3ttq18xdwmyt7jg23sg1ion6kn/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>');
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

return view('commerce/views/transaction-customer', compact('success_msg', 'error_msg', 'products', 'transaction_success', 'customer'));
