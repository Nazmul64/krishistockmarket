import React, { useState } from 'react';

export default function ProductGallery({ gallery = [], routes = {} }) {
    const [selectedImage, setSelectedImage] = useState(null);

    if (!gallery || gallery.length === 0) return null;

    return (
        <section className="product-gallery-section py-5" id="gallery">
            <div className="container text-center">
                {/* Header */}
                <div className="section-head-wrap text-center mb-5">
                    <div className="section-pill-badge">
                        <i className="fa fa-camera me-1"></i> ছবি ও ভিজ্যুয়াল
                    </div>
                    <h2 className="section-main-title">
                        <span className="text-gradient">প্রোডাক্ট</span> গ্যালারি
                    </h2>
                    <p className="section-sub-title">
                        আমাদের বিভিন্ন উচ্চমানসম্পন্ন কৃষি পণ্য, খামার ও স্টকের সংগৃহীত সেরা ছবিসমূহ
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
                        <span>সকল গ্যালারি পিকচার দেখুন</span>
                        <i className="fa fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>

            {/* Lightbox Modal */}
            {selectedImage && (
                <div className="gallery-lightbox-overlay" onClick={() => setSelectedImage(null)}>
                    <div className="lightbox-content-box" onClick={(e) => e.stopPropagation()}>
                        <button
                            type="button"
                            className="lightbox-close-btn"
                            onClick={() => setSelectedImage(null)}
                        >
                            <i className="fa fa-times"></i>
                        </button>
                        <img src={selectedImage} alt="Enlarged preview" className="lightbox-main-img" />
                    </div>
                </div>
            )}
        </section>
    );
}
