<?php 

return '
<a href="'.routeTo('crud/index',['table'=>'invoice_media','filter'=>['invoice_id' => $data->id]]).'" class="btn btn-sm btn-success"><i class="fas fa-image"></i> '.__('commerce.label.pics').'</a> 
<a href="'.routeTo('commerce/detail-transaction',['filter'=>['invoice_id' => $data->id]]).'" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> '.__('commerce.label.detail').'</a> 
';

?>