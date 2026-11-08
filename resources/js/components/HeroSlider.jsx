import React, { useState, useEffect } from 'react';

export default function HeroSlider({
    sliders = [],
    offerBanner = null,
    routes = {},
    lang = 'en',
    t = {}
}) {
    const [currentSlide, setCurrentSlide] = useState(0);

    const defaultSliders = [
        {
            id: 1,
            image: '/frontend/assets/images/slider/slide-1.jpg',
        }
    ];

    const activeSliders = sliders.length > 0 ? sliders : defaultSliders;

    useEffect(() => {
        if (activeSliders.length <= 1) return;
        const interval = setInterval(() => {
            setCurrentSlide((prev) => (prev + 1) % activeSliders.length);
        }, 5000);
        return () => clearInterval(interval);
    }, [activeSliders.length]);

    const goToSlide = (idx) => {
        setCurrentSlide(idx);
    };

    return (
        <section className="hero-slider-section">
            <div className="container">
                <div className="row g-4 align-items-stretch">
                    {/* Main Banner Slider */}
                    <div className={offerBanner ? 'col-lg-8 col-12' : 'col-12'}>
                        <div className="hero-slider-container">
                            {activeSliders.map((slide, index) => (
                                <div
                                    key={slide.id || index}
                                    className={`hero-slide-item ${index === currentSlide ? 'active' : ''}`}
                                >
                                    <img
                                        src={slide.image}
                                        alt="Banner Slide"
                                        className="hero-slide-pure-img"
                                    />
                                </div>
                            ))}

                            {/* Slider Navigation Dots */}
                            {activeSliders.length > 1 && (
                                <div className="hero-slider-dots">
                                    {activeSliders.map((_, idx) => (
                                        <button
                                            key={idx}
                                            type="button"
                                            className={`hero-dot-btn ${idx === currentSlide ? 'active' : ''}`}
                                            onClick={() => goToSlide(idx)}
                                            aria-label={`Slide ${idx + 1}`}
                                        />
                                    ))}
                                </div>
                            )}

                            {/* Slide Prev/Next Arrows */}
                            {activeSliders.length > 1 && (
                                <div className="hero-slider-arrows">
                                    <button
                                        type="button"
                                        className="hero-arrow-btn prev"
                                        onClick={() => setCurrentSlide((prev) => (prev - 1 + activeSliders.length) % activeSliders.length)}
                                        aria-label="Previous Slide"
                                    >
                                        <i className="fa fa-chevron-left"></i>
                                    </button>
                                    <button
                                        type="button"
                                        className="hero-arrow-btn next"
                                        onClick={() => setCurrentSlide((prev) => (prev + 1) % activeSliders.length)}
                                        aria-label="Next Slide"
                                    >
                                        <i className="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Offer Banner on Right (if configured) */}
                    {offerBanner && (
                        <div className="col-lg-4 col-12 d-flex">
                            <div className="offer-banner-card w-100">
                                <a href={offerBanner.link} className="offer-banner-link d-block h-100">
                                    <div className="offer-banner-img-wrap">
                                        <img src={offerBanner.image} alt={offerBanner.title} />
                                        <div className="offer-badge-float">
                                            <i className="fa fa-bolt me-1"></i> {lang === 'bn' ? 'স্পেশাল অফার' : 'Special Offer'}
                                        </div>
                                    </div>
                                    <div className="offer-banner-caption">
                                        <h4 className="offer-title">{offerBanner.title}</h4>
                                        <span className="offer-cta-text">
                                            {lang === 'bn' ? 'বিস্তারিত জানুন' : 'Learn More'} <i className="fa fa-arrow-right ms-1"></i>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    )}
                </div>

                {/* Quick Trust Highlights Strip - Hidden on Mobile Device as requested */}
                <div className="quick-highlights-strip mt-4 d-none d-md-block">
                    <div className="row g-3">
                        <div className="col-6 col-md-3">
                            <div className="highlight-box">
                                <div className="highlight-icon bg-gold">
                                    <i className="fa fa-leaf"></i>
                                </div>
                                <div className="highlight-text">
                                    <h5>{t.hl_1_title || (lang === 'bn' ? 'প্রাকৃতিক ও খাঁটি পণ্য' : 'Natural & Pure Products')}</h5>
                                    <p>{t.hl_1_sub || (lang === 'bn' ? 'সরাসরি কৃষকের মাঠ থেকে' : "Directly from farmer's fields")}</p>
                                </div>
                            </div>
                        </div>
                        <div className="col-6 col-md-3">
                            <div className="highlight-box">
                                <div className="highlight-icon bg-emerald">
                                    <i className="fa fa-line-chart"></i>
                                </div>
                                <div className="highlight-text">
                                    <h5>{t.hl_2_title || (lang === 'bn' ? 'লাইভ স্টক রেট' : 'Live Stock Rates')}</h5>
                                    <p>{t.hl_2_sub || (lang === 'bn' ? 'প্রতিদিনের রিয়েল-টাইম দর' : 'Real-time daily rates')}</p>
                                </div>
                            </div>
                        </div>
                        <div className="col-6 col-md-3">
                            <div className="highlight-box">
                                <div className="highlight-icon bg-amber">
                                    <i className="fa fa-shield"></i>
                                </div>
                                <div className="highlight-text">
                                    <h5>{t.hl_3_title || (lang === 'bn' ? 'নিরাপদ বিনিয়োগ' : 'Secure Investment')}</h5>
                                    <p>{t.hl_3_sub || (lang === 'bn' ? '১০০% সুরক্ষিত লেনদেন' : '100% Protected transactions')}</p>
                                </div>
                            </div>
                        </div>
                        <div className="col-6 col-md-3">
                            <div className="highlight-box">
                                <div className="highlight-icon bg-gold">
                                    <i className="fa fa-credit-card"></i>
                                </div>
                                <div className="highlight-text">
                                    <h5>{t.hl_4_title || (lang === 'bn' ? 'এসএমই ও গোল্ড কার্ড' : 'SME & Gold Cards')}</h5>
                                    <p>{t.hl_4_sub || (lang === 'bn' ? 'বিশেষ বোনাস ও সুবিধা' : 'Special bonuses & perks')}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
