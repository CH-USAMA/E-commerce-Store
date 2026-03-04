<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\GalleryItem;
use App\Models\Banner;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DOMDocument;
use DOMXPath;

class StaticDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBanners();
        $this->seedStores();
        $this->seedBrands();
        $this->seedServices();
        $this->seedTeam();
        $this->seedProducts();
        $this->seedBlogs();
        $this->seedGallery();
    }

    private function seedBanners()
    {
        $path = base_path('../index.html');
        if (!file_exists($path))
            return;

        $html = file_get_contents($path);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $slides = $xpath->query("//div[contains(@class, 'swiper-slide')]//div[@class='hero-slide']");
        foreach ($slides as $slide) {
            $imgNode = $xpath->query(".//div[@class='hero-slider-image']/img", $slide)->item(0);
            $img = $imgNode ? $imgNode->getAttribute('src') : null;

            $titleNode = $xpath->query(".//h1", $slide)->item(0);
            $title = $titleNode ? trim($titleNode->nodeValue) : 'Jabulani Group of Companies';

            $pNodes = $xpath->query(".//div[@class='typing-title']/p", $slide);
            $subtitle = "";
            if ($pNodes->length > 0) {
                $subtitles = [];
                foreach ($pNodes as $p) {
                    $subtitles[] = trim($p->nodeValue);
                }
                $subtitle = implode(", ", $subtitles);
            }

            $descNode = $xpath->query(".//div[@class='hero-video-content']/p", $slide)->item(0);
            $desc = $descNode ? trim($descNode->nodeValue) : '';

            $videoNode = $xpath->query(".//a[contains(@class, 'popup-video')]", $slide)->item(0);
            $video = $videoNode ? $videoNode->getAttribute('href') : null;

            if ($img) {
                Banner::firstOrCreate(['image' => $img], [
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'link' => '/contact',
                ]);
            }
        }
    }

    private function seedStores()
    {
        $path = base_path('../index.html');
        if (!file_exists($path))
            return;

        $html = file_get_contents($path);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $items = $xpath->query("//div[contains(@class, 'project-item-box2')]");
        foreach ($items as $item) {
            $nameNode = $xpath->query(".//div[@class='project-content']/h3", $item)->item(0);
            $imgNode = $xpath->query(".//div[@class='project-image']//img", $item)->item(0);
            $linkNode = $xpath->query(".//div[@class='project-btn']/a", $item)->item(0);

            if ($nameNode && $linkNode) {
                $name = trim($nameNode->nodeValue);
                $image = $imgNode ? $imgNode->getAttribute('src') : null;
                $url = $linkNode->getAttribute('href');

                // Parse URL parameters for store details
                $queryString = parse_url($url, PHP_URL_QUERY);
                parse_str($queryString, $params);

                Store::firstOrCreate(['name' => $name], [
                    'slug' => Str::slug($name),
                    'image' => $image,
                    'address' => $params['address'] ?? 'Eastern Cape, South Africa',
                    'province' => 'Eastern Cape',
                    'lat' => $params['lat'] ?? null,
                    'lng' => $params['lng'] ?? null,
                    'contact_details' => ($params['email'] ?? '') . ' | ' . ($params['num'] ?? ''),
                ]);
            }
        }
    }

    private function seedBrands()
    {
        $path = base_path('../index.html');
        if (!file_exists($path))
            return;

        $html = file_get_contents($path);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $logos = $xpath->query("//div[@class='agency-supports-logo']/img");
        foreach ($logos as $logo) {
            $src = $logo->getAttribute('src');
            $name = pathinfo($src, PATHINFO_FILENAME);
            $name = str_replace(['_', '-'], ' ', $name);
            $name = ucwords($name);

            Brand::firstOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'logo' => $src,
            ]);
        }
    }

    private function seedServices()
    {
        $path = base_path('../services.html');
        if (!file_exists($path))
            return;

        $html = file_get_contents($path);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $services = $xpath->query("//div[contains(@class, 'service-item')]");
        foreach ($services as $service) {
            $titleNode = $xpath->query(".//div[@class='service-item-body']/h3", $service)->item(0);
            $descNode = $xpath->query(".//div[@class='service-item-body']/p", $service)->item(0);

            if ($titleNode) {
                $title = trim($titleNode->nodeValue);
                $desc = $descNode ? trim($descNode->nodeValue) : '';

                Service::firstOrCreate(['slug' => Str::slug($title)], [
                    'title' => $title,
                    'description' => $desc,
                    'icon' => 'fa fa-cog',
                ]);
            }
        }
    }

    private function seedTeam()
    {
        $path = base_path('../team.html');
        if (!file_exists($path))
            return;

        $html = file_get_contents($path);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $nameNode = $xpath->query("//div[@class='team-info-title']/h2")->item(0);
        $roleNode = $xpath->query("//div[@class='team-info-title']/p")->item(0);
        $bioNode = $xpath->query("//div[@class='team-contect-list']")->item(0);
        $imgNode = $xpath->query("//div[@class='team-single-image']//img")->item(0);

        if ($nameNode) {
            TeamMember::firstOrCreate(['name' => trim($nameNode->nodeValue)], [
                'role' => $roleNode ? trim($roleNode->nodeValue) : 'CEO',
                'bio' => $bioNode ? trim($bioNode->nodeValue) : '',
                'image' => $imgNode ? $imgNode->getAttribute('src') : null,
            ]);
        }
    }

    private function seedProducts()
    {
        $path = base_path('../products.html');
        if (!file_exists($path))
            return;

        $html = file_get_contents($path);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $items = $xpath->query("//div[contains(@class, 'project-item-box')]");
        foreach ($items as $index => $item) {
            $nameNode = $xpath->query(".//div[@class='project-content']/h3", $item)->item(0);
            $imgNode = $xpath->query(".//div[@class='project-image']//img", $item)->item(0);
            $tagNode = $xpath->query(".//div[@class='project-tag']/a", $item)->item(0);

            if ($nameNode) {
                $name = trim($nameNode->nodeValue);
                $categoryName = $tagNode ? trim($tagNode->nodeValue) : 'General';
                $image = $imgNode ? $imgNode->getAttribute('src') : null;

                $category = Category::firstOrCreate(['name' => $categoryName], ['slug' => Str::slug($categoryName)]);

                Product::firstOrCreate(
                    ['name' => $name],
                    [
                        'slug' => Str::slug($name) . '-' . ($index + 1),
                        'category_id' => $category->id,
                        'image' => $image,
                        'description' => "Jabulani " . $name . " - High quality construction material.",
                        'sku' => 'JB-' . strtoupper(Str::random(8)),
                        'price' => 0.00,
                    ]
                );
            }
        }
    }

    private function seedBlogs()
    {
        $path = base_path('../blog.html');
        if (!file_exists($path))
            return;

        $html = file_get_contents($path);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $author = User::first();
        if (!$author)
            return;

        $category = BlogCategory::firstOrCreate(['name' => 'General'], ['slug' => 'general']);

        $posts = $xpath->query("//div[contains(@class, 'post-item')]");
        foreach ($posts as $post) {
            $titleNode = $xpath->query(".//div[@class='post-item-content']/h3/a", $post)->item(0);
            $imgNode = $xpath->query(".//div[@class='post-featured-image']//img", $post)->item(0);
            $linkNode = $xpath->query(".//div[@class='post-item-content']/h3/a", $post)->item(0);

            if ($titleNode) {
                $title = trim($titleNode->nodeValue);
                $image = $imgNode ? $imgNode->getAttribute('src') : null;
                $link = $linkNode ? $linkNode->getAttribute('href') : null;

                $content = "This is a placeholder content for " . $title . ". Jabulani Group provides reliable and efficient services in hardware, building material, construction, and logistics across South Africa.";

                if ($link && file_exists(base_path('../' . $link))) {
                    $singleHtml = file_get_contents(base_path('../' . $link));
                    $singleDom = new DOMDocument();
                    @$singleDom->loadHTML($singleHtml);
                    $singleXpath = new DOMXPath($singleDom);
                    $entryNode = $singleXpath->query("//div[@class='post-entry']")->item(0);
                    if ($entryNode) {
                        $content = $singleDom->saveHTML($entryNode);
                    }
                }

                BlogPost::firstOrCreate(
                    ['title' => $title],
                    [
                        'blog_category_id' => $category->id,
                        'author_id' => $author->id,
                        'slug' => Str::slug($title),
                        'content' => $content,
                        'feature_image' => $image,
                        'is_published' => true,
                    ]
                );
            }
        }
    }

    private function seedGallery()
    {
        $path = base_path('../image-gallery.html');
        if (!file_exists($path))
            return;

        $html = file_get_contents($path);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        $items = $xpath->query("//div[contains(@class, 'photo-gallery')]/a");
        foreach ($items as $item) {
            $image = $item->getAttribute('href');
            if ($image) {
                GalleryItem::firstOrCreate([
                    'image' => $image,
                ], [
                    'title' => 'Gallery Showcase',
                    'category' => 'General'
                ]);
            }
        }
    }
}
