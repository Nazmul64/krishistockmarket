@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-primary"><i class="fa fa-th me-2"></i> Role vs Permission Matrix (রোল বনাম পারমিশন ম্যাট্রিক্স)</h3>
                    <p class="text-muted mb-0">আন্তর্জাতিক মানের RBAC ম্যাট্রিক্স নির্দেশিকা (Section 6)</p>
                </div>
                <div>
                    <a href="{{ route('admin.rbac.roles') }}" class="btn btn-primary"><i class="fa fa-shield me-1"></i> রোল পরিচালনা</a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="box">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0 text-center font-size-13">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="text-start" style="min-width: 260px;">Module & Permission Capability</th>
                                    <th>Super Admin</th>
                                    <th>Admin</th>
                                    <th>Manager</th>
                                    <th>Employee</th>
                                    <th>Staff</th>
                                    <th>Support</th>
                                    <th>Security</th>
                                    <th>Auditor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissionsByModule as $moduleName => $perms)
                                <tr class="bg-light text-start">
                                    <td colspan="9" class="fw-bold text-primary font-size-14 py-2">
                                        <i class="fa fa-folder-open me-2 text-warning"></i> {{ $moduleName }} Module
                                    </td>
                                </tr>
                                @foreach($perms as $p)
                                <tr>
                                    <td class="text-start">
                                        <span class="fw-bold text-dark d-block">{{ $p->name }}</span>
                                        <small class="text-muted"><code>{{ $p->code }}</code></small>
                                    </td>
                                    @php
                                        $roleCodes = ['super_admin', 'admin', 'manager', 'employee', 'staff', 'support_agent', 'security_analyst', 'auditor'];
                                    @endphp
                                    @foreach($roleCodes as $rc)
                                        @php
                                            $rObj = $roles->firstWhere('code', $rc);
                                            $hasPerm = false;
                                            if ($rc === 'super_admin') {
                                                $hasPerm = true;
                                            } elseif ($rObj) {
                                                $hasPerm = $rObj->permissions->contains('id', $p->id);
                                            }
                                        @endphp
                                        <td>
                                            @if($hasPerm)
                                                <span class="badge bg-success font-size-12 px-2 py-1"><i class="fa fa-check"></i></span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
