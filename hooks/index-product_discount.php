<?php


if($filter)
{
    $filter_query = [];
    foreach($filter as $f_key => $f_value)
    {
        $filter_query[] = "$f_key = '$f_value'";
    }

    $filter_query = implode(' AND ', $filter_query);

    $where .= (empty($where) ? 'WHERE ' : ' AND ') . $filter_query;
}

$db->query = "SELECT inventory_items.name product_name, discounts.name discount_name, product_discount.id id, product_discount.status, product_discount.product_id product_id 
                FROM product_discount 
                JOIN products ON products.id = product_discount.product_id 
                JOIN inventory_items ON inventory_items.id = products.item_id
                JOIN discounts ON discounts.id = product_discount.discount_id 
                $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";

$data  = $this->db->exec('all');

$total = $this->db->exists('product_discount',$where,[
    $col_order => $order[0]['dir']
]);

return compact('data','total');