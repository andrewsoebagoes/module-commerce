<?php

use Core\Page;
use Core\Database;
use Core\Request;
use Modules\Crud\Libraries\Repositories\CrudRepository;
$tableName  = 'invoices';
$table      = tableFields($tableName);
$fields     = $table->getFields();
$module     = $table->getModule();
$title      = _ucwords(__("$module.label.$tableName"));
$error_msg  = get_flash_msg('error');
$old        = get_flash_msg('old');

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
    WHERE products.status = 'PUBLISH'";

$products = $db->exec('all');

$db->query = "SELECT users.* FROM users JOIN user_roles ON user_roles.user_id = users.id AND user_roles.role_id = " . env('CUSTOMER_ROLE_ID')  . " ";
$customer  = $db->exec('all');

if (Request::isMethod('POST')) {

    extract($_POST);

    $is_customer = get_role(auth()->id)->role_id == env('CUSTOMER_ROLE_ID');
    $organizationUser = $db->single('organization_users', ['user_id' => auth()->id]);
    $organizationId = $organizationUser ? $organizationUser->organization_id : 1;

    if ($is_customer) {
        $ongkir   = explode('-', $ongkir);
        $provinsiTujuan = explode('-', $provinsiTujuan);
        $kabupatenTujuan = explode('-', $kabupatenTujuan);

        $user_id = $customer_user_id;
    }

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
        'organization_id'   => $organizationId
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
            products.id,
            products.item_id id_product,
            products.price,
            products.sku,
            inventory_items.name AS product_name
            FROM products
            JOIN inventory_items ON inventory_items.id = products.item_id
            WHERE products.item_id = {$id_product[$i]}";

        $product = $db->exec('single');
        
        $invoice_items = $db->insert('invoice_items', [
            'invoice_id'            => $lastInvoiceId,
            'item_id'               => $product->id,
            'item_type'             => 'products',
            'discount_id'           => 0,
            'item_snapshot'         => json_encode($product),
            'discount_snapshot'     => "[]",
            'quantity'              => $jumlah_barang[$i],
            'item_price'            => $final_price[$i],
            'discount_price'        => 0,
            'total_price'           => $total_barang[$i]
        ]);

        $db->insert('inventory_item_logs', [
            'item_id' => $product->id_product,
            'amount'  => $jumlah_barang[$i],
            'organization_src_id' => $organizationId,
            'organization_dst_id' => env('CUSTOMER_ORGANIZATION_ID'),
        ]);

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
    header('Location: '. routeTo('crud/index', ['table' => 'invoices']));
    die();
}

// page section
$title = 'Transaction';
Page::setActive("$module.$tableName");
Page::setTitle('Transaction');
Page::setModuleName('Transaction');
Page::setBreadcrumbs([
    [
        'url' => routeTo('/'),
        'title' => __('crud.label.home')
    ],
    [
        'url' => routeTo('crud/index', ['table' => $tableName]),
        'title' => $title
    ],
    [
        'title' => 'Index'
    ]
]);




Page::pushHead('<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />');

Page::pushHead('<style>.select2,.select2-selection{height:38px!important;} .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:38px!important;}.select2-selection__arrow{height:34px!important;}</style>');
Page::pushFoot('<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>');
Page::pushHead("<link rel='stylesheet' href='" . asset('assets/commerce/css/style.css') . "'>");
Page::pushFoot("<script src='" . asset('assets/crud/js/crud.js') . "'></script>");
Page::pushFoot("<script src='" . asset('assets/commerce/js/script.js') . "'></script>");
Page::pushFoot("<script src='" . asset('assets/commerce/js/sweetalert.min.js') . "'></script>");
Page::pushFoot("<script src='" . asset('assets/commerce/js/jquery.form-validator.min.js') . "'></script>");


// Page::pushHook();

return view('commerce/views/transaction', compact('success_msg', 'error_msg', 'products', 'transaction_success', 'customer'));
