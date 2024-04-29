<?php

use Core\Page;
use Modules\Crud\Libraries\Repositories\CrudRepository;

// init table fields
$tableName  = 'users';
$table      = tableFields($tableName);
$fields     = $table->getFields();
$module     = 'commerce';
$success_msg = get_flash_msg('success');
$error_msg   = get_flash_msg('error');



// get data
$crudRepository = new CrudRepository($tableName);
$crudRepository->setModule($module);

if(isset($_GET['draw']))
{
    return $crudRepository->dataTable($fields);
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

Page::pushFoot("<script src='".asset('assets/crud/js/crud.js')."'></script>");

Page::pushHook();

return view('commerce/views/customer', compact('fields', 'tableName', 'success_msg', 'error_msg', 'crudRepository'));