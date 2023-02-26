<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogUsers extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $table = 'log_users';

    protected $fillable = [
        'user_id',
        'action'
    ];
}
