import React from 'react';

export default function Footer({
    site = {},
    routes = {},
    lang = 'en',
    t = {}
}) {
    const scrollToTop = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const currentYear = new Date().getFullYear();
    const copyrightText = site.footer_copyright || `© ${currentYear} ekrishisheba. ${t.footer_rights || 'All Rights Reserved.'}`;
    const developedByText = site.footer_developed_by || 'ekrishisheba';
    const developedByUrl = site.footer_developed_by_url || '#';

    return (
        <footer className="react-main-footer-colorful">
            {/* Top Wave / Gradient Accent */}
            <div className="footer-glow-bar"></div>

            {/* Upper Footer Grid */}
            <div className="footer-top-wrap py-5">
                <div className="container">
                    <div className="row g-4">
                        {/* Col 1: About */}
                        <div className="col-12 col-md-6 col-lg-3">
                            <div className="footer-col-content">
                                <a href={routes.home || '/'} className="footer-brand-logo d-inline-block mb-3">
                                    <img src={site.logo} alt={site.title} />
                                </a>
                                <p className="footer-about-text">
                                    {site.footer_about || site.about_us_text || t.footer_about_desc || 'ekrishisheba - Your trusted agro investment and real-time market trading digital platform.'}
                                </p>
                            </div>
                        </div>

                        {/* Col 2: Quick Links */}
                        <div className="col-6 col-md-6 col-lg-3">
                            <div className="footer-col-content">
                                <h4 className="footer-col-title">{t.footer_links_title || 'Quick Links'}</h4>
                                <ul className="footer-links-list">
                                    <li><a href={routes.home || '/'}><i className="fa fa-angle-right me-2 text-warning"></i> {t.home || 'Home'}</a></li>
                                    <li><a href={routes.about || '/about'}><i className="fa fa-angle-right me-2 text-warning"></i> {t.about || 'About Us'}</a></li>
                                    <li><a href={routes.stock_index || '/stocks'}><i className="fa fa-angle-right me-2 text-warning"></i> {t.stocks || 'Stock Market'}</a></li>
                                    <li><a href={routes.gallery || '/gallery'}><i className="fa fa-angle-right me-2 text-warning"></i> {t.gallery || 'Produce Gallery'}</a></li>
                                    <li><a href={routes.privacy || '/privacy-policy'}><i className="fa fa-angle-right me-2 text-warning"></i> {t.privacy || 'Privacy Policy'}</a></li>
                                    <li><a href={routes.terms || '/terms'}><i className="fa fa-angle-right me-2 text-warning"></i> {t.terms || 'Terms & Conditions'}</a></li>
                                </ul>
                            </div>
                        </div>

                        {/* Col 3: Contact Info */}
                        <div className="col-6 col-md-6 col-lg-3">
                            <div className="footer-col-content">
                                <h4 className="footer-col-title">{t.footer_contact_title || 'Contact Info'}</h4>
                                <ul className="footer-contact-info-list">
                                    {site.address1 && (
                                        <li>
                                            <i className="fa fa-map-marker text-warning"></i>
                                            <span>
                                                {site.address1}
                                                {site.address2 && site.address2 !== site.address1 ? `, ${site.address2}` : ''}
                                            </span>
                                        </li>
                                    )}
                                    {site.phone1 && (
                                        <li>
                                            <i className="fa fa-phone text-warning"></i>
                                            <a href={`tel:${site.phone1}`}>{site.phone1}</a>
                                        </li>
                                    )}
                                    {site.phone2 && site.phone2 !== site.phone1 && (
                                        <li>
                                            <i className="fa fa-phone text-warning"></i>
                                            <a href={`tel:${site.phone2}`}>{site.phone2}</a>
                                        </li>
                                    )}
                                    {site.email1 && (
                                        <li>
                                            <i className="fa fa-envelope text-warning"></i>
                                            <a href={`mailto:${site.email1}`}>{site.email1}</a>
                                        </li>
                                    )}
                                    {site.email2 && site.email2 !== site.email1 && (
                                        <li>
                                            <i className="fa fa-envelope text-warning"></i>
                                            <a href={`mailto:${site.email2}`}>{site.email2}</a>
                                        </li>
                                    )}
                                </ul>
                            </div>
                        </div>

                        {/* Col 4: Social & Trust */}
                        <div className="col-12 col-md-6 col-lg-3">
                            <div className="footer-col-content">
                                <h4 className="footer-col-title">{lang === 'bn' ? 'সোশ্যাল মিডিয়া' : 'Social Media & Security'}</h4>
                                <p className="small text-slate-400 mb-3">
                                    {lang === 'bn' ? 'আমাদের সামাজিক যোগাযোগ মাধ্যমে যুক্ত থাকুন এবং প্রতিদিনের স্টক রেট ও কৃষি অফারের সাথে আপডেট থাকুন।' : 'Connect with us on social channels to stay updated with daily stock rates and member offers.'}
                                </p>
                                <div className="footer-social-links-row d-flex align-items-center flex-wrap">
                                    {site.facebook && (
                                        <a href={site.facebook} target="_blank" rel="noopener noreferrer" className="footer-social-icon ftr-btn fb" title="Facebook" style={{ marginRight: '12px', marginBottom: '8px' }}>
                                            <i className="fa fa-facebook"></i>
                                        </a>
                                    )}
                                    {site.instagram && (
                                        <a href={site.instagram} target="_blank" rel="noopener noreferrer" className="footer-social-icon ftr-btn ig insta" title="Instagram" style={{ marginRight: '12px', marginBottom: '8px' }}>
                                            <i className="fa fa-instagram"></i>
                                        </a>
                                    )}
                                    {site.twitter && (
                                        <a href={site.twitter} target="_blank" rel="noopener noreferrer" className="footer-social-icon ftr-btn tw" title="Twitter" style={{ marginRight: '12px', marginBottom: '8px' }}>
                                            <i className="fa fa-twitter"></i>
                                        </a>
                                    )}
                                    {site.linkedin && (
                                        <a href={site.linkedin} target="_blank" rel="noopener noreferrer" className="footer-social-icon ftr-btn in" title="LinkedIn" style={{ marginRight: '12px', marginBottom: '8px' }}>
                                            <i className="fa fa-linkedin"></i>
                                        </a>
                                    )}
                                    {site.youtube && (
                                        <a href={site.youtube} target="_blank" rel="noopener noreferrer" className="footer-social-icon ftr-btn yt" title="YouTube" style={{ marginRight: '12px', marginBottom: '8px' }}>
                                            <i className="fa fa-youtube-play"></i>
                                        </a>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Bottom Sub-Footer Bar */}
            <div className="footer-bottom-bar py-3">
                <div className="container">
                    <div className="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 text-center text-md-start">
                        <div className="footer-copy-text small text-slate-400">
                            {copyrightText}
                        </div>
                        <div className="footer-dev-text small text-slate-400">
                            {lang === 'bn' ? 'কারিগরি সহায়তায়:' : 'Developed by:'} <a href={developedByUrl} target="_blank" rel="noopener noreferrer" className="text-warning text-decoration-none fw-bold">{developedByText}</a>
                        </div>
                    </div>
                </div>
            </div>

            {/* Floating Scroll to Top Button */}
            <button
                type="button"
                className="scroll-to-top-btn"
                onClick={scrollToTop}
                aria-label="Scroll to top"
            >
                <i className="fa fa-chevron-up"></i>
            </button>
        </footer>
    );
}
