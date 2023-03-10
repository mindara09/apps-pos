<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOut extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $table = 'cash_outs';

    protected $fillable = [
        'item',
        'cash_out'
    ];
}
