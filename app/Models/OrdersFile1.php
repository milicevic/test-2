<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersFile1 extends Model
{
    protected $table = 'orders_file1';
    protected $fillable = ['channel', 'sku', 'item_description', 'origin', 'so', 'cost', 'shipping_cost', 'total_price'];
}
