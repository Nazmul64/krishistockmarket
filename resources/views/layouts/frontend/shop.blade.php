<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Asmi Supershop | e-Krishi Sheba - নিত্যপ্রয়োজনীয় সুপারশপ')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('upload/images/backend/logo') }}/{{ setting('favicon') }}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Shop Styles -->
    <style>
        :root {
            --shop-teal: #007e8a;
            --shop-teal-dark: #005f69;
            --shop-teal-light: #e0f2f1;
            --shop-teal-accent: #00b4d8;
            --shop-orange: #f59e0b;
            --shop-red: #ef4444;
            --shop-bg: #f8fafc;
            --shop-border: #e2e8f0;
            --shop-text: #1e293b;
            --shop-muted: #64748b;
        }

        html {
            font-family: 'Hind Siliguri', 'Poppins', sans-serif;
            background-color: var(--shop-bg);
            color: var(--shop-text);
            margin: 0;
            padding: 0;
            overflow-x: clip !important;
            overflow-y: visible !important;
        }

        body {
            font-family: 'Hind Siliguri', 'Poppins', sans-serif;
            background-color: var(--shop-bg);
            color: var(--shop-text);
            margin: 0;
            padding: 0;
            overflow-x: clip !important;
            overflow-y: visible !important;
            max-width: 100vw;
            width: 100%;
            position: relative;
        }

        /* Top Notification Bar */
        .shop-top-notice {
            background: #064e54;
            color: #d1fae5;
            font-size: 13px;
            padding: 6px 0;
            text-align: center;
            font-weight: 500;
        }
        .shop-top-notice a {
            color: #38bdf8;
            text-decoration: underline;
            margin-left: 8px;
        }

        /* Shop Main Header */
        .shop-header {
            background: #ffffff;
            border-bottom: 1px solid var(--shop-border);
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            padding: 10px 0;
        }

        .shop-logo-wrap {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 8px;
        }
        .shop-logo-svg {
            font-weight: 900;
            font-size: 28px;
            letter-spacing: -1px;
            color: var(--shop-teal);
            display: flex;
            align-items: center;
        }
        .shop-logo-svg span.dot-o {
            color: #f97316;
            margin: 0 1px;
        }
        .shop-logo-sub {
            font-size: 11px;
            color: #64748b;
            display: block;
            margin-top: -4px;
            font-weight: 600;
        }

        .whatsapp-contact {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #15803d;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.2;
            padding: 4px 10px;
            border-radius: 8px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            text-decoration: none;
        }
        .whatsapp-contact i {
            font-size: 20px;
            color: #22c55e;
        }

        /* Shop Search Box */
        .shop-search-form {
            position: relative;
            width: 100%;
            max-width: 520px;
        }
        .shop-search-input {
            width: 100%;
            border: 2px solid var(--shop-teal);
            border-radius: 6px;
            padding: 8px 45px 8px 16px;
            font-size: 14px;
            outline: none;
            background: #fff;
            transition: all 0.2s;
        }
        .shop-search-input:focus {
            box-shadow: 0 0 0 3px rgba(0, 126, 138, 0.2);
        }
        .shop-search-btn {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 42px;
            background: var(--shop-teal);
            border: none;
            border-radius: 0 5px 5px 0;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        .shop-search-btn:hover {
            background: var(--shop-teal-dark);
        }

        /* App Download Banner Pill */
        .app-download-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid var(--shop-border);
            padding: 5px 12px;
            border-radius: 20px;
            text-decoration: none;
            color: #334155;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .app-download-pill:hover {
            border-color: var(--shop-teal);
            color: var(--shop-teal);
        }

        .btn-shop-login {
            background: var(--shop-teal);
            color: #ffffff;
            font-weight: 600;
            font-size: 13px;
            padding: 6px 16px;
            border-radius: 6px;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .btn-shop-login:hover {
            background: var(--shop-teal-dark);
            color: #ffffff;
        }

        /* Category Left Sidebar (Pinned Sticky to Viewport & Never Leaves Empty Space) */
        .shop-sidebar {
            background: #ffffff;
            border: 1px solid var(--shop-border);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            position: sticky;
            top: 75px;
            height: calc(100vh - 85px);
            max-height: calc(100vh - 85px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-bottom: 25px;
            z-index: 100;
        }

        .sidebar-header-section {
            flex-shrink: 0;
        }

        .sidebar-scrollable-cats {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
        }
        .sidebar-scrollable-cats::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-scrollable-cats::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        .sidebar-scrollable-cats::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .sidebar-scrollable-cats::-webkit-scrollbar-thumb:hover {
            background: var(--shop-teal);
        }

        .sidebar-footer-strip {
            flex-shrink: 0;
        }

        .sidebar-brand-box {
            padding: 14px 16px;
            background: #ffffff;
            border-bottom: 1px solid var(--shop-border);
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .sidebar-brand-box .brand-tagline {
            font-size: 10px;
            color: #e11d48;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 3px;
        }

        /* Quick Badges List */
        .sidebar-badges-list {
            padding: 8px 0;
            border-bottom: 1px solid var(--shop-border);
        }
        .sidebar-badge-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            color: #334155;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
            position: relative;
        }
        .sidebar-badge-link:hover,
        .sidebar-badge-link.active {
            background: #f1f5f9;
            color: var(--shop-teal);
            font-weight: 600;
            padding-left: 20px;
        }
        .sidebar-badge-link .icon-box {
            width: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        /* Category Header */
        .sidebar-cat-title {
            padding: 12px 16px 6px;
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Main Category List with Hover Flyout */
        .sidebar-cat-list {
            list-style: none;
            padding: 0;
            margin: 0;
            position: relative;
        }
        .sidebar-cat-item {
            position: relative;
        }
        .sidebar-cat-item > a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 16px;
            color: #334155;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s;
        }
        .sidebar-cat-item:hover > a,
        .sidebar-cat-item.active > a {
            background: #f0fdfa;
            color: var(--shop-teal);
            font-weight: 600;
        }
        .sidebar-cat-item .cat-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-cat-item .cat-icon {
            font-size: 15px;
            color: #64748b;
            width: 20px;
            text-align: center;
        }
        .sidebar-cat-item:hover .cat-icon {
            color: var(--shop-teal);
        }
        .sidebar-cat-item .arrow-icon {
            font-size: 11px;
            color: #cbd5e1;
            transition: transform 0.2s;
        }
        .sidebar-cat-item:hover .arrow-icon {
            color: var(--shop-teal);
            transform: translateX(3px);
        }

        /* Hover Flyout Dropdown for Subcategories */
        .cat-flyout-menu {
            position: absolute;
            left: 100%;
            top: 0;
            width: 260px;
            background: #ffffff;
            border: 1px solid var(--shop-border);
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            padding: 10px 0;
            display: none;
            z-index: 1000;
            animation: fadeInFlyout 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeInFlyout {
            from { opacity: 0; transform: translateX(-8px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .sidebar-cat-item:hover .cat-flyout-menu {
            display: block;
        }
        .cat-flyout-menu .flyout-header {
            padding: 6px 16px 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--shop-teal);
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 6px;
        }
        .cat-flyout-menu a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            color: #334155;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.15s;
        }
        .cat-flyout-menu a:hover {
            background: #f0fdfa;
            color: var(--shop-teal);
            padding-left: 20px;
            font-weight: 600;
        }

        /* Product Cards */
        .shop-product-card {
            background: #ffffff;
            border: 1px solid var(--shop-border);
            border-radius: 10px;
            padding: 14px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            transition: all 0.25s ease;
            position: relative;
        }
        .shop-product-card:hover {
            border-color: var(--shop-teal);
            box-shadow: 0 8px 20px rgba(0, 126, 138, 0.1);
            transform: translateY(-2px);
        }

        .prod-delivery-badge {
            font-size: 11px;
            color: #059669;
            background: #ecfdf5;
            padding: 2px 8px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 8px;
            font-weight: 600;
            border: 1px solid #d1fae5;
        }

        .prod-img-wrap {
            position: relative;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            overflow: hidden;
            background: #fff;
            border-radius: 8px;
        }
        .prod-img-wrap img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        .shop-product-card:hover .prod-img-wrap img {
            transform: scale(1.06);
        }

        .prod-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
            line-height: 1.35;
            min-height: 38px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-decoration: none;
        }
        .prod-name:hover {
            color: var(--shop-teal);
        }

        .prod-price-wrap {
            font-size: 16px;
            font-weight: 700;
            color: var(--shop-teal);
            margin-bottom: 12px;
        }
        .prod-old-price {
            font-size: 13px;
            color: #94a3b8;
            text-decoration: line-through;
            margin-left: 6px;
            font-weight: 400;
        }

        .btn-add-bag {
            background: var(--shop-teal);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-add-bag:hover {
            background: var(--shop-teal-dark);
            color: #ffffff;
            transform: translateY(-1px);
        }
        .btn-add-bag:active {
            transform: scale(0.98);
        }

        /* Floating Cart Widget */
        .floating-cart-badge {
            position: fixed;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: #007e8a;
            color: #ffffff;
            border: 2px solid #ef4444;
            border-right: none;
            border-radius: 8px 0 0 8px;
            padding: 10px 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: -4px 0 15px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            z-index: 1040;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            text-align: center;
        }
        .floating-cart-badge:hover {
            background: #006069;
            padding-right: 18px;
        }
        .floating-cart-icon {
            font-size: 20px;
            margin-bottom: 3px;
            color: #fef08a;
        }
        .floating-cart-count {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        .floating-cart-price {
            font-size: 13px;
            font-weight: 800;
            margin-top: 2px;
            color: #fff;
        }

        /* Cart Drawer (Slide-out) */
        .cart-drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(2px);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .cart-drawer-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .cart-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            max-width: 400px;
            height: 100vh;
            background: #ffffff;
            z-index: 1055;
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.18);
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            visibility: hidden;
            pointer-events: none;
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.35s;
        }
        .cart-drawer.active {
            transform: translateX(0);
            visibility: visible;
            pointer-events: auto;
        }

        .cart-drawer-header {
            padding: 16px 20px;
            background: var(--shop-teal);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cart-drawer-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cart-drawer-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .cart-drawer-close:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .cart-drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .cart-empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }
        .cart-empty-state i {
            font-size: 54px;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .cart-item-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .cart-item-img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 4px;
            background: #fff;
        }
        .cart-item-info {
            flex: 1;
            min-width: 0;
        }
        .cart-item-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cart-item-price {
            font-size: 13px;
            font-weight: 700;
            color: var(--shop-teal);
        }

        .cart-qty-ctrls {
            display: flex;
            align-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            overflow: hidden;
        }
        .cart-qty-btn {
            background: #f8fafc;
            border: none;
            width: 26px;
            height: 26px;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }
        .cart-qty-btn:hover {
            background: #e2e8f0;
        }
        .cart-qty-val {
            width: 30px;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
        }

        .cart-item-del {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 14px;
            cursor: pointer;
            padding: 4px;
            transition: color 0.15s;
        }
        .cart-item-del:hover {
            color: #ef4444;
        }

        .cart-drawer-footer {
            padding: 16px 20px;
            background: #f8fafc;
            border-top: 1px solid var(--shop-border);
        }
        .cart-summary-line {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 8px;
            color: #475569;
        }
        .cart-summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 14px;
            padding-top: 6px;
            border-top: 1px dashed #cbd5e1;
        }
        .btn-checkout-drawer {
            background: var(--shop-teal);
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-checkout-drawer:hover {
            background: var(--shop-teal-dark);
            color: #ffffff;
        }

        /* Floating Support Bubble */
        .shop-support-bubble {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #ffffff;
            border: 2px solid var(--shop-teal);
            border-radius: 30px;
            padding: 6px 14px 6px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.12);
            text-decoration: none;
            color: #1e293b;
            font-size: 13px;
            font-weight: 700;
            z-index: 1030;
            transition: all 0.2s ease;
        }
        .shop-support-bubble:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 126, 138, 0.25);
            color: var(--shop-teal);
        }
        .shop-support-bubble img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
        }

        /* Interactive Support Chat Widget */
        .shop-chat-widget {
            position: fixed;
            bottom: 80px;
            right: 25px;
            width: 360px;
            max-width: calc(100vw - 30px);
            height: 500px;
            max-height: calc(100vh - 120px);
            background: #ffffff;
            border-radius: 16px;
            z-index: 1060;
            display: none;
            flex-direction: column;
            border: 1px solid rgba(0, 126, 138, 0.25);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.18);
            overflow: hidden;
            animation: chatSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes chatSlideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .shop-chat-header {
            background: linear-gradient(135deg, #007e8a 0%, #005f69 100%);
            padding: 12px 16px;
            color: #ffffff;
        }
        .chat-header-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-chat-close {
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 16px;
            opacity: 0.85;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-chat-close:hover { opacity: 1; }
        .onboarding-icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e6fffa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .chat-messages-scroll {
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f8fafc;
        }
        .chat-bubble {
            max-width: 82%;
            padding: 9px 13px;
            border-radius: 14px;
            font-size: 13px;
            line-height: 1.4;
            word-break: break-word;
        }
        .chat-bubble-agent {
            background: #ffffff;
            color: #1e293b;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }
        .chat-bubble-user {
            background: #007e8a;
            color: #ffffff;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 126, 138, 0.25);
        }
        .chat-bubble-time {
            font-size: 10px;
            opacity: 0.7;
            margin-top: 3px;
            display: block;
            text-align: right;
        }

        /* Shop Footer */
        .shop-footer {
            background: #004d53;
            color: #e2e8f0;
            padding: 50px 0 25px;
            margin-top: 60px;
            border-top: 4px solid #007e8a;
        }
        .shop-footer a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s;
        }
        .shop-footer a:hover {
            color: #38bdf8;
            padding-left: 3px;
        }
        .shop-footer-title {
            font-size: 14px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 18px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .shop-footer-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .shop-footer-list li {
            margin-bottom: 9px;
            font-size: 13px;
        }
        .shop-footer-list li i {
            margin-right: 6px;
            font-size: 10px;
            color: #2dd4bf;
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
            font-size: 13px;
        }
        .footer-contact-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2dd4bf;
            font-size: 14px;
            flex-shrink: 0;
        }

        .app-store-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #064e54;
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 8px 14px;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            margin-bottom: 8px;
            font-size: 12px;
            line-height: 1.2;
            transition: background 0.2s;
        }
        .app-store-badge:hover {
            background: #007e8a;
            color: #fff;
        }
        .app-store-badge i {
            font-size: 22px;
        }

        .quick-portal-btn {
            display: inline-block;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            margin-right: 6px;
            margin-top: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s;
        }
        .quick-portal-btn:hover {
            background: #ffffff;
            color: #004d53 !important;
        }

        .shop-copyright-bar {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 35px;
            padding-top: 20px;
            font-size: 12px;
            color: #94a3b8;
        }

        /* Toast notification */
        .shop-toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #0f172a;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1060;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }
        .shop-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
        .shop-toast i {
            color: #10b981;
            font-size: 16px;
        }

        @media (max-width: 991px) {
            .shop-sidebar {
                margin-bottom: 20px;
            }
            .cart-drawer {
                max-width: 85%;
            }
        }
        @media (max-width: 576px) {
            .cart-drawer {
                max-width: 92%;
            }
            .floating-cart-badge {
                padding: 6px 10px;
                border-radius: 6px 0 0 6px;
            }
            .floating-cart-icon {
                font-size: 15px;
            }
            .floating-cart-count {
                font-size: 9px;
            }
            .floating-cart-price {
                font-size: 11px;
            }
            .shop-support-bubble {
                bottom: 15px;
                right: 15px;
                padding: 5px 10px;
                font-size: 11px;
            }
            .shop-logo-svg {
                font-size: 22px;
            }
            .btn-shop-login {
                padding: 5px 10px;
                font-size: 12px;
            }
            .shop-search-input {
                padding: 7px 40px 7px 12px;
                font-size: 13px;
            }
        }
    </style>
    @yield('extra_css')
</head>
<body>

    <!-- Top Announcement Bar -->
    <div class="shop-top-notice">
        <div class="container-fluid px-3 px-lg-5 d-flex justify-content-between align-items-center">
            <div>
                <i class="fa fa-bullhorn me-1 text-warning"></i>
                {{ $settings['top_notice_bar'] ?? 'এখন ঢাকাজুড়ে ফ্রি হোম ডেলিভারি! সীমিত সময়ের অফার চলছে।' }}
            </div>
            <div class="d-none d-md-block">
                হটলাইন: <strong class="text-white">{{ $settings['shop_hotline'] ?? '09617900200' }}</strong> | 
                <a href="{{ route('shop.track_order') }}" class="text-warning text-decoration-none me-2 fw-semibold"><i class="fa fa-truck-fast me-1"></i>অর্ডার ট্র্যাক করুন</a> | 
                <a href="{{ route('/') }}" class="text-info text-decoration-none"><i class="fa fa-home me-1"></i> মূল ওয়েবসাইটে ফিরুন</a>
            </div>
        </div>
    </div>

    <!-- Main Header Navbar -->
    <header class="shop-header">
        <div class="container-fluid px-3 px-lg-5">
            <div class="row align-items-center gy-2">
                <!-- Logo & Mobile Toggle -->
                <div class="col-6 col-lg-3 d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary d-lg-none btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebarOffcanvas">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a href="{{ route('products.index') }}" class="shop-logo-wrap text-decoration-none">
                        @if(setting('logo') && file_exists(public_path('upload/images/backend/logo/' . setting('logo'))))
                            <img src="{{ asset('upload/images/backend/logo/' . setting('logo')) }}" alt="{{ setting('website_title', 'e-Krishi Sheba') }}" style="height: 48px; max-width: 175px; object-fit: contain;">
                        @else
                            <div class="shop-logo-svg">
                                A<span class="dot-o">●</span>SMI
                            </div>
                            <div>
                                <span class="badge bg-danger rounded-pill" style="font-size: 9px; padding: 2px 6px;">Supershop</span>
                                <span class="shop-logo-sub">e-Krishi Sheba</span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- WhatsApp Contacts (Desktop) -->
                <div class="col-lg-2 d-none d-xl-flex">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['shop_whatsapp'] ?? '8801805020340') }}" target="_blank" class="whatsapp-contact">
                        <i class="fa-brands fa-whatsapp"></i>
                        <div>
                            <div>{{ $settings['shop_whatsapp'] ?? '01805020340' }}</div>
                            <div style="font-size: 11px; color: #16a34a;">01805020346</div>
                        </div>
                    </a>
                </div>

                <!-- Live Search Bar -->
                <div class="col-12 col-lg-4 order-3 order-lg-2">
                    <form action="{{ route('products.index') }}" method="GET" class="shop-search-form mx-auto">
                        <input type="text" name="search" class="shop-search-input" value="{{ request('search') }}" placeholder="Search for Products (e.g. Rice, Oil, Vegetables)..." autocomplete="off" id="shopSearchInput">
                        <button type="submit" class="shop-search-btn" title="Search">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- App Pill, Login & Cart Button -->
                <div class="col-6 col-lg-3 order-2 order-lg-3 d-flex align-items-center justify-content-end gap-2 gap-md-3">
                    <a href="#download-app" class="app-download-pill d-none d-md-flex">
                        <i class="fa-solid fa-mobile-screen-button text-primary"></i>
                        <span>Download App!</span>
                    </a>

                    @if(Auth::check())
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown">
                                <i class="fa fa-user-circle text-primary"></i>
                                <span class="d-none d-sm-inline">{{ Str::limit(Auth::user()->name, 10) }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fa fa-th-large me-2 text-primary"></i>ড্যাশবোর্ড</a></li>
                                <li><a class="dropdown-item" href="{{ route('shop.checkout') }}"><i class="fa fa-shopping-bag me-2 text-success"></i>আমার ব্যাগ</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fa fa-sign-out me-2"></i>লগআউট</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-shop-login">
                            <i class="fa fa-arrow-right-to-bracket"></i>
                            <span>Login</span>
                        </a>
                    @endif

                    <button type="button" class="btn btn-outline-teal position-relative" style="border: 1px solid var(--shop-teal); color: var(--shop-teal); border-radius: 6px; padding: 6px 12px;" onclick="ShopCart.openDrawer()">
                        <i class="fa fa-shopping-bag"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="navCartBadge">
                            0
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container with Left Sidebar -->
    <div class="container-fluid px-3 px-lg-5 py-3 py-lg-4">
        <div class="row">
            <!-- Left Category Sidebar Column (Desktop) -->
            <div class="col-lg-3 col-xl-2 d-none d-lg-block">
                @include('frontend.shop.partials.sidebar', ['categories' => $categories])
            </div>

            <!-- Main Shop Content Slot -->
            <div class="col-lg-9 col-xl-10">
                @yield('shop_content')
            </div>
        </div>
    </div>

    <!-- Mobile Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebarOffcanvas" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header" style="background: var(--shop-teal); color: #fff;">
            <h5 class="offcanvas-title" id="mobileSidebarLabel"><i class="fa fa-list me-2"></i>ক্যাটাগরি সমূহ</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('frontend.shop.partials.sidebar', ['categories' => $categories, 'isMobile' => true])
        </div>
    </div>

    <!-- Floating Bag Widget on the Right (0 ITEM ৳0) -->
    <div class="floating-cart-badge" id="floatingCartBtn" onclick="ShopCart.openDrawer()">
        <i class="fa-solid fa-basket-shopping floating-cart-icon"></i>
        <div class="floating-cart-count" id="floatingCartCount">0 ITEM</div>
        <div class="floating-cart-price" id="floatingCartTotal">৳ 0</div>
    </div>

    <!-- Slide-out Cart Drawer -->
    <div class="cart-drawer-overlay" id="cartDrawerOverlay" onclick="ShopCart.closeDrawer()"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-drawer-header">
            <h5 class="cart-drawer-title">
                <i class="fa-solid fa-bag-shopping"></i>
                <span>আমার ব্যাগ (<span id="drawerCartCount">0</span> টি পণ্য)</span>
            </h5>
            <button type="button" class="cart-drawer-close" onclick="ShopCart.closeDrawer()" title="Close Bag">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <div class="cart-drawer-body" id="drawerCartItems">
            <!-- Cart items injected here via JavaScript -->
        </div>
        <div class="cart-drawer-footer" id="drawerCartFooter">
            <div class="cart-summary-line">
                <span>পণ্যের মূল্য (Subtotal):</span>
                <strong id="drawerSubtotal">৳ 0</strong>
            </div>
            <div class="cart-summary-line text-success small">
                <span><i class="fa fa-truck me-1"></i> হোম ডেলিভারি:</span>
                <span>চেকআউটে নির্ধারিত হবে</span>
            </div>
            <div class="cart-summary-total">
                <span>সর্বমোট:</span>
                <span class="text-teal" id="drawerGrandTotal">৳ 0</span>
            </div>
            <a href="{{ route('shop.checkout') }}" class="btn-checkout-drawer" id="btnDrawerCheckout">
                <span>অর্ডার সম্পন্ন করুন / Checkout</span>
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Floating Support Bubble (Interactive Chatbox Trigger) -->
    <button type="button" class="shop-support-bubble shadow border-0" id="btnToggleChat" onclick="toggleShopChat()" title="Asmi Support">
        <i class="fa-solid fa-headset text-teal fs-5" style="color: var(--shop-teal);"></i>
        <span>Asmi Support</span>
    </button>

    <!-- Interactive Customer Support Chatbox -->
    <div class="shop-chat-widget shadow-lg" id="shopChatWidget">
        <!-- Chat Header -->
        <div class="shop-chat-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="chat-header-avatar">
                    <i class="fa-solid fa-headset text-white"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-white fw-bold" style="font-size: 14px;">Asmi Support</h6>
                    <small class="text-white-50" style="font-size: 11px;"><i class="fa fa-circle text-success me-1" style="font-size: 8px;"></i>অনলাইন | e-Krishi Sheba</small>
                </div>
            </div>
            <button type="button" class="btn-chat-close" onclick="toggleShopChat()" aria-label="Close Chat">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- State 1: Registration Form (Shown if not yet registered) -->
        <div class="shop-chat-onboarding p-4" id="chatOnboardingState">
            <div class="text-center mb-3">
                <div class="onboarding-icon-circle mx-auto mb-2">
                    <i class="fa-solid fa-comments text-teal fs-3" style="color: var(--shop-teal);"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1" style="font-size: 16px;">কাস্টমার কেয়ারে স্বাগতম!</h5>
                <p class="text-muted small mb-0">চ্যাট শুরু করতে অনুগ্রহ করে আপনার নাম ও মোবাইল নম্বর প্রদান করুন:</p>
            </div>

            <form id="chatRegisterForm" onsubmit="handleStartChat(event)">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark mb-1">আপনার নাম (Your Name) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa fa-user text-muted"></i></span>
                        <input type="text" id="chatInputName" class="form-control" placeholder="যেমন: নাজমুল হাসান" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark mb-1">মোবাইল নম্বর (Phone Number) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa fa-phone text-muted"></i></span>
                        <input type="tel" id="chatInputPhone" class="form-control" placeholder="যেমন: 017XXXXXXXX" required pattern="[0-9]{11}" title="১১ ডিজিটের মোবাইল নম্বর দিন">
                    </div>
                </div>

                <button type="submit" class="btn text-white w-100 py-2 fw-bold shadow-sm" style="background: var(--shop-teal); border: none;">
                    <i class="fa-solid fa-comments me-1"></i> চ্যাট শুরু করুন
                </button>
            </form>

            <div class="text-center mt-3 pt-3 border-top">
                <a href="https://wa.me/8801805020340?text=Hello%20Asmi%20Shop%20Support" target="_blank" class="text-decoration-none small text-success fw-bold">
                    <i class="fa-brands fa-whatsapp me-1"></i> অথবা সরাসরি হোয়াটসঅ্যাপে লিখুন
                </a>
            </div>
        </div>

        <!-- State 2: Active Chat Conversation (Shown after name & phone are provided) -->
        <div class="shop-chat-conversation d-flex flex-column h-100" id="chatConversationState" style="display: none;">
            <!-- Customer Info Bar -->
            <div class="chat-user-bar px-3 py-2 bg-light border-bottom d-flex align-items-center justify-content-between">
                <div class="small text-truncate">
                    <i class="fa fa-user-check text-success me-1"></i>
                    <strong id="chatUserDisplayName" class="text-dark">User</strong>
                    <span id="chatUserDisplayPhone" class="text-muted small ms-1">(01700000000)</span>
                </div>
                <button type="button" class="btn btn-sm btn-link text-muted p-0 small text-decoration-none" onclick="resetChatUser()" title="নতুন নাম বা নম্বর দিয়ে শুরু করুন">
                    <i class="fa fa-rotate-left"></i>
                </button>
            </div>

            <!-- Messages Area -->
            <div class="chat-messages-scroll flex-grow-1 p-3" id="chatMessagesArea">
                <!-- Messages inserted dynamically via JS -->
            </div>

            <!-- Quick Question Chips -->
            <div class="chat-quick-chips px-3 py-2 bg-white border-top">
                <div class="d-flex gap-1 overflow-x-auto pb-1" style="scrollbar-width: none;">
                    <button type="button" class="btn btn-sm btn-outline-secondary text-nowrap rounded-pill py-0 px-2" style="font-size: 11px;" onclick="sendQuickMessage('অর্ডার কীভাবে ট্র্যাক করব?')">📦 অর্ডার ট্র্যাকিং</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-nowrap rounded-pill py-0 px-2" style="font-size: 11px;" onclick="sendQuickMessage('ডেলিভারি চার্জ কত ও সময় কত?')">🚚 ডেলিভারি চার্জ</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-nowrap rounded-pill py-0 px-2" style="font-size: 11px;" onclick="sendQuickMessage('পেমেন্ট করার পদ্ধতি কি কি?')">💳 পেমেন্ট পদ্ধতি</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-nowrap rounded-pill py-0 px-2" style="font-size: 11px;" onclick="sendQuickMessage('হটলাইনে সরাসরি কথা বলতে চাই')">📞 হটলাইন</button>
                </div>
            </div>

            <!-- Message Input Box -->
            <form class="chat-input-bar p-2 bg-light border-top d-flex gap-2 align-items-center" onsubmit="handleSendChatMessage(event)">
                <input type="text" id="chatTextMessage" class="form-control form-control-sm border-0 bg-white" placeholder="আপনার মেসেজ লিখুন..." required autocomplete="off">
                <button type="submit" class="btn btn-sm text-white px-3" style="background: var(--shop-teal); border: none;" aria-label="Send">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Toast Notification for Add to Cart -->
    <div class="shop-toast" id="shopToast">
        <i class="fa-solid fa-circle-check"></i>
        <span id="shopToastMsg">পণ্যটি আপনার ব্যাগে যোগ করা হয়েছে!</span>
    </div>

    <!-- Shop Footer Matching Image 5 & 6 -->
    <footer class="shop-footer" id="download-app">
        <div class="container-fluid px-lg-5">
            <div class="row g-4">
                <!-- Column 1: Brand & Contact Info -->
                <div class="col-lg-3 col-md-6">
                    @if(setting('logo') && file_exists(public_path('upload/images/backend/logo/' . setting('logo'))))
                        <div class="mb-3">
                            <img src="{{ asset('upload/images/backend/logo/' . setting('logo')) }}" alt="{{ setting('website_title', 'e-Krishi Sheba') }}" style="height: 48px; max-width: 175px; object-fit: contain; background: #ffffff; padding: 4px 8px; border-radius: 8px;">
                        </div>
                    @else
                        <div class="shop-logo-svg text-white mb-3" style="font-size: 32px;">
                            A<span class="dot-o text-warning">●</span>SMI
                        </div>
                    @endif
                    
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="fa fa-phone"></i></div>
                        <div>
                            <div class="text-white-50 small">হটলাইন / মোবাইল</div>
                            <strong>{{ $settings['shop_whatsapp'] ?? '01805-020340' }}</strong>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="fa fa-headset"></i></div>
                        <div>
                            <div class="text-white-50 small">কাস্টমার কেয়ার</div>
                            <strong>{{ $settings['shop_hotline'] ?? '09617900200' }}</strong>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="fa fa-envelope"></i></div>
                        <div>
                            <div class="text-white-50 small">ইমেইল সহায়তা</div>
                            <span>{{ $settings['shop_email'] ?? 'support@asmishop.com' }}</span>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <div class="footer-contact-icon"><i class="fa fa-map-marker-alt"></i></div>
                        <div>
                            <div class="text-white-50 small">অফিস ঠিকানা</div>
                            <span>{{ $settings['shop_address'] ?? 'Plot #45, Probal Tower, Ring Road, Mohammadpur, Dhaka-1207' }}</span>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ $settings['social_facebook'] ?? '#' }}" class="btn btn-sm btn-outline-light rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="{{ $settings['social_linkedin'] ?? '#' }}" class="btn btn-sm btn-outline-light rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="{{ $settings['social_youtube'] ?? '#' }}" class="btn btn-sm btn-outline-light rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;"><i class="fa-brands fa-youtube"></i></a>
                        <a href="{{ $settings['social_tiktok'] ?? '#' }}" class="btn btn-sm btn-outline-light rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Column 2: Product Category -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="shop-footer-title">PRODUCT CATEGORY</h5>
                    <ul class="shop-footer-list">
                        <li><a href="{{ route('products.index', ['category' => 'food']) }}"><i class="fa fa-angle-right"></i> Food (খাদ্যদ্রব্য)</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'baby-food-care']) }}"><i class="fa fa-angle-right"></i> Baby Food & Care</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'home-cleaning']) }}"><i class="fa fa-angle-right"></i> Home Cleaning</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'beauty-health']) }}"><i class="fa fa-angle-right"></i> Beauty & Health</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'fashion-lifestyle']) }}"><i class="fa fa-angle-right"></i> Fashion & Lifestyle</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'home-kitchen']) }}"><i class="fa fa-angle-right"></i> Home & Kitchen</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'stationeries']) }}"><i class="fa fa-angle-right"></i> Stationeries</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'toys-sports']) }}"><i class="fa fa-angle-right"></i> Toys & Sports</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'pet-care']) }}"><i class="fa fa-angle-right"></i> Pet Care</a></li>
                    </ul>
                </div>

                <!-- Column 3: Customer Care -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="shop-footer-title">CUSTOMER CARE</h5>
                    <ul class="shop-footer-list">
                        <li><a href="{{ route('/') }}"><i class="fa fa-angle-right"></i> Home</a></li>
                        <li><a href="{{ route('about') }}"><i class="fa fa-angle-right"></i> About Us</a></li>
                        <li><a href="{{ route('terms') }}"><i class="fa fa-angle-right"></i> Return Policy</a></li>
                        <li><a href="{{ route('terms') }}"><i class="fa fa-angle-right"></i> Terms & Condition</a></li>
                        <li><a href="{{ route('privacy') }}"><i class="fa fa-angle-right"></i> Privacy Policy</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fa fa-angle-right"></i> Contact Us</a></li>
                        <li><a href="{{ route('career') }}"><i class="fa fa-angle-right"></i> Career</a></li>
                        <li><a href="{{ route('corporate.board') }}"><i class="fa fa-angle-right"></i> About Corporate Support</a></li>
                    </ul>
                </div>

                <!-- Column 4: App Download & Quick Portals -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="shop-footer-title">DOWNLOAD OUR APP</h5>
                    <p class="small text-white-50 mb-3">আমাদের মোবাইল অ্যাপ ডাউনলোড করে আরও সহজে কেনাকাটা করুন এবং নিয়মিত এক্সক্লুসিভ অফার পান।</p>
                    
                    <div>
                        <a href="#" class="app-store-badge w-100">
                            <i class="fa-brands fa-google-play"></i>
                            <div>
                                <span class="d-block" style="font-size: 10px; color: #94a3b8;">GET IT ON</span>
                                <strong class="fs-6">Google Play</strong>
                            </div>
                        </a>
                        <a href="#" class="app-store-badge w-100">
                            <i class="fa-brands fa-apple"></i>
                            <div>
                                <span class="d-block" style="font-size: 10px; color: #94a3b8;">Download on the</span>
                                <strong class="fs-6">App Store</strong>
                            </div>
                        </a>
                    </div>

                    <h5 class="shop-footer-title mt-4 mb-2">QUICK ACCESS</h5>
                    <div>
                        <a href="{{ route('corporate.board') }}" class="quick-portal-btn"><i class="fa fa-building me-1"></i> Corporate Portal</a>
                        <a href="{{ route('login') }}" class="quick-portal-btn"><i class="fa fa-motorcycle me-1"></i> Rider Portal</a>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Line -->
            <div class="shop-copyright-bar d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div>
                    COPYRIGHT © 2026. All Rights Reserved By Asmishop.
                </div>
                <div>
                    Developed By <a href="{{ route('/') }}" class="text-white fw-bold">Asmi IT Solutions / e-Krishi Sheba</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global Cart Manager JS with LocalStorage Persistence -->
    <script>
        const ShopCart = {
            storageKey: 'asmi_supershop_cart_v1',

            getItems: function() {
                try {
                    const raw = localStorage.getItem(this.storageKey);
                    return raw ? JSON.parse(raw) : [];
                } catch(e) {
                    return [];
                }
            },

            saveItems: function(items) {
                localStorage.setItem(this.storageKey, JSON.stringify(items));
                this.updateUI();
            },

            addItem: function(product) {
                let items = this.getItems();
                const existing = items.find(item => item.id == product.id);
                if (existing) {
                    existing.quantity = (existing.quantity || 1) + (product.quantity || 1);
                } else {
                    items.push({
                        id: product.id,
                        name: product.name,
                        price: parseFloat(product.price),
                        image: product.image,
                        quantity: product.quantity || 1,
                        sku: product.sku || '',
                        delivery_time: product.delivery_time || 'Delivery 1-2 hours'
                    });
                }
                this.saveItems(items);
                this.showToast((product.name || 'পণ্যটি') + ' ব্যাগে যোগ করা হয়েছে!');
            },

            updateQty: function(id, delta) {
                let items = this.getItems();
                const item = items.find(i => i.id == id);
                if (item) {
                    item.quantity += delta;
                    if (item.quantity <= 0) {
                        items = items.filter(i => i.id != id);
                    }
                }
                this.saveItems(items);
            },

            removeItem: function(id) {
                let items = this.getItems().filter(i => i.id != id);
                this.saveItems(items);
                this.showToast('পণ্যটি ব্যাগ থেকে সরানো হয়েছে');
            },

            clearCart: function() {
                localStorage.removeItem(this.storageKey);
                this.updateUI();
            },

            openDrawer: function() {
                document.getElementById('cartDrawerOverlay').classList.add('active');
                document.getElementById('cartDrawer').classList.add('active');
                this.renderDrawerList();
            },

            closeDrawer: function() {
                document.getElementById('cartDrawerOverlay').classList.remove('active');
                document.getElementById('cartDrawer').classList.remove('active');
            },

            updateUI: function() {
                const items = this.getItems();
                const totalCount = items.reduce((sum, item) => sum + (item.quantity || 1), 0);
                const subtotal = items.reduce((sum, item) => sum + (item.price * (item.quantity || 1)), 0);

                // Update Header Cart Badge
                const navBadge = document.getElementById('navCartBadge');
                if (navBadge) navBadge.textContent = totalCount;

                // Update Floating Widget
                const floatCount = document.getElementById('floatingCartCount');
                if (floatCount) floatCount.textContent = totalCount + ' ITEM' + (totalCount !== 1 ? 'S' : '');
                const floatTotal = document.getElementById('floatingCartTotal');
                if (floatTotal) floatTotal.textContent = '৳ ' + Math.round(subtotal);

                // Update Drawer title
                const drawerCount = document.getElementById('drawerCartCount');
                if (drawerCount) drawerCount.textContent = totalCount;

                // Update Drawer totals
                const subEl = document.getElementById('drawerSubtotal');
                if (subEl) subEl.textContent = '৳ ' + Math.round(subtotal);
                const grandEl = document.getElementById('drawerGrandTotal');
                if (grandEl) grandEl.textContent = '৳ ' + Math.round(subtotal);

                this.renderDrawerList();

                // Custom event for checkout sync
                window.dispatchEvent(new CustomEvent('shop_cart_updated', { detail: { items, totalCount, subtotal } }));
            },

            renderDrawerList: function() {
                const container = document.getElementById('drawerCartItems');
                if (!container) return;
                const items = this.getItems();

                if (items.length === 0) {
                    container.innerHTML = `
                        <div class="cart-empty-state">
                            <i class="fa-solid fa-basket-shopping"></i>
                            <h6 class="fw-bold mt-2">আপনার ব্যাগ খালি!</h6>
                            <p class="small text-muted">প্রয়োজনীয় পণ্য বাছাই করে 'Add to Bag' বাটনে ক্লিক করুন।</p>
                            <button type="button" class="btn btn-sm btn-outline-teal mt-2" onclick="ShopCart.closeDrawer()" style="border: 1px solid var(--shop-teal); color: var(--shop-teal);">
                                কেনাকাটা চালিয়ে যান
                            </button>
                        </div>
                    `;
                    const footer = document.getElementById('drawerCartFooter');
                    if (footer) footer.style.opacity = '0.5';
                    const btn = document.getElementById('btnDrawerCheckout');
                    if (btn) btn.classList.add('disabled');
                    return;
                }

                const footer = document.getElementById('drawerCartFooter');
                if (footer) footer.style.opacity = '1';
                const btn = document.getElementById('btnDrawerCheckout');
                if (btn) btn.classList.remove('disabled');

                let html = '';
                items.forEach(item => {
                    const lineTotal = Math.round(item.price * item.quantity);
                    html += `
                        <div class="cart-item-row">
                            <img src="${item.image || 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=200'}" alt="${item.name}" class="cart-item-img">
                            <div class="cart-item-info">
                                <div class="cart-item-name" title="${item.name}">${item.name}</div>
                                <div class="cart-item-price">৳ ${Math.round(item.price)} × ${item.quantity} = <strong>৳ ${lineTotal}</strong></div>
                            </div>
                            <div class="cart-qty-ctrls">
                                <button type="button" class="cart-qty-btn" onclick="ShopCart.updateQty(${item.id}, -1)">−</button>
                                <span class="cart-qty-val">${item.quantity}</span>
                                <button type="button" class="cart-qty-btn" onclick="ShopCart.updateQty(${item.id}, 1)">+</button>
                            </div>
                            <button type="button" class="cart-item-del" onclick="ShopCart.removeItem(${item.id})" title="Remove item">
                                <i class="fa fa-trash-can"></i>
                            </button>
                        </div>
                    `;
                });
                container.innerHTML = html;
            },

            showToast: function(msg) {
                const toast = document.getElementById('shopToast');
                const toastMsg = document.getElementById('shopToastMsg');
                if (toast && toastMsg) {
                    toastMsg.textContent = msg;
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 2800);
                }
            }
        };

        function addCardToBag(btn) {
            if (!btn || !btn.dataset) return;
            const ds = btn.dataset;
            ShopCart.addItem({
                id: ds.id,
                name: ds.name,
                price: parseFloat(ds.price || 0),
                image: ds.image || '',
                sku: ds.sku || '',
                delivery_time: ds.delivery || 'Delivery 1-2 hours',
                quantity: 1
            });
        }

        // Initialize UI on page load
        document.addEventListener('DOMContentLoaded', () => {
            ShopCart.updateUI();
            initChatWidget();
        });

        /* ================= Asmi Interactive Support Chat ================= */
        let chatUser = null;
        let chatMessages = [];

        function initChatWidget() {
            try {
                const savedUser = localStorage.getItem('asmi_chat_user');
                if (savedUser) {
                    chatUser = JSON.parse(savedUser);
                }
                const savedMsgs = localStorage.getItem('asmi_chat_messages');
                if (savedMsgs) {
                    chatMessages = JSON.parse(savedMsgs);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function toggleShopChat() {
            const widget = document.getElementById('shopChatWidget');
            if (!widget) return;
            const isVisible = widget.style.display === 'flex';
            if (isVisible) {
                widget.style.display = 'none';
            } else {
                widget.style.display = 'flex';
                initChatWidget();
                renderChatState();
            }
        }

        function renderChatState() {
            const onboarding = document.getElementById('chatOnboardingState');
            const conversation = document.getElementById('chatConversationState');
            if (!chatUser || !chatUser.name || !chatUser.phone) {
                if (onboarding) onboarding.style.display = 'block';
                if (conversation) conversation.style.display = 'none';
            } else {
                if (onboarding) onboarding.style.display = 'none';
                if (conversation) conversation.style.display = 'flex';
                
                const nameEl = document.getElementById('chatUserDisplayName');
                const phoneEl = document.getElementById('chatUserDisplayPhone');
                if (nameEl) nameEl.textContent = chatUser.name;
                if (phoneEl) phoneEl.textContent = '(' + chatUser.phone + ')';

                if (!chatMessages || chatMessages.length === 0) {
                    chatMessages.push({
                        sender: 'agent',
                        text: `আসসালামু আলাইকুম <strong>${chatUser.name}</strong>! Asmi Supershop এ আপনাকে স্বাগতম। আপনার অর্ডার, ডেলিভারি বা পণ্যের বিষয়ে কীভাবে সাহায্য করতে পারি?`,
                        time: getCurrentChatTime()
                    });
                    saveChatMessages();
                }
                renderChatMessages();
            }
        }

        function handleStartChat(e) {
            e.preventDefault();
            const nameInput = document.getElementById('chatInputName');
            const phoneInput = document.getElementById('chatInputPhone');
            if (!nameInput || !phoneInput) return;

            const name = nameInput.value.trim();
            const phone = phoneInput.value.trim();
            if (!name || !phone) return;

            chatUser = { name, phone };
            try {
                localStorage.setItem('asmi_chat_user', JSON.stringify(chatUser));
            } catch (err) {}

            renderChatState();
        }

        function resetChatUser() {
            if (confirm('আপনি কি নাম ও ফোন নম্বর পরিবর্তন করে নতুন চ্যাট শুরু করতে চান?')) {
                chatUser = null;
                chatMessages = [];
                try {
                    localStorage.removeItem('asmi_chat_user');
                    localStorage.removeItem('asmi_chat_messages');
                } catch (e) {}
                renderChatState();
            }
        }

        function getCurrentChatTime() {
            const d = new Date();
            let h = d.getHours();
            let m = d.getMinutes();
            const ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            m = m < 10 ? '0' + m : m;
            return `${h}:${m} ${ampm}`;
        }

        function renderChatMessages() {
            const area = document.getElementById('chatMessagesArea');
            if (!area) return;
            let html = '';
            chatMessages.forEach(msg => {
                const isUser = msg.sender === 'user';
                html += `
                    <div class="chat-bubble ${isUser ? 'chat-bubble-user' : 'chat-bubble-agent'}">
                        <div>${msg.text}</div>
                        <span class="chat-bubble-time">${msg.time}</span>
                    </div>
                `;
            });
            area.innerHTML = html;
            area.scrollTop = area.scrollHeight;
        }

        function saveChatMessages() {
            try {
                localStorage.setItem('asmi_chat_messages', JSON.stringify(chatMessages));
            } catch (e) {}
        }

        function handleSendChatMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chatTextMessage');
            if (!input) return;
            const text = input.value.trim();
            if (!text) return;
            input.value = '';
            sendMessage(text);
        }

        function sendQuickMessage(text) {
            sendMessage(text);
        }

        function sendMessage(text) {
            chatMessages.push({
                sender: 'user',
                text: escapeHtml(text),
                time: getCurrentChatTime()
            });
            renderChatMessages();
            saveChatMessages();

            // Smart automated support response after 600ms
            setTimeout(() => {
                let reply = '';
                const lower = text.toLowerCase();
                if (lower.includes('ট্র্যাক') || lower.includes('অর্ডার') || lower.includes('track') || lower.includes('order')) {
                    reply = 'আপনার অর্ডার ট্র্যাক করতে বাম পাশের সাইডবারে <strong>কাস্টমার ডেলিভারি পোর্টাল</strong> অথবা অর্ডার কনফার্মেশন এসএমএসে পাঠানো অর্ডার কোড ও ফোন নম্বর ব্যবহার করুন। যেকোনো অনুসন্ধানে হটলাইনে কল করুন: <strong>09617900200</strong>।';
                } else if (lower.includes('চার্জ') || lower.includes('ডেলিভারি') || lower.includes('delivery')) {
                    reply = 'ঢাকা সিটির ভেতরে সকল অর্ডারে <strong>ফ্রি হোম ডেলিভারি!</strong> অর্ডার কনফার্ম হওয়ার ১-২ ঘণ্টার মধ্যে আপনার বাসায় পণ্য পৌঁছে দেওয়া হয়।';
                } else if (lower.includes('পেমেন্ট') || lower.includes('টাকা') || lower.includes('payment') || lower.includes('bkash')) {
                    reply = 'আমরা বিকাশ (bKash), নগদ (Nagad), রকেট (Rocket), ক্যাশ অন ডেলিভারি (Cash on Delivery) এবং অনলাইন ব্যাংকিং গ্রহণ করি।';
                } else if (lower.includes('হটলাইন') || lower.includes('কথা') || lower.includes('কল') || lower.includes('প্রতিনিধি') || lower.includes('phone')) {
                    reply = 'সরাসরি প্রতিনিধির সাথে কথা বলতে ডায়াল করুন আমাদের হটলাইন: <strong>09617900200</strong> অথবা হোয়াটসঅ্যাপ করুন: <strong>01805-020340</strong> (প্রতিদিন সকাল ৮টা থেকে রাত ১০টা)।';
                } else {
                    reply = `ধন্যবাদ <strong>${chatUser.name}</strong>! আপনার বার্তাটি রেকর্ড করা হয়েছে। আমাদের কাস্টমার প্রতিনিধি শীঘ্রই আপনার নম্বরে (${chatUser.phone}) যোগাযোগ করবেন। প্রয়োজনে কল করুন: 09617900200।`;
                }

                chatMessages.push({
                    sender: 'agent',
                    text: reply,
                    time: getCurrentChatTime()
                });
                renderChatMessages();
                saveChatMessages();
            }, 600);
        }

        function escapeHtml(str) {
            return str.replace(/[&<>"']/g, function(m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
            });
        }

        // Smart Sticky Sidebar Subcategory Flyout Placement
        (function initCategoryFlyouts() {
            function setupFlyouts() {
                const catItems = document.querySelectorAll('.shop-sidebar:not(.mobile-sidebar) .sidebar-cat-item');
                catItems.forEach(item => {
                    const flyout = item.querySelector('.cat-flyout-menu');
                    if (!flyout) return;

                    let hideTimeout;

                    function showFlyout() {
                        clearTimeout(hideTimeout);
                        document.querySelectorAll('.cat-flyout-menu').forEach(f => {
                            if (f !== flyout) f.style.display = 'none';
                        });

                        const rect = item.getBoundingClientRect();
                        flyout.style.position = 'fixed';
                        flyout.style.left = (rect.right + 2) + 'px';
                        
                        const flyoutH = flyout.offsetHeight || 220;
                        let topPos = rect.top;
                        if (topPos + flyoutH > window.innerHeight - 15) {
                            topPos = Math.max(75, window.innerHeight - flyoutH - 15);
                        }
                        flyout.style.top = topPos + 'px';
                        flyout.style.display = 'block';
                        flyout.style.zIndex = '99999';
                    }

                    function hideFlyout() {
                        hideTimeout = setTimeout(() => {
                            flyout.style.display = 'none';
                        }, 120);
                    }

                    item.addEventListener('mouseenter', showFlyout);
                    item.addEventListener('mouseleave', hideFlyout);
                    flyout.addEventListener('mouseenter', () => clearTimeout(hideTimeout));
                    flyout.addEventListener('mouseleave', hideFlyout);
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupFlyouts);
            } else {
                setupFlyouts();
            }
        })();
    </script>
    @yield('extra_js')
</body>
</html>
