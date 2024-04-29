<?php

use Core\Page;
use Core\Database;


use Modules\Crud\Libraries\Repositories\CrudRepository;

// init table fields
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

$invoice_id = $_GET['filter']['invoice_id'];

$db = new Database;
$db->query  = "SELECT
invoice_items.*,
users.name AS user_name,
invoices.id AS id_invoice,
invoices.code,
invoices.user_id,
invoices.total_amount,
invoices.created_at,
invoices.created_by
FROM invoices
JOIN invoice_items ON invoices.id = invoice_items.invoice_id
JOIN users ON invoices.user_id = users.id
WHERE invoices.id = $invoice_id";


$invoice = $db->exec('single');

// echo '<pre>';
// print_r($invoice);
// die();

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
