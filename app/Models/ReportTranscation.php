<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTranscation extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $table = 'report_transcations';

    protected $fillable = [
    	'status',
        'start_date',
        'end_date'
    ];
}
