@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-primary"><i class="fa fa-shield me-2"></i> Roles & Permissions (রোল ও পারমিশন ক্যাটালগ)</h3>
                    <p class="text-muted mb-0">Role Catalogue, Permission Tree, Clone Role ও Least Privilege এনফোর্সমেন্ট</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.rbac.permissions') }}" class="btn btn-outline btn-info">
                        <i class="fa fa-th me-1"></i> পারমিশন ম্যাট্রিক্স
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন রোল তৈরি করুন (Create Role)
                    </button>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Roles Grid Cards -->
            <div class="row g-3">
                @foreach($roles as $role)
                <div class="col-xl-4 col-md-6 col-12">
                    <div class="box h-100 border-top border-4 {{ $role->is_system ? 'border-primary' : 'border-info' }} pull-up">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h4 class="fw-bold mb-1 text-dark">{{ $role->name }}</h4>
                                    <code>{{ $role->code }}</code>
                                </div>
                                <div class="text-end">
                                    @if($role->is_system)
                                        <span class="badge bg-primary"><i class="fa fa-lock me-1"></i> System Role</span>
                                    @else
                                        <span class="badge bg-info"><i class="fa fa-cogs me-1"></i> Custom</span>
                                    @endif
                                    <span class="badge {{ $role->status === 'active' ? 'bg-success-light text-success' : 'bg-secondary' }} d-block mt-1">
                                        {{ ucfirst($role->status) }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-muted font-size-13 min-h-40 mb-3">
                                {{ $role->description ?? 'কোন বিবরণ দেওয়া হয়নি।' }}
                            </p>

                            <div class="bg-light p-3 rounded mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted font-size-12">ক্যাটাগরি:</span>
                                    <span class="fw-bold">{{ $role->category }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted font-size-12">ডাটা স্কোপ (Scope):</span>
                                    <span class="badge bg-secondary-light text-dark fw-bold">{{ $role->data_scope }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted font-size-12">নির্ধারিত পারমিশন:</span>
                                    <span class="badge bg-info-light text-info fw-bold">{{ $role->permissions_count }} টি এক্সেস</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted font-size-12">সক্রিয় সদস্য (Users):</span>
                                    <span class="badge bg-success-light text-success fw-bold">{{ $role->users_count }} জন</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                                    <i class="fa fa-pencil me-1"></i> পারমিশন কনফিগার
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline btn-secondary" data-bs-toggle="modal" data-bs-target="#cloneRoleModal{{ $role->id }}" title="রোল ক্লোন করুন">
                                        <i class="fa fa-copy"></i> ক্লোন
                                    </button>
                                    @if(!$role->is_system)
                                    <form action="{{ route('admin.rbac.roles.archive', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই রোলটি আর্কাইভ করতে চান?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline btn-danger" title="আর্কাইভ করুন">
                                            <i class="fa fa-archive"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Role & Permissions Modal -->
                <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <form action="{{ route('admin.rbac.roles.update', $role->id) }}" method="POST">
                                @csrf
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title"><i class="fa fa-key me-2"></i> রোল ও পারমিশন কনফিগারেশন: {{ $role->name }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">রোলের নাম</label>
                                            <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">ডাটা স্কোপ (Data Scope)</label>
                                            <select name="data_scope" class="form-select">
                                                <option value="all" {{ $role->data_scope === 'all' ? 'selected' : '' }}>All (সিস্টেমের সমস্ত রেকর্ড)</option>
                                                <option value="team" {{ $role->data_scope === 'team' ? 'selected' : '' }}>Team (নিজের টিম/বিভাগ)</option>
                                                <option value="branch" {{ $role->data_scope === 'branch' ? 'selected' : '' }}>Branch (নির্দিষ্ট শাখা)</option>
                                                <option value="assigned" {{ $role->data_scope === 'assigned' ? 'selected' : '' }}>Assigned (বরাদ্দকৃত কাজ)</option>
                                                <option value="own" {{ $role->data_scope === 'own' ? 'selected' : '' }}>Own (শুধু নিজস্ব রেকর্ড)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">বিবরণ</label>
                                            <input type="text" name="description" class="form-control" value="{{ $role->description }}">
                                        </div>
                                    </div>

                                    <h5 class="fw-bold border-bottom pb-2 text-dark"><i class="fa fa-list-check me-2 text-primary"></i> পারমিশন ট্রি (Permission Tree)</h5>
                                    <p class="text-muted font-size-13 mb-3">মডিউলভিত্তিক এক্সেস রাইটস টিক মার্ক করুন। ডিনাই বাই ডিফল্ট নীতি প্রযোজ্য।</p>

                                    @php
                                        $rolePermIds = $role->permissions->pluck('id')->toArray();
                                    @endphp

                                    <div class="row g-3">
                                        @foreach($permissionsByModule as $moduleName => $perms)
                                        <div class="col-md-6 col-12">
                                            <div class="card border h-100 shadow-none">
                                                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold text-dark"><i class="fa fa-folder text-warning me-1"></i> {{ $moduleName }}</span>
                                                    <span class="badge bg-secondary font-size-11">{{ $perms->count() }} পারমিশন</span>
                                                </div>
                                                <div class="card-body p-3">
                                                    <div class="row g-2">
                                                        @foreach($perms as $p)
                                                        <div class="col-12">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $p->id }}" id="perm_{{ $role->id }}_{{ $p->id }}" {{ in_array($p->id, $rolePermIds) ? 'checked' : '' }}>
                                                                <label class="form-check-label font-size-13" for="perm_{{ $role->id }}_{{ $p->id }}">
                                                                    <span class="fw-bold text-dark">{{ $p->name }}</span>
                                                                    <small class="text-muted d-block"><code>{{ $p->code }}</code></small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> পারমিশন সংরক্ষণ করুন</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Clone Role Modal -->
                <div class="modal fade" id="cloneRoleModal{{ $role->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.rbac.roles.clone', $role->id) }}" method="POST">
                                @csrf
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title"><i class="fa fa-copy me-2"></i> রোল ক্লোন করুন (Clone from {{ $role->name }})</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted font-size-13">এই রোলের সমস্ত পারমিশন হুবহু নতুন রোলে কপি হয়ে যাবে।</p>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">নতুন রোলের নাম <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="যেমন: Assistant {{ $role->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">নতুন রোল কোড <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control" placeholder="যেমন: asst_{{ $role->code }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                    <button type="submit" class="btn btn-info"><i class="fa fa-copy me-1"></i> ক্লোন তৈরি করুন</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Create New Role Modal -->
            <div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <form action="{{ route('admin.rbac.roles.store') }}" method="POST">
                            @csrf
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title"><i class="fa fa-plus-circle me-2"></i> নতুন রোল তৈরি ও পারমিশন নির্বাচন</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">রোলের নাম <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="যেমন: Branch Supervisor" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">রোল কোড (Unique Code) <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control" placeholder="যেমন: branch_supervisor" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">ক্যাটাগরি <span class="text-danger">*</span></label>
                                        <select name="category" class="form-select" required>
                                            <option value="Internal" selected>Internal (অভ্যন্তরীণ)</option>
                                            <option value="External">External (বাইরের)</option>
                                            <option value="Service">Service (সার্ভিস)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">ডাটা স্কোপ (Scope) <span class="text-danger">*</span></label>
                                        <select name="data_scope" class="form-select" required>
                                            <option value="all">All (সিস্টেমের সমস্ত রেকর্ড)</option>
                                            <option value="team" selected>Team (নিজের টিম/বিভাগ)</option>
                                            <option value="branch">Branch (নির্দিষ্ট শাখা)</option>
                                            <option value="assigned">Assigned (বরাদ্দকৃত কাজ)</option>
                                            <option value="own">Own (শুধু নিজস্ব রেকর্ড)</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">সংক্ষিপ্ত বিবরণ</label>
                                        <input type="text" name="description" class="form-control" placeholder="এই রোলের প্রধান দায়িত্ব ও উদ্দেশ্য লিখুন">
                                    </div>
                                </div>

                                <h5 class="fw-bold border-bottom pb-2 text-dark"><i class="fa fa-check-square-o me-2 text-primary"></i> পারমিশন নির্বাচন করুন</h5>
                                <div class="row g-3">
                                    @foreach($permissionsByModule as $moduleName => $perms)
                                    <div class="col-md-6 col-12">
                                        <div class="card border h-100 shadow-none">
                                            <div class="card-header bg-light py-2">
                                                <span class="fw-bold text-dark">{{ $moduleName }}</span>
                                            </div>
                                            <div class="card-body p-3">
                                                <div class="row g-2">
                                                    @foreach($perms as $p)
                                                    <div class="col-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $p->id }}" id="new_perm_{{ $p->id }}">
                                                            <label class="form-check-label font-size-13" for="new_perm_{{ $p->id }}">
                                                                <span class="fw-bold text-dark">{{ $p->name }}</span>
                                                                <small class="text-muted d-block"><code>{{ $p->code }}</code></small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-plus me-1"></i> রোল সংরক্ষণ করুন</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
