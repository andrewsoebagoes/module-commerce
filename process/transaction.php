<?php

use Core\Page;
use Core\Database;
use Core\Request;

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
    products.id id_product,
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
    $cartData = json_decode($cartData);

    // echo '<pre>';
    // print_r($_POST);
    // print_r($cartData);
    // die();

    $is_customer = get_role(auth()->id)->role_id == env('CUSTOMER_ROLE_ID');
    $organizationUser = $db->single('organization_users', ['user_id' => auth()->id]);
    $organizationId = $organizationUser ? $organizationUser->organization_id : 1;


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
            'role_id'   => env('CUSTOMER_ROLE_ID')
        ]);
    }
    $kode_transaksi = "T". date('dmYHis');

    $dataInvoices = [
        'code'              => $kode_transaksi,
        'user_id'           => $user_id,
        'status'            => !$is_customer ? 'Finished' : (isset($preorder) ? 'Pre Order' :  'Waiting for Payment'),
        'notes'             => '',
        'total_amount'      => $totalProduk,
        'payment_receive'   => $bayar,
        'payment_return'    => $bayar - $totalProduk,
        'created_at'        => date('Y-m-d H:i:s'),
        'created_by'        => auth()->id,
        'organization_id'   => $organizationId
    ];

    $data = $db->insert('invoices', $dataInvoices);
    $lastInvoiceId = $data->id;

    foreach ($cartData as $item) {
        $productId = $item->productId;
        $quantity = $item->quantity;

        // Dapatkan data produk dari database
        $db->query = "SELECT
        products.id AS id_product,
        products.item_id,
        products.price,
        products.sku,
        inventory_items.name AS product_name
        FROM products
        JOIN inventory_items ON inventory_items.id = products.item_id
        WHERE products.id = {$productId}";
        $product = $db->exec('single');

        // Hitung harga total barang
        $itemPrice = $item->final_price;
        $totalBarang = $itemPrice * $quantity;

        // Masukkan data ke dalam tabel invoice_items
        $invoice_items = $db->insert('invoice_items', [
            'invoice_id'            => $lastInvoiceId,
            'item_id'               => $productId,
            'item_type'             => 'products',
            'discount_id'           => 0,
            'item_snapshot'         => json_encode($product),
            'discount_snapshot'     => "[]",
            'quantity'              => $quantity,
            'item_price'            => $itemPrice,
            'discount_price'        => 0,
            'total_price'           => $totalBarang
        ]);

        $db->insert('inventory_item_logs', [
            'item_id' => $product->item_id,
            'amount'  => $item->quantity,
            'organization_src_id' => $organizationId,
            'organization_dst_id' => env('CUSTOMER_ORGANIZATION_ID'),
        ]);
    }

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
Page::pushFoot("<script src='" . asset('assets/commerce/js/sweetalert.min.js') . "'></script>");
Page::pushFoot("<script src='" . asset('assets/commerce/js/jquery.form-validator.min.js') . "'></script>");


// Page::pushHook();

return view('commerce/views/transaction2', compact('success_msg', 'error_msg', 'products', 'transaction_success', 'customer'));
