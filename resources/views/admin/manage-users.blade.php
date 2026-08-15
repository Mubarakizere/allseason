@extends('layouts.admin')

@section('title', 'Manage Admin Users — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .usr-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .usr-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .usr-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .usr-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-usr {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        background: #dc2626;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .btn-add-usr:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .usr-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .usr-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .usr-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        padding: 0;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        padding: 14px 20px 10px;
        font-size: 13px;
        color: #6b7280;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 13px;
        outline: none;
        margin-left: 8px;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #dc2626;
    }
    table.dataTable {
        border-collapse: collapse !important;
        width: 100% !important;
        border: none !important;
        margin: 0 !important;
    }
    table.dataTable<thead>th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb !important;
        border-top: none !important;
        color: #374151;
        font-weight: 600;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 10px 18px !important;
    }
    table.dataTable<tbody>td {
        padding: 12px 18px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6 !important;
        border-top: none !important;
        color: #111827;
        font-size: 13px;
    }
    table.dataTable<tbody>tr:hover {
        background-color: #f9fafb !important;
    }
    .dataTables_info,
    .dataTables_paginate {
        padding: 12px 20px !important;
        font-size: 12.5px;
        color: #6b7280;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px !important;
        border: 1px solid #e5e7eb !important;
        background: #ffffff !important;
        color: #374151 !important;
        font-size: 12px !important;
        padding: 3px 9px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #111827 !important;
        color: #ffffff !important;
        border-color: #111827 !important;
        box-shadow: none !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#users-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search admin accounts..."
            }
        });

        window.editUser = function(button) {
            let user = $(button).data();
            let actionUrl = "{{ route('admin.users.update', ':id') }}".replace(':id', user.id);
            $('#editUserForm').attr('action', actionUrl);

            $('#editFirstName').val(user.firstName);  
            $('#editMiddleName').val(user.middleName || '');  
            $('#editLastName').val(user.lastName);
            $('#editEmail').val(user.email);
            $('#editRole').val(user.role);

            if (user.notice === 'change_password_to_activate_account') {
                $('#banCheckboxDiv').hide();
            } else {
                $('#banCheckboxDiv').show();
                $('#banCheckbox').prop('checked', user.status === 0); 
            }
        };

        $('#viewUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var first_name = button.data('first_name');
            var middle_name = button.data('middle_name');
            var last_name = button.data('last_name');
            var email = button.data('email');
            var role = button.data('role');
            var status = button.data('status');
            var phoneNumber = button.data('phone-number');
            var address = button.data('address');
            var profilePicture = button.data('profile-picture');

            var modal = $(this);
            modal.find('#viewProfilePicture').attr('src', profilePicture || "{{ asset('assets/images/user-icon.png') }}");
            modal.find('#viewFirstName').text(first_name);
            modal.find('#viewMiddleName').text(middle_name || 'N/A');
            modal.find('#viewLastName').text(last_name);
            modal.find('#viewEmail').text(email);
            modal.find('#viewRole').text(role);
            modal.find('#viewStatus').html(status === 1 
                ? '<span class="badge bg-success text-white fw-semibold"><i class="fas fa-check me-1"></i> Active</span>' 
                : '<span class="badge bg-danger text-white fw-semibold"><i class="fas fa-ban me-1"></i> Banned</span>');
            modal.find('#viewPhoneNumber').text(phoneNumber || 'N/A');
            modal.find('#viewAddress').text(address || 'N/A');
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper usr-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="usr-header">
        <div class="usr-title-group">
            <h1>Admin Users & Roles</h1>
            <p>Manage system administrator accounts, staff access roles, and permissions.</p>
        </div>
        <button class="btn-add-usr" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="fas fa-plus me-1"></i> Add Admin User
        </button>
    </div>

    {{-- Users Card --}}
    <div class="usr-card">
        <div class="usr-card-header">
            <h3 class="usr-card-title">All Admin Accounts ({{ $users->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="users-table">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Email Address</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; font-size: 13px; font-weight: 600;">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                        </div>
                                        <div class="fw-bold text-dark">
                                            {{ $user->first_name }} {{ $user->middle_name ? $user->middle_name . ' ' : '' }} {{ $user->last_name }}
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11.5px;">
                                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->status === 1)
                                        <span class="badge bg-success text-white fw-semibold" style="font-size: 11px;"><i class="fas fa-check me-1"></i> Active</span>
                                    @else
                                        <span class="badge bg-danger text-white fw-semibold" style="font-size: 11px;"><i class="fas fa-ban me-1"></i> Banned</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <!-- View -->
                                        <button class="btn btn-sm btn-dark" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewUserModal" 
                                                data-first_name="{{ $user->first_name }}" 
                                                data-middle_name="{{ $user->middle_name }}" 
                                                data-last_name="{{ $user->last_name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ ucwords(str_replace('_', ' ', $user->role)) }}"
                                                data-status="{{ $user->status }}"
                                                data-phone-number="{{ $user->phone_number }}"
                                                data-address="{{ $user->address }}"
                                                data-profile-picture="{{ $user->profile_picture ? asset('storage/profile-picture/' . $user->profile_picture) : asset('assets/images/user-icon.png') }}"
                                                title="View User">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editUserModal"
                                                data-id="{{ $user->id }}"
                                                data-first-name="{{ $user->first_name }}"
                                                data-middle-name="{{ $user->middle_name }}"
                                                data-last-name="{{ $user->last_name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}"
                                                data-status="{{ $user->status }}"
                                                data-notice="{{ $user->notice }}"
                                                onclick="editUser(this)"
                                                title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No admin user records found.</td>
                            </tr>
                        @endforelse
                    </tbody>                    
                </table>
            </div>
        </div>
    </div>

    {{-- Create User Modal --}}
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.users.store') }}" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Create Admin Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="alert alert-warning border-0 py-2 px-3 mb-3" style="font-size: 12px; border-radius: 6px;">
                            <i class="fas fa-info-circle me-1"></i> Initial password will be the email address. The user must change it upon first login.
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">First Name *</label>
                                <input type="text" name="first_name" id="FirstName" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Middle Name</label>
                                <input type="text" name="middle_name" id="MiddleName" class="form-control" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Last Name *</label>
                                <input type="text" name="last_name" id="LastName" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Email Address *</label>
                                <input type="email" name="email" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Access Role *</label>
                                <select name="role" class="form-select" required style="font-size: 13px;">
                                    <option value="admin">Admin</option>
                                    <option value="global_admin">Global Admin</option>
                                    <option value="sales">Sales (POS)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Create Account</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="editUserForm" style="width: 100%;">
                @csrf
                @method('PUT')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Edit Admin Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">First Name *</label>
                                <input type="text" name="first_name" id="editFirstName" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Middle Name</label>
                                <input type="text" name="middle_name" id="editMiddleName" class="form-control" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Last Name *</label>
                                <input type="text" name="last_name" id="editLastName" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Email Address *</label>
                                <input type="email" name="email" id="editEmail" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Access Role *</label>
                                <select name="role" id="editRole" class="form-select" required style="font-size: 13px;">
                                    <option value="admin">Admin</option>
                                    <option value="global_admin">Global Admin</option>
                                    <option value="sales">Sales (POS)</option>
                                </select>
                            </div>
                            <div class="col-12 mt-2" id="banCheckboxDiv">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="banCheckbox" name="ban">
                                    <label class="form-check-label text-danger fw-semibold" for="banCheckbox" style="font-size: 12.5px;">
                                        Ban / Disable this user account
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Account</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- View User Modal --}}
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">User Account Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="text-center mb-3">
                        <img id="viewProfilePicture" src="{{ asset('assets/images/user-icon.png') }}" 
                             alt="Profile Image" 
                             class="rounded-circle border" 
                             style="width: 72px; height: 72px; object-fit: cover;">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm border-0 mb-0">
                            <tr>
                                <th class="text-muted fw-semibold" style="width: 40%; font-size: 12.5px;">First Name</th>
                                <td id="viewFirstName" class="fw-bold text-dark" style="font-size: 13px;"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold" style="font-size: 12.5px;">Middle Name</th>
                                <td id="viewMiddleName" class="text-dark" style="font-size: 13px;"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold" style="font-size: 12.5px;">Last Name</th>
                                <td id="viewLastName" class="fw-bold text-dark" style="font-size: 13px;"></td>
                            </tr>                    
                            <tr>
                                <th class="text-muted fw-semibold" style="font-size: 12.5px;">Email</th>
                                <td id="viewEmail" class="text-dark" style="font-size: 13px;"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold" style="font-size: 12.5px;">Role</th>
                                <td id="viewRole" class="text-dark" style="font-size: 13px;"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold" style="font-size: 12.5px;">Status</th>
                                <td id="viewStatus" style="font-size: 13px;"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold" style="font-size: 12.5px;">Phone Number</th>
                                <td id="viewPhoneNumber" class="text-dark" style="font-size: 13px;"></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-semibold" style="font-size: 12.5px;">Address</th>
                                <td id="viewAddress" class="text-dark" style="font-size: 13px;"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection