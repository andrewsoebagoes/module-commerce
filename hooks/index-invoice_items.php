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
                    invoice_items.*,
                    CASE
                        WHEN invoice_items.item_type = 'products' THEN inventory_items.name
                        WHEN invoice_items.item_type = 'shippings' THEN shippings.courier
                        ELSE NULL
                    END AS name
                    FROM
                    invoice_items
                    LEFT JOIN inventory_items ON invoice_items.item_id = inventory_items.id
                    AND invoice_items.item_type = 'products'
                    LEFT JOIN shippings ON invoice_items.invoice_id = shippings.invoice_id
                    AND invoice_items.item_type = 'shippings' 
                    WHERE invoice_items.invoice_id = {$_GET['id']}
                    ORDER BY " . $col_order . " " . $order[0]['dir'] . " LIMIT $start,$length";
$data  = $this->db->exec('all');

$total = $this->db->exists('invoice_items', $where, [
    $col_order => $order[0]['dir']
]);

return compact('data', 'total');
