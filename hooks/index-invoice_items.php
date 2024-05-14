<?php


if ($filter) {
    $filter_query = [];
    foreach ($filter as $f_key => $f_value) {
        $filter_query[] = "$f_key = '$f_value'";
    }

    $filter_query = implode(' AND ', $filter_query);

    $where .= (empty($where) ? 'WHERE ' : ' AND ') . $filter_query;
}

$db->query = "SELECT
invoice_items.id id,
invoice_items.item_id,
invoice_items.item_type,
invoice_items.discount_id,
invoice_items.quantity,
invoice_items.discount_price,
invoice_items.item_price,
invoice_items.total_price,
inventory_items.id AS inventory_item_id,
inventory_items.name AS item_name,
CASE
    WHEN invoice_items.item_type = 'products' THEN inventory_items.name
    WHEN invoice_items.item_type = 'shippings' THEN shippings.courier
    ELSE NULL
END AS name
FROM
invoices
LEFT JOIN invoice_items ON invoices.id = invoice_items.invoice_id
LEFT JOIN products ON invoice_items.item_id = products.id AND invoice_items.item_type = 'products'
LEFT JOIN inventory_items ON products.item_id = inventory_items.id
LEFT JOIN shippings ON invoice_items.invoice_id = shippings.invoice_id AND invoice_items.item_type = 'shippings' 
LEFT JOIN invoice_media ON invoices.id = invoice_media.invoice_id
LEFT JOIN media ON invoice_media.media_id = media.id
LEFT JOIN users ON invoices.user_id = users.id
WHERE invoice_items.invoice_id = {$_GET['id']}
ORDER BY " . $col_order . " " . $order[0]['dir'] . " LIMIT $start,$length";
$data  = $this->db->exec('all');

$total = $this->db->exists('invoice_items', $where, [
    $col_order => $order[0]['dir']
]);

return compact('data', 'total');
