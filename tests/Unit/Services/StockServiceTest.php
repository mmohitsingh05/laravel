<?php

declare(strict_types=1);

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Validation\ValidationException;

test('stock in increases product quantity', function (): void {
    $product = Product::factory()->create(['quantity' => 10]);
    $user = User::factory()->create();

    app(StockService::class)->recordMovement(
        product: $product,
        type: StockMovementType::In,
        quantity: 5,
        notes: 'restock',
        user: $user,
    );

    expect($product->fresh()->quantity)->toBe(15);
    expect($product->stockMovements()->count())->toBe(1);
});

test('stock in records the correct user and notes', function (): void {
    $product = Product::factory()->create();
    $user = User::factory()->create();

    app(StockService::class)->recordMovement(
        product: $product,
        type: StockMovementType::In,
        quantity: 5,
        notes: 'received from supplier',
        user: $user,
    );

    $movement = $product->stockMovements()->first();
    expect($movement->type)->toBe(StockMovementType::In)
        ->and($movement->quantity)->toBe(5)
        ->and($movement->notes)->toBe('received from supplier')
        ->and($movement->user_id)->toBe($user->id);
});

test('stock out decreases product quantity', function (): void {
    $product = Product::factory()->withStock(20)->create();

    app(StockService::class)->recordMovement(
        product: $product,
        type: StockMovementType::Out,
        quantity: 7,
        user: User::factory()->create(),
    );

    expect($product->fresh()->quantity)->toBe(13);
});

test('stock out is rejected when it exceeds available quantity', function (): void {
    $product = Product::factory()->withStock(5)->create();

    app(StockService::class)->recordMovement(
        product: $product,
        type: StockMovementType::Out,
        quantity: 10,
    );
})->throws(ValidationException::class, 'Insufficient stock');

test('stock out that would take quantity below zero is rejected', function (): void {
    $product = Product::factory()->withStock(3)->create();

    app(StockService::class)->recordMovement(
        product: $product,
        type: StockMovementType::Out,
        quantity: 4,
    );
})->throws(ValidationException::class);

test('failed stock movement cannot leave quantity negative', function (): void {
    $product = Product::factory()->withStock(1)->create();

    try {
        app(StockService::class)->recordMovement(
            product: $product,
            type: StockMovementType::Out,
            quantity: 2,
        );
    } catch (ValidationException) {
        // expected
    }

    expect($product->fresh()->quantity)->toBe(1)
        ->and($product->stockMovements()->count())->toBe(0);
});
