<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersFile2 extends Model
{
    protected $table = 'orders_file2';

    protected $fillable = [
        'order_date',
        'channel',
        'item_description',
        'origin',
        'office',
        'cost',
        'shipping_cost',
        'total_price',
    ];
}
