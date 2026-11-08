import React from 'react';

export default function Footer({ site = {}, routes = {} }) {
    const scrollToTop = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const currentYear = new Date().getFullYear();
    const copyrightText = site.footer_copyright || `© ${currentYear} ekrishisheba. All Rights Reserved.`;
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
                                    {site.footer_about || site.about_us_text || 'ekrishisheba - আপনার কৃষি সেবা, পণ্যের সঠিক মূল্য এবং আর্থিক সমৃদ্ধির নির্ভরযোগ্য ডিজিটাল মাধ্যম।'}
                                </p>
                            </div>
                        </div>

                        {/* Col 2: Quick Links */}
                        <div className="col-6 col-md-6 col-lg-3">
                            <div className="footer-col-content">
                                <h4 className="footer-col-title">প্রয়োজনীয় লিঙ্ক</h4>
                                <ul className="footer-links-list">
                                    <li><a href={routes.home || '/'}><i className="fa fa-angle-right me-2 text-warning"></i> হোমপেজ</a></li>
                                    <li><a href={routes.about || '/about'}><i className="fa fa-angle-right me-2 text-warning"></i> আমাদের সম্পর্কে</a></li>
                                    <li><a href={routes.stock_index || '/stocks'}><i className="fa fa-angle-right me-2 text-warning"></i> স্টক মার্কেট</a></li>
                                    <li><a href={routes.gallery || '/gallery'}><i className="fa fa-angle-right me-2 text-warning"></i> প্রোডাক্ট গ্যালারি</a></li>
                                    <li><a href={routes.privacy || '/privacy-policy'}><i className="fa fa-angle-right me-2 text-warning"></i> প্রাইভেসি পলিসি</a></li>
                                    <li><a href={routes.terms || '/terms'}><i className="fa fa-angle-right me-2 text-warning"></i> টার্মস এন্ড কন্ডিশন</a></li>
                                </ul>
                            </div>
                        </div>

                        {/* Col 3: Contact Info */}
                        <div className="col-6 col-md-6 col-lg-3">
                            <div className="footer-col-content">
                                <h4 className="footer-col-title">যোগাযোগ করুন</h4>
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
                                    {site.email1 && (
                                        <li>
                                            <i className="fa fa-envelope text-warning"></i>
                                            <a href={`mailto:${site.email1}`} style={{ wordBreak: 'break-all', overflowWrap: 'anywhere' }}>{site.email1}</a>
                                        </li>
                                    )}
                                </ul>
                            </div>
                        </div>

                        {/* Col 4: Social Media */}
                        <div className="col-12 col-md-6 col-lg-3">
                            <div className="footer-col-content">
                                <h4 className="footer-col-title">সোশ্যাল মিডিয়া</h4>
                                <p className="footer-social-desc">
                                    আমাদের সাথে সামাজিক যোগাযোগ মাধ্যমে যুক্ত থাকুন ও সর্বশেষ আপডেট পান।
                                </p>
                                <div className="footer-social-buttons">
                                    {site.facebook && (
                                        <a href={site.facebook} target="_blank" rel="noreferrer" className="ftr-btn fb" title="Facebook">
                                            <i className="fa fa-facebook"></i>
                                        </a>
                                    )}
                                    {site.instagram && (
                                        <a href={site.instagram} target="_blank" rel="noreferrer" className="ftr-btn insta" title="Instagram">
                                            <i className="fa fa-instagram"></i>
                                        </a>
                                    )}
                                    {site.twitter && (
                                        <a href={site.twitter} target="_blank" rel="noreferrer" className="ftr-btn tw" title="Twitter">
                                            <i className="fa fa-twitter"></i>
                                        </a>
                                    )}
                                    {site.linkedin && (
                                        <a href={site.linkedin} target="_blank" rel="noreferrer" className="ftr-btn in" title="LinkedIn">
                                            <i className="fa fa-linkedin"></i>
                                        </a>
                                    )}
                                    {site.youtube && (
                                        <a href={site.youtube} target="_blank" rel="noreferrer" className="ftr-btn yt" title="YouTube">
                                            <i className="fa fa-youtube-play"></i>
                                        </a>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Bottom Bar with Dynamic Copyright and Developed By */}
            <div className="footer-bottom-bar py-3">
                <div className="container">
                    <div className="row align-items-center justify-content-between">
                        <div className="col-12 col-md-6 text-center text-md-start mb-2 mb-md-0">
                            <p className="copyright-text mb-0">
                                {copyrightText}
                            </p>
                        </div>
                        <div className="col-12 col-md-6 text-center text-md-end">
                            <p className="developed-by-text mb-0">
                                {developedByUrl && developedByUrl !== '#' ? (
                                    <a href={developedByUrl} target="_blank" rel="noreferrer" className="developed-by-link">
                                        {developedByText}
                                    </a>
                                ) : (
                                    <span>{developedByText}</span>
                                )}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Floating Back to Top Button */}
            <button
                type="button"
                className="scroll-to-top-btn"
                onClick={scrollToTop}
                aria-label="Scroll to top"
            >
                <i className="fa fa-arrow-up"></i>
            </button>
        </footer>
    );
}
