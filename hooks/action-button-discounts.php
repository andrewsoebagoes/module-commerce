<?php 

return '<a href="'.routeTo('crud/index',['table'=>'discount_applicables','filter'=>['discount_id' => $data->id]]).'" class="btn btn-sm btn-info"><i class="fas fa-calculator"></i> '.__('commerce.label.discount_applicables').'</a> ';

?>