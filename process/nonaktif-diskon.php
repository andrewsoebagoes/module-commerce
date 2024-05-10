<?php 

use Core\Database;

$db = new Database;

$id     = $_GET['id'];
$product_id     = $_GET['product_id'];

$db->query = ("UPDATE product_discount
                    SET status= 'DRAFT'
                    WHERE id = $id
                    ");
$db->exec();

set_flash_msg(['success'=> 'Diskon berhasil di DRAFT']);
header('Location:'.routeTo('crud/index',['table'=>'product_discount','filter'=>['product_id' => $product_id]]));




?>