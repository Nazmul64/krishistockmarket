import React, { useState, useEffect } from 'react';

export default function Navbar({ site, auth, routes }) {
    const [scrolled, setScrolled] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

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

    const currentPath = typeof window !== 'undefined' ? window.location.pathname : '/';

    const navLinks = [
        { label: 'হোম', url: routes.home || '/', active: currentPath === '/' || currentPath === '' },
        { label: 'আমাদের সম্পর্কে', url: routes.about || '/about', active: currentPath.includes('/about') },
        { label: 'স্টক মার্কেট', url: routes.stock_index || '/stocks', active: currentPath.includes('/stocks') || currentPath.includes('/stock') },
        { label: 'গ্যালারি', url: routes.gallery || '/gallery', active: currentPath.includes('/gallery') },
        { label: 'যোগাযোগ', url: routes.contact || '/contact', active: currentPath.includes('/contact') },
        { label: 'টার্মস এন্ড কন্ডিশন', url: routes.terms || '/terms', active: currentPath.includes('/terms') },
        { label: 'প্রাইভেসি এন্ড পলিসি', url: routes.privacy || '/privacy-policy', active: currentPath.includes('/privacy') },
    ];

    return (
        <>
            {/* Top Marquee Notice Header Bar - Aligned with Grid Container */}
            <div className="top-marquee-notice-bar">
                <div className="container">
                    <div className="d-flex align-items-center gap-3">
                        {/* Notice Badge */}
                        <div className="marquee-badge-wrap d-flex align-items-center gap-2 flex-shrink-0">
                            <span className="marquee-live-dot"></span>
                            <i className="fa fa-bullhorn marquee-icon"></i>
                            <span className="marquee-badge-title">নোটিশ:</span>
                        </div>

                        {/* Scrolling Marquee Area - Full Width */}
                        <div className="marquee-ticker-wrap flex-grow-1 overflow-hidden">
                            <marquee
                                behavior="scroll"
                                direction="left"
                                scrollamount="5"
                                onMouseOver={(e) => e.target.stop()}
                                onMouseOut={(e) => e.target.start()}
                                className="marquee-ticker-content"
                            >
                                {site.top_notice || 'কৃষি পরিবারে স্বাগতম! আধুনিক ও নিরাপদ কৃষি বিনিয়োগের নির্ভরযোগ্য ডিজিটাল প্ল্যাটফর্ম।'}
                            </marquee>
                        </div>
                    </div>
                </div>
            </div>

            {/* Main Golden Navbar Header */}
            <header className={`react-main-header golden-navbar ${scrolled ? 'header-scrolled shadow-lg' : ''}`}>
                <div className="container">
                    <div className="d-flex align-items-center justify-content-between header-inner-row">
                        {/* Brand Logo */}
                        <a href={routes.home || '/'} className="react-brand-logo">
                            <img src={site.logo} alt={site.title} />
                        </a>

                        {/* Desktop Navigation Links with White Text */}
                        <nav className="d-none d-lg-flex align-items-center main-desktop-nav">
                            {navLinks.map((item, index) => (
                                <a
                                    key={index}
                                    href={item.url}
                                    className={`desktop-nav-link-golden ${item.active ? 'active' : ''}`}
                                >
                                    {item.label}
                                </a>
                            ))}
                        </nav>

                        {/* Desktop Golden Action Button */}
                        <div className="d-none d-lg-flex align-items-center header-auth-container">
                            {auth.is_authenticated ? (
                                <a href={auth.dashboard_url} className="btn-golden-action">
                                    <i className="fa fa-tachometer me-2"></i> ড্যাশবোর্ড
                                </a>
                            ) : (
                                <div className="header-auth-buttons">
                                    <a href={auth.login_url} className="btn-golden-action-outline">
                                        <i className="fa fa-sign-in me-1"></i> লগইন
                                    </a>
                                    <a href={auth.register_url} className="btn-golden-action">
                                        <i className="fa fa-user-plus me-1"></i> রেজিস্ট্রেশন
                                    </a>
                                </div>
                            )}
                        </div>

                        {/* Mobile Toggle Button - Strictly pinned on far right of header */}
                        <div className="d-flex d-lg-none align-items-center">
                            <button
                                type="button"
                                className={`mobile-nav-toggle-btn-golden ${mobileMenuOpen ? 'active' : ''}`}
                                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                                aria-label="Toggle Navigation"
                            >
                                <i className={`fa ${mobileMenuOpen ? 'fa-times' : 'fa-bars'}`}></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            {/* Mobile Slide-out Drawer */}
            <div className={`mobile-drawer-overlay ${mobileMenuOpen ? 'show' : ''}`} onClick={() => setMobileMenuOpen(false)}></div>
            <div className={`mobile-nav-drawer ${mobileMenuOpen ? 'open' : ''}`}>
                <div className="mobile-drawer-header golden-drawer-head">
                    <img src={site.logo} alt="Logo" className="mobile-drawer-logo" />
                    <button className="mobile-drawer-close" onClick={() => setMobileMenuOpen(false)}>
                        <i className="fa fa-times"></i>
                    </button>
                </div>

                <div className="mobile-drawer-body">
                    {/* User Quick Info */}
                    <div className="mobile-user-card mb-3">
                        {auth.is_authenticated ? (
                            <a href={auth.dashboard_url} className="btn btn-golden-action w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                                <i className="fa fa-tachometer"></i> ড্যাশবোর্ডে প্রবেশ করুন
                            </a>
                        ) : (
                            <div className="d-flex gap-2">
                                <a href={auth.login_url} className="btn btn-outline-warning w-50 py-2 text-center text-dark fw-bold">
                                    <i className="fa fa-sign-in me-1"></i> লগইন
                                </a>
                                <a href={auth.register_url} className="btn btn-golden-action w-50 py-2 text-center">
                                    <i className="fa fa-user-plus me-1"></i> রেজিস্টার
                                </a>
                            </div>
                        )}
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
                        <div className="small text-muted mb-2 fw-bold">জরুরী হেল্পলাইন:</div>
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
