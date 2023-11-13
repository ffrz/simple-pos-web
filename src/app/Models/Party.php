<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    public const TYPE_SUPPLIER = 1;
    public const TYPE_CUSTOMER = 2;

    protected $fillable = [
        'type',
        'name',
        'active',
        'contact',
        'address',
        'url',
        'notes',
    ];
}
