<?php

namespace App\Support;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

/**
 * Cart key scheme and line resolution.
 *
 * The session cart is a flat `[key => quantity]` map. Before sizes existed the key
 * was simply the product id, and those keys are still out there — in live sessions
 * and in `users.cart_data`, which persists across logins. So the scheme had to stay
 * readable for both:
 *
 *     "12"     product 12, no size          (legacy, still produced for simple products)
 *     "12:5"   product 12, variant 5
 *
 * parse() reads both, which is what makes the change safe for carts that already
 * exist — no migration of stored cart data, no customer losing a basket.
 *
 * Resolution lives here rather than in the controller because the same
 * product+variant+price lookup was needed by the cart page, the checkout summary,
 * the order writer and the nearest-store rule. Four copies would have been four
 * chances to price a line off `products.price` when a size was chosen.
 */
class Cart
{
    /** Build the session key for a product, optionally with a chosen size. */
    public static function key(int $productId, ?int $variantId = null): string
    {
        return $variantId ? $productId.':'.$variantId : (string) $productId;
    }

    /**
     * Split a session key into its product and variant ids.
     *
     * @return array{0: int, 1: int|null}
     */
    public static function parse(string|int $key): array
    {
        $parts = explode(':', (string) $key, 2);

        return [
            (int) $parts[0],
            isset($parts[1]) && $parts[1] !== '' ? (int) $parts[1] : null,
        ];
    }

    /** Distinct product ids in a cart, for queries that do not care about size. */
    public static function productIds(array $cart): array
    {
        return array_values(array_unique(array_map(
            fn ($key) => self::parse($key)[0],
            array_keys($cart)
        )));
    }

    /**
     * Resolve a session cart into priced lines.
     *
     * Each line is an object with: key, product, variant (nullable), quantity,
     * unit_price and subtotal. Lines whose product or chosen size no longer exists
     * are dropped — see invalidKeys() for pruning them out of the session so the
     * customer is not billed for something that has been withdrawn.
     *
     * The unit price comes from the VARIANT when one is chosen. Reading
     * `products.price` for a sized line is the bug this method exists to prevent.
     */
    public static function lines(array $cart): Collection
    {
        if (empty($cart)) {
            return collect();
        }

        $products = Product::with('variants')
            ->whereIn('id', self::productIds($cart))
            ->get()
            ->keyBy('id');

        return collect($cart)
            ->map(function ($quantity, $key) use ($products) {
                [$productId, $variantId] = self::parse($key);

                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                $variant = null;

                if ($variantId !== null) {
                    $variant = $product->variants->firstWhere('id', $variantId);

                    // The size was deleted after it went into the cart. Dropping the
                    // line is deliberate: falling back to the product price would
                    // quietly sell a different thing at a different price.
                    if (! $variant) {
                        return null;
                    }
                }

                $unitPrice = (float) ($variant->price ?? $product->price);
                $quantity = max(1, (int) $quantity);

                return (object) [
                    'key' => (string) $key,
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $unitPrice * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    /** Keys in the cart that no longer resolve, so the caller can drop them. */
    public static function invalidKeys(array $cart): array
    {
        $valid = self::lines($cart)->pluck('key')->all();

        return array_values(array_diff(array_map('strval', array_keys($cart)), $valid));
    }

    /** Total number of items, unchanged by the key scheme. */
    public static function count(array $cart): int
    {
        return (int) array_sum($cart);
    }

    /**
     * Human label for a line, e.g. "Lintel (1.2m)".
     *
     * Used in the cart, the WhatsApp enquiry and the order record, so the size a
     * customer picked reads the same everywhere.
     */
    public static function label(Product $product, ?ProductVariant $variant): string
    {
        return $variant ? $product->name.' ('.$variant->label.')' : $product->name;
    }
}
