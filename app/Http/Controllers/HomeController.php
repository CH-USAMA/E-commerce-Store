<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = \App\Models\Banner::all();
        $stores = \App\Models\Store::all();
        $brands = \App\Models\Brand::all();
        $featuredProducts = \App\Models\Product::with('category')->take(12)->get();
        $latestPosts = \App\Models\BlogPost::with('category')->latest()->take(3)->get();

        return view('home', compact('banners', 'stores', 'brands', 'featuredProducts', 'latestPosts'));
    }

    public function about()
    {
        $teamMembers = \App\Models\TeamMember::take(4)->get();
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
        $post = \App\Models\BlogPost::where('slug', $slug)->firstOrFail();
        return view('frontend.blog-detail', compact('post'));
    }

    public function team()
    {
        $teamMembers = \App\Models\TeamMember::all();
        return view('frontend.team', compact('teamMembers'));
    }

    public function gallery()
    {
        $galleryItems = \App\Models\GalleryItem::all();
        return view('frontend.gallery', compact('galleryItems'));
    }

    public function stores()
    {
        $stores = \App\Models\Store::all();
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
        // For now, these might be static or fetched from a 'Promotion' model if created.
        return view('frontend.specials');
    }

    public function storeDetail($id)
    {
        $store = \App\Models\Store::findOrFail($id);
        return view('frontend.store-detail', compact('store'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function products(Request $request)
    {
        $categories = \App\Models\Category::with('products')->get();
        // Filter by category if requested
        $selectedCategory = $request->get('category');
        $products = \App\Models\Product::when($selectedCategory, function ($query) use ($selectedCategory) {
            return $query->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        })->paginate(12);

        return view('frontend.products', compact('categories', 'products'));
    }

    public function productDetail($slug)
    {
        $product = \App\Models\Product::where('slug', $slug)->with('category')->firstOrFail();
        return view('frontend.product-single', compact('product'));
    }

    public function submitContact(Request $request)
    {
        // For now, just a placeholder. 
        // Later we can send email or save to DB.
        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
