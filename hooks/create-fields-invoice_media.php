<?php

if(isset($_GET['filter']) && isset($_GET['filter']['invoice_id']))
{
    unset($fields['invoice_id']);
    unset($fields['status']);
    
}

$fields['media_id']['type'] = 'file';

return $fields;