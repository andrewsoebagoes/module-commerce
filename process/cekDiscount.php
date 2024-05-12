<?php 

use Core\Database;

$db = new Database();
extract($_POST);

$user_id = $user_id == '' ? 0 : $user_id;
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

$db->query = "SELECT products.item_id id_product, products.sku, COALESCE(product_prices.price, products.price), products.description, inventory_items.name AS product_name, discounts.discount_value, discount_user.discount_value AS user_discount_value,
CASE WHEN discount_user.discount_value IS NOT NULL 
     THEN COALESCE(product_prices.price, products.price) - discount_user.discount_value 
     ELSE COALESCE(product_prices.price, products.price) - COALESCE(discounts.discount_value,0)
END AS final_price
FROM products 
LEFT JOIN (SELECT MAX(id) price_id, product_id FROM product_prices WHERE min_quantity <= $quantity GROUP BY product_id) price_rule ON price_rule.product_id = products.id
LEFT JOIN product_prices ON product_prices.id = price_rule.price_id
LEFT JOIN product_discount ON products.item_id = product_discount.product_id 
LEFT JOIN inventory_items ON products.item_id = inventory_items.id 
LEFT JOIN discounts ON discounts.id = product_discount.discount_id 
LEFT JOIN discount_applicables ON discount_applicables.user_id = $user_id
LEFT JOIN discounts as discount_user ON discount_user.id = discount_applicables.discount_id
AND products.status = 'PUBLISH'";

$discount = $db->exec('all');


foreach ($discount as $product){
       echo ' <div class="col-md-4 pb-3">
       <div class="produk list-group-item justify-content-between align-items-center active-list">
            <div class="col-md-6 col-sm-4 text-group">
                <p class="m-0">Rp. '.number_format($product->final_price) .'</p>
                <p class="m-0 txt-light">'.$product->product_name .'</p>
            </div>

            <div class="col-md-3 col-sm-4 d-flex align-items-center">
                <span class=""><i class="mdi mdi-cube-outline"></i></span>
                <p class="m-0">'.$product->sku .'</p>
            </div>

            <a href="#" class="col-md-1 col-sm-12 btn btn-icons btn-rounded btn-outline-primary font-weight-bold btn-pilih" id="'.$product->id_product .'" role="button"><i class="mdi mdi-chevron-right"></i></a>
        </div>
    </div>
    </div>';
}






?>