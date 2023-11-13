<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function account(): BelongsTo
    {
        return $this->belongsTo(CashAccount::class);
    }

    public function category()
    {
        return $this->belongsTo(CashTransactionCategory::class);
    }

}
