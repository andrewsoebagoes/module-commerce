<?php 

return '<a href="'.routeTo('commerce/detail-transaction',['filter'=>['invoice_id' => $data->id]]).'" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> '.__('commerce.label.detail').'</a> ';

?>