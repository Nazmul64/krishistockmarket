@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-key me-2 text-primary"></i> ডাইনামিক পারমিশন কনফিগারেশন</h4>
                    <p class="text-muted mb-0">নির্দিষ্ট ব্যবহারকারীর জন্য বিশেষ অ্যাড-হক (Fine-Grained) পারমিশন নির্ধারণ</p>
                </div>
                <div>
                    <a href="{{ route('admin.rbac.users') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ব্যবহারকারী তালিকায় ফিরুন
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <section class="content">
            <!-- User Summary Box -->
            <div class="box shadow-sm mb-4">
                <div class="box-body d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <img src="{{ $user->avatar ? asset('upload/images/user/'.$user->avatar) : asset('upload/no_image.jpg') }}"
                            class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #e2e8f0;"
                            alt="Avatar">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                            <span class="text-muted small me-2"><i class="fa fa-user me-1"></i> {{ $user->username }}</span>
                            <span class="text-muted small me-2"><i class="fa fa-envelope me-1"></i> {{ $user->email ?? 'N/A' }}</span>
                            <span class="text-muted small"><i class="fa fa-phone me-1"></i> {{ $user->phone }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <span class="text-muted small d-block">অ্যাসাইন করা রোল:</span>
                            <span class="badge bg-primary fs-13">{{ $user->roleRelation->name ?? strtoupper($user->role) }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">পোর্টাল প্রিফিক্স:</span>
                            <span class="badge bg-dark fs-13">{{ $user->getPortalPrefix() }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">স্ট্যাটাস:</span>
                            <span class="badge {{ $user->isActive() ? 'bg-success' : 'bg-danger' }} fs-13">
                                {{ strtoupper($user->status ?? 'ACTIVE') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.rbac.users.permissions.update', $user->id) }}">
                @csrf

                <div class="box shadow-sm mb-4">
                    <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="box-title text-dark">
                                <i class="fa fa-check-square me-2 text-success"></i> গ্র্যানুলার পারমিশন চেকলিস্ট
                            </h4>
                            <span class="text-muted d-block small mt-1">
                                রোলের মাধ্যমে পাওয়া পারমিশনগুলো সবুজ ব্যাজ দিয়ে চিহ্নিত। নিচে যেকোনো নির্দিষ্ট পারমিশনে টিকচিহ্ন দিয়ে অতিরিক্ত অধিকার অর্পণ করুন।
                            </span>
                        </div>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="fa fa-save me-1"></i> পারমিশন সংরক্ষণ করুন
                        </button>
                    </div>

                    <div class="box-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            @foreach($permissionsByModule as $module => $modulePermissions)
                                <div class="col-xl-6 col-lg-6 mb-4">
                                    <div class="border rounded p-3 h-100 bg-white shadow-none">
                                        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                                            <h5 class="fw-bold text-dark mb-0">
                                                <i class="fa fa-folder-open text-warning me-1"></i> {{ $module }}
                                            </h5>
                                            <span class="badge bg-secondary-light text-dark small">
                                                {{ count($modulePermissions) }} Actions
                                            </span>
                                        </div>

                                        <div class="d-flex flex-column gap-2">
                                            @foreach($modulePermissions as $perm)
                                                @php
                                                    $isFromRole = in_array($perm->id, $rolePermissions);
                                                    $isDirect = in_array($perm->id, $directPermissionIds);
                                                @endphp
                                                <div class="d-flex align-items-start justify-content-between p-2 rounded {{ $isFromRole ? 'bg-light' : ($isDirect ? 'bg-primary-light' : '') }}">
                                                    <div class="form-check mb-0">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                                            value="{{ $perm->id }}" id="perm_{{ $perm->id }}"
                                                            {{ ($isFromRole || $isDirect) ? 'checked' : '' }}
                                                            {{ $isFromRole ? 'disabled' : '' }}>
                                                        <label class="form-check-label ms-1" for="perm_{{ $perm->id }}">
                                                            <strong class="text-dark d-block" style="font-size: 13.5px;">{{ $perm->name }}</strong>
                                                            <code class="text-muted small" style="font-size: 11.5px;">{{ $perm->code }}</code>
                                                            @if($perm->description)
                                                                <span class="d-block text-muted" style="font-size: 11px;">{{ $perm->description }}</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                    <div>
                                                        @if($isFromRole)
                                                            <span class="badge bg-success-light text-success fw-bold" style="font-size: 11px;">
                                                                <i class="fa fa-shield me-1"></i> Role Granted
                                                            </span>
                                                        @elseif($isDirect)
                                                            <span class="badge bg-primary fw-bold" style="font-size: 11px;">
                                                                <i class="fa fa-user-plus me-1"></i> Direct Granted
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-end pt-3 border-top">
                            <a href="{{ route('admin.rbac.users') }}" class="btn btn-outline-secondary me-2">বাতিল</a>
                            <button type="submit" class="btn btn-success px-4 py-2 fw-bold">
                                <i class="fa fa-check me-1"></i> পরিবর্তন নিশ্চিত ও সংরক্ষণ করুন
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection

