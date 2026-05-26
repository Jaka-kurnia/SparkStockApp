<link href="{{ asset('dist/css/tabler.min.css?1692870487') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/tabler-flags.min.css?1692870487') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/tabler-payments.min.css?1692870487') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/tabler-vendors.min.css?1692870487') }}" rel="stylesheet" />
<link href="{{ asset('dist/css/demo.min.css?1692870487') }}" rel="stylesheet" />
<style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

<style>
    .navbar-nav .nav-item.active {
        background-color: transparent !important;
    }

    .navbar-nav .nav-item.active>.nav-link,
    .navbar-nav .nav-item.active>.nav-link .nav-link-icon i,
    .navbar-nav .nav-item.active>.nav-link .nav-link-title {
        color: inherit !important;
        /* Mengikuti warna teks default tema Tabler */
    }

    /* 2. Style Khusus untuk Sub-Menu (Dropdown Item) yang Sedang Aktif */
    .navbar-nav .dropdown-menu .dropdown-item.active {
        background-color: #0054a6 !important;
        /* Warna biru utama */
        color: #ffffff !important;
        /* Warna font menjadi putih */
        border-radius: 4px;
        /* Membuat sudut sedikit melengkung rapi */
        font-weight: 500;
        /* Membuat teks sedikit lebih tegas */
        transition: all 0.15s ease-in-out;
    }

    /* Memberikan sedikit ruang (margin) di dalam list dropdown agar background aktif terlihat rapi */
    .navbar-nav .dropdown-menu .dropdown-item {
        margin: 2px 8px;
        width: calc(100% - 16px);
        /* Memastikan lebar item pas setelah dikurangi margin */
    }


    .navbar-nav .dropdown-menu .dropdown-item.active:hover {
        background-color: #00458a !important;
        color: #ffffff !important;
    }
</style>
