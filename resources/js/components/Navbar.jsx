import React, { useState, useEffect, useRef } from 'react';

export default function Navbar({
    site = {},
    auth = {},
    routes = {},
    stocks = [],
    packages = [],
    cardBenefits = [],
    lang = 'en',
    onSwitchLang = () => {},
    theme = 'light',
    onToggleTheme = () => {},
    t = {}
}) {
    const [scrolled, setScrolled] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    // Search state
    const [searchQuery, setSearchQuery] = useState('');
    const [searchOpen, setSearchOpen] = useState(false);
    const [isSearching, setIsSearching] = useState(false);
    const [searchResults, setSearchResults] = useState({
        stocks: [],
        packages: [],
        cards: [],
        pages: [],
        total: 0
    });

    const searchWrapRef = useRef(null);
    const mobileSearchWrapRef = useRef(null);
    const debounceTimerRef = useRef(null);

    useEffect(() => {
        const handleScroll = () => {
            if (window.scrollY > 30) {
                setScrolled(true);
            } else {
                setScrolled(false);
            }
        };
        window.addEventListener('scroll', handleScroll, { passive: true });
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    // Prevent body scroll when mobile menu is open
    useEffect(() => {
        if (mobileMenuOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'unset';
        }
    }, [mobileMenuOpen]);

    // Close search dropdown on click outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (
                searchWrapRef.current &&
                !searchWrapRef.current.contains(event.target) &&
                mobileSearchWrapRef.current &&
                !mobileSearchWrapRef.current.contains(event.target)
            ) {
                setSearchOpen(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const currentPath = typeof window !== 'undefined' ? window.location.pathname : '/';

    const navLinks = [
        { label: t.home || 'Home', url: routes.home || '/', active: currentPath === '/' || currentPath === '' },
        { label: t.about || 'About Us', url: routes.about || '/about', active: currentPath.includes('/about') },
        { label: t.stocks || 'Stock Market', url: routes.stock_index || '/stocks', active: currentPath.includes('/stocks') || currentPath.includes('/stock') },
        { label: t.packages_nav || 'Our Packages', url: (routes.home || '/') + '#packages', active: false },
        { label: t.cards_nav || 'Membership Cards', url: (routes.home || '/') + '#card-benefits', active: false },
        { label: t.gallery || 'Gallery', url: routes.gallery || '/gallery', active: currentPath.includes('/gallery') },
        { label: t.contact || 'Contact', url: routes.contact || '/contact', active: currentPath.includes('/contact') },
        { label: t.terms || 'Terms & Conditions', url: routes.terms || '/terms', active: currentPath.includes('/terms') },
        { label: t.privacy || 'Privacy Policy', url: routes.privacy || '/privacy-policy', active: currentPath.includes('/privacy') },
    ];

    // Static pages lookup definition
    const staticPages = [
        { title: t.home || 'Home', subtitle: 'Main Homepage', url: routes.home || '/', keywords: 'home main index হোম ekrishisheba' },
        { title: t.about || 'About Us', subtitle: 'Who We Are', url: routes.about || '/about', keywords: 'about us info কোম্পানি আমাদের সম্পর্কে' },
        { title: t.stocks || 'Stock Market', subtitle: 'Live Rates & Trading', url: routes.stock_index || '/stocks', keywords: 'stocks market live price দর স্টক মার্কেট কৃষি পণ্য' },
        { title: t.pkg_title || 'Our Packages', subtitle: 'Exclusive Packages', url: (routes.home || '/') + '#packages', keywords: 'packages offer discount প্যাকেজ আওয়ার প্যাকেজ' },
        { title: t.card_title || 'Membership Cards', subtitle: 'SME & Gold Cards', url: (routes.home || '/') + '#card-benefits', keywords: 'cards sme gold card membership মেম্বারশিপ কার্ড গোল্ড' },
        { title: t.gallery || 'Produce Gallery', subtitle: 'Farm & Commodity Photos', url: routes.gallery || '/gallery', keywords: 'gallery photo images ছবি গ্যালারি প্রোডাক্ট' },
        { title: t.contact || 'Contact Us', subtitle: 'Support & Helpline', url: routes.contact || '/contact', keywords: 'contact support helpline যোগাযোগ হেল্পলাইন ফোন' },
        { title: t.terms || 'Terms & Conditions', subtitle: 'Platform Policies', url: routes.terms || '/terms', keywords: 'terms conditions নিয়মাবলী শর্তাবলী টার্মস' },
        { title: t.privacy || 'Privacy Policy', subtitle: 'User Data Security', url: routes.privacy || '/privacy-policy', keywords: 'privacy policy সিকিউরিটি প্রাইভেসি পলিসি' },
        { title: t.login || 'Login', subtitle: 'Access Account', url: routes.login || '/login', keywords: 'login signin প্রবেশ লগইন একাউন্ট' },
        { title: t.register || 'Register', subtitle: 'Create Account', url: routes.register || '/register', keywords: 'register signup registration নিবন্ধন রেজিস্ট্রেশন অ্যাকাউন্ট তৈরি' },
    ];

    // Instant local filter combined with backend search
    const performSearch = (query) => {
        const q = query.trim().toLowerCase();
        if (!q) {
            setSearchResults({ stocks: [], packages: [], cards: [], pages: [], total: 0 });
            setSearchOpen(false);
            return;
        }

        // 1. Filter local stocks
        const matchedStocks = stocks.filter((s) => {
            const name = (s.name || '').toLowerCase();
            const cat = (s.category || '').toLowerCase();
            return name.includes(q) || cat.includes(q);
        }).slice(0, 5).map((s) => ({
            id: s.id,
            type: 'stock',
            title: s.name,
            subtitle: s.category || 'Agro Stock',
            price: `৳${Number(s.selling_price || 0).toLocaleString('en-US')}`,
            image: s.image,
            url: s.details_url || `/stock/${s.id}`
        }));

        // 2. Filter local packages
        const matchedPackages = packages.filter((p) => {
            const title = (p.title || '').toLowerCase();
            const desc = (p.description || '').toLowerCase();
            return title.includes(q) || desc.includes(q);
        }).slice(0, 4).map((p) => ({
            id: p.id,
            type: 'package',
            title: p.title || 'Package',
            subtitle: p.price ? `Price: ${p.price}` : 'Special Offer',
            image: p.image,
            url: (routes.home || '/') + '#packages'
        }));

        // 3. Filter local cards
        const matchedCards = cardBenefits.filter((c) => {
            const name = (c.card_name || '').toLowerCase();
            const type = (c.card_type || '').toLowerCase();
            const desc = (c.short_description || '').toLowerCase();
            return name.includes(q) || type.includes(q) || desc.includes(q);
        }).slice(0, 3).map((c) => ({
            id: c.id,
            type: 'card',
            title: c.card_name,
            subtitle: c.badge_text || c.card_type || 'Membership Card',
            image: c.image,
            url: (routes.home || '/') + '#card-benefits'
        }));

        // 4. Filter static pages
        const matchedPages = staticPages.filter((p) => {
            const title = (p.title || '').toLowerCase();
            const sub = (p.subtitle || '').toLowerCase();
            const kw = (p.keywords || '').toLowerCase();
            return title.includes(q) || sub.includes(q) || kw.includes(q);
        }).slice(0, 4);

        const localTotal = matchedStocks.length + matchedPackages.length + matchedCards.length + matchedPages.length;

        setSearchResults({
            stocks: matchedStocks,
            packages: matchedPackages,
            cards: matchedCards,
            pages: matchedPages,
            total: localTotal
        });
        setSearchOpen(true);

        // Debounced Deep Search from API
        if (debounceTimerRef.current) clearTimeout(debounceTimerRef.current);
        debounceTimerRef.current = setTimeout(async () => {
            try {
                setIsSearching(true);
                const searchApiUrl = routes.live_search || '/api/live-search';
                const res = await fetch(`${searchApiUrl}?q=${encodeURIComponent(query)}`);
                if (res.ok) {
                    const data = await res.json();
                    if (data && typeof data.total !== 'undefined') {
                        setSearchResults({
                            stocks: data.stocks && data.stocks.length > 0 ? data.stocks : matchedStocks,
                            packages: data.packages && data.packages.length > 0 ? data.packages : matchedPackages,
                            cards: data.cards && data.cards.length > 0 ? data.cards : matchedCards,
                            pages: data.pages && data.pages.length > 0 ? data.pages : matchedPages,
                            total: data.total || localTotal
                        });
                    }
                }
            } catch (err) {
                // Keep local results if network error
            } finally {
                setIsSearching(false);
            }
        }, 300);
    };

    const handleSearchChange = (e) => {
        const val = e.target.value;
        setSearchQuery(val);
        performSearch(val);
    };

    const handleSearchSubmit = (e) => {
        if (e) e.preventDefault();
        if (searchQuery.trim().length > 0) {
            performSearch(searchQuery);
            setSearchOpen(true);
        }
    };

    const clearSearch = () => {
        setSearchQuery('');
        setSearchResults({ stocks: [], packages: [], cards: [], pages: [], total: 0 });
        setSearchOpen(false);
    };

    const handleResultClick = (url) => {
        setSearchOpen(false);
        setMobileMenuOpen(false);
        if (url) {
            window.location.href = url;
        }
    };

    // Render results dropdown items
    const renderSearchResultsDropdown = () => (
        <div className="header-search-dropdown-menu">
            <div className="search-dropdown-header d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                <span className="small text-muted fw-bold">
                    {isSearching ? (
                        <span><i className="fa fa-spinner fa-spin me-1 text-warning"></i> Searching...</span>
                    ) : (
                        <span>Results ({searchResults.total})</span>
                    )}
                </span>
                <button
                    type="button"
                    className="btn btn-sm btn-link text-muted p-0 text-decoration-none"
                    onClick={() => setSearchOpen(false)}
                >
                    <i className="fa fa-times"></i>
                </button>
            </div>

            <div className="search-dropdown-body">
                {searchResults.total === 0 ? (
                    <div className="p-4 text-center text-muted">
                        <i className="fa fa-search fa-2x mb-2 text-warning opacity-75"></i>
                        <p className="mb-0 small">{t.search_no_results} "{searchQuery}"</p>
                    </div>
                ) : (
                    <>
                        {/* Stocks */}
                        {searchResults.stocks && searchResults.stocks.length > 0 && (
                            <div className="search-group-section">
                                <div className="search-group-title">
                                    <i className="fa fa-line-chart me-1 text-success"></i> {t.stocks_group}
                                </div>
                                {searchResults.stocks.map((item) => (
                                    <div
                                        key={`stock-${item.id}`}
                                        className="search-result-row"
                                        onClick={() => handleResultClick(item.url)}
                                    >
                                        <div className="result-thumb-box">
                                            {item.image ? (
                                                <img src={item.image} alt={item.title} />
                                            ) : (
                                                <i className="fa fa-cubes text-muted"></i>
                                            )}
                                        </div>
                                        <div className="result-info-box flex-grow-1">
                                            <div className="result-title">{item.title}</div>
                                            <div className="result-sub text-muted small">{item.subtitle}</div>
                                        </div>
                                        {item.price && (
                                            <div className="result-price-badge">
                                                {item.price}
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Packages */}
                        {searchResults.packages && searchResults.packages.length > 0 && (
                            <div className="search-group-section">
                                <div className="search-group-title">
                                    <i className="fa fa-gift me-1 text-warning"></i> {t.packages_group}
                                </div>
                                {searchResults.packages.map((item) => (
                                    <div
                                        key={`pkg-${item.id}`}
                                        className="search-result-row"
                                        onClick={() => handleResultClick(item.url)}
                                    >
                                        <div className="result-thumb-box">
                                            {item.image ? (
                                                <img src={item.image} alt={item.title} />
                                            ) : (
                                                <i className="fa fa-gift text-muted"></i>
                                            )}
                                        </div>
                                        <div className="result-info-box flex-grow-1">
                                            <div className="result-title">{item.title}</div>
                                            <div className="result-sub text-muted small">{item.subtitle}</div>
                                        </div>
                                        <i className="fa fa-angle-right text-muted"></i>
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Membership Cards */}
                        {searchResults.cards && searchResults.cards.length > 0 && (
                            <div className="search-group-section">
                                <div className="search-group-title">
                                    <i className="fa fa-credit-card me-1 text-primary"></i> {t.cards_group}
                                </div>
                                {searchResults.cards.map((item) => (
                                    <div
                                        key={`card-${item.id}`}
                                        className="search-result-row"
                                        onClick={() => handleResultClick(item.url)}
                                    >
                                        <div className="result-thumb-box">
                                            {item.image ? (
                                                <img src={item.image} alt={item.title} />
                                            ) : (
                                                <i className="fa fa-id-card text-muted"></i>
                                            )}
                                        </div>
                                        <div className="result-info-box flex-grow-1">
                                            <div className="result-title">{item.title}</div>
                                            <div className="result-sub text-muted small">{item.subtitle}</div>
                                        </div>
                                        <i className="fa fa-angle-right text-muted"></i>
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Static Pages */}
                        {searchResults.pages && searchResults.pages.length > 0 && (
                            <div className="search-group-section">
                                <div className="search-group-title">
                                    <i className="fa fa-link me-1 text-info"></i> {t.pages_group}
                                </div>
                                {searchResults.pages.map((item, idx) => (
                                    <div
                                        key={`page-${idx}`}
                                        className="search-result-row"
                                        onClick={() => handleResultClick(item.url)}
                                    >
                                        <div className="result-icon-circle">
                                            <i className={`fa ${item.icon || 'fa-file-text-o'}`}></i>
                                        </div>
                                        <div className="result-info-box flex-grow-1">
                                            <div className="result-title">{item.title}</div>
                                            <div className="result-sub text-muted small">{item.subtitle}</div>
                                        </div>
                                        <i className="fa fa-arrow-right text-muted small"></i>
                                    </div>
                                ))}
                            </div>
                        )}
                    </>
                )}
            </div>
        </div>
    );

    return (
        <>
            {/* Top Marquee Notice Header Bar - Inside Container */}
            <div className="top-marquee-notice-bar">
                <div className="container">
                    <div className="d-flex align-items-center w-100">
                        {/* Notice Badge */}
                        <div className="marquee-badge-wrap d-flex align-items-center flex-shrink-0" style={{ marginRight: '14px' }}>
                            <span className="marquee-live-dot" style={{ marginRight: '6px' }}></span>
                            <i className="fa fa-bullhorn marquee-icon" style={{ marginRight: '6px' }}></i>
                            <span className="marquee-badge-title">{t.notice_badge}</span>
                        </div>

                        {/* Scrolling Marquee Area */}
                        <div className="marquee-ticker-wrap flex-grow-1 overflow-hidden" style={{ minWidth: 0, width: '100%' }}>
                            <marquee
                                behavior="scroll"
                                direction="left"
                                scrollamount="5"
                                onMouseOver={(e) => e.target.stop()}
                                onMouseOut={(e) => e.target.start()}
                                className="marquee-ticker-content"
                                width="100%"
                                style={{ width: '100%', display: 'block' }}
                            >
                                {site.top_notice || t.default_notice}
                            </marquee>
                        </div>
                    </div>
                </div>
            </div>

            {/* Main Header Row: Logo + Prominent Full-Width Search Bar + Language + Dark Mode + Auth */}
            <header className={`react-main-header golden-navbar ${scrolled ? 'header-scrolled shadow-lg' : ''}`}>
                <div className="container">
                    <div className="d-flex align-items-center justify-content-between header-inner-row">
                        {/* Brand Logo */}
                        <a href={routes.home || '/'} className="react-brand-logo flex-shrink-0">
                            <img src={site.logo} alt={site.title} />
                        </a>

                        {/* Center Prominent Search Bar (Taking Full Space on Desktop as requested) */}
                        <div className="d-none d-lg-flex flex-grow-1 search-container-col" ref={searchWrapRef} style={{ margin: '0 18px' }}>
                            <form className="header-search-bar-unified w-100" onSubmit={handleSearchSubmit}>
                                <input
                                    type="text"
                                    className="unified-search-input"
                                    placeholder={t.search_placeholder}
                                    value={searchQuery}
                                    onChange={handleSearchChange}
                                    onFocus={() => { if (searchQuery.trim().length > 0) setSearchOpen(true); }}
                                    onKeyDown={(e) => { if (e.key === 'Escape') setSearchOpen(false); }}
                                />
                                {searchQuery ? (
                                    <button
                                        type="button"
                                        className="search-clear-inline-btn"
                                        onClick={clearSearch}
                                        aria-label="Clear Search"
                                    >
                                        <i className="fa fa-times-circle"></i>
                                    </button>
                                ) : null}
                                <button
                                    type="submit"
                                    className="unified-search-submit-btn"
                                    onClick={handleSearchSubmit}
                                >
                                    <i className="fa fa-search me-1"></i>
                                    <span>{t.search_btn || 'Search'}</span>
                                </button>
                            </form>

                            {/* Dropdown Menu Popup */}
                            {searchOpen && renderSearchResultsDropdown()}
                        </div>

                        {/* Right Action Icons: Language Pill + Dark Mode Moon/Sun + Auth Buttons (With clear spacing) */}
                        <div className="d-none d-lg-flex align-items-center header-right-actions flex-shrink-0">
                            {/* Language Switcher Pill [ EN | বাংলা ] */}
                            <div className="header-lang-switcher-pill" style={{ marginRight: '16px' }}>
                                <button
                                    type="button"
                                    className={`lang-btn ${lang === 'en' ? 'active' : ''}`}
                                    onClick={() => onSwitchLang('en')}
                                    title="Switch to English (Default)"
                                >
                                    EN
                                </button>
                                <span className="lang-divider">|</span>
                                <button
                                    type="button"
                                    className={`lang-btn ${lang === 'bn' ? 'active' : ''}`}
                                    onClick={() => onSwitchLang('bn')}
                                    title="বাংলা ভাষায় পরিবর্তন করুন"
                                >
                                    বাংলা
                                </button>
                            </div>

                            {/* Black Mode / Dark Mode Toggle Button (Moon 🌙 in light mode, Sun ☀️ in dark mode) */}
                            <button
                                type="button"
                                className={`header-theme-toggle-btn ${theme === 'dark' ? 'is-dark' : ''}`}
                                onClick={onToggleTheme}
                                title={theme === 'dark' ? (t.theme_light || 'Switch to Light Mode') : (t.theme_dark || 'Switch to Dark Mode / ব্ল্যাক মোড')}
                                aria-label="Toggle Black/Dark Mode"
                                style={{ marginRight: '16px' }}
                            >
                                {theme === 'dark' ? (
                                    <i className="fa fa-sun-o theme-icon-sun"></i>
                                ) : (
                                    <i className="fa fa-moon-o theme-icon-moon"></i>
                                )}
                            </button>

                            {/* Auth / Dashboard Buttons */}
                            <div className="header-auth-buttons">
                                {auth.is_authenticated ? (
                                    <a href={auth.dashboard_url} className="btn-golden-action">
                                        <i className="fa fa-tachometer me-2"></i> {t.dashboard}
                                    </a>
                                ) : (
                                    <div className="d-flex align-items-center">
                                        <a href={auth.login_url} className="btn-golden-action-outline" style={{ marginRight: '14px' }}>
                                            <i className="fa fa-sign-in me-1"></i> {t.login}
                                        </a>
                                        <a href={auth.register_url} className="btn-golden-action">
                                            <i className="fa fa-user-plus me-1"></i> {t.register}
                                        </a>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Mobile Header Controls - Clean Hamburger Toggle Only */}
                        <div className="mobile-header-controls d-flex d-lg-none align-items-center">
                            <button
                                type="button"
                                className={`mobile-nav-toggle-btn-golden ${mobileMenuOpen ? 'active' : ''}`}
                                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                                aria-label="Toggle Navigation Menu"
                            >
                                <i className={`fa ${mobileMenuOpen ? 'fa-times' : 'fa-bars'}`}></i>
                            </button>
                        </div>
                    </div>
                </div>

                {/* Sub-Navigation Strip (Desktop Navigation Bar below header with active highlights) */}
                <div className="header-sub-nav-bar d-none d-lg-block">
                    <div className="container">
                        <nav className="d-flex align-items-center sub-nav-links-row">
                            {navLinks.map((item, index) => (
                                <a
                                    key={index}
                                    href={item.url}
                                    className={`sub-nav-link-item ${item.active ? 'active' : ''}`}
                                >
                                    {item.label}
                                </a>
                            ))}
                        </nav>
                    </div>
                </div>
            </header>

            {/* Mobile Slide-out Drawer */}
            <div className={`mobile-drawer-overlay ${mobileMenuOpen ? 'show' : ''}`} onClick={() => setMobileMenuOpen(false)}></div>
            <div className={`mobile-nav-drawer ${mobileMenuOpen ? 'open' : ''}`}>
                <div className="mobile-drawer-header golden-drawer-head">
                    <img src={site.logo} alt="Logo" className="mobile-drawer-logo" />
                    <button className="mobile-drawer-close" onClick={() => setMobileMenuOpen(false)} aria-label="Close Menu">
                        <i className="fa fa-times"></i>
                    </button>
                </div>

                <div className="mobile-drawer-body">
                    {/* 1. Login & Registration / Dashboard in Mobile Drawer */}
                    <div className="mobile-user-card mb-3 p-3 rounded-3" style={{ background: 'linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%)', border: '1px solid #fed7aa' }}>
                        {auth.is_authenticated ? (
                            <a href={auth.dashboard_url} className="btn-golden-action w-100 py-2 d-flex align-items-center justify-content-center text-decoration-none">
                                <i className="fa fa-tachometer me-2"></i> {t.dashboard}
                            </a>
                        ) : (
                            <div>
                                <div className="small text-muted mb-2 fw-bold text-center">
                                    {lang === 'bn' ? 'একাউন্টে প্রবেশ করুন অথবা নিবন্ধন করুন' : 'Sign in to account or register'}
                                </div>
                                <div className="d-flex align-items-center">
                                    <a href={auth.login_url} className="mobile-drawer-btn-login flex-fill text-center py-2 text-decoration-none" style={{ marginRight: '10px' }}>
                                        <i className="fa fa-sign-in me-1"></i> {t.login}
                                    </a>
                                    <a href={auth.register_url} className="mobile-drawer-btn-register flex-fill text-center py-2 text-decoration-none">
                                        <i className="fa fa-user-plus me-1"></i> {t.register}
                                    </a>
                                </div>
                            </div>
                        )}
                    </div>

                    {/* 2. Theme & Language Switchers in Mobile Drawer */}
                    <div className="d-flex align-items-center justify-content-between mb-3 p-2 bg-slate-100 rounded mobile-pref-card" style={{ background: '#f8fafc', border: '1px solid #e2e8f0' }}>
                        {/* Theme Toggle (Dark / Light) */}
                        <div className="d-flex align-items-center">
                            <span className="small text-muted fw-bold me-2" style={{ marginRight: '8px' }}>
                                {lang === 'bn' ? 'থিম:' : 'Theme:'}
                            </span>
                            <button
                                type="button"
                                className={`btn btn-sm ${theme === 'dark' ? 'btn-dark text-warning' : 'btn-light text-dark'} border fw-bold px-3 py-1`}
                                onClick={onToggleTheme}
                                style={{ borderRadius: '20px' }}
                            >
                                {theme === 'dark' ? (
                                    <span><i className="fa fa-sun-o text-warning me-1"></i> Light</span>
                                ) : (
                                    <span><i className="fa fa-moon-o text-warning me-1"></i> Dark</span>
                                )}
                            </button>
                        </div>

                        {/* Language Switcher Pill [ EN | বাংলা ] */}
                        <div className="d-flex align-items-center">
                            <span className="small text-muted fw-bold me-2" style={{ marginRight: '8px' }}>
                                {lang === 'bn' ? 'ভাষা:' : 'Lang:'}
                            </span>
                            <div className="header-lang-switcher-pill d-inline-flex">
                                <button
                                    type="button"
                                    className={`lang-btn ${lang === 'en' ? 'active' : ''}`}
                                    onClick={() => onSwitchLang('en')}
                                >
                                    EN
                                </button>
                                <span className="lang-divider">|</span>
                                <button
                                    type="button"
                                    className={`lang-btn ${lang === 'bn' ? 'active' : ''}`}
                                    onClick={() => onSwitchLang('bn')}
                                >
                                    বাংলা
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* 3. Mobile Search Box with Integrated Search Button */}
                    <div className="mobile-search-holder mb-3" ref={mobileSearchWrapRef}>
                        <form className="header-search-bar-unified w-100" onSubmit={handleSearchSubmit}>
                            <input
                                type="text"
                                className="unified-search-input"
                                placeholder={t.search_placeholder}
                                value={searchQuery}
                                onChange={handleSearchChange}
                                onFocus={() => { if (searchQuery.trim().length > 0) setSearchOpen(true); }}
                            />
                            {searchQuery && (
                                <button
                                    type="button"
                                    className="search-clear-inline-btn"
                                    onClick={clearSearch}
                                >
                                    <i className="fa fa-times-circle"></i>
                                </button>
                            )}
                            <button
                                type="submit"
                                className="unified-search-submit-btn px-3"
                                onClick={handleSearchSubmit}
                            >
                                <i className="fa fa-search"></i>
                            </button>
                        </form>
                        {searchOpen && renderSearchResultsDropdown()}
                    </div>

                    {/* Nav Links List */}
                    <ul className="mobile-nav-list">
                        {navLinks.map((item, index) => (
                            <li key={index} className="mobile-nav-item">
                                <a
                                    href={item.url}
                                    className={`mobile-nav-link ${item.active ? 'active' : ''}`}
                                    onClick={() => setMobileMenuOpen(false)}
                                >
                                    <span>{item.label}</span>
                                    <i className="fa fa-angle-right"></i>
                                </a>
                            </li>
                        ))}
                    </ul>

                    {/* Quick Contacts inside Mobile Drawer */}
                    <div className="mobile-drawer-contact mt-4 pt-3 border-top">
                        <div className="small text-muted mb-2 fw-bold">Contact & Helpline:</div>
                        {site.phone1 && (
                            <a href={`tel:${site.phone1}`} className="d-block text-dark mb-2 small text-decoration-none">
                                <i className="fa fa-phone text-warning me-2"></i> {site.phone1}
                            </a>
                        )}
                        {site.email1 && (
                            <a href={`mailto:${site.email1}`} className="d-block text-dark small text-decoration-none">
                                <i className="fa fa-envelope text-warning me-2"></i> {site.email1}
                            </a>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}
