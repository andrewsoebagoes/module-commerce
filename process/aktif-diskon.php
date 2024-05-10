<?php 

use Core\Database;

$db = new Database;

$id             = $_GET['id'];
$product_id     = $_GET['product_id'];

$db->query = ("UPDATE product_discount
                    SET status= 'PUBLISH'
                    WHERE id = $id
                    AND product_id = $product_id
                    ");
$db->exec();

$db->query = ("UPDATE product_discount
                    SET status= 'DRAFT'
                    WHERE status = 'PUBLISH'
                    AND product_id != $product_id
                    ");
$db->exec();


set_flash_msg(['success'=> 'Diskon berhasil di PUBLISH']);
header('Location:'.routeTo('crud/index',['table'=>'product_discount','filter'=>['product_id' => $product_id]]));




?>