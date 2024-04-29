<?php 

$action_button = "";

if($data->status == 'DRAFT'){
    $action_button .= '<a href="'.routeTo('commerce/aktif-diskon',['id'=>$data->id, 'product_id' => $data->product_id]).'" class="btn btn-sm btn-success text-white" style="margin-right:3px"><i class="fas fa-check"></i> PUBLISH</a>';
}
else if($data->status == 'PUBLISH'){
$action_button .= '<a href="'.routeTo('commerce/nonaktif-diskon',['id'=>$data->id,  'product_id' => $data->product_id]).'" class="btn btn-sm btn-secondary text-white" style="margin-right:3px"><i class="fas fa-times"></i> DRAFT</a>';
}

return $action_button;


;

?>