<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'description',
        'amount',
        'date',
    ];

    public function category()
    {
        return $this->belongsTo(CostCategory::class);
    }
}
