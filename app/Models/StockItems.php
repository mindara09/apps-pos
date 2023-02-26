<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItems extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $table = 'stock_items';

    protected $fillable = [
        'code_item',
        'name_item',
        'category_item',
        'category_order',
        'quantity',
        'price',
    ];
    
}
