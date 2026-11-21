<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NavigationMenu extends Model
{
    use HasFactory;

    protected $table = 'navigation_menus';

    protected $fillable = [
        'key',
        'title',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(NavigationMenuItem::class, 'menu_id')
            ->whereNull('parent_id')
            ->orderBy('sort_order', 'asc');
    }

    public function allItems()
    {
        return $this->hasMany(NavigationMenuItem::class, 'menu_id')
            ->orderBy('sort_order', 'asc');
    }
}
