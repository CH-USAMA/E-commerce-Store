{{--
    Build credit for Jabulani Tech Solutions.

    Kept as one partial so the agency name and URL live in a single place — it
    appears in both the storefront footer and the customer portal footer, and a
    rename or domain change should not mean hunting through layouts.

    @param  string  $class  optional wrapper classes, for the two different footers
--}}
<p class="{{ $class ?? 'text-xs text-dark-muted tracking-wide' }}">
    Developed by
    <a href="https://agency.jabulanigroupofcompanies.co.za/" target="_blank" rel="noopener noreferrer"
       class="text-gold-400 hover:text-white transition-colors font-semibold">
        Jabulani Tech Solutions
    </a>
</p>
