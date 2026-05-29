<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'thumbnail',
        'is_featured',
        'stock',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    function images()
    {
        return $this->hasMany(MenuImage::class);
    }

    function prices()
    {
        return $this->hasMany(MenuPrice::class);
    }

    function activePrice()
    {
        return $this->hasOne(MenuPrice::class)
            ->where('is_active', true)
            ->latest();
    }
}
