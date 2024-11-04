<?php

namespace App\Models;

class ProductCategory extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'description'
    ];

    protected $hidden = [
        'created_datetime',
        'created_by_uid',
        'updated_datetime',
        'updated_by_uid',
    ];

    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'category_id');
    // }
}
