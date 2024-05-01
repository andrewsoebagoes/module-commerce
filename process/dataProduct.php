<?php

use Core\Database;
use Core\Response;

$db = new Database;
$id = $_GET['id'];

$db->query  = "SELECT
                products.item_id id_product,
                products.price,
                products.sku,
                inventory_items.name AS product_name
                FROM products
                JOIN inventory_items ON inventory_items.id = products.item_id
                WHERE products.item_id = $id";

$product = $db->exec('single');


return Response::json($product,'');