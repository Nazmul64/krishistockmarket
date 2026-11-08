import React, { useState, useEffect } from 'react';
import BrochureModal from './BrochureModal';

export default function MembershipCards({ cardBenefits = [], routes = {} }) {
    const [selectedBrochureCard, setSelectedBrochureCard] = useState(null);
    const [activeIdx, setActiveIdx] = useState(0);
    const [isPaused, setIsPaused] = useState(false);
    const [isMobile, setIsMobile] = useState(false);
    const [animating, setAnimating] = useState(false);

    const totalCards = cardBenefits.length;

    useEffect(() => {
        const handleResize = () => {
            setIsMobile(window.innerWidth < 992);
        };
        handleResize();
        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    // Auto rotate every 4 seconds if more cards exist than visible slots
    useEffect(() => {
        const slotsCount = isMobile ? 1 : Math.min(2, totalCards);
        if (totalCards <= slotsCount || isPaused) return;

        const interval = setInterval(() => {
            triggerNext();
        }, 4000);

        return () => clearInterval(interval);
    }, [totalCards, isMobile, isPaused]);

    const triggerNext = () => {
        setAnimating(true);
        setTimeout(() => {
            setActiveIdx((prev) => (prev + 1) % totalCards);
            setAnimating(false);
        }, 250);
    };

    const triggerPrev = () => {
        setAnimating(true);
        setTimeout(() => {
            setActiveIdx((prev) => (prev - 1 + totalCards) % totalCards);
            setAnimating(false);
        }, 250);
    };

    if (!cardBenefits || cardBenefits.length === 0) return null;

    // Determine cards to show in the 2 boxes (or 1 on mobile)
    let visibleCards = [];
    if (totalCards === 1) {
        visibleCards = [cardBenefits[0]];
    } else if (isMobile) {
        visibleCards = [cardBenefits[activeIdx % totalCards]];
    } else {
        // Desktop: Always show 2 full cards
        const first = cardBenefits[activeIdx % totalCards];
        const second = cardBenefits[(activeIdx + 1) % totalCards];
        visibleCards = [first, second];
    }

    const renderCard = (card, key) => {
        if (!card) return null;
        const isGold = card.card_color_theme === 'gold' || card.card_type === 'gold';
        const isRed = card.card_color_theme === 'red' || card.card_type === 'red';
        const themeClass = isGold ? 'card-theme-gold' : (isRed ? 'card-theme-red' : 'card-theme-blue');

        return (
            <div key={key} className={`${visibleCards.length === 1 ? 'col-12 col-md-8 col-lg-6' : 'col-12 col-md-6'} mb-3`}>
                <div className={`membership-card-box ${themeClass} ${animating ? 'fade-transition' : ''} h-100 d-flex flex-column`}>
                    {/* Card Top Header */}
                    <div className="card-box-header p-3 p-md-4 border-bottom">
                        <div className="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <span className="membership-badge-pill">
                                <i className="fa fa-star me-1"></i> {card.badge_text || (isGold ? 'গোল্ডেন মেম্বারশিপ' : 'নরমাল মেম্বারশিপ')}
                            </span>
                            {card.validity && (
                                <span className="validity-badge">
                                    <i className="fa fa-calendar-check-o me-1"></i> মেয়াদ: {card.validity}
                                </span>
                            )}
                        </div>
                        <h3 className="membership-card-name mb-1">{card.card_name}</h3>
                        {card.card_number_sample && (
                            <div className="card-sample-number-tag">
                                <i className="fa fa-credit-card me-1"></i>
                                <code>{card.card_number_sample}</code>
                            </div>
                        )}
                    </div>

                    {/* Card Visual Flyer Image - 100% Full Width */}
                    <div className="card-box-visual p-2 p-md-3 text-center bg-slate-50 border-bottom">
                        <div 
                            className="card-flyer-image-holder"
                            onClick={() => card.brochure_image ? setSelectedBrochureCard(card) : (card.image ? setSelectedBrochureCard({ ...card, brochure_image: card.image }) : null)}
                            title="ব্রোশিউর বড় করে দেখতে ক্লিক করুন"
                        >
                            {card.image ? (
                                <img
                                    src={card.image}
                                    alt={card.card_name}
                                    className="card-flyer-img"
                                    loading="lazy"
                                />
                            ) : (
                                <div className="card-empty-placeholder">
                                    <i className="fa fa-id-card fa-3x text-amber-500 mb-2"></i>
                                    <p className="mb-0 fw-bold">{card.card_name}</p>
                                </div>
                            )}
                        </div>

                        {(card.brochure_image || card.image) && (
                            <div className="mt-2">
                                <button
                                    type="button"
                                    className="btn-brochure-modal-trigger"
                                    onClick={() => setSelectedBrochureCard(card.brochure_image ? card : { ...card, brochure_image: card.image })}
                                >
                                    <i className="fa fa-eye me-1"></i> অফিসিয়াল ব্রোশিউর / লিফলেট দেখুন
                                </button>
                            </div>
                        )}
                    </div>

                    {/* Specifications and Details */}
                    <div className="card-box-content p-3 p-md-4 d-flex flex-column flex-grow-1">
                        {card.short_description && (
                            <div className="card-summary-box mb-3">
                                <p className="card-summary-desc mb-0">
                                    {card.short_description}
                                </p>
                            </div>
                        )}

                        {/* Key Specs Grid */}
                        <div className="card-specs-list mb-3">
                            {card.card_fee && (
                                <div className="spec-info-row">
                                    <div className="spec-title">
                                        <i className="fa fa-receipt text-warning me-2"></i>
                                        <span>সদস্য ফি:</span>
                                    </div>
                                    <div className="spec-val fw-bold">{card.card_fee}</div>
                                </div>
                            )}

                            {card.investment_limit && (
                                <div className="spec-info-row highlight-green">
                                    <div className="spec-title">
                                        <i className="fa fa-line-chart text-success me-2"></i>
                                        <span>বিনিয়োগ সীমা:</span>
                                    </div>
                                    <div className="spec-val text-success fw-bold">{card.investment_limit}</div>
                                </div>
                            )}

                            {card.monthly_profit && (
                                <div className="spec-info-row">
                                    <div className="spec-title">
                                        <i className="fa fa-coins text-warning me-2"></i>
                                        <span>লভ্যাংশ বিবরণ:</span>
                                    </div>
                                    <div className="spec-val">{card.monthly_profit}</div>
                                </div>
                            )}

                            {card.withdrawal_notice && (
                                <div className="spec-info-row highlight-red">
                                    <div className="spec-title">
                                        <i className="fa fa-clock-o text-danger me-2"></i>
                                        <span>উত্তোলন নোটিশ:</span>
                                    </div>
                                    <div className="spec-val text-danger">{card.withdrawal_notice}</div>
                                </div>
                            )}
                        </div>

                        {/* Rules and Facilities Checklist */}
                        <div className="card-rules-facilities mb-3 flex-grow-1">
                            <h5 className="rules-section-title mb-2">
                                <i className="fa fa-check-square-o text-amber-500 me-2"></i>
                                নিয়মাবলী ও বিশেষ সুবিধাসমূহ:
                            </h5>
                            {Array.isArray(card.facilities) && card.facilities.length > 0 ? (
                                <div className="rules-scrollable-box">
                                    <ul className="rules-list-wrap list-unstyled mb-0">
                                        {card.facilities.map((fac, idx) => (
                                            <li key={idx} className="rule-list-item">
                                                <i className="fa fa-check-circle text-success me-2 flex-shrink-0 mt-1"></i>
                                                <span>{fac}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            ) : (
                                <p className="text-muted small mb-0">কোনো নিয়ম বা সুবিধা তালিকাভুক্ত নেই</p>
                            )}
                        </div>

                        {/* CTA Action Button */}
                        <div className="mt-auto pt-2">
                            <a
                                href={card.action_button_url || routes.register_url || '/user/register'}
                                className="btn-card-register-cta w-100 text-center"
                            >
                                <span>{card.action_button_text || 'রেজিস্ট্রেশন করুন'}</span>
                                <i className="fa fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        );
    };

    return (
        <section className="membership-cards-section py-5" id="card-benefits">
            <div className="container">
                {/* Header */}
                <div className="section-head-wrap text-center mb-4">
                    <div className="section-pill-badge">
                        <i className="fa fa-id-card me-1"></i> কৃষি পরিবার মেম্বারশিপ কার্ড
                    </div>
                    <h2 className="section-main-title">
                        <span className="text-gradient">কার্ড বিশেষ সুবিধাসমূহ</span> ও বিনিয়োগ নিয়মাবলী
                    </h2>
                    <p className="section-sub-title">
                        কৃষি এসএমই কার্ড ও গোল্ডেন মেম্বারশিপ কার্ডের বিশেষ সুযোগ-সুবিধা, সর্বোচ্চ লাভ ও এক্সক্লুসিভ অফারসমূহ
                    </p>
                </div>

                {/* Fixed Container with 2 Card Boxes on Desktop */}
                <div 
                    className="membership-fixed-slider-container position-relative"
                    onMouseEnter={() => setIsPaused(true)}
                    onMouseLeave={() => setIsPaused(false)}
                >
                    <div className="row g-4 justify-content-center">
                        {visibleCards.map((card, idx) => renderCard(card, `${card.id}-${idx}`))}
                    </div>

                    {/* Navigation Arrows */}
                    {totalCards > (isMobile ? 1 : 2) && (
                        <>
                            <button
                                type="button"
                                className="membership-nav-btn membership-prev-btn"
                                onClick={triggerPrev}
                                aria-label="Previous Card"
                            >
                                <i className="fa fa-chevron-left"></i>
                            </button>
                            <button
                                type="button"
                                className="membership-nav-btn membership-next-btn"
                                onClick={triggerNext}
                                aria-label="Next Card"
                            >
                                <i className="fa fa-chevron-right"></i>
                            </button>
                        </>
                    )}

                    {/* Pagination Dots */}
                    {totalCards > (isMobile ? 1 : 2) && (
                        <div className="membership-slider-dots text-center mt-4">
                            {cardBenefits.map((_, idx) => (
                                <button
                                    key={idx}
                                    type="button"
                                    className={`card-dot-btn ${idx === (activeIdx % totalCards) ? 'active' : ''}`}
                                    onClick={() => {
                                        setAnimating(true);
                                        setTimeout(() => {
                                            setActiveIdx(idx);
                                            setAnimating(false);
                                        }, 200);
                                    }}
                                    aria-label={`Go to card ${idx + 1}`}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* Brochure Modal for High-Res View */}
            <BrochureModal
                isOpen={Boolean(selectedBrochureCard)}
                card={selectedBrochureCard}
                onClose={() => setSelectedBrochureCard(null)}
            />
        </section>
    );
}
