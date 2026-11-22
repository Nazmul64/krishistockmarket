import React, { useState } from 'react';

export default function Footer({
    site = {},
    routes = {},
    lang = 'en',
    t = {},
    uiConfig = {},
    activeTemplate = 'template_01_modern_agriculture'
}) {
    const [newsletterEmail, setNewsletterEmail] = useState('');
    const [isSubscribed, setIsSubscribed] = useState(false);

    const scrollToTop = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const handleNewsletterSubmit = (e) => {
        e.preventDefault();
        if (newsletterEmail.trim()) {
            setIsSubscribed(true);
            setTimeout(() => {
                setNewsletterEmail('');
            }, 3000);
        }
    };

    const currentYear = new Date().getFullYear();
    const copyrightText = site.footer_copyright || `© ${currentYear} ekrishisheba. ${t.footer_rights || (lang === 'bn' ? 'সর্বস্বত্ব সংরক্ষিত।' : 'All Rights Reserved.')}`;
    const developedByText = site.footer_developed_by || 'ekrishisheba';
    const developedByUrl = site.footer_developed_by_url || '#';

    // Phone & Email defaults
    const phone = site.phone1 || site.phone2 || '+88018********';
    const email = site.email1 || site.email2 || 'info@ekrishisheba.com';
    const address = site.address1 || site.address2 || (lang === 'bn' ? 'ঢাকা, বাংলাদেশ' : 'Dhaka, Bangladesh');

    return (
        <footer className="react-main-footer-modern">
            {/* Top Multi-Color Gradient Wave Strip */}
            <div className="footer-top-accent-line"></div>

            {/* 1. Top Value Proposition / Trust Features Bar */}
            <div className="footer-trust-features-bar py-4">
                <div className="container">
                    <div className="row g-3">
                        <div className="col-12 col-sm-6 col-lg-3">
                            <div className="trust-feature-card">
                                <div className="trust-icon-box bg-emerald">
                                    <i className="fa fa-truck"></i>
                                </div>
                                <div className="trust-feature-text">
                                    <h5 className="trust-title">{lang === 'bn' ? 'দেশব্যাপী হোম ডেলিভারি' : 'Fast Home Delivery'}</h5>
                                    <p className="trust-desc">{lang === 'bn' ? 'নির্ধারিত পয়েন্ট ও দ্রুততম হোম ডেলিভারি' : 'Reliable delivery across all districts'}</p>
                                </div>
                            </div>
                        </div>

                        <div className="col-12 col-sm-6 col-lg-3">
                            <div className="trust-feature-card">
                                <div className="trust-icon-box bg-amber">
                                    <i className="fa fa-shield"></i>
                                </div>
                                <div className="trust-feature-text">
                                    <h5 className="trust-title">{lang === 'bn' ? '১০০% খাঁটি ও তাজা পণ্য' : '100% Quality Guaranteed'}</h5>
                                    <p className="trust-desc">{lang === 'bn' ? 'কৃষকের খাঁটি ফসল ও পুষ্টিকর খাবার' : 'Fresh, verified organic produce'}</p>
                                </div>
                            </div>
                        </div>

                        <div className="col-12 col-sm-6 col-lg-3">
                            <div className="trust-feature-card">
                                <div className="trust-icon-box bg-cyan">
                                    <i className="fa fa-credit-card"></i>
                                </div>
                                <div className="trust-feature-text">
                                    <h5 className="trust-title">{lang === 'bn' ? 'সুরক্ষিত ক্যাশলেস পেমেন্ট' : 'Safe & Secure Payment'}</h5>
                                    <p className="trust-desc">{lang === 'bn' ? 'বিকাশ, নগদ, কার্ড ও ক্যাশ অন ডেলিভারি' : 'Multiple cashless payment methods'}</p>
                                </div>
                            </div>
                        </div>

                        <div className="col-12 col-sm-6 col-lg-3">
                            <div className="trust-feature-card">
                                <div className="trust-icon-box bg-purple">
                                    <i className="fa fa-phone"></i>
                                </div>
                                <div className="trust-feature-text">
                                    <h5 className="trust-title">{lang === 'bn' ? '২৪/৭ সার্বক্ষণিক হেল্পলাইন' : 'Dedicated Support 24/7'}</h5>
                                    <p className="trust-desc">{lang === 'bn' ? 'যেকোনো সহায়তায় আমাদের টিম প্রস্তুত' : 'Always here to assist your needs'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* 2. Newsletter & Direct Hotline CTA Ribbon */}
            <div className="footer-newsletter-hotline-ribbon py-4">
                <div className="container">
                    <div className="newsletter-hotline-inner-card">
                        <div className="row align-items-center g-4">
                            <div className="col-12 col-lg-7">
                                <div className="d-flex align-items-center flex-wrap gap-3">
                                    <div className="newsletter-bell-icon">
                                        <i className="fa fa-bell-o"></i>
                                    </div>
                                    <div className="flex-fill">
                                        <h4 className="newsletter-heading mb-1">
                                            {lang === 'bn' ? 'দৈনিক বাজার দর ও বিশেষ অফারের আপডেট পান' : 'Get Daily Market Rates & Agro Offers'}
                                        </h4>
                                        <p className="newsletter-sub mb-0">
                                            {lang === 'bn' ? 'আপনার ইমেইল সাবস্ক্রাইব করে রাখুন এবং সাশ্রয়ী অফার সবার আগে জানুন' : 'Subscribe to receive stock commodity rates and grocery deals directly'}
                                        </p>
                                    </div>
                                </div>
                                <form onSubmit={handleNewsletterSubmit} className="newsletter-form-modern mt-3">
                                    <div className="newsletter-input-group">
                                        <input
                                            type="email"
                                            value={newsletterEmail}
                                            onChange={(e) => setNewsletterEmail(e.target.value)}
                                            placeholder={lang === 'bn' ? 'আপনার ইমেইল ঠিকানা লিখুন...' : 'Enter your email address...'}
                                            required
                                            className="newsletter-input"
                                        />
                                        <button type="submit" className="newsletter-submit-btn">
                                            <i className="fa fa-paper-plane me-1"></i> {lang === 'bn' ? 'সাবস্ক্রাইব' : 'Subscribe'}
                                        </button>
                                    </div>
                                    {isSubscribed && (
                                        <div className="newsletter-success-alert mt-2">
                                            <i className="fa fa-check-circle me-1"></i>
                                            {lang === 'bn' ? 'ধন্যবাদ! আপনি সফলভাবে সাবস্ক্রাইব হয়েছেন।' : 'Thank you! You have successfully subscribed.'}
                                        </div>
                                    )}
                                </form>
                            </div>

                            <div className="col-12 col-lg-5">
                                <div className="footer-hotline-banner-card">
                                    <div className="hotline-icon-pulse">
                                        <i className="fa fa-headphones"></i>
                                    </div>
                                    <div>
                                        <span className="hotline-label">{lang === 'bn' ? 'গ্রাহক সেবা ও কল সেন্টার' : 'Customer Care Hotline'}</span>
                                        <a href={`tel:${phone}`} className="hotline-number-link">
                                            {phone}
                                        </a>
                                        <span className="hotline-timing">
                                            <i className="fa fa-clock-o me-1"></i> {lang === 'bn' ? 'সকাল ৮:০০টা - রাত ১০:০০টা' : '8:00 AM - 10:00 PM (Daily)'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* 3. Main Footer Links & Information Grid */}
            <div className="footer-main-grid-wrap py-5">
                <div className="container">
                    <div className="row g-4">
                        {/* Col 1: Brand & About */}
                        <div className="col-12 col-md-6 col-lg-4">
                            <div className="footer-modern-col pe-lg-3">
                                <a href={routes.home || '/'} className="footer-brand-logo-modern d-inline-block mb-3">
                                    <img src={site.logo} alt={site.title} />
                                </a>
                                <p className="footer-brand-bio">
                                    {site.footer_about || site.about_us_text || (lang === 'bn' 
                                        ? 'ই-কৃষি সেবা – দেশের কৃষক, খামারি, উদ্যোক্তা ও সাধারণ পরিবারের জন্য সাশ্রয়ী মূল্যে পুষ্টিকর খাদ্যপণ্য ও আধুনিক কৃষিপণ্য সরবরাহের বিশ্বস্ত ডিজিটাল প্ল্যাটফর্ম।'
                                        : 'ekrishisheba - Your trusted agro investment, real-time commodity trading, and essential grocery distribution digital platform.'
                                    )}
                                </p>

                                {/* Verification Trust Badges */}
                                <div className="footer-trust-badges-list mt-3">
                                    <div className="trust-pill">
                                        <i className="fa fa-check-circle text-success me-1"></i>
                                        <span>{lang === 'bn' ? 'সরকার অনুমোদিত ও ভেরিফাইড' : 'Govt. Approved Platform'}</span>
                                    </div>
                                    <div className="trust-pill">
                                        <i className="fa fa-lock text-warning me-1"></i>
                                        <span>{lang === 'bn' ? 'জিরো-ট্রাস্ট এনক্রিপ্টেড সার্ভার' : 'Zero-Trust Protected'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Col 2: Services & Market */}
                        <div className="col-12 col-md-6 col-lg-2">
                            <div className="footer-modern-col">
                                <h4 className="footer-modern-title">
                                    <span>{lang === 'bn' ? 'আমাদের সেবাসমূহ' : 'Our Services'}</span>
                                </h4>
                                <ul className="footer-modern-links">
                                    <li>
                                        <a href={routes.home || '/'}>
                                            <i className="fa fa-angle-right"></i> {t.home || (lang === 'bn' ? 'হোম পেজ' : 'Home')}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/shop">
                                            <i className="fa fa-angle-right"></i> {lang === 'bn' ? 'মাসিক বাজার শপ' : 'Monthly Bazaar'}
                                        </a>
                                    </li>
                                    <li>
                                        <a href={routes.gallery || '/gallery'}>
                                            <i className="fa fa-angle-right"></i> {t.gallery || (lang === 'bn' ? 'কৃষি গ্যালারি' : 'Produce Gallery')}
                                        </a>
                                    </li>
                                    <li>
                                        <a href={routes.our_videos || '/our-videos'}>
                                            <i className="fa fa-angle-right"></i> {lang === 'bn' ? 'ভিডিও ও টিউটোরিয়াল' : 'Our Videos'}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {/* Col 3: Support & Policies */}
                        <div className="col-12 col-md-6 col-lg-3">
                            <div className="footer-modern-col">
                                <h4 className="footer-modern-title">
                                    <span>{lang === 'bn' ? 'তথ্য ও পলিসি' : 'Info & Policies'}</span>
                                </h4>
                                <ul className="footer-modern-links">
                                    <li>
                                        <a href={routes.about || '/about'}>
                                            <i className="fa fa-angle-right"></i> {t.about || (lang === 'bn' ? 'আমাদের সম্পর্কে' : 'About Us')}
                                        </a>
                                    </li>
                                    <li>
                                        <a href={routes.privacy || '/privacy-policy'}>
                                            <i className="fa fa-angle-right"></i> {t.privacy || (lang === 'bn' ? 'প্রাইভেসি পলিসি' : 'Privacy Policy')}
                                        </a>
                                    </li>
                                    <li>
                                        <a href={routes.terms || '/terms'}>
                                            <i className="fa fa-angle-right"></i> {t.terms || (lang === 'bn' ? 'শর্তাবলী ও নিয়মাবলী' : 'Terms & Conditions')}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {/* Col 4: Corporate Contact & Social */}
                        <div className="col-12 col-md-6 col-lg-3">
                            <div className="footer-modern-col">
                                <h4 className="footer-modern-title">
                                    <span>{lang === 'bn' ? 'যোগাযোগ ও সোশ্যাল মিডিয়া' : 'Contact & Connect'}</span>
                                </h4>
                                <ul className="footer-contact-modern-list mb-3">
                                    <li>
                                        <i className="fa fa-map-marker text-warning mt-1"></i>
                                        <span>{address}</span>
                                    </li>
                                    <li>
                                        <i className="fa fa-phone text-success mt-1"></i>
                                        <a href={`tel:${phone}`}>{phone}</a>
                                    </li>
                                    <li>
                                        <i className="fa fa-envelope text-info mt-1"></i>
                                        <a href={`mailto:${email}`}>{email}</a>
                                    </li>
                                </ul>

                                {/* Social Links with Colorful 3D Gradient Buttons */}
                                <div className="footer-social-modern-wrap">
                                    <div className="footer-social-modern-title">
                                        {lang === 'bn' ? 'যুক্ত থাকুন আমাদের সাথে:' : 'Follow Us:'}
                                    </div>
                                    <div className="footer-social-modern-icons d-flex align-items-center flex-wrap gap-2">
                                        {site.facebook && (
                                            <a href={site.facebook} target="_blank" rel="noopener noreferrer" className="modern-social-btn fb" title="Facebook">
                                                <i className="fa fa-facebook"></i>
                                            </a>
                                        )}
                                        {site.youtube && (
                                            <a href={site.youtube} target="_blank" rel="noopener noreferrer" className="modern-social-btn yt" title="YouTube">
                                                <i className="fa fa-youtube-play"></i>
                                            </a>
                                        )}
                                        {site.linkedin && (
                                            <a href={site.linkedin} target="_blank" rel="noopener noreferrer" className="modern-social-btn in" title="LinkedIn">
                                                <i className="fa fa-linkedin"></i>
                                            </a>
                                        )}
                                        {site.instagram && (
                                            <a href={site.instagram} target="_blank" rel="noopener noreferrer" className="modern-social-btn ig" title="Instagram">
                                                <i className="fa fa-instagram"></i>
                                            </a>
                                        )}
                                        {site.twitter && (
                                            <a href={site.twitter} target="_blank" rel="noopener noreferrer" className="modern-social-btn tw" title="Twitter / X">
                                                <i className="fa fa-twitter"></i>
                                            </a>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* 4. Accepted Payment Methods & Security Seal Ribbon */}
            <div className="footer-payment-security-ribbon py-3">
                <div className="container">
                    <div className="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3 text-center text-lg-start">
                        <div className="d-flex align-items-center flex-wrap justify-content-center gap-2">
                            <span className="payment-label">
                                <i className="fa fa-shield text-success me-1"></i>
                                {lang === 'bn' ? 'নিরাপদ পেমেন্ট পার্টনারসমূহ:' : 'Accepted Payment Gateways:'}
                            </span>
                            <div className="payment-badges-row d-flex align-items-center flex-wrap gap-2">
                                <span className="payment-badge bkash">bKash</span>
                                <span className="payment-badge nagad">Nagad</span>
                                <span className="payment-badge rocket">Rocket</span>
                                <span className="payment-badge upay">Upay</span>
                                <span className="payment-badge visa">VISA</span>
                                <span className="payment-badge mastercard">MasterCard</span>
                                <span className="payment-badge cod">{lang === 'bn' ? 'ক্যাশ অন ডেলিভারি' : 'Cash on Delivery'}</span>
                            </div>
                        </div>

                        <div className="d-flex align-items-center justify-content-center gap-3">
                            <span className="security-seal">
                                <i className="fa fa-lock text-warning me-1"></i> 256-Bit SSL Encrypted
                            </span>
                            <span className="security-seal">
                                <i className="fa fa-check-shield text-info me-1"></i> Zero-Trust Secured
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {/* 5. Bottom Copyright & Credits Strip */}
            <div className="footer-bottom-bar-modern py-3">
                <div className="container">
                    <div className="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 text-center text-md-start">
                        <div className="footer-copyright-text">
                            {copyrightText}
                        </div>
                        <div className="footer-legal-quick-links d-none d-md-flex align-items-center gap-3 font-size-12">
                            <a href={routes.privacy || '/privacy-policy'}>{lang === 'bn' ? 'গোপনীয়তা' : 'Privacy'}</a>
                            <span className="text-muted">•</span>
                            <a href={routes.terms || '/terms'}>{lang === 'bn' ? 'শর্তাবলী' : 'Terms'}</a>
                            <span className="text-muted">•</span>
                            <a href={routes.about || '/about'}>{lang === 'bn' ? 'পরিচিতি' : 'About'}</a>
                        </div>
                        <div className="footer-developer-credit">
                            {lang === 'bn' ? 'কারিগরি সহায়তায়:' : 'Developed by:'}{' '}
                            <a href={developedByUrl} target="_blank" rel="noopener noreferrer" className="dev-brand-link">
                                {developedByText}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {/* Floating Smooth Scroll to Top Button */}
            <button
                type="button"
                className="scroll-to-top-btn-modern"
                onClick={scrollToTop}
                aria-label="Scroll to top"
                title="উপরে যান"
            >
                <i className="fa fa-arrow-up"></i>
            </button>
        </footer>
    );
}
