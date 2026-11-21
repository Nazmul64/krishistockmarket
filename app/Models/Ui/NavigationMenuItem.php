<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NavigationMenuItem extends Model
{
    use HasFactory;

    protected $table = 'navigation_menu_items';

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title_bn',
        'title_en',
        'url',
        'route_name',
        'icon',
        'badge_text',
        'sort_order',
        'show_on_web',
        'show_on_app',
        'is_active',
        'target',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'show_on_web' => 'boolean',
        'show_on_app' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(NavigationMenu::class, 'menu_id');
    }

    public function parent()
    {
        return $this->belongsTo(NavigationMenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationMenuItem::class, 'parent_id')->orderBy('sort_order', 'asc');
    }
}
