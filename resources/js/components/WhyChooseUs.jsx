import React from 'react';

export default function WhyChooseUs({ features = [] }) {
    const defaultFeatures = [
        {
            id: 1,
            title: '১০০% নিরাপদ ও বিশ্বস্ত',
            description: 'আমাদের প্ল্যাটফর্মে সকল লেনদেন, সঞ্চয় ও ডাটা সর্বোচ্চ ব্যাংক-গ্রেড নিরাপত্তা ও স্বচ্ছতা বজায় রেখে পরিচালিত হয়।',
            icon: 'fa-shield',
            color: '#1b88ce',
        },
        {
            id: 2,
            title: 'স্মার্ট স্টক পর্যবেক্ষণ',
            description: 'দৈনিক ও সাপ্তাহিক স্টকের রিয়েল-টাইম প্রাইস আপডেট, লাভ-ক্ষতির পূর্ণ বিশ্লেষণ ও সঠিক পূর্বাভাস সহজেই দেখুন।',
            icon: 'fa-line-chart',
            color: '#10b981',
        },
        {
            id: 3,
            title: '২৪/৭ সার্বক্ষণিক সাপোর্ট',
            description: 'যেকোনো জিজ্ঞাসা, বিনিয়োগ সংক্রান্ত পরামর্শ ও সহযোগিতার জন্য আমাদের অভিজ্ঞ সাপোর্ট টিম সর্বদা আপনার পাশে রয়েছে।',
            icon: 'fa-headphones',
            color: '#f59e0b',
        },
    ];

    const activeFeatures = features && features.length > 0 ? features : defaultFeatures;

    return (
        <section className="why-choose-section py-5" id="features">
            <div className="container">
                {/* Header */}
                <div className="section-head-wrap text-center mb-5">
                    <div className="section-pill-badge">
                        <i className="fa fa-diamond me-1"></i> আমাদের বিশেষত্ব
                    </div>
                    <h2 className="section-main-title">
                        <span className="text-gradient">কেন কৃষি পরিবার</span> বেছে নেবেন?
                    </h2>
                    <p className="section-sub-title">
                        কৃষি ও ব্যবসা খাতের জন্য আমাদের বিশ্বস্ত সেবা, আধুনিক সুযোগ-সুবিধা ও নির্ভরযোগ্য ডিজিটাল প্ল্যাটফর্ম
                    </p>
                </div>

                {/* Features Grid */}
                <div className="row g-4 justify-content-center">
                    {activeFeatures.map((feat, idx) => (
                        <div key={feat.id || idx} className="col-12 col-md-6 col-lg-4">
                            <div className="why-choose-modern-card h-100">
                                <div className="feat-icon-bubble" style={{ color: feat.color || '#1b88ce' }}>
                                    {feat.image ? (
                                        <img src={feat.image} alt={feat.title} className="feat-custom-img" />
                                    ) : (
                                        <i className={`fa ${feat.icon || 'fa-check-circle'}`}></i>
                                    )}
                                </div>
                                <h3 className="feat-title">{feat.title}</h3>
                                <p className="feat-description">{feat.description}</p>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
