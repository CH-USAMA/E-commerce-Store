<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = \Illuminate\Support\Facades\Cache::remember('banners', 3600, fn() => \App\Models\Banner::all());
        $stores = \Illuminate\Support\Facades\Cache::remember('stores_all', 3600, fn() => \App\Models\Store::all());
        $brands = \Illuminate\Support\Facades\Cache::remember('brands', 3600, fn() => \App\Models\Brand::all());
        $categories = \App\Models\Category::topLevel()->with('children')->get();
        // Fetch products by new flags, with fallbacks for unflagged databases
        $featuredProducts = \App\Models\Product::with('category', 'subcategory')->where('is_featured', true)->take(12)->get();
        $topSellingProducts = \App\Models\Product::with('category', 'subcategory')->where('is_top_selling', true)->take(10)->get();
        if ($topSellingProducts->isEmpty()) {
            $topSellingProducts = \App\Models\Product::with('category', 'subcategory')->inRandomOrder()->take(10)->get();
        }
        $newArrivalProducts = \App\Models\Product::with('category', 'subcategory')->where('is_new_arrival', true)->take(10)->get();
        if ($newArrivalProducts->isEmpty()) {
            $newArrivalProducts = \App\Models\Product::with('category', 'subcategory')->latest()->take(10)->get();
        }

        $latestPosts = \App\Models\BlogPost::with('category')->latest()->take(3)->get();

        return view('home', compact('banners', 'stores', 'brands', 'categories', 'featuredProducts', 'topSellingProducts', 'newArrivalProducts', 'latestPosts'));
    }

    public function about()
    {
        $teamMembers = \Illuminate\Support\Facades\Cache::remember('team_about', 3600, fn() => \App\Models\TeamMember::take(4)->get());
        return view('frontend.about', compact('teamMembers'));
    }

    public function services()
    {
        return view('frontend.services');
    }

    public function blog()
    {
        $posts = \App\Models\BlogPost::with('category')->latest()->paginate(9);
        return view('frontend.blog', compact('posts'));
    }

    public function blogDetail($slug)
    {
        $post = \Illuminate\Support\Facades\Cache::remember("blog_post_$slug", 3600, fn() => \App\Models\BlogPost::where('slug', $slug)->firstOrFail());
        return view('frontend.blog-detail', compact('post'));
    }

    public function team()
    {
        $teamMembers = \Illuminate\Support\Facades\Cache::remember('team_all', 3600, fn() => \App\Models\TeamMember::all());
        return view('frontend.team', compact('teamMembers'));
    }

    public function gallery()
    {
        $galleryItems = \Illuminate\Support\Facades\Cache::remember('gallery_all', 3600, fn() => \App\Models\GalleryItem::all());
        return view('frontend.gallery', compact('galleryItems'));
    }

    public function stores()
    {
        $stores = \Illuminate\Support\Facades\Cache::remember('stores_page', 3600, fn() => \App\Models\Store::all());
        return view('frontend.stores', compact('stores'));
    }

    public function testimonials()
    {
        return view('frontend.testimonials');
    }

    public function videoGallery()
    {
        return view('frontend.video-gallery');
    }

    public function specials()
    {
        return view('frontend.specials');
    }

    public function storeDetail($id)
    {
        $store = \Illuminate\Support\Facades\Cache::remember("store_detail_$id", 3600, fn() => \App\Models\Store::findOrFail($id));
        return view('frontend.store-detail', compact('store'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function products(Request $request)
    {
        // Top-level categories with their sub-categories
        $topCategories = \App\Models\Category::topLevel()->with('children')->get();

        $selectedCategory = $request->get('category');
        $selectedSubcategory = $request->get('subcategory');
        $search = $request->get('search');
        $sort = $request->get('sort', 'latest');

        $query = \App\Models\Product::with('category', 'subcategory')
            ->when($selectedCategory, function ($q) use ($selectedCategory) {
                $q->whereHas('category', fn($q) => $q->where('slug', $selectedCategory));
            })
            ->when($selectedSubcategory, function ($q) use ($selectedSubcategory) {
                $q->whereHas('subcategory', fn($q) => $q->where('slug', $selectedSubcategory));
            })
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            });

        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        // For highlighted active category/subcategory in sidebar
        $activeCategory = $selectedCategory
            ? \App\Models\Category::where('slug', $selectedCategory)->first()
            : null;
        $activeSubcategory = $selectedSubcategory
            ? \App\Models\Category::where('slug', $selectedSubcategory)->first()
            : null;

        return view('frontend.products', compact(
            'topCategories',
            'products',
            'activeCategory',
            'activeSubcategory',
            'search',
            'sort'
        ));
    }

    public function productDetail($slug)
    {
        $product = \App\Models\Product::where('slug', $slug)
            ->with('category', 'subcategory', 'brand')
            ->firstOrFail();
        return view('frontend.product-single', compact('product'));
    }

    public function submitContact(Request $request)
    {
        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    public function search(Request $request)
    {
        return redirect()->route('products', ['search' => $request->get('q')]);
    }
}
