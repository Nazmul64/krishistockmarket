import React from 'react';

export default function OutletsShowcase({ outlets = [], routes = {}, lang = 'bn', activeTemplate = '' }) {
    if (!outlets || outlets.length === 0) return null;

    const isBn = lang === 'bn';
    const allOutletsUrl = routes.outlets || '/outlets';

    return (
        <section className="py-5 bg-white border-top border-bottom" style={{ borderColor: '#f1f5f9' }} id="outlets-section">
            <div className="container">
                {/* Section Header */}
                <div className="text-center mb-5">
                    <span
                        className="badge border px-3 py-1 rounded-pill mb-2 fw-bold text-uppercase"
                        style={{
                            borderColor: '#fed7aa',
                            color: '#ea580c',
                            fontSize: '11px',
                            letterSpacing: '1.5px',
                            background: '#fff7ed'
                        }}
                    >
                        {isBn ? 'শোরুম ও আউটলেট' : 'STORE LOCATIONS'}
                    </span>
                    <h2
                        className="fw-bold text-dark mb-2"
                        style={{ fontSize: 'clamp(24px, 3.5vw, 36px)', letterSpacing: '-0.5px' }}
                    >
                        {isBn ? 'আমাদের নিকটস্থ' : 'Find Your Nearest'}{' '}
                        <span style={{ color: '#ea580c' }}>
                            {isBn ? 'আউটলেট ও শোরুম' : 'Store & Outlets'}
                        </span>
                    </h2>
                    <p className="text-muted mx-auto mb-0" style={{ maxWidth: '650px', fontSize: '15px' }}>
                        {isBn
                            ? 'সারাদেশে ১৮+ আধুনিক সুপারশপ ও এগ্রিকালচার আউটলেট নেটওয়ার্ক — আপনার নিকটস্থ শোরুমে সরাসরি যোগাযোগ করুন'
                            : 'Our 18+ Super Shop & Agricultural Showrooms Across Bangladesh — Visit Our Store Near You'}
                    </p>

                    {/* Stats Counters */}
                    <div className="d-flex justify-content-center align-items-center flex-wrap gap-4 gap-md-5 my-4">
                        <div className="text-center">
                            <div className="fw-bold" style={{ fontSize: '26px', color: '#ea580c', lineHeight: 1 }}>
                                18+
                            </div>
                            <small className="text-muted text-uppercase fw-bold" style={{ fontSize: '11px', letterSpacing: '0.5px' }}>
                                {isBn ? 'আউটলেট' : 'OUTLETS'}
                            </small>
                        </div>
                        <div className="border-start d-none d-sm-block" style={{ height: '30px', borderColor: '#e2e8f0' }}></div>
                        <div className="text-center">
                            <div className="fw-bold" style={{ fontSize: '26px', color: '#ea580c', lineHeight: 1 }}>
                                10K+
                            </div>
                            <small className="text-muted text-uppercase fw-bold" style={{ fontSize: '11px', letterSpacing: '0.5px' }}>
                                {isBn ? 'সন্তুষ্ট গ্রাহক' : 'HAPPY CUSTOMERS'}
                            </small>
                        </div>
                        <div className="border-start d-none d-sm-block" style={{ height: '30px', borderColor: '#e2e8f0' }}></div>
                        <div className="text-center">
                            <div className="fw-bold" style={{ fontSize: '26px', color: '#ea580c', lineHeight: 1 }}>
                                24/7
                            </div>
                            <small className="text-muted text-uppercase fw-bold" style={{ fontSize: '11px', letterSpacing: '0.5px' }}>
                                {isBn ? 'সাপোর্ট' : 'SUPPORT'}
                            </small>
                        </div>
                    </div>
                </div>

                {/* Outlets Cards Grid */}
                <div className="row g-4 mb-4">
                    {outlets.map((outlet, index) => {
                        const weeklyOff = (outlet.weekly_off_day || '').toLowerCase();
                        const isDailyOpen = weeklyOff.includes('daily') || weeklyOff.includes('7') || !weeklyOff;
                        const days = ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                        const mapUrl = outlet.google_map_url || `https://maps.google.com/?q=${encodeURIComponent((outlet.name || '') + ' ' + (outlet.address || ''))}`;

                        return (
                            <div key={outlet.id || index} className="col-12 col-md-6 col-lg-4">
                                <div
                                    className="card h-100 rounded-4 border-0 shadow-sm overflow-hidden bg-white"
                                    style={{
                                        border: '1.5px solid #fed7aa',
                                        transition: 'transform 0.2s ease, box-shadow 0.2s ease',
                                    }}
                                    onMouseEnter={(e) => {
                                        e.currentTarget.style.transform = 'translateY(-3px)';
                                        e.currentTarget.style.boxShadow = '0 10px 25px rgba(234, 88, 12, 0.12)';
                                    }}
                                    onMouseLeave={(e) => {
                                        e.currentTarget.style.transform = 'translateY(0)';
                                        e.currentTarget.style.boxShadow = '';
                                    }}
                                >
                                    {/* Card Header: OUTLET - 01 */}
                                    <div
                                        className="px-4 py-2 d-flex align-items-center justify-content-between"
                                        style={{ background: '#fff7ed', borderBottom: '1px solid #ffedd5' }}
                                    >
                                        <span
                                            className="fw-bold text-uppercase"
                                            style={{ color: '#ea580c', fontSize: '12px', letterSpacing: '0.5px' }}
                                        >
                                            {outlet.code || `OUTLET - 0${index + 1}`}
                                        </span>
                                        {isDailyOpen ? (
                                            <span className="badge bg-success rounded-pill px-2 py-1" style={{ fontSize: '10px' }}>
                                                {isBn ? 'সপ্তাহে ৭ দিন খোলা' : 'Open 7 Days'}
                                            </span>
                                        ) : (
                                            <span className="badge bg-secondary rounded-pill px-2 py-1" style={{ fontSize: '10px' }}>
                                                {isBn ? `ছুটি: ${outlet.weekly_off_day}` : `Off: ${outlet.weekly_off_day}`}
                                            </span>
                                        )}
                                    </div>

                                    {/* Card Body */}
                                    <div className="card-body p-4 d-flex flex-column justify-content-between">
                                        <div>
                                            <h5 className="fw-bold text-dark mb-2" style={{ fontSize: '16px', lineHeight: 1.4 }}>
                                                {outlet.name}
                                            </h5>

                                            {/* Address */}
                                            <p className="text-muted mb-3 d-flex align-items-start" style={{ fontSize: '13px', lineHeight: 1.5 }}>
                                                <i className="fa fa-map-marker-alt text-danger me-2 mt-1 flex-shrink-0"></i>
                                                <span>{outlet.address}</span>
                                            </p>

                                            {/* Phone */}
                                            {outlet.phone && (
                                                <p className="text-muted mb-3 d-flex align-items-center" style={{ fontSize: '13px' }}>
                                                    <i className="fa fa-phone-alt text-success me-2 flex-shrink-0"></i>
                                                    <a
                                                        href={`tel:${outlet.phone}`}
                                                        className="text-decoration-none text-muted fw-semibold"
                                                    >
                                                        {outlet.phone}
                                                    </a>
                                                </p>
                                            )}

                                            {/* Schedule Days */}
                                            <div className="mb-3">
                                                <div className="d-flex align-items-center gap-1 flex-wrap">
                                                    {days.map((day) => {
                                                        const isOffDay = weeklyOff.includes(day.toLowerCase()) ||
                                                            (weeklyOff.includes('fri') && day === 'Fri');
                                                        return (
                                                            <span
                                                                key={day}
                                                                className="badge rounded-pill fw-normal"
                                                                style={{
                                                                    fontSize: '10px',
                                                                    padding: '3px 8px',
                                                                    background: isOffDay ? '#fee2e2' : '#f0fdf4',
                                                                    color: isOffDay ? '#dc2626' : '#166534',
                                                                    border: isOffDay ? '1px solid #fecaca' : '1px solid #bbf7d0',
                                                                }}
                                                            >
                                                                {day}
                                                            </span>
                                                        );
                                                    })}
                                                </div>
                                            </div>
                                        </div>

                                        {/* Google Map Link Button */}
                                        <div className="pt-2 border-top" style={{ borderColor: '#f1f5f9' }}>
                                            <a
                                                href={mapUrl}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="btn btn-sm w-100 rounded-pill fw-semibold d-flex align-items-center justify-content-center gap-2"
                                                style={{
                                                    background: '#fff7ed',
                                                    color: '#ea580c',
                                                    border: '1px solid #fed7aa',
                                                    padding: '7px 14px',
                                                    fontSize: '12.5px',
                                                }}
                                            >
                                                <i className="fa fa-location-arrow"></i>
                                                <span>{isBn ? 'গুগল ম্যাপে দেখুন' : 'View On Google Map'}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>

                {/* View All Outlets Link Button */}
                <div className="text-center mt-3">
                    <a
                        href={allOutletsUrl}
                        className="btn rounded-pill px-4 py-2 fw-bold text-white shadow-sm"
                        style={{
                            background: 'linear-gradient(135deg, #ea580c 0%, #c2410c 100%)',
                            fontSize: '14px',
                            border: 'none',
                        }}
                    >
                        <span>{isBn ? 'সকল আউটলেট দেখুন (১৮+ আউটলেট)' : 'View All 18+ Outlets'}</span>
                        <i className="fa fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </section>
    );
}
