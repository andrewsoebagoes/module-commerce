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
$crudRepository = new CrudRepository($tableName);
$crudRepository->setModule($module);

if (isset($_GET['draw'])) {
    return $crudRepository->dataTable($fields);
}



$db = new Database;
$db->query  = "SELECT
invoice_items.*,
users.name AS user_name,
invoices.id AS id_invoice,
invoices.code,
invoices.status,
invoices.user_id,
invoices.total_amount,
invoices.created_at,
invoices.created_by,
shippings.country,
shippings.province,
shippings.city,
shippings.courier,
shippings.address,
shippings.notes,
media.name image
FROM invoices
LEFT JOIN invoice_items ON invoices.id = invoice_items.invoice_id
LEFT JOIN shippings ON invoices.id = shippings.invoice_id
LEFT JOIN users ON invoices.user_id = users.id
LEFT JOIN invoice_media ON invoices.id = invoice_media.invoice_id
LEFT JOIN media ON invoice_media.media_id = media.id
WHERE invoices.id = $invoice_id";


$invoice = $db->exec('single');

// echo '<pre>';
// print_r($invoice);
// die();


if (Request::isMethod('POST')) {

    extract($_POST);
    extract($_FILES);
    // echo '<pre>';
    // print_r($_POST);
    // die();


    $db->query = ("UPDATE invoices
                    SET invoices.status = '$status'
                    WHERE invoices.id = $invoice_id
                ");
    $db->exec();

    // Set flash message
    set_flash_msg(['success' => "Status berhasil diubah"]);

    header('Location:' . routeTo('commerce/detail-transaction', ['id' => $invoice_id]));


    die();
}
// page section
$title = _ucwords(__("$module.label.$tableName"));
Page::setActive("$module.$tableName");
Page::setTitle($title);
Page::setModuleName($title);
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

Page::pushFoot("<script src='" . asset('assets/crud/js/crud.js') . "'></script>");

Page::pushHook();

return view('commerce/views/detail-transaction', compact('fields', 'tableName', 'success_msg', 'error_msg', 'crudRepository', 'invoice'));
