import React, { useState, useEffect } from 'react';
import Navbar from './components/Navbar';
import HeroSlider from './components/HeroSlider';
import LiveStockMarket from './components/LiveStockMarket';
import PackageSection from './components/PackageSection';
import MembershipCards from './components/MembershipCards';
import WhyChooseUs from './components/WhyChooseUs';
import ProductGallery from './components/ProductGallery';
import ContactSection from './components/ContactSection';
import Footer from './components/Footer';
import { translations, getInitialLang, saveLang } from './utils/translations';
import { getInitialTheme, saveTheme, applyTheme } from './utils/theme';

export default function HomeApp({ data = {} }) {
    const [lang, setLang] = useState(getInitialLang());
    const [theme, setTheme] = useState(getInitialTheme());

    // Apply theme on mount
    useEffect(() => {
        applyTheme(theme);
    }, [theme]);

    const handleSwitchLang = (newLang) => {
        setLang(newLang);
        saveLang(newLang);
    };

    const handleToggleTheme = () => {
        const next = theme === 'dark' ? 'light' : 'dark';
        setTheme(next);
        saveTheme(next);
    };

    const t = translations[lang] || translations.en;

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
            {/* Topbar & Sticky Navigation with Live Search, Language Switcher & Dark Mode */}
            <Navbar
                site={site}
                auth={auth}
                routes={routes}
                stocks={stocks}
                packages={packages}
                cardBenefits={cardBenefits}
                lang={lang}
                onSwitchLang={handleSwitchLang}
                theme={theme}
                onToggleTheme={handleToggleTheme}
                t={t}
            />

            {/* Main Content Area */}
            <main className="react-main-body">
                {/* Hero / Slider Section */}
                <HeroSlider
                    sliders={sliders}
                    offerBanner={offerBanner}
                    routes={routes}
                    lang={lang}
                    t={t}
                />

                {/* Live Stock Market Section */}
                <LiveStockMarket
                    stocks={stocks}
                    routes={routes}
                    lang={lang}
                    t={t}
                />

                {/* Our Packages Showcase */}
                <PackageSection
                    packages={packages}
                    lang={lang}
                    t={t}
                />

                {/* SME & Golden Cards Section */}
                <MembershipCards
                    cardBenefits={cardBenefits}
                    routes={routes}
                    lang={lang}
                    t={t}
                />

                {/* Why Choose Us */}
                <WhyChooseUs
                    features={features}
                    lang={lang}
                    t={t}
                />

                {/* Product Gallery Showcase */}
                <ProductGallery
                    gallery={gallery}
                    routes={routes}
                    lang={lang}
                    t={t}
                />

                {/* Contact Section */}
                <ContactSection
                    site={site}
                    routes={routes}
                    lang={lang}
                    t={t}
                />
            </main>

            {/* Dynamic Footer */}
            <Footer
                site={site}
                routes={routes}
                lang={lang}
                t={t}
            />
        </div>
    );
}
