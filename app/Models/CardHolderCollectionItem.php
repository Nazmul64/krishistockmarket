<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardHolderCollectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'request_item_id',
        'product_name',
        'category',
        'unit',
        'requested_quantity',
        'approved_quantity',
        'allocated_quantity',
        'delivered_quantity',
        'remaining_quantity',
        'unit_rate',
        'total_amount',
        'notes',
    ];

    public function collection()
    {
        return $this->belongsTo(CardHolderCollection::class, 'collection_id');
    }

    public function requestItem()
    {
        return $this->belongsTo(MonthlyMarketRequestItem::class, 'request_item_id');
    }
}
