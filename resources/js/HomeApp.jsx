import React from 'react';
import Navbar from './components/Navbar';
import HeroSlider from './components/HeroSlider';
import LiveStockMarket from './components/LiveStockMarket';
import PackageSection from './components/PackageSection';
import MembershipCards from './components/MembershipCards';
import WhyChooseUs from './components/WhyChooseUs';
import ProductGallery from './components/ProductGallery';
import ContactSection from './components/ContactSection';
import Footer from './components/Footer';

export default function HomeApp({ data = {} }) {
    const site = data.site || {};
    const auth = data.auth || {};
    const routes = data.routes || {};
    const sliders = data.sliders || [];
    const offerBanner = data.offer_banner || null;
    const stocks = data.stocks || [];
    const packages = data.packages || [];
    const cardBenefits = data.card_benefits || [];
    const features = data.features || [];
    const gallery = data.gallery || [];

    return (
        <div className="react-homepage-wrapper">
            {/* Topbar & Sticky Navigation */}
            <Navbar site={site} auth={auth} routes={routes} />

            {/* Main Content Area */}
            <main className="react-main-body">
                {/* Hero / Slider Section */}
                <HeroSlider sliders={sliders} offerBanner={offerBanner} routes={routes} />

                {/* Live Stock Market Section */}
                <LiveStockMarket stocks={stocks} routes={routes} />

                {/* Our Packages Showcase */}
                <PackageSection packages={packages} />

                {/* SME & Golden Cards Section */}
                <MembershipCards cardBenefits={cardBenefits} routes={routes} />

                {/* Why Choose Us */}
                <WhyChooseUs features={features} />

                {/* Product Gallery Showcase */}
                <ProductGallery gallery={gallery} routes={routes} />

                {/* Contact Section */}
                <ContactSection site={site} routes={routes} />
            </main>

            {/* Dynamic Footer */}
            <Footer site={site} routes={routes} />
        </div>
    );
}
