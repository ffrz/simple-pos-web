<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashAccount extends Model
{
    public const ACCOUNT_TYPE_CASH = 0;
    public const ACCOUNT_TYPE_BANK = 1;

    protected $fillable = [
        'name',
        'type',
        'balance',
    ];

    public static function typeNames($type)
    {
        switch ($type) {
            case self::ACCOUNT_TYPE_CASH: return  'Kas Tunai';
            case self::ACCOUNT_TYPE_BANK: return  'Rekening Bank';
        }
        return '';
    }
}
