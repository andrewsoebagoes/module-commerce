<?php 

use Core\Database;
use Core\Response;

$db = new Database();
extract($_POST);

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;



$db->query = "SELECT products.id id_product, products.sku, COALESCE(product_prices.price, products.price), products.description, inventory_items.name AS product_name, discounts.discount_value, discount_user.discount_value AS user_discount_value,
CASE WHEN discount_user.discount_value IS NOT NULL 
     THEN COALESCE(product_prices.price, products.price) - discount_user.discount_value 
     ELSE COALESCE(product_prices.price, products.price) - COALESCE(discounts.discount_value,0)
END AS final_price
FROM products 
LEFT JOIN (SELECT MAX(id) price_id, product_id FROM product_prices WHERE min_quantity <= $quantity GROUP BY product_id) price_rule ON price_rule.product_id = products.id
LEFT JOIN product_prices ON product_prices.id = price_rule.price_id
LEFT JOIN product_discount ON products.id = product_discount.product_id 
LEFT JOIN inventory_items ON products.item_id = inventory_items.id 
LEFT JOIN discounts ON discounts.id = product_discount.discount_id 
LEFT JOIN discount_applicables ON discount_applicables.user_id = $user_id
LEFT JOIN discounts as discount_user ON discount_user.id = discount_applicables.discount_id
WHERE products.id = '$productId'";

$discount = $db->exec('single');
// print_r($discount);
Response::json($discount, 'data diskon');

