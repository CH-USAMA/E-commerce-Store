<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Converts the two products that worked around the missing size feature.
     *
     * Both shapes were in the live catalog and both failed:
     *   - "Lintels from 1M to 6M" — one product with the range in its NAME, so no price
     *     could be shown and no length could be chosen.
     *   - "Paint Brush 50MM / 100MM / 150MM" — three separate products, which sort
     *     100, 150, 50 alphabetically.
     *
     * ---------------------------------------------------------------------------
     * PRICES ARE DERIVED FROM EACH ROW, NEVER HARDCODED.
     *
     * Local and production hold different numbers for these same products (live had
     * Paint Brush 50MM at R437.80 and 150MM at R86.00 — the smallest brush costing
     * five times the largest, which is the complaint that put the store into inquiry
     * mode). Hardcoding either environment's figures would push known-bad data into
     * the other, so:
     *
     *   - Paint brushes reuse THEIR OWN three prices, re-matched in ascending size
     *     order. No number is invented; the inversion is simply corrected.
     *   - Lintels treat the product's existing price as a PER-METRE rate, so a 3M
     *     lintel is 3x. A transparent rule the owner can eyeball and overwrite.
     *
     * These are placeholders pending real figures. `hide_pricing = 1` means no
     * customer sees them today. See CHANGELOG 2026-08-24.
     * ---------------------------------------------------------------------------
     *
     * Idempotent: a product that already has sizes is skipped, so re-running is safe.
     * Superseded duplicates are DEACTIVATED, never deleted — recoverable if a price
     * turns out to belong to the row rather than the size.
     */
    public function up(): void
    {
        $this->convertPaintBrushes();
        $this->convertLintels();
    }

    private function convertPaintBrushes(): void
    {
        $brushes = Product::where('name', 'REGEXP', '^Paint Brush [0-9]+ *MM$')->orderBy('id')->get();

        if ($brushes->count() < 2) {
            return; // Nothing to merge in this environment.
        }

        // Sort by the millimetre value in the name, not the name itself — that is the
        // whole bug being fixed here (100, 150, 50 as text).
        $sized = $brushes->map(function ($p) {
            preg_match('/([0-9]+) *MM$/i', $p->name, $m);

            return ['product' => $p, 'mm' => (int) ($m[1] ?? 0)];
        })->sortBy('mm')->values();

        // Reuse the group's own prices, ascending, so the cheapest belongs to the
        // smallest brush. No invented figures.
        $prices = $sized->pluck('product.price')->map(fn ($p) => (float) $p)->sort()->values();

        $survivor = $sized->first()['product'];

        if ($survivor->has_variants) {
            return; // Already converted.
        }

        DB::transaction(function () use ($sized, $prices, $survivor) {
            $survivor->update([
                'name' => 'Paint Brush',
                // Keep a clean URL only if it is genuinely free; a collision would
                // throw on the unique index mid-migration.
                'slug' => Product::where('slug', 'paint-brush')->exists()
                    ? $survivor->slug
                    : 'paint-brush',
                'has_variants' => true,
            ]);

            foreach ($sized as $i => $row) {
                $survivor->variants()->create([
                    'label' => $row['mm'].'MM',
                    'price' => $prices[$i],
                    'sku' => $row['product']->sku,
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ]);

                // Everything except the survivor is now represented as a size.
                // Deactivated rather than deleted so nothing is unrecoverable.
                if ($row['product']->id !== $survivor->id) {
                    $row['product']->update(['status' => 'inactive']);
                }
            }
        });
    }

    private function convertLintels(): void
    {
        // Local carries two rows with this name, production one. Convert each on its
        // own terms rather than assuming a single match.
        $lintels = Product::where('name', 'LIKE', 'Lintels from%M to%M')->orderBy('id')->get();

        foreach ($lintels as $lintel) {
            if ($lintel->has_variants) {
                continue;
            }

            // Read the range out of the name it was smuggled into.
            if (! preg_match('/from *([0-9]+) *M *to *([0-9]+) *M/i', $lintel->name, $m)) {
                continue;
            }

            [$from, $to] = [(int) $m[1], (int) $m[2]];

            if ($from < 1 || $to <= $from || $to > 20) {
                continue; // Not a range this migration should be guessing at.
            }

            $perMetre = (float) $lintel->price;

            DB::transaction(function () use ($lintel, $from, $to, $perMetre) {
                $lintel->update([
                    // The lengths live in the sizes now, so the name stops carrying them.
                    'name' => 'Lintels',
                    'has_variants' => true,
                ]);

                $position = 1;

                for ($metres = $from; $metres <= $to; $metres++) {
                    $lintel->variants()->create([
                        'label' => $metres.'M',
                        'price' => round($perMetre * $metres, 2),
                        'is_active' => true,
                        'sort_order' => $position++,
                    ]);
                }
            });
        }
    }

    /**
     * Puts the workaround shapes back.
     *
     * The deactivated brush rows are reactivated and the merged product returns to
     * naming its own size, so a rollback lands on the catalog as it was rather than
     * on a half-converted state.
     */
    public function down(): void
    {
        $brush = Product::where('name', 'Paint Brush')->first();

        if ($brush) {
            $smallest = $brush->variants()->orderBy('sort_order')->first();
            $brush->variants()->delete();
            $brush->update([
                'name' => $smallest ? 'Paint Brush '.$smallest->label : 'Paint Brush',
                'has_variants' => false,
            ]);
            Product::where('name', 'REGEXP', '^Paint Brush [0-9]+ *MM$')
                ->update(['status' => 'active']);
        }

        foreach (Product::where('name', 'Lintels')->get() as $lintel) {
            $labels = $lintel->variants()->orderBy('sort_order')->pluck('label');
            $lintel->variants()->delete();

            if ($labels->isNotEmpty()) {
                $lintel->update([
                    'name' => 'Lintels from '.$labels->first().' to '.$labels->last(),
                    'has_variants' => false,
                ]);
            }
        }
    }
};
