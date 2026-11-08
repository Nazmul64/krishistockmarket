import React, { useState } from 'react';

export default function ContactSection({ site = {}, routes = {} }) {
    const [formData, setFormData] = useState({
        name: '',
        phone: '',
        email: '',
        subject: '',
        message: '',
    });

    const [status, setStatus] = useState({
        submitting: false,
        success: null,
        error: null,
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setStatus({ submitting: true, success: null, error: null });

        try {
            const csrf = routes.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(routes.contact_send || '/contact/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    'contact-name': formData.name,
                    'contact-phone': formData.phone,
                    'contact-email': formData.email,
                    'contact-subject': formData.subject,
                    'textarea-message': formData.message,
                }),
            });

            if (res.ok) {
                setStatus({
                    submitting: false,
                    success: 'আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে! আমাদের প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবেন।',
                    error: null,
                });
                setFormData({ name: '', phone: '', email: '', subject: '', message: '' });
            } else {
                const data = await res.json().catch(() => ({}));
                setStatus({
                    submitting: false,
                    success: null,
                    error: data.message || 'মেসেজ পাঠাতে সমস্যা হয়েছে। অনুগ্রহ করে ফোন নম্বরে সরাসরি যোগাযোগ করুন।',
                });
            }
        } catch (err) {
            setStatus({
                submitting: false,
                success: null,
                error: 'নেটওয়ার্ক সংযোগ ত্রুটি। পুনরায় চেষ্টা করুন।',
            });
        }
    };

    return (
        <section className="contact-modern-section py-5" id="contact">
            <div className="container">
                {/* Header */}
                <div className="section-head-wrap text-center mb-4 mb-md-5">
                    <div className="section-pill-badge">
                        <i className="fa fa-envelope me-1"></i> সরাসরি যোগাযোগ
                    </div>
                    <h2 className="section-main-title">
                        <span className="text-gradient">আমাদের সাথে</span> যোগাযোগ করুন
                    </h2>
                    <p className="section-sub-title">
                        যেকোনো জিজ্ঞাসা, মতামত বা সহযোগিতার জন্য নিচের ফর্মে বার্তা পাঠান কিংবা সরাসরি হেল্পলাইনে কল করুন।
                    </p>
                </div>

                <div className="row g-4 justify-content-center">
                    {/* Left info cards with generous gaps */}
                    <div className="col-12 col-md-5 col-lg-4">
                        <div className="contact-info-cards-stack d-flex flex-column gap-3 mb-4 mb-md-0">
                            {site.phone1 && (
                                <div className="contact-quick-card">
                                    <div className="quick-icon-circle bg-blue">
                                        <i className="fa fa-phone"></i>
                                    </div>
                                    <div className="quick-details">
                                        <h6>হেল্পলাইন নম্বর</h6>
                                        <div className="d-flex flex-column gap-1 mt-1">
                                            <a href={`tel:${site.phone1}`}>{site.phone1}</a>
                                            {site.phone2 && site.phone2 !== site.phone1 && (
                                                <a href={`tel:${site.phone2}`}>{site.phone2}</a>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {site.email1 && (
                                <div className="contact-quick-card">
                                    <div className="quick-icon-circle bg-emerald">
                                        <i className="fa fa-envelope"></i>
                                    </div>
                                    <div className="quick-details">
                                        <h6>অফিসিয়াল ইমেইল</h6>
                                        <div className="d-flex flex-column gap-1 mt-1">
                                            <a href={`mailto:${site.email1}`} style={{ wordBreak: 'break-all' }}>{site.email1}</a>
                                            {site.email2 && site.email2 !== site.email1 && (
                                                <a href={`mailto:${site.email2}`} style={{ wordBreak: 'break-all' }}>{site.email2}</a>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            )}

                            {site.address1 && (
                                <div className="contact-quick-card">
                                    <div className="quick-icon-circle bg-amber">
                                        <i className="fa fa-map-marker"></i>
                                    </div>
                                    <div className="quick-details">
                                        <h6>অফিস ঠিকানা</h6>
                                        <p className="mb-0 text-slate-700 mt-1">
                                            {site.address1}
                                            {site.address2 && site.address2 !== site.address1 ? `, ${site.address2}` : ''}
                                        </p>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Right Form with professional placeholders */}
                    <div className="col-12 col-md-7 col-lg-8">
                        <div className="contact-form-glass-card p-4 p-md-5">
                            {status.success && (
                                <div className="alert alert-success d-flex align-items-center gap-2 mb-4">
                                    <i className="fa fa-check-circle fs-5"></i>
                                    <div>{status.success}</div>
                                </div>
                            )}

                            {status.error && (
                                <div className="alert alert-danger d-flex align-items-center gap-2 mb-4">
                                    <i className="fa fa-exclamation-triangle fs-5"></i>
                                    <div>{status.error}</div>
                                </div>
                            )}

                            <form onSubmit={handleSubmit} className="row g-3">
                                <div className="col-12 col-md-6 mb-2">
                                    <label className="contact-field-label">আপনার নাম <span className="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="name"
                                        required
                                        className="form-control contact-input"
                                        placeholder="আপনার পূর্ণ নাম লিখুন (Enter your name)"
                                        value={formData.name}
                                        onChange={handleChange}
                                    />
                                </div>

                                <div className="col-12 col-md-6 mb-2">
                                    <label className="contact-field-label">ফোন নম্বর <span className="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="phone"
                                        required
                                        className="form-control contact-input"
                                        placeholder="ফোন নম্বর লিখুন (017xxxxxxxx)"
                                        value={formData.phone}
                                        onChange={handleChange}
                                    />
                                </div>

                                <div className="col-12 col-md-6 mb-2">
                                    <label className="contact-field-label">ইমেইল এড্রেস</label>
                                    <input
                                        type="email"
                                        name="email"
                                        className="form-control contact-input"
                                        placeholder="ইমেইল এড্রেস লিখুন (youremail@example.com)"
                                        value={formData.email}
                                        onChange={handleChange}
                                    />
                                </div>

                                <div className="col-12 col-md-6 mb-2">
                                    <label className="contact-field-label">বার্তা বিষয় (Subject)</label>
                                    <input
                                        type="text"
                                        name="subject"
                                        className="form-control contact-input"
                                        placeholder="বার্তার বিষয় লিখুন (Subject)"
                                        value={formData.subject}
                                        onChange={handleChange}
                                    />
                                </div>

                                <div className="col-12 mb-3">
                                    <label className="contact-field-label">আপনার বার্তাটি লিখুন <span className="text-danger">*</span></label>
                                    <textarea
                                        name="message"
                                        required
                                        rows={4}
                                        className="form-control contact-input"
                                        placeholder="আপনার বার্তা বা জিজ্ঞাসা বিস্তারিত লিখুন..."
                                        value={formData.message}
                                        onChange={handleChange}
                                    ></textarea>
                                </div>

                                <div className="col-12 text-center pt-2">
                                    <button
                                        type="submit"
                                        disabled={status.submitting}
                                        className="btn-contact-submit"
                                    >
                                        {status.submitting ? (
                                             <>
                                                <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                                                <span>বার্তা পাঠানো হচ্ছে...</span>
                                            </>
                                        ) : (
                                            <>
                                                <span>মেসেজ পাঠান</span>
                                                <i className="fa fa-paper-plane ms-2"></i>
                                            </>
                                        )}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
