<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends BaseModel
{
    const STOCKED = 0;
    const NON_STOCKED = 1;
    const SERVICE = 2;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'type',
        'active',
        'code',
        'description',
        'barcode',
        'stock',
        'minimum_stock',
        'uom',
        'cost',
        'price',
        'notes',
    ];

    public static function formatType($type)
    {
        switch ($type) {
            case self::STOCKED:
                return 'Barang Stok';
            case self::NON_STOCKED:
                return 'Barang Non Stok';
            case self::SERVICE:
                return 'Servis';
        }

        return throw new Exception('Unknown product type: ' . $type);
    }

    public function typeFormatted()
    {
        return static::formatType($this->type);
    }

    public function idFormatted()
    {
        return 'P-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
