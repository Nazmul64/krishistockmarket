import React, { useState, useEffect } from 'react';

export default function PackageSection({ packages = [] }) {
    const [activeIdx, setActiveIdx] = useState(0);
    const [selectedImage, setSelectedImage] = useState(null);
    const [isPaused, setIsPaused] = useState(false);
    const [slotsCount, setSlotsCount] = useState(3);
    const [animating, setAnimating] = useState(false);

    const totalPackages = packages.length;

    useEffect(() => {
        const handleResize = () => {
            if (window.innerWidth < 640) {
                setSlotsCount(1);
            } else if (window.innerWidth < 992) {
                setSlotsCount(2);
            } else {
                setSlotsCount(Math.min(3, totalPackages));
            }
        };

        handleResize();
        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, [totalPackages]);

    // Auto rotate every 3.5 seconds if more packages exist than slots
    useEffect(() => {
        if (totalPackages <= slotsCount || isPaused) return;

        const interval = setInterval(() => {
            triggerNext();
        }, 3500);

        return () => clearInterval(interval);
    }, [totalPackages, slotsCount, isPaused]);

    const triggerNext = () => {
        setAnimating(true);
        setTimeout(() => {
            setActiveIdx((prev) => (prev + 1) % totalPackages);
            setAnimating(false);
        }, 250);
    };

    const triggerPrev = () => {
        setAnimating(true);
        setTimeout(() => {
            setActiveIdx((prev) => (prev - 1 + totalPackages) % totalPackages);
            setAnimating(false);
        }, 250);
    };

    if (!packages || packages.length === 0) {
        return null;
    }

    // Prepare visible packages for the exact slots
    const visiblePkgs = [];
    const count = Math.min(slotsCount, totalPackages);
    for (let i = 0; i < count; i++) {
        visiblePkgs.push(packages[(activeIdx + i) % totalPackages]);
    }

    const colClass = slotsCount === 1 
        ? 'col-12 col-md-8 col-lg-6' 
        : (slotsCount === 2 ? 'col-12 col-md-6' : 'col-12 col-md-6 col-lg-4');

    return (
        <section className="packages-section py-5" id="packages">
            <div className="container">
                {/* Section Header */}
                <div className="section-head-wrap text-center mb-4">
                    <span className="section-pill-badge">
                        <i className="fa fa-gift me-1"></i> স্পেশাল প্যাকেজ
                    </span>
                    <h2 className="section-main-title">
                        <span className="text-gradient">আওয়ার প্যাকেজসমূহ</span> (Our Packages)
                    </h2>
                    <p className="section-sub-title">
                        আমাদের বিশেষ মেম্বারশিপ প্রসেস, ব্যবসায়িক প্যাকেজ ও অনন্য সুযোগ-সুবিধা উপভোগ করুন।
                    </p>
                </div>

                {/* Package Carousel Slider with Fixed Slots */}
                <div 
                    className="package-fixed-slider-container position-relative"
                    onMouseEnter={() => setIsPaused(true)}
                    onMouseLeave={() => setIsPaused(false)}
                >
                    <div className="row g-4 justify-content-center">
                        {visiblePkgs.map((pkg, index) => (
                            <div key={`${pkg.id || index}-${index}`} className={colClass}>
                                <div 
                                    className={`package-pure-image-card ${animating ? 'fade-transition' : ''}`}
                                    onClick={() => setSelectedImage(pkg.image)}
                                    title="বড় করে দেখতে ক্লিক করুন"
                                >
                                    <div className="pkg-img-box">
                                        <img
                                            src={pkg.image}
                                            alt={`Package ${index + 1}`}
                                            className="pkg-pure-img"
                                            loading="lazy"
                                        />
                                        <div className="pkg-hover-zoom-hint">
                                            <i className="fa fa-search-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Navigation Arrows */}
                    {totalPackages > slotsCount && (
                        <>
                            <button
                                type="button"
                                className="pkg-slider-btn pkg-prev-btn"
                                onClick={triggerPrev}
                                aria-label="Previous Package"
                            >
                                <i className="fa fa-chevron-left"></i>
                            </button>
                            <button
                                type="button"
                                className="pkg-slider-btn pkg-next-btn"
                                onClick={triggerNext}
                                aria-label="Next Package"
                            >
                                <i className="fa fa-chevron-right"></i>
                            </button>
                        </>
                    )}

                    {/* Pagination Dots */}
                    {totalPackages > slotsCount && (
                        <div className="pkg-slider-dots text-center mt-4">
                            {packages.map((_, idx) => (
                                <button
                                    key={idx}
                                    type="button"
                                    className={`pkg-dot-btn ${idx === (activeIdx % totalPackages) ? 'active' : ''}`}
                                    onClick={() => {
                                        setAnimating(true);
                                        setTimeout(() => {
                                            setActiveIdx(idx);
                                            setAnimating(false);
                                        }, 200);
                                    }}
                                    aria-label={`Go to slide ${idx + 1}`}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* Lightbox / Full View Modal for Package Image */}
            {selectedImage && (
                <div className="pkg-lightbox-overlay" onClick={() => setSelectedImage(null)}>
                    <div className="pkg-lightbox-content" onClick={(e) => e.stopPropagation()}>
                        <button 
                            type="button" 
                            className="pkg-lightbox-close"
                            onClick={() => setSelectedImage(null)}
                        >
                            <i className="fa fa-times"></i>
                        </button>
                        <img src={selectedImage} alt="Package Detail" className="pkg-lightbox-img" />
                    </div>
                </div>
            )}
        </section>
    );
}
