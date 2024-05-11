<?php 

return '
        <a href="'.routeTo('crud/index',['table'=>'product_prices','filter'=>['product_id' => $data->id]]).'" class="btn btn-sm btn-success"><i class="fas fa-dollar"></i> '.__('commerce.label.price').'</a> 
        <a href="'.routeTo('crud/index',['table'=>'product_pics','filter'=>['product_id' => $data->id]]).'" class="btn btn-sm btn-success"><i class="fas fa-image"></i> '.__('commerce.label.pics').'</a> 
        <a href="'.routeTo('crud/index',['table'=>'product_discount','filter'=>['product_id' => $data->id]]).'" class="btn btn-sm btn-info"><i class="fas fa-calculator"></i> '.__('commerce.label.discount').'</a> 
    
        ';

?>