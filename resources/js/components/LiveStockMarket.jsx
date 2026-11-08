import React from 'react';

export default function LiveStockMarket({ stocks = [], routes = {} }) {
    // Generate an aesthetic SVG mini sparkline path for real-time stock rate visualization
    const renderMiniChart = (id, isUp = true) => {
        // Unique variations based on id
        const pathData = (id % 3 === 0)
            ? "M0,35 Q25,10 50,22 T100,12 T150,25 T200,8"
            : (id % 2 === 0)
                ? "M0,30 Q30,15 60,28 T120,8 T160,18 T200,5"
                : "M0,38 Q35,20 70,30 T130,12 T170,22 T200,6";

        const fillData = (id % 3 === 0)
            ? "M0,35 Q25,10 50,22 T100,12 T150,25 T200,8 L200,45 L0,45 Z"
            : (id % 2 === 0)
                ? "M0,30 Q30,15 60,28 T120,8 T160,18 T200,5 L200,45 L0,45 Z"
                : "M0,38 Q35,20 70,30 T130,12 T170,22 T200,6 L200,45 L0,45 Z";

        return (
            <div className="stock-sparkline-wrap">
                <div className="d-flex justify-content-between align-items-center mb-1">
                    <span className="sparkline-title">
                        <i className="fa fa-line-chart text-success me-1"></i> লাইভ প্রাইস ট্রেন্ড (Live Trend)
                    </span>
                    <span className="sparkline-badge text-success">
                        <i className="fa fa-caret-up me-1"></i>মার্কেট সক্রিয়
                    </span>
                </div>
                <svg viewBox="0 0 200 45" className="stock-sparkline-svg" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id={`chartGrad-${id}`} x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stopColor="#10b981" stopOpacity="0.28" />
                            <stop offset="100%" stopColor="#10b981" stopOpacity="0.0" />
                        </linearGradient>
                    </defs>
                    <path d={fillData} fill={`url(#chartGrad-${id})`} />
                    <path d={pathData} fill="none" stroke="#10b981" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round" />
                    <circle cx="200" cy={id % 3 === 0 ? "8" : (id % 2 === 0 ? "5" : "6")} r="3.5" fill="#10b981" />
                </svg>
            </div>
        );
    };

    return (
        <section className="live-stock-section py-5" id="stock-market">
            <div className="container">
                {/* Section Header */}
                <div className="section-head-wrap text-center mb-4 mb-md-5">
                    <div className="section-pill-badge">
                        <span className="live-pulsing-badge"></span>
                        <span>রিয়েল-টাইম মার্কেট ফিড</span>
                    </div>
                    <h2 className="section-main-title">
                        লাইভ কৃষি স্টক ও বাজার দর
                    </h2>
                    <p className="section-sub-title">
                        দৈনিক আপডেটকৃত কৃষি পণ্যের ক্রয় ও বিক্রয় মূল্য পর্যবেক্ষণ করুন এবং নির্ভরযোগ্য সিদ্ধান্তে বিনিয়োগ করুন।
                    </p>
                </div>

                {/* Stock Cards Grid - Fully Responsive & Centered */}
                {stocks && stocks.length > 0 ? (
                    <div className="row g-3 g-md-4 justify-content-center">
                        {stocks.map((stock) => (
                            <div key={stock.id} className="col-12 col-sm-6 col-lg-4">
                                <div className="stock-card-box h-100">
                                    {/* Full-width Product Image Container */}
                                    <div className="stock-card-thumb-wrap">
                                        <a href={stock.details_url} className="d-block w-100 h-100 text-center">
                                            <img
                                                src={stock.image}
                                                alt={stock.name}
                                                className="stock-card-thumb-img"
                                                loading="lazy"
                                            />
                                        </a>
                                        {/* Floating Glassmorphic Live Tag */}
                                        <div className="stock-floating-live-badge">
                                            <span className="live-dot-green"></span>
                                            <span>লাইভ</span>
                                        </div>
                                        {/* Category Badge */}
                                        <div className="stock-floating-cat-badge">
                                            {stock.category || 'কৃষি পণ্য'}
                                        </div>
                                    </div>

                                    {/* Stock Content Body */}
                                    <div className="stock-card-body">
                                        <div className="stock-title-row mb-2">
                                            <h4 className="stock-name" title={stock.name}>
                                                <a href={stock.details_url}>
                                                    {stock.name}
                                                </a>
                                            </h4>
                                        </div>

                                        {/* Stock Availability Info */}
                                        <div className="stock-avail-info mb-3">
                                            <span className="avail-label">
                                                <i className="fa fa-cubes text-warning me-1"></i> মজুদ পরিমাণ:
                                            </span>
                                            <span className="avail-value-badge">
                                                {stock.is_unlimited ? 'আনলিমিটেড' : `${stock.quantity} ${stock.unit || 'কেজি'}`}
                                            </span>
                                        </div>

                                        {/* Live Sparkline Graph Chart */}
                                        {renderMiniChart(stock.id)}

                                        {/* Price Comparison Container with distinct color cards */}
                                        <div className="stock-price-grid mt-3 mb-3">
                                            <div className="price-col selling-col">
                                                <span className="price-label">
                                                    <i className="fa fa-tag me-1"></i> বিক্রয় মূল্য
                                                </span>
                                                <span className="price-val text-emerald">
                                                    ৳{Number(stock.selling_price || 0).toLocaleString('en-US')}
                                                </span>
                                            </div>
                                            <div className="price-col buying-col">
                                                <span className="price-label">
                                                    <i className="fa fa-shopping-bag me-1"></i> ক্রয় মূল্য
                                                </span>
                                                <span className="price-val text-sky">
                                                    ৳{Number(stock.buying_price || 0).toLocaleString('en-US')}
                                                </span>
                                            </div>
                                        </div>

                                        {/* CTA Button */}
                                        <a
                                            href={stock.details_url}
                                            className="btn-stock-view"
                                        >
                                            <span>বিস্তারিত দেখুন</span>
                                            <i className="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="empty-stock-state text-center py-5">
                        <i className="fa fa-cubes fa-3x text-muted mb-3"></i>
                        <h5>কোনো স্টক পণ্য পাওয়া যায়নি</h5>
                    </div>
                )}

                {/* Section Bottom Button */}
                <div className="text-center mt-4 mt-md-5">
                    <a href={routes.stock_index || '/stocks'} className="btn-browse-all-stocks">
                        <span>সকল স্টক ও পূর্ণ মার্কেট রেট দেখুন</span>
                        <i className="fa fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>
        </section>
    );
}
