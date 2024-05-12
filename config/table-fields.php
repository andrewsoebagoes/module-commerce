<?php return [

    'products' => [
        'item_id' => [
            'label' => __('commerce.label.item'),
            'type' => 'options-obj:inventory_items,id,name'
        ],
        // 'parent_id' => [
        //     'label' => __('commerce.label.parent'),
        //     'type'  => 'options-obj:products,id,id'
        // ],
        'sku' => [
            'label' => __('commerce.label.sku'),
            'type'  => 'text'
        ],
        'price' => [
            'label' => __('commerce.label.price'),
            'type'  => 'number'
        ],
        'status' => [
            'label' => __('commerce.label.status'),
            'type'  => 'options:PUBLISH|DRAFT'
        ],
        'description' => [
            'label' => __('commerce.label.description'),
            'type'  => 'textarea'
        ]
    ],

    'product_prices' => [
        'product_id' => [
            'label' => __('commerce.label.product'),
            'type'  => 'text'
        ],
        'price' => [
            'label' => __('commerce.label.price'),
            'type'  => 'number'
        ],
        'min_quantity' => [
            'label' => __('commerce.label.min_quantity'),
            'type'  => 'number'
        ],
    ],

    'product_pics' => [
        'product_id' => [
            'label' => __('commerce.label.product'),
            'type'  => 'text'
        ],
        'media_id' => [
            'label' => __('commerce.label.media'),
            'type'  => 'options-obj:media,id,name'
        ]
    ],

    'discounts' => [
        'code' => [
            'label' => __('commerce.label.code'),
            'type'  => 'text'
        ],
        'name' => [
            'label' => __('commerce.label.name'),
            'type'  => 'text'
        ],
        'discount_type' => [
            'label' => __('commerce.label.discount_type'),
            'type'  => 'text'
        ],
        'discount_value' => [
            'label' => __('commerce.label.discount_value'),
            'type'  => 'number'
        ],
        'record_type' => [
            'label' => __('commerce.label.record_type'),
            'type'  => 'text'
        ],
        'status' => [
            'label' => __('commerce.label.status'),
            'type'  => 'options:Aktif|Tidak Aktif'
        ]
    ],

    'discount_applicables' => [
        'discount_id' => [
            'label' => __('commerce.label.discount'),
            'type'  => 'options-obj:discounts,id,name'
        ],
        'user_id' => [
            'label' => __('commerce.label.user'),
            'type'  => 'options-obj:users,id,name'
        ]
    ],

    'product_discount'  => [
        'discount_id' => [
            'label' => __('commerce.label.discount'),
            'type'  => 'options-obj:discounts,id,name'
        ],
        'product_id' => [
            'label' => __('commerce.label.product'),
            'type'  => 'options-obj:inventory_items,id,name'
        ],
        'status' => [
            'label' => __('commerce.label.status'),
            'type' => 'options:PUBLISH|DRAFT'
        ]
    ],

    'invoices' => [
        'code' => [
            'label' => __('commerce.label.code'),
            'type'  => 'text'
        ],
        'user_id' => [
            'label' => __('commerce.label.customer'),
            'type'  => 'options-obj:users,id,name'
        ],
        'status' => [
            'label' => __('commerce.label.status'),
            'type'  => 'text'
        ],
        'notes' => [
            'label' => __('commerce.label.notes'),
            'type'  => 'text'
        ],
        'total_amount' => [
            'label' => __('commerce.label.total_amount'),
            'type'  => 'number'
        ],
        'payment_receive' => [
            'label' => __('commerce.label.payment_receive'),
            'type'  => 'number'
        ],
        'payment_return' => [
            'label' => __('commerce.label.payment_return'),
            'type'  => 'number'
        ],
        'created_at' => [
            'label' => __('commerce.label.created_at'),
            'type'  => 'text'
        ],
        'created_by' => [
            'label' => __('commerce.label.created_by'),
            'type'  => 'options-obj:users,id,name'
        ],
        'organization_id' => [
            'label' => __('commerce.label.organization'),
            'type'  => 'options-obj:organizations,id,name'
        ]
    ],

    'invoice_media' => [
        'invoice_id' => [
            'label' => __('commerce.label.invoice'),
            'type'  => 'options-obj:invoices,id,code'
        ],
        'media_id' => [
            'label' => __('commerce.label.media'),
            'type'  => 'options-obj:media,id,name'
        ],
        'status' => [
            'label' => __('commerce.label.status'),
            'type'  => 'text'
        ]
    ],

    'invoice_items' => [
        'invoice_id' => [
            'label' => __('commerce.label.invoice'),
            'type'  => 'options-obj:invoices,id,name'
        ],
        'name' => [
            'label' => __('commerce.label.item'),
            'type'  => 'text'
        ],
        'item_type' => [
            'label' => __('commerce.label.item_type'),
            'type'  => 'text'
        ],
        'discount_id' => [
            'label' => __('commerce.label.discount'),
            'type'  => 'options-obj:discounts,id,name'
        ],
        'item_snapshot' => [
            'label' => __('commerce.label.item_snapshot'),
            'type'  => 'text'
        ],
        'discount_snapshot' => [
            'label' => __('commerce.label.discount_snapshot'),
            'type'  => 'text'
        ],
        'quantity'  => [
            'label' => __('commerce.label.quantity'),
            'type'  => 'number'
        ],
        'item_price' => [
            'label' => __('commerce.label.item_price'),
            'type'  => 'number'
        ],
        'discount_price' => [
            'label' => __('commerce.label.discount_price'),
            'type'  => 'number'
        ],
        'total_price'   => [
            'label' => __('commerce.label.total_price'),
            'type'  => 'number'
        ]
        ],

        'shippings' => [
            'invoice_id' => [
                'label' => __('commerce.label.invoice'),
                'type' => 'options-obj:invoices,id,name'
            ],
            'country'   => [
                'label' => __('commerce.label.country'),
                'type'  => 'text'
            ],
            'province'   => [
                'label' => __('commerce.label.province'),
                'type'  => 'text'
            ],
            'city'   => [
                'label' => __('commerce.label.city'),
                'type'  => 'text'
            ],
            'address'   => [
                'label' => __('commerce.label.address'),
                'type'  => 'text'
            ],
            'courier'   => [
                'label' => __('commerce.label.courier'),
                'type'  => 'text'
            ],
            'notes'   => [
                'label' => __('commerce.label.notes'),
                'type'  => 'text'
            ],
        ]


];
