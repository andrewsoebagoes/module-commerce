<?php 

use Core\Database;

$db = new Database();
extract($_POST);


$db->query = "SELECT products.item_id id_product, products.sku, products.price, products.description, inventory_items.name AS product_name, discounts.discount_value, discount_user.discount_value AS user_discount_value,
CASE WHEN discount_user.discount_value 
IS NOT NULL THEN products.price - discount_user.discount_value 
ELSE products.price - COALESCE(discounts.discount_value,0)
END final_price
FROM products 
LEFT JOIN product_discount ON products.item_id = product_discount.product_id 
LEFT JOIN inventory_items ON products.item_id = inventory_items.id 
LEFT JOIN discounts ON discounts.id = product_discount.discount_id 
LEFT JOIN discount_applicables ON discount_applicables.user_id = $user_id
LEFT JOIN discounts as discount_user ON discount_user.id = discount_applicables.discount_id
WHERE products.sku > 0
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