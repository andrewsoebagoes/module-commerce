<?php return [
    
    [
        'label' => 'commerce.menu.discounts',
        'icon'  => 'fa-fw fa-xl me-2 fa-solid fa-list-ul',
        'route' => routeTo('crud/index', ['table' => 'discounts']),
        'activeState' => 'commerce.discounts'
    ],
    [
        'label' => 'commerce.menu.products',
        'icon'  => 'fa-fw fa-xl me-2 fa-solid fa-list-ul',
        'route' => routeTo('crud/index', ['table' => 'products']),
        'activeState' => 'commerce.products'
    ],
    [
        'label' => 'commerce.menu.customer',
        'icon'  => 'fa-fw fa-xl me-2 fa-solid fa-users',
        'route' => routeTo('commerce/customer'),
        'activeState' => 'commerce.customer'
    ],
    [
        'label' => 'commerce.menu.transaction',
        'icon'  => 'fa-fw fa-xl me-2 fa-solid fa-random',
        'route' => routeTo('commerce/data-transaction'),
        'activeState' => 'commerce.transaction'
    ],
   

];
