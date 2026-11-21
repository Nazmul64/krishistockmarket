@extends('layouts.backend.app')

@section('title', 'কর্মচারী তথ্য সম্পাদনা - ' . $employee->name)

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-pencil-alt text-primary me-2"></i> কর্মচারী তথ্য সম্পাদনা: {{ $employee->name }}
            </h4>
            <p class="text-muted small mb-0">ID: {{ $employee->employee_id }} | ব্রাঞ্চ: {{ $employee->branch->name ?? '' }}</p>
        </div>
        <div>
            <a href="{{ route('admin.supershop.employee_system.employees.show', $employee->id) }}" class="btn btn-outline-secondary">
                <i class="ti-arrow-left me-1"></i> প্রোফাইলে ফিরে যান
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
            <h6 class="fw-bold mb-2"><i class="ti-alert me-1"></i> ত্রুটিসমূহ:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.supershop.employee_system.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h5 class="fw-bold text-primary border-bottom pb-2 mb-4"><i class="ti-user me-2"></i> ব্যক্তিগত ও যোগাযোগের তথ্য</h5>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-bold">কর্মচারীর পূর্ণ নাম <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Employee ID</label>
                    <input type="text" class="form-control font-monospace bg-light" value="{{ $employee->employee_id }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label">প্রোফাইল ছবি পরিবর্তন</label>
                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                </div>

                <div class="col-md-6">
                    <label class="form-label">পিতার নাম</label>
                    <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $employee->father_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">মাতার নাম</label>
                    <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $employee->mother_name) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">জন্ম তারিখ</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($employee->date_of_birth)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">লিঙ্গ</label>
                    <select name="gender" class="form-select">
                        <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>পুরুষ (Male)</option>
                        <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>মহিলা (Female)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">রক্তের গ্রুপ</label>
                    <input type="text" name="blood_group" class="form-control" value="{{ old('blood_group', $employee->blood_group) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">জাতীয়তা</label>
                    <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $employee->nationality) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">NID নম্বর</label>
                    <input type="text" name="nid_number" class="form-control font-monospace" value="{{ old('nid_number', $employee->nid_number) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ইমেইল</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">বর্তমান ঠিকানা</label>
                    <textarea name="present_address" class="form-control" rows="2">{{ old('present_address', $employee->present_address) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">স্থায়ী ঠিকানা</label>
                    <textarea name="permanent_address" class="form-control" rows="2">{{ old('permanent_address', $employee->permanent_address) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h5 class="fw-bold text-success border-bottom pb-2 mb-4"><i class="ti-briefcase me-2"></i> চাকরির পদ ও বেতন তথ্য</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">পদবি (Designation) <span class="text-danger">*</span></label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation', $employee->designation) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">বিভাগ (Department)</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $employee->department) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">চাকরির ধরন</label>
                    <select name="job_type" class="form-select">
                        <option value="Full Time" {{ $employee->job_type == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                        <option value="Part Time" {{ $employee->job_type == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                        <option value="Contract" {{ $employee->job_type == 'Contract' ? 'selected' : '' }}>Contract</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">মূল বেতন (Basic) ৳</label>
                    <input type="number" step="0.01" name="basic_salary" id="basic_salary" class="form-control fw-bold" value="{{ old('basic_salary', $employee->basic_salary) }}" oninput="calculateTotalSalary()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">বাড়ি ভাড়া ৳</label>
                    <input type="number" step="0.01" name="house_rent" id="house_rent" class="form-control" value="{{ old('house_rent', $employee->house_rent) }}" oninput="calculateTotalSalary()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">চিকিৎসা ভাতা ৳</label>
                    <input type="number" step="0.01" name="medical_allowance" id="medical_allowance" class="form-control" value="{{ old('medical_allowance', $employee->medical_allowance) }}" oninput="calculateTotalSalary()">
                </div>
                <div class="col-md-3">
                    <label class="form-label">যাতায়াত ও খাবার ভাতা ৳</label>
                    <input type="number" step="0.01" name="transport_allowance" id="transport_allowance" class="form-control" value="{{ old('transport_allowance', $employee->transport_allowance) }}" oninput="calculateTotalSalary()">
                </div>

                <div class="col-md-4">
                    <label class="form-label">বেতন পরিশোধ মাধ্যম</label>
                    <select name="salary_payment_method" class="form-select">
                        <option value="cash" {{ $employee->salary_payment_method == 'cash' ? 'selected' : '' }}>নগদ (Cash)</option>
                        <option value="bank" {{ $employee->salary_payment_method == 'bank' ? 'selected' : '' }}>ব্যাংক (Bank)</option>
                        <option value="mobile_banking" {{ $employee->salary_payment_method == 'mobile_banking' ? 'selected' : '' }}>মোবাইল ব্যাংকিং (bKash/Nagad)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ব্যাংকের নাম</label>
                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $employee->bank_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">ব্যাংক একাউন্ট নম্বর</label>
                    <input type="text" name="bank_account_no" class="form-control font-monospace" value="{{ old('bank_account_no', $employee->bank_account_no) }}">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white d-flex flex-row justify-content-between align-items-center">
            <span class="text-muted small">পরিবর্তনগুলো সংরক্ষণ করতে নিচের বাটনে চাপ দিন।</span>
            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                <i class="ti-check me-1"></i> আপডেট সংরক্ষণ করুন
            </button>
        </div>
    </form>

        </section>
    </div>
</div>

<script>
function calculateTotalSalary() {
    let basic = parseFloat(document.getElementById('basic_salary').value) || 0;
    let house = parseFloat(document.getElementById('house_rent').value) || 0;
    let medical = parseFloat(document.getElementById('medical_allowance').value) || 0;
    let transport = parseFloat(document.getElementById('transport_allowance').value) || 0;
    let total = basic + house + medical + transport;
}
</script>
@endsection
