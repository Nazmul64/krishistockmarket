import React, { useState, useEffect } from 'react';
import Navbar from './components/Navbar';
import HeroSlider from './components/HeroSlider';
import MonthlyBazarSection from './components/MonthlyBazarSection';
import LiveStockMarket from './components/LiveStockMarket';
import PackageSection from './components/PackageSection';
import MembershipCards from './components/MembershipCards';
import WhyChooseUs from './components/WhyChooseUs';
import ProductGallery from './components/ProductGallery';
import OutletsShowcase from './components/OutletsShowcase';
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
    const outlets = data.outlets || [];

    // Centralized Dynamic UI Configuration (Active Template, Sections, Menus)
    const uiConfig = data.ui_config || {};
    const activeTemplate = uiConfig.active_template || 'template_01_modern_agriculture';
    const sectionsWeb = uiConfig.sections_web || [];

    // Section Component Registry
    const sectionComponents = {
        hero_slider: (
            <HeroSlider
                key="hero_slider"
                sliders={sliders}
                offerBanner={offerBanner}
                routes={routes}
                lang={lang}
                t={t}
                activeTemplate={activeTemplate}
            />
        ),
        monthly_bazar: (site && site.monthly_bazar_status !== false && site.monthly_bazar_status !== '0' && site.monthly_bazar_status !== 0) ? (
            <MonthlyBazarSection
                key="monthly_bazar"
                products={data.monthly_bazar_products || []}
                routes={routes}
                site={site}
                lang={lang}
                t={t}
                activeTemplate={activeTemplate}
            />
        ) : null,
        stock_market: (site && site.menu_stock_status !== false && site.menu_stock_status !== '0' && site.menu_stock_status !== 0) ? (
            <LiveStockMarket
                key="stock_market"
                stocks={stocks}
                routes={routes}
                site={site}
                lang={lang}
                t={t}
                activeTemplate={activeTemplate}
            />
        ) : null,
        our_packages: (site && site.menu_packages_status !== false && site.menu_packages_status !== '0' && site.menu_packages_status !== 0) ? (
            <PackageSection
                key="our_packages"
                packages={packages}
                lang={lang}
                t={t}
                activeTemplate={activeTemplate}
            />
        ) : null,
        membership_cards: (site && site.menu_membership_status !== false && site.menu_membership_status !== '0' && site.menu_membership_status !== 0) ? (
            <MembershipCards
                key="membership_cards"
                cardBenefits={cardBenefits}
                routes={routes}
                lang={lang}
                t={t}
                activeTemplate={activeTemplate}
            />
        ) : null,
        why_choose_us: (site.why_choose_us_status !== '0' && site.why_choose_us_status !== false && site.why_choose_us_status !== 0) ? (
            <WhyChooseUs
                key="why_choose_us"
                features={features}
                lang={lang}
                t={t}
                activeTemplate={activeTemplate}
            />
        ) : null,
        gallery: (
            <ProductGallery
                key="gallery"
                gallery={gallery}
                routes={routes}
                lang={lang}
                t={t}
                activeTemplate={activeTemplate}
            />
        ),
        outlets: (outlets && outlets.length > 0) ? (
            <OutletsShowcase
                key="outlets"
                outlets={outlets}
                routes={routes}
                lang={lang}
                t={t}
                activeTemplate={activeTemplate}
            />
        ) : null,
        contact_us: (
            <ContactSection
                key="contact_us"
                site={site}
                routes={routes}
                lang={lang}
                t={t}
                activeTemplate={activeTemplate}
            />
        ),
    };

    // Render sections either dynamically from admin configuration or default fallback
    const renderHomepageSections = () => {
        const isContactEnabled = site && site.contact_section_enabled !== false && site.contact_section_enabled !== '0';
        const isStockEnabled = site && site.menu_stock_status !== false && site.menu_stock_status !== '0' && site.menu_stock_status !== 0;
        const isPackagesEnabled = site && site.menu_packages_status !== false && site.menu_packages_status !== '0' && site.menu_packages_status !== 0;
        const isMembershipEnabled = site && site.menu_membership_status !== false && site.menu_membership_status !== '0' && site.menu_membership_status !== 0;
        const isWhyChooseEnabled = site && site.why_choose_us_status !== false && site.why_choose_us_status !== '0' && site.why_choose_us_status !== 0;
        const isMonthlyBazarEnabled = site && site.monthly_bazar_status !== false && site.monthly_bazar_status !== '0' && site.monthly_bazar_status !== 0;

        if (sectionsWeb && sectionsWeb.length > 0) {
            const list = sectionsWeb
                .filter(sec => sec.is_enabled_web)
                .filter(sec => sec.section_key !== 'stock_market' || isStockEnabled)
                .filter(sec => sec.section_key !== 'our_packages' || isPackagesEnabled)
                .filter(sec => sec.section_key !== 'membership_cards' || isMembershipEnabled)
                .filter(sec => sec.section_key !== 'why_choose_us' || isWhyChooseEnabled)
                .filter(sec => sec.section_key !== 'monthly_bazar' || isMonthlyBazarEnabled)
                .filter(sec => sec.section_key !== 'contact_us' || isContactEnabled)
                .map(sec => sectionComponents[sec.section_key])
                .filter(Boolean);

            const hasOutlets = sectionsWeb.some(sec => sec.section_key === 'outlets');
            if (!hasOutlets && sectionComponents.outlets) {
                list.push(sectionComponents.outlets);
            }
            return list;
        }

        // Default Fallback Ordering
        const defaultList = [
            sectionComponents.hero_slider,
            sectionComponents.monthly_bazar,
            sectionComponents.stock_market,
            sectionComponents.our_packages,
            sectionComponents.membership_cards,
            sectionComponents.why_choose_us,
            sectionComponents.outlets,
            sectionComponents.gallery,
        ];

        if (isContactEnabled) {
            defaultList.push(sectionComponents.contact_us);
        }

        return defaultList.filter(Boolean);
    };

    return (
        <div className={`react-homepage-wrapper theme-template-${activeTemplate}`}>
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
                uiConfig={uiConfig}
                activeTemplate={activeTemplate}
            />

            {/* Main Content Area: Dynamically rendered in exact admin drag-and-drop order */}
            <main className="react-main-body">
                {renderHomepageSections()}
            </main>

            {/* Dynamic Footer */}
            <Footer
                site={site}
                routes={routes}
                lang={lang}
                t={t}
                uiConfig={uiConfig}
                activeTemplate={activeTemplate}
            />
        </div>
    );
}
