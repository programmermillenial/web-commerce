<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPrice extends Model
{
    protected $fillable = [
        'menu_id',
        'normal_price',
        'promo_price',
        'promo_start',
        'promo_end',
        'is_active',
    ];

    protected $casts = [
        'promo_start' => 'datetime',
        'promo_end' => 'datetime',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
