<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const TYPE_NONSTOCKED = 0;
    public const TYPE_STOCKED    = 1;
    public const TYPE_SERVICE    = 2;

    public const COSTING_METHOD_MANUAL  = 0;
    public const COSTING_METHOD_LAST    = 1;
    public const COSTING_METHOD_AVERAGE = 2;

    protected $fillable = [
        'type', 'costing_method', 'name', 'active', 'stock', 'uom', 'description', 'cost', 'price', 'notes',
        'supplier_id', 'last_supplier_id', 'category_id'
    ];
}
