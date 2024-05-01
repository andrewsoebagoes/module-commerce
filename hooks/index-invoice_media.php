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

$db->query = "SELECT invoice_media.*, invoices.code, media.name media_name 
                                        FROM invoice_media 
                                         JOIN invoices ON invoices.id = invoice_media.invoice_id 
                                         JOIN media ON media.id = invoice_media.media_id 
                                         $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";

$data  = $this->db->exec('all');

$total = $this->db->exists('invoice_media',$where,[
    $col_order => $order[0]['dir']
]);

return compact('data','total');