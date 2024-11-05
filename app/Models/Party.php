<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Party extends BaseModel
{
    const TYPE_SUPPLIER = 1;
    const TYPE_CUSTOMER = 2;

    protected $fillable = [
        'type', 'id2', 'active', 'name', 'phone', 'address', 'notes'
    ];

    public function idFormatted()
    {
        return ($this->type == self::TYPE_CUSTOMER ? 'CST-' : 'SUP-') . str_pad($this->id2, 5, '0', STR_PAD_LEFT);
    }

    public static function getNextId2($type)
    {
        return DB::table('parties')
            ->selectRaw('ifnull(max(id2), 0) + 1 as next_id')
            ->where('type', '=', $type)
            ->value('next_id');
    }

}
