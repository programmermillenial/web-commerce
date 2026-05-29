<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class MenuCategory extends Model
{
    protected $table = 'menu_categories';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'icon',
        'sort_order'
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class,  'category_id');
    }


    function getRouteKeyName()
    {
        return 'slug';
    }
}
