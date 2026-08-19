@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="ti-key text-warning me-2"></i>এমপ্লয়ী রোল ও পারমিশন কন্ট্রোল</h4>
                    <p class="text-muted mb-0">নির্দিষ্ট এমপ্লয়ীর জন্য কোন কোন মেনু ও ফিচার ড্যাশবোর্ডে দৃশ্যমান হবে তা নির্বাচন করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.employee.index') }}" class="btn btn-secondary btn-sm">
                        <i class="ti-arrow-left me-1"></i> ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti-check-box me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Employee Summary Card -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card bg-primary-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar avatar-lg bg-primary rounded-circle text-white me-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; font-size: 24px;">
                                    {{ strtoupper(substr($employee->name ?? 'E', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold">{{ $employee->name }}</h4>
                                    <span class="badge bg-info text-white">এমপ্লয়ী (Employee)</span>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-2">
                                <span class="text-muted">ইউজারনেম:</span>
                                <strong class="ms-1">{{ $employee->username }}</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted">ফোন নম্বর:</span>
                                <strong class="ms-1">{{ $employee->phone ?? 'N/A' }}</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted">বর্তমান পারমিশন সংখ্যা:</span>
                                <strong class="ms-1 text-success">{{ count($assignedList) }} টি মডিউল সক্রিয়</strong>
                            </div>

                            <div class="alert alert-info border-0 mt-3 mb-0" style="font-size: 13px;">
                                <i class="ti-info-alt me-1"></i> <strong>পারমিশন গাইড:</strong> এখান হতে যে সকল অপশনে টিকচিহ্ন (Check) দিয়ে সংরক্ষণ করা হবে, কেবল সেই মেনুগুলো উক্ত এমপ্লয়ীর ড্যাশবোর্ডে দৃশ্যমান হবে ও তিনি ব্যবহার করতে পারবেন।
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permission Checkbox Grid Form -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card">
                        <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0"><i class="ti-shield text-success me-2"></i>পারমিশন এক্সেস কন্ট্রোল লিস্ট</h4>
                            <div>
                                <button type="button" id="selectAllBtn" class="btn btn-outline-primary btn-xs me-1">
                                    <i class="ti-check me-1"></i> সব সিলেক্ট করুন
                                </button>
                                <button type="button" id="deselectAllBtn" class="btn btn-outline-danger btn-xs">
                                    <i class="ti-close me-1"></i> সব বাতিল করুন
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.employee.permissions.update', $employee->id) }}" method="POST">
                                @csrf

                                <div class="row g-3">
                                    @foreach($allPermissions as $key => $perm)
                                        @php
                                            $isChecked = in_array($key, $assignedList);
                                        @endphp
                                        <div class="col-md-6 col-12">
                                            <div class="card border mb-2 permission-card {{ $isChecked ? 'border-primary shadow-sm bg-light' : '' }}" style="border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-start justify-content-between">
                                                        <div class="d-flex align-items-start">
                                                            <div class="icon-box me-3 rounded p-2 text-center" style="background: rgba(0,0,0,0.04); min-width: 40px;">
                                                                <i class="{{ $perm['icon'] }} fs-4"></i>
                                                            </div>
                                                            <div>
                                                                <label class="fw-bold text-dark mb-1 d-block" style="cursor: pointer;">
                                                                    {{ $perm['title'] }}
                                                                </label>
                                                                <p class="text-muted mb-0" style="font-size: 12px; line-height: 1.3;">
                                                                    {{ $perm['desc'] }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="form-check ms-2">
                                                            <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="{{ $key }}" id="perm_{{ $key }}" {{ $isChecked ? 'checked' : '' }} style="cursor: pointer; transform: scale(1.3);">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="ti-save me-1"></i> পারমিশন সংরক্ষণ করুন
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const checkboxes = document.querySelectorAll('.perm-checkbox');
    const cards = document.querySelectorAll('.permission-card');

    function updateCardHighlight(checkbox) {
        const card = checkbox.closest('.permission-card');
        if (checkbox.checked) {
            card.classList.add('border-primary', 'shadow-sm', 'bg-light');
        } else {
            card.classList.remove('border-primary', 'shadow-sm', 'bg-light');
        }
    }

    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT') {
                const cb = this.querySelector('.perm-checkbox');
                cb.checked = !cb.checked;
                updateCardHighlight(cb);
            }
        });
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            updateCardHighlight(this);
        });
    });

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(cb => {
                cb.checked = true;
                updateCardHighlight(cb);
            });
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(cb => {
                cb.checked = false;
                updateCardHighlight(cb);
            });
        });
    }
});
</script>
@endsection
