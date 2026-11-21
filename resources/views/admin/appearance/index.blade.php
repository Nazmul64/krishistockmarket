@extends('layouts.backend.app')

@section('content')
<style>
/* Drag & Drop Builder Styling */
.cursor-grab {
    cursor: grab !important;
}
.cursor-grab:active, .section-drag-handle:active, .menu-drag-handle:active {
    cursor: grabbing !important;
}
.sortable-ghost {
    opacity: 0.35 !important;
    background-color: #f0fdf4 !important;
    border: 2px dashed #16a34a !important;
    box-shadow: none !important;
}
.sortable-chosen {
    box-shadow: 0 14px 28px rgba(0,0,0,0.12), 0 10px 10px rgba(0,0,0,0.08) !important;
    transform: scale(1.015);
    background: #ffffff !important;
}
.section-item-card {
    transition: all 0.2s ease-in-out;
}
.section-item-card:hover {
    border-color: #10b981 !important;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1) !important;
}
.menu-drag-handle:hover, .section-drag-handle:hover {
    background-color: #e2e8f0 !important;
    color: #0f172a !important;
}
.template-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.09) !important;
}
</style>

<div class="content-wrapper" style="background-color: #f8fafc;">
    <!-- Content Header (Page header) -->
    <div class="content-header px-4 pt-4 pb-2">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h3 class="page-title mb-1 fw-bold text-dark d-flex align-items-center">
                    <span class="avatar avatar-md rounded-circle bg-success text-white me-2 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px;">
                        <i class="fa fa-paint-brush"></i>
                    </span>
                    ডিজাইন ও টেমপ্লেট স্টুডিও (Appearance & Customization Studio)
                </h3>
                <p class="text-muted small mb-0">
                    ওয়েবসাইট ও মোবাইল অ্যাপসের টেমপ্লেট, মেনু, হোমপেজ সেকশন, রঙ ও লেআউট সম্পূর্ণ ডাইনামিকভাবে নিয়ন্ত্রণ করুন।
                </p>
            </div>

            <!-- Action Controls: Draft, Preview, Publish -->
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span class="badge bg-light text-dark border px-3 py-2 font-size-12 shadow-sm rounded-pill">
                    <i class="fa fa-tag text-primary me-1"></i> সক্রিয় টেমপ্লেট: 
                    <strong class="text-success ms-1" id="active_template_badge">
                        {{ $config->template ? $config->template->name_bn : 'মডার্ন এগ্রিকালচার' }}
                    </strong>
                </span>

                <button type="button" class="btn btn-outline-info rounded-pill px-3 py-2 fw-bold shadow-sm" onclick="openLivePreviewModal()">
                    <i class="fa fa-desktop me-1"></i> লাইভ প্রিভিউ
                </button>

                <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-bold shadow-sm" onclick="saveDraftSettings()">
                    <i class="fa fa-save me-1"></i> ড্রাফট সংরক্ষণ
                </button>

                <button type="button" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm" onclick="openPublishModal()">
                    <i class="fa fa-paper-plane me-1"></i> লাইভ পাবলিশ করুন
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content px-4 py-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Customization Tabs Navigation -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-2 bg-white">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row gap-1" id="appearanceTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold py-3 rounded-3" id="templates-tab" data-bs-toggle="pill" data-bs-target="#tab-templates" type="button" role="tab">
                            <i class="fa fa-th-large me-2 text-warning"></i> ১. ১০টি প্রিমিয়াম টেমপ্লেট
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-3 rounded-3" id="menu-tab" data-bs-toggle="pill" data-bs-target="#tab-menu" type="button" role="tab">
                            <i class="fa fa-bars me-2 text-primary"></i> ২. নেভিগেশন মেনু বিল্ডার (ড্র্যাগ & ড্রপ)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-3 rounded-3" id="sections-tab" data-bs-toggle="pill" data-bs-target="#tab-sections" type="button" role="tab">
                            <i class="fa fa-cubes me-2 text-success"></i> ৩. হোমপেজ সেকশন বিল্ডার (মাউস ড্র্যাগ)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-3 rounded-3" id="styles-tab" data-bs-toggle="pill" data-bs-target="#tab-styles" type="button" role="tab">
                            <i class="fa fa-sliders me-2 text-danger"></i> ৪. কালার, হেডার ও ফুটার
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-3 rounded-3" id="mobile-tab" data-bs-toggle="pill" data-bs-target="#tab-mobile" type="button" role="tab">
                            <i class="fa fa-mobile-phone me-2 text-info"></i> ৫. মোবাইল অ্যাপ UI
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-3 rounded-3" id="versions-tab" data-bs-toggle="pill" data-bs-target="#tab-versions" type="button" role="tab">
                            <i class="fa fa-history me-2 text-secondary"></i> ৬. ভার্সন হিস্ট্রি ও রোলব্যাক
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('admin.appearance.components') }}" class="nav-link fw-bold py-3 rounded-3 text-success">
                            <i class="fa fa-magic me-2 text-success"></i> ৭. প্রোডাক্ট, প্রিলোডার ও বাটন স্টাইলিং
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="appearanceTabContent">

            <!-- ========================================================================= -->
            <!-- 🌟 TAB 1: 10 Distinct Design Templates -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade show active" id="tab-templates" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-star text-warning me-2"></i>১০টি সম্পূর্ণ আলাদা ডিজাইন টেমপ্লেট</h5>
                            <small class="text-muted">যেকোনো একটি টেমপ্লেট সক্রিয় করলে ওয়েবসাইট ও অ্যাপের সম্পূর্ণ লেআউট স্বয়ংক্রিয়ভাবে পরিবর্তিত হবে। কোনো ডেটা মুছে যাবে না।</small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($templates as $tmpl)
                            @php
                                $isActive = ($config->active_template_slug === $tmpl->slug);
                            @endphp
                            <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                                <div class="card h-100 border-2 {{ $isActive ? 'border-success shadow-lg' : 'border-light shadow-sm' }} rounded-4 overflow-hidden position-relative template-card" style="transition: all 0.25s ease;">
                                    @if($isActive)
                                        <div class="position-absolute top-0 end-0 m-3" style="z-index: 5;">
                                            <span class="badge bg-success shadow-sm px-3 py-2 rounded-pill font-size-12">
                                                <i class="fa fa-check-circle me-1"></i> বর্তমানে সক্রিয়
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Template Mockup Header -->
                                    <div class="p-4 text-white text-center position-relative" style="background: linear-gradient(135deg, {{ $tmpl->default_config['primary_color'] ?? '#ea580c' }} 0%, {{ $tmpl->default_config['secondary_color'] ?? '#007e8a' }} 100%); min-height: 140px; display: flex; flex-direction: column; justify-content: center;">
                                        <span class="badge bg-white text-dark shadow-sm rounded-pill align-self-start mb-2 font-size-11">
                                            {{ $tmpl->category }}
                                        </span>
                                        <h4 class="fw-bold mb-1 text-white">{{ $tmpl->name_bn }}</h4>
                                        <small class="opacity-75">{{ $tmpl->name_en }}</small>
                                    </div>

                                    <!-- Body -->
                                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                                        <div>
                                            <p class="text-muted small mb-3" style="min-height: 48px;">
                                                {{ $tmpl->description_bn }}
                                            </p>

                                            <!-- Preset Colors Preview -->
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <small class="text-muted fw-bold me-1">থিম কালার:</small>
                                                <span class="d-inline-block rounded-circle shadow-sm" style="width: 20px; height: 20px; background-color: {{ $tmpl->default_config['primary_color'] ?? '#ea580c' }};" title="Primary Color"></span>
                                                <span class="d-inline-block rounded-circle shadow-sm" style="width: 20px; height: 20px; background-color: {{ $tmpl->default_config['secondary_color'] ?? '#007e8a' }};" title="Secondary Color"></span>
                                                <span class="d-inline-block rounded-circle shadow-sm" style="width: 20px; height: 20px; background-color: {{ $tmpl->default_config['button_color'] ?? '#007e8a' }};" title="Button Color"></span>
                                            </div>
                                        </div>

                                        <!-- Card Actions -->
                                        <div class="d-flex align-items-center gap-2 pt-3 border-top">
                                            @if($isActive)
                                                <button type="button" class="btn btn-light text-success w-100 fw-bold rounded-pill py-2" disabled>
                                                    <i class="fa fa-check me-1"></i> সক্রিয় রয়েছে
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-success w-100 fw-bold rounded-pill py-2 shadow-sm" onclick="activateTemplate('{{ $tmpl->slug }}', '{{ $tmpl->name_bn }}')">
                                                    <i class="fa fa-magic me-1"></i> এটি সক্রিয় করুন
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-2" onclick="previewTemplate('{{ $tmpl->slug }}')" title="প্রিভিউ">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 🗂 TAB 2: Navigation Menu Builder (Drag & Drop with Mouse) -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade" id="tab-menu" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-bars text-primary me-2"></i>নেভিগেশন মেনু বিল্ডার (মাউস ড্র্যাগ & ড্রপ)</h5>
                            <small class="text-muted">মাউস দিয়ে চেপে টেনে (<i class="fa fa-bars text-secondary mx-1"></i>) যেকোনো মেনু উপরে/নিচে পুনর্বিন্যাস করুন। স্বয়ংক্রিয়ভাবে সেভ হবে।</small>
                        </div>
                        <button type="button" class="btn btn-primary rounded-pill px-3 py-2 fw-bold shadow-sm" onclick="openAddMenuModal()">
                            <i class="fa fa-plus me-1"></i> নতুন মেনু যোগ করুন
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="menuItemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 90px;" class="text-center"><i class="fa fa-arrows-v me-1"></i> ড্র্যাগ</th>
                                        <th>মেনুর নাম (বাংলা ও ইংরেজি)</th>
                                        <th>লিংক / রুট</th>
                                        <th class="text-center" style="width: 130px;">ওয়েবসাইটে শো</th>
                                        <th class="text-center" style="width: 130px;">অ্যাপসে শো</th>
                                        <th class="text-center" style="width: 140px;">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody id="sortableMenuList">
                                    @foreach($menuItems as $index => $item)
                                    <tr data-id="{{ $item->id }}" class="menu-row">
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <div class="menu-drag-handle p-1 text-muted cursor-grab rounded shadow-xs" style="cursor: grab; background: #f1f5f9; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #cbd5e1;" title="মাউস দিয়ে চেপে উপরে বা নিচে টেনে সাজান">
                                                    <i class="fa fa-bars font-size-13 text-secondary"></i>
                                                </div>
                                                <span class="badge bg-secondary rounded-pill font-size-12 px-2 py-1 sort-number">{{ $index + 1 }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm rounded-circle bg-light text-primary me-2 d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;">
                                                    <i class="fa {{ $item->icon ?: 'fa-link' }}"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block">{{ $item->title_bn }}</span>
                                                    <small class="text-muted">{{ $item->title_en }}</small>
                                                    @if($item->badge_text)
                                                        <span class="badge bg-danger font-size-10 ms-1">{{ $item->badge_text }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-dark bg-light px-2 py-1 rounded">{{ $item->url }}</code>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input menu-web-toggle" type="checkbox" role="switch" {{ $item->show_on_web ? 'checked' : '' }} onchange="saveMenuOrderImmediate(true)">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input menu-app-toggle" type="checkbox" role="switch" {{ $item->show_on_app ? 'checked' : '' }} onchange="saveMenuOrderImmediate(true)">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary" onclick="moveRowUp(this)" title="উপরে তুলুন"><i class="fa fa-arrow-up"></i></button>
                                                <button type="button" class="btn btn-outline-secondary" onclick="moveRowDown(this)" title="নিচে নামান"><i class="fa fa-arrow-down"></i></button>
                                                <button type="button" class="btn btn-outline-danger" onclick="deleteMenuItem({{ $item->id }})" title="মুছুন"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm" onclick="saveMenuOrderImmediate(true)">
                                <i class="fa fa-save me-1"></i> মেনু ক্রম সংরক্ষণ করুন
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 🧩 TAB 3: Homepage Visual Builder (15 Sections - Mouse Drag & Drop) -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade" id="tab-sections" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-cubes text-success me-2"></i>হোমপেজ সেকশন ড্র্যাগ অ্যান্ড ড্রপ বিল্ডার</h5>
                            <small class="text-muted">মাউস দিয়ে চেপে টেনে (<i class="fa fa-ellipsis-v text-muted"></i><i class="fa fa-ellipsis-v text-muted"></i>) সেকশনগুলো ইচ্ছামত উপরে বা নিচে সাজান। ওয়েবসাইট ও অ্যাপে তাৎক্ষণিক পরিবর্তন হবে।</small>
                        </div>
                        <button type="button" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm" onclick="saveSectionsOrderImmediate(true)">
                            <i class="fa fa-save me-1"></i> সেকশন ক্রম সংরক্ষণ করুন
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div class="row" id="sortableSectionsList">
                            @foreach($sections as $index => $sec)
                            <div class="col-12 mb-3 section-draggable-item" data-id="{{ $sec->id }}" data-key="{{ $sec->section_key }}">
                                <div class="card border rounded-3 p-3 shadow-sm bg-white section-item-card">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <!-- Drag Handle & Details -->
                                        <div class="d-flex align-items-center">
                                            <div class="section-drag-handle me-3 d-flex align-items-center justify-content-center text-secondary rounded shadow-xs cursor-grab" style="cursor: grab; background: #f1f5f9; border: 1px solid #cbd5e1; width: 42px; height: 42px;" title="মাউস দিয়ে চেপে উপরে বা নিচে টেনে সাজান">
                                                <i class="fa fa-ellipsis-v fa-lg me-1 text-muted"></i>
                                                <i class="fa fa-ellipsis-v fa-lg text-muted"></i>
                                            </div>
                                            <span class="badge bg-light text-dark border me-3 py-2 px-3 font-size-14 fw-bold sec-index-badge">
                                                #{{ $index + 1 }}
                                            </span>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark font-size-15 d-flex align-items-center gap-2">
                                                    {{ $sec->title_bn }} 
                                                    <span class="text-muted fw-normal font-size-13">({{ $sec->title_en }})</span>
                                                    @if($sec->is_enabled_web && $sec->is_enabled_app)
                                                        <span class="badge bg-success-subtle text-success border border-success font-size-11 rounded-pill px-2">Web + App Live</span>
                                                    @elseif($sec->is_enabled_web)
                                                        <span class="badge bg-info-subtle text-info border border-info font-size-11 rounded-pill px-2">Web Only</span>
                                                    @elseif($sec->is_enabled_app)
                                                        <span class="badge bg-primary-subtle text-primary border border-primary font-size-11 rounded-pill px-2">App Only</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger border border-danger font-size-11 rounded-pill px-2">লুকানো (Hidden)</span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">{{ $sec->subtitle_bn ?: 'উপশিরোনাম নেই' }}</small>
                                            </div>
                                        </div>

                                        <!-- Controls: Switches & Reorder -->
                                        <div class="d-flex align-items-center flex-wrap gap-3">
                                            <!-- Web Switch -->
                                            <div class="d-flex align-items-center gap-1">
                                                <small class="text-muted fw-bold font-size-11"><i class="fa fa-globe text-info me-1"></i>ওয়েবসাইট:</small>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input sec-web-toggle" type="checkbox" role="switch" {{ $sec->is_enabled_web ? 'checked' : '' }} onchange="saveSectionsOrderImmediate(true)">
                                                </div>
                                            </div>

                                            <!-- App Switch -->
                                            <div class="d-flex align-items-center gap-1">
                                                <small class="text-muted fw-bold font-size-11"><i class="fa fa-mobile text-primary me-1"></i>অ্যাপস:</small>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input sec-app-toggle" type="checkbox" role="switch" {{ $sec->is_enabled_app ? 'checked' : '' }} onchange="saveSectionsOrderImmediate(true)">
                                                </div>
                                            </div>

                                            <!-- Move buttons -->
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary" onclick="moveSectionCardUp(this)" title="উপরে উঠান"><i class="fa fa-arrow-up"></i></button>
                                                <button type="button" class="btn btn-outline-secondary" onclick="moveSectionCardDown(this)" title="নিচে নামান"><i class="fa fa-arrow-down"></i></button>
                                                <button type="button" class="btn btn-outline-info" onclick='openSectionSettingsModal({{ $sec->id }}, @json($sec->title_bn), @json($sec->title_en), @json($sec->subtitle_bn), @json($sec->section_settings))' title="সেটিংস কাস্টমাইজ"><i class="fa fa-gear"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 🎨 TAB 4: Global Colors, Header, Footer & Typography -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade" id="tab-styles" role="tabpanel">
                <div class="row g-4">
                    <!-- Global Colors -->
                    <div class="col-lg-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-paint-brush text-warning me-2"></i>কালার প্যালেট কাস্টমাইজেশন</h5>
                            </div>
                            <div class="card-body p-4">
                                @php
                                    $colors = $config->global_colors ?: [];
                                @endphp
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold font-size-13">হেডার ব্যাকগ্রাউন্ড কালার</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color border-0 p-1" id="global_header_bg_picker" value="{{ $colors['header_bg'] ?? '#ea580c' }}" onchange="document.getElementById('global_header_bg').value = this.value">
                                            <input type="text" class="form-control fw-bold" id="global_header_bg" value="{{ $colors['header_bg'] ?? '#ea580c' }}" onchange="document.getElementById('global_header_bg_picker').value = this.value">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold font-size-13">বাটন কালার (অর্ডার করুন)</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color border-0 p-1" id="global_button_color_picker" value="{{ $colors['button'] ?? '#007e8a' }}" onchange="document.getElementById('global_button_color').value = this.value">
                                            <input type="text" class="form-control fw-bold" id="global_button_color" value="{{ $colors['button'] ?? '#007e8a' }}" onchange="document.getElementById('global_button_color_picker').value = this.value">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold font-size-13">প্রাইমারি কালার (Primary)</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color border-0 p-1" id="global_primary_picker" value="{{ $colors['primary'] ?? '#ea580c' }}" onchange="document.getElementById('global_primary').value = this.value">
                                            <input type="text" class="form-control fw-bold" id="global_primary" value="{{ $colors['primary'] ?? '#ea580c' }}" onchange="document.getElementById('global_primary_picker').value = this.value">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold font-size-13">সেকেন্ডারি কালার (Secondary)</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color border-0 p-1" id="global_secondary_picker" value="{{ $colors['secondary'] ?? '#007e8a' }}" onchange="document.getElementById('global_secondary').value = this.value">
                                            <input type="text" class="form-control fw-bold" id="global_secondary" value="{{ $colors['secondary'] ?? '#007e8a' }}" onchange="document.getElementById('global_secondary_picker').value = this.value">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold font-size-13">ফুটার ব্যাকগ্রাউন্ড কালার</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color border-0 p-1" id="global_footer_bg_picker" value="{{ $colors['footer_bg'] ?? '#0f172a' }}" onchange="document.getElementById('global_footer_bg').value = this.value">
                                            <input type="text" class="form-control fw-bold" id="global_footer_bg" value="{{ $colors['footer_bg'] ?? '#0f172a' }}" onchange="document.getElementById('global_footer_bg_picker').value = this.value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Header & Layout Options -->
                    <div class="col-lg-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-header text-info me-2"></i>হেডার লেআউট ও উপাদান</h5>
                            </div>
                            <div class="card-body p-4">
                                @php
                                    $hdr = $config->header_config ?: [];
                                @endphp
                                <div class="mb-3">
                                    <label class="form-label fw-bold font-size-13">হেডার লেআউট স্টাইল</label>
                                    <select class="form-select fw-bold" id="header_layout_select">
                                        <option value="layout_1_standard" {{ ($hdr['layout'] ?? '') === 'layout_1_standard' ? 'selected' : '' }}>লেআউট ১: লোগো বামে + সার্চ মাঝখানে + অ্যাকশন ডানে (Standard)</option>
                                        <option value="layout_2_center_logo" {{ ($hdr['layout'] ?? '') === 'layout_2_center_logo' ? 'selected' : '' }}>লেআউট ২: লোগো সেন্টারে + সার্চ নিচে (Center Logo)</option>
                                        <option value="layout_3_compact" {{ ($hdr['layout'] ?? '') === 'layout_3_compact' ? 'selected' : '' }}>লেআউট ৩: কম্প্যাক্ট হেডার (Compact Minimal)</option>
                                        <option value="layout_4_fullwidth" {{ ($hdr['layout'] ?? '') === 'layout_4_fullwidth' ? 'selected' : '' }}>লেআউট ৪: ফুল-উইডথ হেডার (Full Width Modern)</option>
                                        <option value="layout_5_floating" {{ ($hdr['layout'] ?? '') === 'layout_5_floating' ? 'selected' : '' }}>লেআউট ৫: ফ্লোটিং কার্ড হেডার (Floating Pill)</option>
                                    </select>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="header_show_search" {{ ($hdr['show_search'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold font-size-13" for="header_show_search">সার্চ বার প্রদর্শন</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="header_show_dark" {{ ($hdr['show_dark_toggle'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold font-size-13" for="header_show_dark">ডার্ক মোড টগল বাটন</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="header_is_sticky" {{ ($hdr['is_sticky'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold font-size-13" for="header_is_sticky">স্টিকি হেডার (Sticky Navbar)</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="header_show_notice" {{ ($hdr['show_notice'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold font-size-13" for="header_show_notice">টপ নোটিশ বার</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold font-size-13">টপ নোটিশ বার্তা:</label>
                                    <input type="text" class="form-control" id="header_notice_text" value="{{ $hdr['notice_text'] ?? 'e-Krishi Sheba প্ল্যাটফর্মে আপনাকে স্বাগতম।' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm" onclick="saveGlobalStylesForm()">
                        <i class="fa fa-save me-1"></i> গ্লোবাল স্টাইল ও হেডার সেটিংস সেভ করুন
                    </button>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 📱 TAB 5: Mobile App UI Configuration -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade" id="tab-mobile" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-mobile text-info me-2"></i>মোবাইল অ্যাপস UI ও বটম নেভিগেশন</h5>
                        <small class="text-muted">মোবাইল অ্যাপ্লিকেশনের বটম বার আইটেম এবং মোবাইল-নির্দিষ্ট ডিসপ্লে পরিচালনা করুন।</small>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info border-0 shadow-xs rounded-3">
                            <i class="fa fa-info-circle me-2"></i> <strong>সেন্ট্রালাইজড সিংক্রোনাইজেশন:</strong> হোমপেজ সেকশন এবং থিম কালার ওয়েবসাইট ও মোবাইল অ্যাপে একসাথেই রিয়েলটাইমে কাজ করে।
                        </div>

                        <h6 class="fw-bold text-dark mb-3">অ্যাপস বটম নেভিগেশন বার আইটেমস (Bottom Navigation):</h6>
                        <div class="row g-3">
                            <div class="col-md-4 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <i class="fa fa-home fs-4 text-primary mb-2"></i>
                                    <h6 class="fw-bold mb-0">হোম (Home)</h6>
                                    <small class="text-muted">লকড / ডিফল্ট</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <i class="fa fa-line-chart fs-4 text-success mb-2"></i>
                                    <h6 class="fw-bold mb-0">স্টক মার্কেট (Stock)</h6>
                                    <small class="text-success">সক্রিয়</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <i class="fa fa-gift fs-4 text-warning mb-2"></i>
                                    <h6 class="fw-bold mb-0">প্যাকেজ (Packages)</h6>
                                    <small class="text-success">সক্রিয়</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <i class="fa fa-id-card fs-4 text-danger mb-2"></i>
                                    <h6 class="fw-bold mb-0">মেম্বারশিপ (Cards)</h6>
                                    <small class="text-success">সক্রিয়</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <i class="fa fa-phone fs-4 text-info mb-2"></i>
                                    <h6 class="fw-bold mb-0">যোগাযোগ (Contact)</h6>
                                    <small class="text-success">সক্রিয়</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 📜 TAB 6: Version History & Instant 1-Click Rollback -->
            <!-- ========================================================================= -->
            <div class="tab-pane fade" id="tab-versions" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-history text-secondary me-2"></i>ভার্সন ইতিহাস ও রোলব্যাক</h5>
                            <small class="text-muted">যেকোনো সময় পূর্বের যেকোনো কনফিগারেশনে ১-ক্লিকে ফিরে যান।</small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ভার্সন কোড</th>
                                        <th>টেমপ্লেট</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>মন্তব্য / নোট</th>
                                        <th>পাবলিশের তারিখ</th>
                                        <th class="text-center">রোলব্যাক অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($versions as $ver)
                                    <tr>
                                        <td><span class="badge bg-dark font-size-12 px-2 py-1">{{ $ver->version_code }}</span></td>
                                        <td><strong>{{ $ver->template ? $ver->template->name_bn : 'N/A' }}</strong></td>
                                        <td>
                                            @if($ver->status === 'published')
                                                <span class="badge bg-success">পাবলিশড</span>
                                            @elseif($ver->status === 'draft')
                                                <span class="badge bg-warning text-dark">ড্রাফট</span>
                                            @else
                                                <span class="badge bg-secondary">আর্কাইভড</span>
                                            @endif
                                        </td>
                                        <td>{{ $ver->change_summary ?: 'কোনো মন্তব্য নেই' }}</td>
                                        <td>{{ $ver->created_at->format('d M Y, h:i A') }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="rollbackVersion({{ $ver->id }}, '{{ $ver->version_code }}')">
                                                <i class="fa fa-rotate-left me-1"></i> রোলব্যাক করুন
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">এখনো কোনো পুরনো ভার্সন সংরক্ষিত হয়নি।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- ========================================================================= -->
<!-- 📱 MODAL: Multi-Device Live Preview Modal -->
<!-- ========================================================================= -->
<div class="modal fade" id="livePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0 bg-black text-white py-2 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success px-3 py-2 rounded-pill font-size-12">
                        <i class="fa fa-eye me-1"></i> লাইভ ড্রাফট প্রিভিউ
                    </span>
                    <span class="text-muted small">রিয়েলটাইম ডিভাইস রেসপন্সিভ প্রিভিউ</span>
                </div>

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-light active" id="btnPreviewDesktop" onclick="switchPreviewDevice('desktop')">
                        <i class="fa fa-desktop me-1"></i> ডেস্কটপ
                    </button>
                    <button type="button" class="btn btn-outline-light" id="btnPreviewTablet" onclick="switchPreviewDevice('tablet')">
                        <i class="fa fa-tablet me-1"></i> ট্যাবলেট
                    </button>
                    <button type="button" class="btn btn-outline-light" id="btnPreviewMobile" onclick="switchPreviewDevice('mobile')">
                        <i class="fa fa-mobile me-1"></i> মোবাইল
                    </button>
                </div>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex align-items-center justify-content-center bg-secondary" style="overflow: hidden;">
                <iframe id="livePreviewIframe" src="{{ url('/?preview_theme=draft') }}" style="width: 100%; height: 100%; border: none; transition: all 0.3s ease;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 🚀 MODAL: Publish Modal -->
<!-- ========================================================================= -->
<div class="modal fade" id="publishConfigModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-success text-white py-3 border-0">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-paper-plane me-2"></i>লাইভ পাবলিশ নিশ্চিতকরণ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-dark fw-bold mb-2">আপনি কি বর্তমান ডিজাইন ও সেটিংস লাইভ ওয়েবসাইটে ও মোবাইল অ্যাপে কার্যকর করতে চান?</p>
                <p class="text-muted small mb-3">এটি পাবলিশ করার সাথে সাথে একটি স্বয়ংক্রিয় ব্যাকআপ ভার্সন সংরক্ষিত হবে, যাতে প্রয়োজনে যেকোনো সময় পূর্ববর্তী ডিজাইনে রোলব্যাক করতে পারেন।</p>
                <div class="mb-3">
                    <label class="form-label fw-bold font-size-12">পাবলিশ নোট / মন্তব্য (ঐচ্ছিক):</label>
                    <input type="text" class="form-control" id="publish_note_input" placeholder="যেমন: নতুন টেমপ্লেট ও সেকশন রিঅর্ডার">
                </div>
            </div>
            <div class="modal-footer border-0 p-3 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" onclick="executePublish()">
                    <i class="fa fa-check me-1"></i> হ্যাঁ, পাবলিশ করুন
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- ⚙️ MODAL: Section Settings Modal (Full Customization) -->
<!-- ========================================================================= -->
<div class="modal fade" id="sectionSettingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-light py-3 border-bottom">
                <h5 class="modal-title fw-bold mb-0 text-dark"><i class="fa fa-gear text-info me-2"></i>সেকশন সেটিংস ও ভিজ্যুয়াল কাস্টমাইজেশন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="edit_section_id">
                <div class="row g-3">
                    <div class="col-md-6 col-12">
                        <label class="form-label fw-bold font-size-13">সেকশন শিরোনাম (বাংলা):</label>
                        <input type="text" class="form-control" id="edit_sec_title_bn">
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label fw-bold font-size-13">সেকশন শিরোনাম (English):</label>
                        <input type="text" class="form-control" id="edit_sec_title_en">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold font-size-13">উপশিরোনাম (Subtitle):</label>
                        <textarea class="form-control" id="edit_sec_subtitle_bn" rows="2"></textarea>
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label fw-bold font-size-13">সেকশন ব্যাকগ্রাউন্ড কালার:</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color border-0 p-1" id="edit_sec_bg_picker" value="#ffffff" onchange="document.getElementById('edit_sec_bg').value = this.value">
                            <input type="text" class="form-control" id="edit_sec_bg" value="#ffffff" onchange="document.getElementById('edit_sec_bg_picker').value = this.value">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label fw-bold font-size-13">টেক্সট কালার:</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color border-0 p-1" id="edit_sec_text_color_picker" value="#0f172a" onchange="document.getElementById('edit_sec_text_color').value = this.value">
                            <input type="text" class="form-control" id="edit_sec_text_color" value="#0f172a" onchange="document.getElementById('edit_sec_text_color_picker').value = this.value">
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label fw-bold font-size-13">প্রদর্শন লিমিট (আইটেম সংখ্যা):</label>
                        <select class="form-select" id="edit_sec_item_limit">
                            <option value="4">৪টি আইটেম</option>
                            <option value="6">৬টি আইটেম</option>
                            <option value="8">৮টি আইটেম</option>
                            <option value="10" selected>১০টি আইটেম</option>
                            <option value="12">১২টি আইটেম</option>
                            <option value="16">১৬টি আইটেম</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label fw-bold font-size-13">ডেস্কটপ গ্রিড কলাম:</label>
                        <select class="form-select" id="edit_sec_grid_cols">
                            <option value="2">২ কলাম</option>
                            <option value="3">৩ কলাম</option>
                            <option value="4">৪ কলাম</option>
                            <option value="5">৫ কলাম</option>
                            <option value="6" selected>৬ কলাম (Compact Grid)</option>
                        </select>
                    </div>

                    <div class="col-12 pt-2 border-top">
                        <h6 class="fw-bold font-size-13 text-dark mb-2">উপাদান অন/অফ নিয়ন্ত্রণ:</h6>
                        <div class="row g-2">
                            <div class="col-md-4 col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_sec_show_price" checked>
                                    <label class="form-check-label font-size-12 fw-bold" for="edit_sec_show_price">মূল্য প্রদর্শন</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_sec_show_order_btn" checked>
                                    <label class="form-check-label font-size-12 fw-bold" for="edit_sec_show_order_btn">অর্ডার বাটন প্রদর্শন</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="edit_sec_show_view_all" checked>
                                    <label class="form-check-label font-size-12 fw-bold" for="edit_sec_show_view_all">'View All' বাটন প্রদর্শন</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" onclick="executeSaveSectionSettings()">
                    <i class="fa fa-save me-1"></i> পরিবর্তন সংরক্ষণ
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- ➕ MODAL: Add / Edit Menu Item Modal -->
<!-- ========================================================================= -->
<div class="modal fade" id="menuItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-light py-3 border-bottom">
                <h5 class="modal-title fw-bold mb-0 text-dark"><i class="fa fa-plus text-primary me-2"></i>মেনু আইটেম যোগ / পরিবর্তন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="edit_menu_item_id">
                <input type="hidden" id="edit_menu_parent_menu_id" value="{{ $headerMenu->id ?? 1 }}">
                <div class="mb-3">
                    <label class="form-label fw-bold font-size-13">মেনুর নাম (বাংলা):</label>
                    <input type="text" class="form-control" id="menu_title_bn" placeholder="যেমন: নতুন অফার">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold font-size-13">মেনুর নাম (English):</label>
                    <input type="text" class="form-control" id="menu_title_en" placeholder="e.g. New Offers">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold font-size-13">ইউআরএল / রুট (URL Path):</label>
                    <input type="text" class="form-control" id="menu_url" placeholder="যেমন: /offers বা /our-products">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold font-size-13">আইকন ক্লাস (FontAwesome):</label>
                        <input type="text" class="form-control" id="menu_icon" placeholder="যেমন: fa-gift">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold font-size-13">হাইলাইট ব্যাজ (ঐচ্ছিক):</label>
                        <input type="text" class="form-control" id="menu_badge" placeholder="যেমন: New, Hot">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" onclick="executeSaveMenuItem()">
                    <i class="fa fa-check me-1"></i> সংরক্ষণ করুন
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- SortableJS for Smooth Drag and Drop with Mouse -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';

    document.addEventListener('DOMContentLoaded', function () {
        // =========================================================================
        // 🖱️ Initialize Mouse Drag & Drop for Navigation Menu
        // =========================================================================
        const menuListEl = document.getElementById('sortableMenuList');
        if (menuListEl && typeof Sortable !== 'undefined') {
            new Sortable(menuListEl, {
                handle: '.menu-drag-handle',
                animation: 250,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function () {
                    updateMenuRowIndices();
                    saveMenuOrderImmediate(true);
                }
            });
        }

        // =========================================================================
        // 🖱️ Initialize Mouse Drag & Drop for Homepage Sections
        // =========================================================================
        const sectionsListEl = document.getElementById('sortableSectionsList');
        if (sectionsListEl && typeof Sortable !== 'undefined') {
            new Sortable(sectionsListEl, {
                handle: '.section-drag-handle',
                animation: 250,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function () {
                    updateSectionIndices();
                    saveSectionsOrderImmediate(true);
                }
            });
        }
    });

    // =========================================================================
    // 🌟 Template Activation Handler
    // =========================================================================
    function activateTemplate(slug, nameBn) {
        Swal.fire({
            title: 'টেমপ্লেট পরিবর্তন নিশ্চিতকরণ',
            text: `আপনি কি '${nameBn}' সক্রিয় করতে চান? এটি ওয়েবসাইট ও মোবাইল অ্যাপে তাৎক্ষণিকভাবে প্রয়োগ হবে।`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'হ্যাঁ, সক্রিয় করুন',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("admin.appearance.template.activate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ template_slug: slug })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('সফল!', data.message, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('ত্রুটি!', data.message || 'টেমপ্লেট সক্রিয় করা সম্ভব হয়নি।', 'error');
                    }
                })
                .catch(err => Swal.fire('ত্রুটি!', err.message, 'error'));
            }
        });
    }

    function previewTemplate(slug) {
        openLivePreviewModal();
    }

    // =========================================================================
    // 🗂 Navigation Menu Reorder (Move Up / Down & Save)
    // =========================================================================
    function moveRowUp(btn) {
        const row = btn.closest('tr');
        const prev = row.previousElementSibling;
        if (prev) {
            row.parentNode.insertBefore(row, prev);
            updateMenuRowIndices();
            saveMenuOrderImmediate(true);
        }
    }

    function moveRowDown(btn) {
        const row = btn.closest('tr');
        const next = row.nextElementSibling;
        if (next) {
            row.parentNode.insertBefore(next, row);
            updateMenuRowIndices();
            saveMenuOrderImmediate(true);
        }
    }

    function updateMenuRowIndices() {
        document.querySelectorAll('#sortableMenuList tr').forEach((row, idx) => {
            const badge = row.querySelector('.sort-number');
            if (badge) badge.textContent = (idx + 1);
        });
    }

    function saveMenuOrderImmediate(showNotification = false) {
        const items = [];
        document.querySelectorAll('#sortableMenuList tr').forEach((row, idx) => {
            const id = row.getAttribute('data-id');
            const showWeb = row.querySelector('.menu-web-toggle').checked;
            const showApp = row.querySelector('.menu-app-toggle').checked;
            items.push({
                id: id,
                sort_order: idx + 1,
                show_on_web: showWeb,
                show_on_app: showApp,
            });
        });

        fetch('{{ route("admin.appearance.menu.order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ items: items })
        })
        .then(r => r.json())
        .then(data => {
            if (showNotification && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'সংরক্ষিত!',
                    text: data.message,
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        });
    }

    function openAddMenuModal() {
        document.getElementById('edit_menu_item_id').value = '';
        document.getElementById('menu_title_bn').value = '';
        document.getElementById('menu_title_en').value = '';
        document.getElementById('menu_url').value = '';
        document.getElementById('menu_icon').value = 'fa-link';
        document.getElementById('menu_badge').value = '';
        new bootstrap.Modal(document.getElementById('menuItemModal')).show();
    }

    function executeSaveMenuItem() {
        const payload = {
            id: document.getElementById('edit_menu_item_id').value || null,
            menu_id: document.getElementById('edit_menu_parent_menu_id').value,
            title_bn: document.getElementById('menu_title_bn').value,
            title_en: document.getElementById('menu_title_en').value,
            url: document.getElementById('menu_url').value,
            icon: document.getElementById('menu_icon').value,
            badge_text: document.getElementById('menu_badge').value,
        };

        if (!payload.title_bn || !payload.title_en) {
            Swal.fire('সতর্কতা', 'দয়া করে বাংলা ও ইংরেজি নাম প্রদান করুন।', 'warning');
            return;
        }

        fetch('{{ route("admin.appearance.menu.save_item") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('menuItemModal')).hide();
                Swal.fire('সফল!', data.message, 'success').then(() => window.location.reload());
            }
        });
    }

    function deleteMenuItem(id) {
        Swal.fire({
            title: 'আইটেম মুছে ফেলতে চান?',
            text: 'এই মেনু আইটেমটি স্থায়ীভাবে ডিলিট হয়ে যাবে।',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'হ্যাঁ, মুছুন',
            cancelButtonText: 'বাতিল'
        }).then((res) => {
            if (res.isConfirmed) {
                fetch(`{{ url('admin/appearance-studio/menu/delete-item') }}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('মুছে ফেলা হয়েছে!', data.message, 'success').then(() => window.location.reload());
                    }
                });
            }
        });
    }

    // =========================================================================
    // 🧩 Homepage Sections Reorder (Move Up / Down & Save)
    // =========================================================================
    function moveSectionCardUp(btn) {
        const col = btn.closest('.section-draggable-item');
        const prev = col.previousElementSibling;
        if (prev) {
            col.parentNode.insertBefore(col, prev);
            updateSectionIndices();
            saveSectionsOrderImmediate(true);
        }
    }

    function moveSectionCardDown(btn) {
        const col = btn.closest('.section-draggable-item');
        const next = col.nextElementSibling;
        if (next) {
            col.parentNode.insertBefore(next, col);
            updateSectionIndices();
            saveSectionsOrderImmediate(true);
        }
    }

    function updateSectionIndices() {
        document.querySelectorAll('#sortableSectionsList .section-draggable-item').forEach((col, idx) => {
            const badge = col.querySelector('.sec-index-badge');
            if (badge) badge.textContent = '#' + (idx + 1);
        });
    }

    function saveSectionsOrderImmediate(showNotification = false) {
        const sections = [];
        document.querySelectorAll('#sortableSectionsList .section-draggable-item').forEach((col, idx) => {
            const id = col.getAttribute('data-id');
            const showWeb = col.querySelector('.sec-web-toggle').checked;
            const showApp = col.querySelector('.sec-app-toggle').checked;
            sections.push({
                id: id,
                sort_order_web: idx + 1,
                sort_order_app: idx + 1,
                is_enabled_web: showWeb,
                is_enabled_app: showApp,
            });
        });

        fetch('{{ route("admin.appearance.sections.order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ sections: sections })
        })
        .then(r => r.json())
        .then(data => {
            if (showNotification && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'সংরক্ষিত!',
                    text: data.message,
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        });
    }

    function openSectionSettingsModal(id, titleBn, titleEn, subtitleBn, currentSettings) {
        document.getElementById('edit_section_id').value = id;
        document.getElementById('edit_sec_title_bn').value = titleBn || '';
        document.getElementById('edit_sec_title_en').value = titleEn || '';
        document.getElementById('edit_sec_subtitle_bn').value = subtitleBn || '';

        const s = currentSettings || {};
        document.getElementById('edit_sec_bg').value = s.background_color || '#ffffff';
        document.getElementById('edit_sec_bg_picker').value = s.background_color || '#ffffff';
        document.getElementById('edit_sec_text_color').value = s.text_color || '#0f172a';
        document.getElementById('edit_sec_text_color_picker').value = s.text_color || '#0f172a';
        document.getElementById('edit_sec_item_limit').value = s.item_limit || '10';
        document.getElementById('edit_sec_grid_cols').value = s.grid_cols || '6';

        document.getElementById('edit_sec_show_price').checked = s.show_price !== false;
        document.getElementById('edit_sec_show_order_btn').checked = s.show_order_btn !== false;
        document.getElementById('edit_sec_show_view_all').checked = s.show_view_all !== false;

        new bootstrap.Modal(document.getElementById('sectionSettingsModal')).show();
    }

    function executeSaveSectionSettings() {
        const id = document.getElementById('edit_section_id').value;
        const payload = {
            title_bn: document.getElementById('edit_sec_title_bn').value,
            title_en: document.getElementById('edit_sec_title_en').value,
            subtitle_bn: document.getElementById('edit_sec_subtitle_bn').value,
            settings: {
                background_color: document.getElementById('edit_sec_bg').value,
                text_color: document.getElementById('edit_sec_text_color').value,
                item_limit: document.getElementById('edit_sec_item_limit').value,
                grid_cols: document.getElementById('edit_sec_grid_cols').value,
                show_price: document.getElementById('edit_sec_show_price').checked,
                show_order_btn: document.getElementById('edit_sec_show_order_btn').checked,
                show_view_all: document.getElementById('edit_sec_show_view_all').checked,
            }
        };

        fetch(`{{ url('admin/appearance-studio/sections') }}/${id}/settings`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('sectionSettingsModal')).hide();
                Swal.fire('সংরক্ষিত!', data.message, 'success').then(() => window.location.reload());
            }
        });
    }

    // =========================================================================
    // 🎨 Global Styles Form Save (Draft / Publish)
    // =========================================================================
    function saveGlobalStylesForm() {
        const colors = {
            header_bg: document.getElementById('global_header_bg').value,
            button: document.getElementById('global_button_color').value,
            primary: document.getElementById('global_primary').value,
            secondary: document.getElementById('global_secondary').value,
            footer_bg: document.getElementById('global_footer_bg').value,
        };

        const headerConfig = {
            layout: document.getElementById('header_layout_select').value,
            notice_text: document.getElementById('header_notice_text').value,
            show_search: document.getElementById('header_show_search').checked,
            show_dark_toggle: document.getElementById('header_show_dark').checked,
            is_sticky: document.getElementById('header_is_sticky').checked,
            show_notice: document.getElementById('header_show_notice').checked,
        };

        const payload = {
            action_type: 'draft',
            global_colors: colors,
            header_config: headerConfig,
        };

        fetch('{{ route("admin.appearance.save_settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'সংরক্ষিত!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    function saveDraftSettings() {
        saveGlobalStylesForm();
    }

    // =========================================================================
    // 🔍 Live Preview Modal Switcher
    // =========================================================================
    function openLivePreviewModal() {
        new bootstrap.Modal(document.getElementById('livePreviewModal')).show();
        switchPreviewDevice('desktop');
    }

    function switchPreviewDevice(device) {
        const iframe = document.getElementById('livePreviewIframe');
        document.querySelectorAll('#livePreviewModal .btn-group button').forEach(b => b.classList.remove('active'));

        if (device === 'mobile') {
            document.getElementById('btnPreviewMobile').classList.add('active');
            iframe.style.width = '375px';
            iframe.style.height = '667px';
            iframe.style.borderRadius = '24px';
            iframe.style.boxShadow = '0 10px 40px rgba(0,0,0,0.5)';
        } else if (device === 'tablet') {
            document.getElementById('btnPreviewTablet').classList.add('active');
            iframe.style.width = '768px';
            iframe.style.height = '90%';
            iframe.style.borderRadius = '16px';
            iframe.style.boxShadow = '0 10px 40px rgba(0,0,0,0.5)';
        } else {
            document.getElementById('btnPreviewDesktop').classList.add('active');
            iframe.style.width = '100%';
            iframe.style.height = '100%';
            iframe.style.borderRadius = '0';
            iframe.style.boxShadow = 'none';
        }
    }

    // =========================================================================
    // 🚀 Publish Configuration
    // =========================================================================
    function openPublishModal() {
        new bootstrap.Modal(document.getElementById('publishConfigModal')).show();
    }

    function executePublish() {
        const note = document.getElementById('publish_note_input').value;
        const payload = {
            action_type: 'publish',
            publish_note: note,
        };

        fetch('{{ route("admin.appearance.save_settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('publishConfigModal')).hide();
                Swal.fire({
                    icon: 'success',
                    title: 'অভিনন্দন!',
                    text: data.message,
                    confirmButtonText: 'ঠিক আছে'
                }).then(() => window.location.reload());
            }
        });
    }

    // =========================================================================
    // ⏳ Rollback to past version
    // =========================================================================
    function rollbackVersion(id, verCode) {
        Swal.fire({
            title: 'রোলব্যাক নিশ্চিতকরণ',
            text: `আপনি কি ভার্সন '${verCode}'-এ ফিরে যেতে চান? বর্তমান সক্রিয় ডিজাইন এই ভার্সনে প্রতিস্থাপিত হবে।`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'হ্যাঁ, রোলব্যাক করুন',
            cancelButtonText: 'বাতিল'
        }).then((res) => {
            if (res.isConfirmed) {
                fetch(`{{ url('admin/appearance-studio/rollback') }}/${id}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('রোলব্যাক সম্পন্ন!', data.message, 'success').then(() => window.location.reload());
                    }
                });
            }
        });
    }
</script>
@endsection
