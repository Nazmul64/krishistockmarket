import React from 'react';

export default function MonthlyBazarSection({ products = [], routes = {}, site = {}, lang = 'bn' }) {
    if (!products || products.length === 0) {
        return null;
    }

    const shopUrl = routes.our_products ? `${routes.our_products}?badge=monthly-bazar` : '/our-products?badge=monthly-bazar';
    const activeBtnColor = (site && site.button_color) ? site.button_color : '#007e8a';
    const isBtnAnimated = site && site.button_animation !== '0' && site.button_animation !== false;
    const dynamicOrderBtnText = (lang === 'bn')
        ? 'অর্ডার করুন'
        : ((site && site.product_order_btn_text && site.product_order_btn_text !== 'অর্ডার করুন') ? site.product_order_btn_text : 'Order Now');

    return (
        <section className="monthly-bazar-home-showcase py-4" style={{ backgroundColor: '#ffffff', borderBottom: '1px solid #e2e8f0' }}>
            <div className="container">
                {/* Product Grid */}
                <div className="row g-3">
                    {products.slice(0, 10).map((prod) => (
                        <div className="col-6 col-md-4 col-lg-2-4 col-xl-2" key={prod.id}>
                            <div className="card h-100 border shadow-sm rounded-3 overflow-hidden position-relative product-card-hover" style={{ transition: 'transform 0.2s, box-shadow 0.2s' }}>
                                {/* Product Image */}
                                <a href={prod.url} className="d-block p-2 text-center bg-light product-img-wrap" style={{ height: 'var(--product-img-height, 145px)' }}>
                                    <img
                                        src={prod.image}
                                        alt={prod.name}
                                        style={{ width: 'var(--product-img-width, 100%)', height: '100%', objectFit: 'var(--product-img-fit, contain)' }}
                                        loading="lazy"
                                        onError={(e) => { e.target.src = '/uploads/products/default.png'; }}
                                    />
                                </a>

                                {/* Body */}
                                <div className="card-body p-2 d-flex flex-column justify-content-between">
                                    <div>
                                        <div className="text-muted" style={{ fontSize: '12px' }}>
                                            <i className="fa fa-truck text-teal me-1"></i>
                                            {prod.delivery_time || 'Delivery 1-2 hours'}
                                        </div>
                                        <a href={prod.url} className="product-title text-dark fw-bold d-block text-truncate mt-1 text-decoration-none" title={prod.name} style={{ fontSize: 'var(--product-title-size, 15px)', lineHeight: '1.3' }}>
                                            {prod.name}
                                        </a>
                                    </div>

                                    <div className="mt-2 pt-2 border-top">
                                        <div className="d-flex align-items-baseline justify-content-between mb-2">
                                            <span className="product-price fw-bold text-success" style={{ fontSize: 'var(--product-price-size, 18px)' }}>{prod.formatted_price}</span>
                                            {prod.unit && (
                                                <small className="text-muted fw-bold" style={{ fontSize: '12px' }}>/{prod.unit}</small>
                                            )}
                                        </div>

                                        <a
                                            href={prod.url}
                                            className={`btn w-100 text-white rounded-pill py-2 fw-bold shadow-sm product-order-btn ${isBtnAnimated ? 'btn-animated-pulse' : ''}`}
                                            style={{ background: activeBtnColor, borderColor: activeBtnColor, fontSize: 'var(--product-btn-size, 14px)' }}
                                        >
                                            <i className="fa fa-shopping-bag me-1"></i> {dynamicOrderBtnText}
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
