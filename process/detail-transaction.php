<?php

use Core\Page;
use Core\Database;
use Core\Request;


use Modules\Crud\Libraries\Repositories\CrudRepository;

// init table fields
$invoice_id     = $_GET['id'];
$_GET['filter'] = ['invoice_id' => $invoice_id];
$tableName      = 'invoice_items';
$table          = tableFields($tableName);
$fields         = $table->getFields();
$module         = $table->getModule();
$success_msg    = get_flash_msg('success');
$error_msg      = get_flash_msg('error');

// get data

$db = new Database;
$db->query  = "SELECT
invoice_items.item_id,
invoice_items.item_type,
invoice_items.discount_id,
invoice_items.quantity,
invoice_items.discount_price,
invoice_items.item_price,
invoice_items.total_price,
inventory_items.id AS inventory_item_id,
inventory_items.name AS item_name,
users.id AS user_id,
users.name AS user_name,
invoices.id AS id_invoice,
invoices.code,
invoices.status,
invoices.user_id,
invoices.total_amount,
invoices.created_at,
invoices.created_by,
media.name AS image,
CASE
    WHEN invoice_items.item_type = 'products' THEN inventory_items.name
    WHEN invoice_items.item_type = 'shippings' THEN shippings.courier
    ELSE NULL
END AS name
FROM invoices
LEFT JOIN invoice_items ON invoices.id = invoice_items.invoice_id
LEFT JOIN products ON invoice_items.item_id = products.id AND invoice_items.item_type = 'products'
LEFT JOIN inventory_items ON products.item_id = inventory_items.id
LEFT JOIN shippings ON invoice_items.invoice_id = shippings.invoice_id AND invoice_items.item_type = 'shippings' 
LEFT JOIN invoice_media ON invoices.id = invoice_media.invoice_id
LEFT JOIN media ON invoice_media.media_id = media.id
LEFT JOIN users ON invoices.user_id = users.id
WHERE invoices.id = $invoice_id
";

$invoice = $db->exec('all');

$db->query = "SELECT shippings.* FROM shippings WHERE invoice_id = {$_GET['id']}";
$shippings = $db->exec('single');


// echo '<pre>';
// print_r($shippings);
// die();

$organizationUser = $db->single('organization_users', ['user_id' => auth()->id]);
$organizationId = $organizationUser ? $organizationUser->organization_id : 1;

if (Request::isMethod('POST')) {

    extract($_POST);
    // echo '<pre>';
    // print_r($_POST);
    // die();

    $db->query = ("UPDATE invoices
                    SET invoices.status = '$status'
                    WHERE invoices.id = $invoice_id
                ");
    $db->exec();

    if ($status == 'Finished') {
        foreach ($invoice as $key => $product) {

            $db->query  = "SELECT
            products.id,
            products.item_id,
            products.price,
            products.sku,
            inventory_items.name AS product_name
            FROM products
            JOIN inventory_items ON inventory_items.id = products.item_id
            WHERE products.id = {$product->item_id}";

            $produk = $db->exec('single');

            if ($product->item_type == 'shippings') continue;

            $db->insert('inventory_item_logs', [
                'item_id' => $produk->item_id,
                'amount'  => $product->quantity,
                'organization_src_id' => $organizationId,
                'organization_dst_id' => env('CUSTOMER_ORGANIZATION_ID'),
            ]);
        }
   
    }


    set_flash_msg(['success' => "Status berhasil diubah"]);

    header('Location:' . routeTo('commerce/detail-transaction', ['id' => $invoice_id]));
    // Set flash message


    die();
}
// page section
$title = "Detail Transaksi";
Page::setActive("$title");
Page::setTitle($title);
Page::setModuleName($title);


Page::pushFoot("<script src='" . asset('assets/crud/js/crud.js') . "'></script>");


return view('commerce/views/detail-transaction', compact('success_msg', 'error_msg', 'invoice','shippings'));
