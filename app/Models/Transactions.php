<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $table = 'transactions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name_customer',
        'code_transaction',
        'orders',
        'payment',
        'notes',
        'status',
        'total',
        'change',
        'cash_customer'
    ];
}
