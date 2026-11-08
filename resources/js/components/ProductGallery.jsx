import React, { useState } from 'react';

export default function ProductGallery({
    gallery = [],
    routes = {},
    lang = 'en',
    t = {}
}) {
    const [selectedImage, setSelectedImage] = useState(null);

    if (!gallery || gallery.length === 0) return null;

    return (
        <section className="product-gallery-section py-5" id="gallery">
            <div className="container text-center">
                {/* Header */}
                <div className="section-head-wrap text-center mb-5">
                    <div className="section-pill-badge">
                        <i className="fa fa-camera me-1"></i> {t.gallery_badge || 'Produce Gallery'}
                    </div>
                    <h2 className="section-main-title">
                        {t.gallery_title || 'Fresh Agricultural Produce Gallery'}
                    </h2>
                    <p className="section-sub-title">
                        {t.gallery_sub || 'Moments from our partner farms, fields, and high-quality harvested commodities.'}
                    </p>
                </div>

                {/* Grid */}
                <div className="row g-3 g-md-4 gallery-modern-grid">
                    {gallery.map((item, idx) => (
                        <div key={item.id || idx} className="col-6 col-md-4 col-lg-4">
                            <div
                                className="gallery-modern-item"
                                onClick={() => setSelectedImage(item.image)}
                            >
                                <div className="gallery-thumb-wrap">
                                    <img
                                        src={item.image}
                                        alt={item.title || `Gallery ${idx + 1}`}
                                        className="gallery-thumb-img"
                                        loading="lazy"
                                    />
                                    <div className="gallery-hover-overlay">
                                        <div className="zoom-btn-circle">
                                            <i className="fa fa-search-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Browse all button */}
                <div className="mt-4 pt-2">
                    <a href={routes.gallery || '/gallery'} className="btn-gallery-all">
                        <span>{lang === 'bn' ? 'সকল গ্যালারি পিকচার দেখুন' : 'View Complete Gallery'}</span>
                        <i className="fa fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>

            {/* Lightbox Modal */}
            {selectedImage && (
                <div
                    className="image-lightbox-overlay show"
                    onClick={() => setSelectedImage(null)}
                >
                    <div className="image-lightbox-wrapper" onClick={(e) => e.stopPropagation()}>
                        <button
                            type="button"
                            className="lightbox-close-btn"
                            onClick={() => setSelectedImage(null)}
                        >
                            <i className="fa fa-times"></i>
                        </button>
                        <img
                            src={selectedImage}
                            alt="Gallery Large Preview"
                            className="lightbox-full-img"
                        />
                    </div>
                </div>
            )}
        </section>
    );
}
