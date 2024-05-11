<?php

if(is_allowed('commerce/transaction', auth()->id))
{
    return '<a href="'.routeTo('commerce/transaction').'" class="btn btn-success btn-sm">
    <i class="fa-solid fa-plus"></i> '.__('commerce.label.pos_panel').'
</a>';
}

return '';