<?php

use Core\Database;
use Core\Response;

$db = new Database;
$id = $_GET['id'];

$is_customer = get_role(auth()->id)->role_id == env('CUSTOMER_ROLE_ID');
if($is_customer){
    $user_id = auth()->id;
}else{
    $user_id = $_GET['user_id'];
}


$db->query = "SELECT products.id id_product, products.sku, products.price, products.description, inventory_items.name AS product_name, discounts.discount_value, discount_user.discount_value AS user_discount_value,
CASE WHEN discount_user.discount_value 
IS NOT NULL THEN products.price - discount_user.discount_value 
ELSE products.price - COALESCE(discounts.discount_value,0)
END final_price
FROM products 
LEFT JOIN product_discount ON products.id = product_discount.product_id 
LEFT JOIN inventory_items ON products.item_id = inventory_items.id 
LEFT JOIN discounts ON discounts.id = product_discount.discount_id 
LEFT JOIN discount_applicables ON discount_applicables.user_id = $user_id
LEFT JOIN discounts as discount_user ON discount_user.id = discount_applicables.discount_id
WHERE products.id = $id
AND products.sku > 0";

$product = $db->exec('single');


return Response::json($product, '');
