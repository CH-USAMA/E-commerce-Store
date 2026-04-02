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

# Explicitly allow AI Agents to read the public catalog
User-agent: GPTBot
Allow: /

User-agent: ChatGPT-User
Allow: /

User-agent: anthropic-ai
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: Google-Extended
Allow: /

# Sitemap location
Sitemap: {{ config('app.url') }}/sitemap.xml
