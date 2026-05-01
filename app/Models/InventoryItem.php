<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'category',
        'quantity',
        'unit',
        'reorder_level',
        'supplier',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function getStockStatusAttribute(): string
    {
        return $this->quantity <= $this->reorder_level ? 'Low Stock' : 'In Stock';
    }
}
