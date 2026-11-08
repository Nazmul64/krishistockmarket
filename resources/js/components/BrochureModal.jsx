import React from 'react';

export default function BrochureModal({ isOpen, onClose, card }) {
    if (!isOpen || !card || !card.brochure_image) return null;

    return (
        <div className="brochure-modal-overlay" onClick={onClose}>
            <div
                className="brochure-modal-container"
                onClick={(e) => e.stopPropagation()}
            >
                <div className="brochure-modal-header">
                    <div className="d-flex align-items-center gap-2">
                        <i className="fa fa-file-image-o text-primary"></i>
                        <h5 className="modal-title mb-0">{card.card_name} - অফিসিয়াল ব্রোশিউর / লিফলেট</h5>
                    </div>
                    <button
                        type="button"
                        className="modal-close-btn"
                        onClick={onClose}
                        aria-label="Close"
                    >
                        <i className="fa fa-times"></i>
                    </button>
                </div>

                <div className="brochure-modal-body">
                    <img
                        src={card.brochure_image}
                        alt={`${card.card_name} Brochure`}
                        className="brochure-full-image"
                    />
                </div>

                <div className="brochure-modal-footer">
                    <a
                        href={card.brochure_image}
                        download={`${card.card_name}_Brochure`}
                        className="btn btn-primary btn-sm d-inline-flex align-items-center gap-2 px-3"
                    >
                        <i className="fa fa-download"></i>
                        <span>ব্রোশিউর ডাউনলোড করুন</span>
                    </a>
                    <button
                        type="button"
                        className="btn btn-secondary btn-sm px-3"
                        onClick={onClose}
                    >
                        বন্ধ করুন
                    </button>
                </div>
            </div>
        </div>
    );
}
