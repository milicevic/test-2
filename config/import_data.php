<?php
return [
    'orders' => [
        'label' => 'Import Orders',
        'permission_required' => 'import-orders',
        'files' => [
            'file1' => [
                'label' => 'File 1',
                'headers_to_db' => [
                    'channel' => [
                        'label' => 'Channel',
                        'type' => 'string',
                        'validation' => [
                            'required',
                            'in' => ['PI', 'Amazon'],
                        ],
                    ],
                    'sku' => [
                        'label' => 'SKU',
                        'type' => 'string',
                        'validation' => [
                            'required'
                        ],
                    ],
                    'item_description' => [
                        'label' => 'Item Description',
                        'type' => 'string',
                        'validation' => [
                            'nullable',
                        ],
                    ],
                    'origin' => [
                        'label' => 'Origin',
                        'type' => 'string',
                        'validation' => [
                            'required',
                        ],
                    ],
                    'so' => [
                        'label' => 'SO#',
                        'type' => 'string',
                        'validation' => [
                            'required',
                        ],
                    ],
                    'cost' => [
                        'label' => 'Cost',
                        'type' => 'double',
                        'validation' => [
                            'required',
                        ],
                    ],
                    'shipping_cost' => [
                        'label' => 'Shipping Cost',
                        'type' => 'double',
                        'validation' => [
                            'required',
                        ],
                    ],
                    'total_price' => [
                        'label' => 'Total Price',
                        'type' => 'double',
                        'validation' => [
                            'required',
                        ],
                    ],
                ],
                'update_or_create' => ['so', 'sku'],
            ],
            'file2' => [
                'label' => 'File 2',
                'headers_to_db' => [
                    'order_date' => [
                        'label' => 'Order Date',
                        'type' => 'date',
                        'validation' => [
                            'required',
                        ],
                    ],
                    'channel' => [
                        'label' => 'Channel',
                        'type' => 'string',
                        'validation' => [
                            'required',
                            'in' => ['PI', 'Amazon'],
                        ],
                    ],
                    'item_description' => [
                        'label' => 'Item Description',
                        'type' => 'string',
                        'validation' => [
                            'nullable',
                        ],
                    ],
                    'origin' => [
                        'label' => 'Origin',
                        'type' => 'string',
                        'validation' => [
                            'required',
                        ],
                    ],
                    'office' => [
                        'label' => 'SO#',
                        'type' => 'string',
                        'validation' => [
                            'required',
                        ],
                    ],
                    'cost' => [
                        'label' => 'Cost',
                        'type' => 'double',
                        'validation' => [
                            'required',
                        ],
                    ],
                    'shipping_cost' => [
                        'label' => 'Shipping Cost',
                        'type' => 'double',
                        'validation' => [
                            'required',
                        ],
                    ],
                    'total_price' => [
                        'label' => 'Total Price',
                        'type' => 'double',
                        'validation' => [
                            'required',
                        ],
                    ],
                ],
                'update_or_create' => ['shipping_cost', 'total_price'],
            ],
        ],
    ],
    'import-type-1' => [
        'label' => 'Import Type 1',
        'permission_required' => 'import-type-1',
        'files' => [
            'file1' => [
                'label' => 'file 1',
                'headers_to_db' => [
                    'product_name' => [
                        'label' => 'Product Name',
                        'type' => 'string',
                        'validation' => ['required'],
                    ],
                    'sku' => [
                        'label' => 'SKU',
                        'type' => 'string',
                        'validation' => ['required', 'unique:products,sku'],
                    ],
                    'price' => [
                        'label' => 'Retail Price',
                        'type' => 'double',
                        'validation' => ['required', 'numeric', 'min:0'],
                    ],
                    'category' => [
                        'label' => 'Category',
                        'type' => 'string',
                        'validation' => ['nullable'],
                    ],
                    'weight_kg' => [
                        'label' => 'Weight (KG)',
                        'type' => 'double',
                        'validation' => ['nullable', 'numeric'],
                    ],
                ],
                'update_or_create' => ['sku'],
            ],
        ],
    ],
    'import-type-2' => [
        'label' => 'Import Customer/User Data',
        'permission_required' => 'import-customers',
        'files' => [
            'file1' => [
                'label' => 'Customer List',
                'headers_to_db' => [
                    'email' => [
                        'label' => 'Email Address',
                        'type' => 'string',
                        'validation' => ['required', 'email', 'unique:users,email'],
                    ],
                    'first_name' => [
                        'label' => 'First Name',
                        'type' => 'string',
                        'validation' => ['required'],
                    ],
                    'last_name' => [
                        'label' => 'Last Name',
                        'type' => 'string',
                        'validation' => ['required'],
                    ],
                    'phone' => [
                        'label' => 'Phone Number',
                        'type' => 'string',
                        'validation' => ['nullable', 'max:20'],
                    ],
                ],
                'update_or_create' => ['email'],
            ],
        ],
    ],

    'import-type-3' => [
        'label' => 'Update Inventory Stock',
        'permission_required' => 'import-inventory',
        'files' => [
            'file1' => [
                'label' => 'Stock Levels',
                'headers_to_db' => [
                    'sku' => [
                        'label' => 'SKU',
                        'type' => 'string',
                        'validation' => [
                            'required',
                            'exists' => [
                                'table' => 'products',
                                'column' => 'sku',
                            ],
                        ],
                    ],
                    'warehouse' => [
                        'label' => 'Warehouse Location',
                        'type' => 'string',
                        'validation' => ['required', 'in' => ['main', 'auxiliary', 'returns']],
                    ],
                    'stock_level' => [
                        'label' => 'Stock Quantity',
                        'type' => 'integer',
                        'validation' => ['required', 'integer', 'min:0'],
                    ],
                    'last_counted' => [
                        'label' => 'Last Count Date',
                        'type' => 'date',
                        'validation' => ['nullable', 'date'],
                    ],
                ],
                // Composite key for update/create (SKU at a specific Warehouse)
                'update_or_create' => ['sku', 'warehouse'],
            ],
        ],
    ],
];
