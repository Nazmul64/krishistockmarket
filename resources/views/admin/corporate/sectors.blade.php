@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-line-chart text-warning me-2"></i> বিনিয়োগ খাতসমূহ (10 Investment Sectors)</h4>
                    <p class="text-muted small mb-0">১০টি নির্ধারিত বিনিয়োগ খাতের সীমা, মেয়াদ, লাভ-লোকসান বণ্টন নীতিমালা ও নিয়ন্ত্রণ।</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>নং</th>
                                <th>খাতের নাম (বাংলা ও ইংরেজি)</th>
                                <th>বিনিয়োগের সীমা</th>
                                <th>মেয়াদ</th>
                                <th>সক্রিয় প্রকল্প</th>
                                <th>লাভ-লোকসান বণ্টন নীতি</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sectors as $sec)
                            <tr>
                                <td class="fw-bold">{{ $sec->sector_no }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $sec->name_bn }}</div>
                                    <small class="text-muted">{{ $sec->name_en }}</small>
                                </td>
                                <td><span class="badge bg-success-subtle text-success fw-bold font-size-12">{{ $sec->investment_range }}</span></td>
                                <td>{{ $sec->duration }}</td>
                                <td><span class="badge bg-light text-dark">{{ $sec->projects_count }} টি প্রকল্প</span></td>
                                <td><small class="text-muted">{{ Str::limit($sec->profit_loss_policy, 45) }}</small></td>
                                <td>
                                    <span class="badge {{ $sec->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $sec->is_active ? 'সক্রিয়' : 'বন্ধ' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-outline-primary btn-xs rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#editSectorModal{{ $sec->id }}">
                                        <i class="fa fa-edit"></i> এডিট
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Sector Modal -->
                            <div class="modal fade" id="editSectorModal{{ $sec->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark">খাত নং {{ $sec->sector_no }}: তথ্য হালনাগাদ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.corporate.sectors.update', $sec->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-muted">খাতের নাম (বাংলা)</label>
                                                        <input type="text" name="name_bn" class="form-control" value="{{ $sec->name_bn }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-semibold text-muted">খাতের নাম (ইংরেজি)</label>
                                                        <input type="text" name="name_en" class="form-control" value="{{ $sec->name_en }}" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold text-muted">বিনিয়োগ সীমা টেক্সট</label>
                                                        <input type="text" name="investment_range" class="form-control" value="{{ $sec->investment_range }}" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold text-muted">সর্বনিম্ন পরিমাণ (টাকা)</label>
                                                        <input type="number" name="min_amount" class="form-control" value="{{ $sec->min_amount }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold text-muted">মেয়াদ টেক্সট</label>
                                                        <input type="text" name="duration" class="form-control" value="{{ $sec->duration }}" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold text-muted">সংক্ষিপ্ত বিবরণ</label>
                                                        <textarea name="description" class="form-control" rows="2">{{ $sec->description }}</textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-semibold text-muted">লাভ-লোকসান বণ্টন নীতি</label>
                                                        <textarea name="profit_loss_policy" class="form-control" rows="2">{{ $sec->profit_loss_policy }}</textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="secActive{{ $sec->id }}" {{ $sec->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="secActive{{ $sec->id }}">এই খাতটি বিনিয়োগের জন্য উন্মুক্ত রাখুন</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection

