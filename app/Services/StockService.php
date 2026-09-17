<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StockService
{
    /**
     * Record a stock movement and atomically update the product quantity.
     *
     * Business rules:
     * - A stock OUT movement cannot exceed the available quantity.
     * - A product quantity can never drop below zero.
     */
    public function recordMovement(
        Product $product,
        StockMovementType $type,
        int $quantity,
        ?string $notes = null,
        ?User $user = null,
    ): StockMovement {

        return DB::transaction(function () use ($product, $type, $quantity, $notes, $user): StockMovement {
            /** @var Product $lockedProduct */
            $lockedProduct = Product::query()
                ->lockForUpdate()
                ->findOrFail($product->id);

            $delta = $type->sign() * $quantity;

            if ($lockedProduct->quantity + $delta < 0) {
                throw ValidationException::withMessages([
                    'quantity' => "Insufficient stock. Only {$lockedProduct->quantity} {$lockedProduct->unit} available.",
                ]);
            }

            $lockedProduct->update([
                'quantity' => $lockedProduct->quantity + $delta,
            ]);

            return $lockedProduct->stockMovements()->create([
                'type' => $type,
                'quantity' => $quantity,
                'notes' => $notes,
                'user_id' => $user?->id ?? auth()->id(),
            ]);
        });
    }
}
