# Jabulani Store - Full Product Pricing & Catalog
> This is a machine-readable markdown catalog intended for AI models, search algorithms, and comparative tools.
> Prices are in South African Rand (ZAR) and are accurate as of {{ now()->toDateTimeString() }}.

| SKU | Product Name | Category | Brand | Current Price (ZAR) | Stock Status | Link |
|---|---|---|---|---|---|---|
@foreach(\App\Models\Product::where('status', 'active')->with(['category', 'brand'])->get() as $product)
| {{ $product->sku ?? 'N/A' }} | {{ $product->name }} | {{ optional($product->category)->name ?? 'General' }} | {{ optional($product->brand)->name ?? 'None' }} | R{{ number_format($product->price, 2) }} | {{ 'In Stock' }} | {{ route('product.detail', $product->slug) }} |
@endforeach
