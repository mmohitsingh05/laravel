<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'description',
        'quantity',
        'unit',
        'price',
        'image_path',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function getImageUrlAttribute(): string
    {
        return $this->image_path
            ? '/storage/'.$this->image_path
            : '';
    }

    public function getPriceFormattedAttribute(): string
    {
        return '₹'.number_format((float) $this->price, 2);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function scopeWithStockBelow(Builder $query, int $threshold = 10): Builder
    {
        return $query->where('quantity', '<=', $threshold);
    }
}
