<?php

unset($fields['notes']);
unset($fields['payment_return']);
unset($fields['payment_receive']);
// unset($fields['user_id']);
// unset($fields['created_at']);
// unset($fields['created_by']);

if(get_role(auth()->id)->role_id == env('CUSTOMER_ROLE_ID')){
    unset($fields['organization_id']);
}

return $fields;