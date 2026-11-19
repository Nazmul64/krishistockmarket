import React from 'react';

export default function MonthlyBazarSection({ products = [], routes = {}, lang = 'bn' }) {
    if (!products || products.length === 0) {
        return null;
    }

    const shopUrl = routes.our_products ? `${routes.our_products}?badge=monthly-bazar` : '/our-products?badge=monthly-bazar';

    return (
        <section className="monthly-bazar-home-showcase py-4" style={{ backgroundColor: '#ffffff', borderBottom: '1px solid #e2e8f0' }}>
            <div className="container">
                {/* Header */}
                <div className="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 pb-2 border-bottom">
                    <div className="d-flex align-items-center gap-2">
                        <div className="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style={{ width: '46px', height: '46px', background: 'linear-gradient(135deg, #007e8a, #005f69)', color: '#ffffff' }}>
                            <i className="fa fa-shopping-basket fs-5"></i>
                        </div>
                        <div>
                            <h3 className="fw-bold text-dark mb-0" style={{ fontSize: '22px' }}>
                                {lang === 'bn' ? 'মাসিক বাজার' : 'Monthly Bazar'} <span style={{ color: '#007e8a' }}>{lang === 'bn' ? '(সুপারশপ অফার)' : '(SuperShop Deals)'}</span>
                            </h3>
                            <p className="text-muted small mb-0">
                                {lang === 'bn' ? 'নিত্যপ্রয়োজনীয় ফ্রেশ মাসিক বাজার তালিকা ও সাশ্রয়ী মূল্যে পণ্য' : 'Essential monthly grocery list and fresh supermarket deals'}
                            </p>
                        </div>
                    </div>
                    <div>
                        <a href={shopUrl} className="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                            <span>{lang === 'bn' ? 'সকল মাসিক বাজার পণ্য দেখুন (৯২৬+)' : 'View All Monthly Bazar (926+)'}</span>
                            <i className="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {/* Product Grid */}
                <div className="row g-3">
                    {products.slice(0, 10).map((prod) => (
                        <div className="col-6 col-md-4 col-lg-2-4 col-xl-2" key={prod.id}>
                            <div className="card h-100 border shadow-sm rounded-3 overflow-hidden position-relative product-card-hover" style={{ transition: 'transform 0.2s, box-shadow 0.2s' }}>
                                {/* Badge */}
                                <span className="badge bg-success position-absolute top-0 start-0 m-2 rounded-pill font-size-10 shadow-sm" style={{ zIndex: 2, fontSize: '10px' }}>
                                    {lang === 'bn' ? 'মাসিক বাজার' : 'Monthly Bazar'}
                                </span>

                                {/* Product Image */}
                                <a href={prod.url} className="d-block p-2 text-center bg-light" style={{ height: '140px' }}>
                                    <img
                                        src={prod.image}
                                        alt={prod.name}
                                        style={{ width: '100%', height: '100%', objectFit: 'contain' }}
                                        loading="lazy"
                                        onError={(e) => { e.target.src = '/uploads/products/default.png'; }}
                                    />
                                </a>

                                {/* Body */}
                                <div className="card-body p-2 d-flex flex-column justify-content-between">
                                    <div>
                                        <div className="text-muted" style={{ fontSize: '10px' }}>
                                            <i className="fa fa-truck text-teal me-1"></i>
                                            {prod.delivery_time || 'Delivery 1-2 hours'}
                                        </div>
                                        <a href={prod.url} className="text-dark fw-bold d-block text-truncate mt-1 text-decoration-none" title={prod.name} style={{ fontSize: '13px' }}>
                                            {prod.name}
                                        </a>
                                    </div>

                                    <div className="mt-2 pt-2 border-top">
                                        <div className="d-flex align-items-baseline justify-content-between mb-2">
                                            <span className="fw-bold text-success fs-6">{prod.formatted_price}</span>
                                            {prod.unit && (
                                                <small className="text-muted" style={{ fontSize: '10px' }}>/{prod.unit}</small>
                                            )}
                                        </div>

                                        <a href={prod.url} className="btn btn-sm w-100 text-white rounded-pill py-1 fw-bold shadow-sm" style={{ background: '#007e8a', fontSize: '12px' }}>
                                            <i className="fa fa-shopping-bag me-1"></i> {lang === 'bn' ? 'অর্ডার করুন' : 'Order Now'}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
