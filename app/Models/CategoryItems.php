<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItems extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $table = 'category_items';

    protected $fillable = [
        'name_category'
    ];
}
