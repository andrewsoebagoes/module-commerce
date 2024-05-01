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

$db->query = "SELECT * FROM invoices
                            WHERE
                                CASE 
                                    WHEN ". auth()->id ." = 1 THEN TRUE
                                    ELSE user_id = ". auth()->id ."
                                END
                            ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";
$data  = $this->db->exec('all');

$total = $this->db->exists('invoices',$where,[
    $col_order => $order[0]['dir']
]);

return compact('data','total');