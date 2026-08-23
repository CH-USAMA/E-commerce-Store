{{--
    "Single product" vs "product with sizes" switch, plus the size rows.

    Shared by the create and edit forms so the two cannot drift. Rows carry a
    hidden `id` so editing a size keeps its identity — order history joins on it,
    and delete-then-recreate would orphan those rows.

    Unticking the switch does NOT delete the sizes; they stop being offered and
    return intact when it is re-ticked, which is what a seasonal range needs.

    @param  \App\Models\Product|null  $product
--}}
@php
    $existingRows = isset($product)
        ? $product->variants->map(fn ($v) => [
            'id' => $v->id,
            'label' => $v->label,
            'sku' => $v->sku,
            'price' => (string) $v->price,
            'is_active' => (bool) $v->is_active,
        ])->values()->all()
        : [];

    // old() wins so a rejected save does not discard what was typed.
    $rows = old('variants', $existingRows);
    $rows = array_values(array_map(fn ($r) => [
        'id' => $r['id'] ?? '',
        'label' => $r['label'] ?? '',
        'sku' => $r['sku'] ?? '',
        'price' => $r['price'] ?? '',
        'is_active' => (bool) ($r['is_active'] ?? false),
    ], $rows));

    $enabled = (bool) old('has_variants', isset($product) ? $product->has_variants : false);
@endphp

<div class="mb-3 pt-3 border-top" style="border-color: var(--border-default) !important;"
     x-data="{
        enabled: {{ $enabled ? 'true' : 'false' }},
        rows: @js($rows),
        add() { this.rows.push({ id: '', label: '', sku: '', price: '', is_active: true }); },
        remove(i) { this.rows.splice(i, 1); }
     }">

    <div class="form-check mb-2">
        <input type="hidden" name="has_variants" value="0">
        <input type="checkbox" name="has_variants" value="1" id="has_variants"
               class="form-check-input" x-model="enabled">
        <label for="has_variants" class="form-check-label fw-bold" style="font-size: 0.8rem;">
            This product comes in different sizes
        </label>
        <div class="form-text" style="font-size: 0.7rem;">
            For things sold by length or size &mdash; a lintel in 1.2m, 1.5m, 4.6m, 4.8m.
            Customers pick the size on the product page and see that size&rsquo;s price,
            instead of you creating a separate product for each one.
        </div>
    </div>

    <div x-show="enabled" x-cloak class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold" style="font-size: 0.78rem;">Sizes</span>
            <button type="button" class="btn btn-outline-primary btn-sm" @click="add()">
                <i class="fas fa-plus me-1"></i> Add Size
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 28px;"></th>
                        <th>Size <span class="text-danger">*</span></th>
                        <th style="width: 130px;">Price (R) <span class="text-danger">*</span></th>
                        <th style="width: 140px;">Code <span class="text-muted fw-normal">(optional)</span></th>
                        <th style="width: 70px;">Shown</th>
                        <th style="width: 44px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, i) in rows" :key="i">
                        <tr>
                            <td class="text-muted" style="font-size: 0.7rem;" x-text="i + 1"></td>
                            <td>
                                <input type="hidden" :name="`variants[${i}][id]`" :value="row.id">
                                <input type="text" class="form-control form-control-sm"
                                       :name="`variants[${i}][label]`" x-model="row.label"
                                       placeholder="e.g. 1.2m">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" class="form-control form-control-sm"
                                       :name="`variants[${i}][price]`" x-model="row.price"
                                       placeholder="0.00">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm"
                                       :name="`variants[${i}][sku]`" x-model="row.sku"
                                       placeholder="&mdash;">
                            </td>
                            <td class="text-center">
                                {{-- Unchecked posts nothing, which the controller reads as false. --}}
                                <input type="checkbox" class="form-check-input"
                                       :name="`variants[${i}][is_active]`" value="1"
                                       x-model="row.is_active">
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm py-0 px-1"
                                        title="Remove this size" @click="remove(i)">
                                    <i class="fas fa-times" style="font-size: 0.65rem;"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="rows.length === 0">
                        <td colspan="6" class="text-center py-3" style="color: var(--text-muted); font-size: 0.75rem;">
                            No sizes yet &mdash; click <strong>Add Size</strong> to start.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-text mt-2" style="font-size: 0.7rem;">
            Sizes appear on the site in the order listed here &mdash; drag is not needed,
            just enter them in the order you want. That order matters: sizes sort badly
            alphabetically (50MM would come after 150MM).
            <br>Untick <strong>Shown</strong> to hide one size while keeping the rest on sale.
        </div>
    </div>
</div>

@once
    @push('css')
        <style>[x-cloak] { display: none !important; }</style>
    @endpush
@endonce
