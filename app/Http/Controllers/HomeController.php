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
        $featuredProducts = \App\Models\Product::with('category')->take(12)->get();
        $latestPosts = \App\Models\BlogPost::with('category')->latest()->take(3)->get();

        return view('home', compact('banners', 'stores', 'brands', 'featuredProducts', 'latestPosts'));
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
        $categories = \Illuminate\Support\Facades\Cache::remember('categories_products', 3600, fn() => \App\Models\Category::with('products')->get());

        $selectedCategory = $request->get('category');
        $products = \App\Models\Product::with('category')->when($selectedCategory, function ($query) use ($selectedCategory) {
            return $query->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        })->paginate(12);

        return view('frontend.products', compact('categories', 'products'));
    }

    public function productDetail($slug)
    {
        $product = \Illuminate\Support\Facades\Cache::remember("product_detail_$slug", 3600, fn() => \App\Models\Product::where('slug', $slug)->with('category')->firstOrFail());
        return view('frontend.product-single', compact('product'));
    }

    public function submitContact(Request $request)
    {
        // For now, just a placeholder. 
        // Later we can send email or save to DB.
        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
