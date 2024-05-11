<?php

$userId = auth()->id;
$isAdmin = get_role($userId)->role_id == 1;

if(!$isAdmin)
{
    $organization = $db->single('organization_users', ['user_id' => $userId]);
    $where = (empty($where) ? 'WHERE ' : ' AND ') . " created_by = $userId AND organization_id = $organization->organization_id";
}

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


$db->query = "SELECT * FROM invoices $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";
$data  = $this->db->exec('all');

$total = $this->db->exists('invoices',$where,[
    $col_order => $order[0]['dir']
]);

return compact('data','total');