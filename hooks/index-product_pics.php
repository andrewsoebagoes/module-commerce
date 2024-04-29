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

$db->query = "SELECT inventory_items.name product_name, media.name media_name, product_pics.id id FROM product_pics JOIN products ON products.id = product_pics.product_id 
                                        JOIN inventory_items ON inventory_items.id = products.item_id
                                         JOIN media ON media.id = product_pics.media_id $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";

$data  = $this->db->exec('all');

$total = $this->db->exists('product_pics',$where,[
    $col_order => $order[0]['dir']
]);

return compact('data','total');