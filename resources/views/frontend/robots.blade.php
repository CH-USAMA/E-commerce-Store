User-agent: *
Allow: /

# Block admin and private areas from crawling
Disallow: /admin/
Disallow: /branch/
Disallow: /user/
Disallow: /cart/
Disallow: /checkout/
Disallow: /order-success
Disallow: /email/
Disallow: /profile/
Disallow: /logout
Disallow: /register
Disallow: /login

# Allow important public pages
Allow: /products
Allow: /product/
Allow: /stores
Allow: /store/
Allow: /blog/
Allow: /about
Allow: /contact
Allow: /specials
Allow: /track-order

# Sitemap location
Sitemap: {{ config('app.url') }}/sitemap.xml
