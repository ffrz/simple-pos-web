<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    protected $fillable = [
        'account_id',
        'category_id',
        'datetime',
        'amount',
        'description',
        'ref_type',
        'ref_id',
    ];
}
