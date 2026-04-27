@extends('layout.admin')

@section('title', 'User Management')

@section('content')

    <div class="container mt-4">
        <h1 class="mb-4">User Management</h1>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search Form & Filter Dropdown -->
        <div class="d-flex align-items-center mb-4">
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex align-items-center me-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="form-control me-2" style="border-radius: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <button type="submit" class="btn" style="border-radius: 50%; background-color: #14274e; padding: 0.6rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); transition: background-color 0.3s;">
                    <i class="fas fa-search" style="color: #ffffff; font-size: 1rem;"></i>
                </button>
            </form>
            <div class="ms-3">
                <select class="form-select" onchange="location = this.value;">
                    <option value="{{ route('admin.users.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => 'asc'])) }}" {{ request('sort') === 'name' && request('direction') === 'asc' ? 'selected' : '' }}>Sort by Name (A-Z)</option>
                    <option value="{{ route('admin.users.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => 'desc'])) }}" {{ request('sort') === 'name' && request('direction') === 'desc' ? 'selected' : '' }}>Sort by Name (Z-A)</option>
                    <option value="{{ route('admin.users.index', array_merge(request()->all(), ['sort' => 'created_at', 'direction' => 'asc'])) }}" {{ request('sort') === 'created_at' && request('direction') === 'asc' ? 'selected' : '' }}>Oldest</option>
                    <option value="{{ route('admin.users.index', array_merge(request()->all(), ['sort' => 'created_at', 'direction' => 'desc'])) }}" {{ request('sort') === 'created_at' && request('direction') === 'desc' ? 'selected' : '' }}>Newest</option>
                </select>
            </div>
        </div>

        <!-- User Table -->
        <div class="table-responsive">
            <table class="table table-hover shadow-sm rounded" style="background-color: #ffffff; border-radius: 8px;">
                <thead class="bg-primary text-white">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">
                        <a href="{{ route('admin.users.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-white text-decoration-none">
                            Name <i class="fas fa-sort"></i>
                        </a>
                    </th>
                    <th class="p-3">Roles</th>
                    <th class="p-3">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td class="align-middle">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                        <td class="align-middle">{{ $user->name }}</td>
                        <td class="align-middle">
                            @foreach($user->roles as $role)
                                <span class="badge bg-{{ ['Admin' => 'danger', 'Owner' => 'warning', 'Lecturer' => 'info', 'Student' => 'success', 'Parent' => 'secondary'][$role->name] ?? 'secondary' }}">
                                {{ $role->name }}
                            </span>
                            @endforeach
                        </td>
                        <td class="align-middle">
                            <button class="btn btn-info btn-sm me-2" onclick="openViewModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->member_id }}', '{{ $user->email }}', '{{ $user->avatar }}', '{{ $user->roles->pluck('name')->join(', ') }}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-primary btn-sm me-2" onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->member_id }}', {{ json_encode($user->roles->pluck('id')->first()) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 10px;">
                <div class="modal-header" style="background-color: #14274e; color: white; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h5 class="modal-title" id="viewUserModalLabel"><i class="fas fa-user me-2"></i> User Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" style="padding: 2rem;">
                    <div class="mb-3">
                        <img src="{{ asset('storage/avatars/default.jpg') }}" id="viewAvatar" alt="User Avatar" class="rounded-circle shadow" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <p><strong>Name:</strong> <span id="viewName" class="d-block mb-1"></span></p>
                    <p><strong>Member ID:</strong> <span id="viewMemberId" class="d-block mb-1"></span></p>
                    <p><strong>Email:</strong> <span id="viewEmail" class="d-block mb-1"></span></p>
                    <p><strong>Roles:</strong> <span id="viewRoles" class="d-block mb-1"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                <div class="modal-header" style="background-color: #14274e; color: white; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <form id="editUserForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="editName" class="form-label" style="color: #14274e; font-weight: 500;">
                                <i class="fas fa-user me-1"></i> Name
                            </label>
                            <input type="text" class="form-control" id="editName" name="name" required style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ced4da;">
                        </div>
                        <div class="mb-4">
                            <label for="editMemberId" class="form-label" style="color: #14274e; font-weight: 500;">
                                <i class="fas fa-id-badge me-1"></i> Member ID
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="editMemberId" readonly style="border-radius: 8px 0 0 8px; padding: 0.75rem; border: 1px solid #ced4da;">
                                <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard()" style="border-radius: 0 8px 8px 0;">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="editRole" class="form-label" style="color: #14274e; font-weight: 500;">
                                <i class="fas fa-user-tag me-1"></i> Role
                            </label>
                            <select name="roles[]" id="editRole" class="form-select" style="border-radius: 8px; padding: 0.75rem; border: 1px solid #ced4da;">
                                <option disabled selected>Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn w-100" style="background-color: #14274e; color: white; padding: 0.75rem; border-radius: 8px; font-weight: 500;">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Alert for Copy Notification -->
    <div id="copyAlert" class="alert alert-success alert-dismissible fade" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">
        <strong>Success!</strong> Member ID copied to clipboard.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Script for handling modals and actions -->
    <script>
        function openViewModal(userId, userName, memberId, email, avatarUrl, roles) {
            document.getElementById('viewName').innerText = userName;
            document.getElementById('viewMemberId').innerText = memberId;
            document.getElementById('viewEmail').innerText = email;
            document.getElementById('viewRoles').innerText = roles;
            document.getElementById('viewAvatar').src = avatarUrl || '/storage/avatars/default.jpg';
            new bootstrap.Modal(document.getElementById('viewUserModal')).show();
        }

        function openEditModal(userId, userName, memberId, userRole) {
            document.getElementById('editUserForm').action = `/admin/users/${userId}`;
            document.getElementById('editName').value = userName;
            document.getElementById('editMemberId').value = memberId;
            const rolesDropdown = document.getElementById('editRole');
            for (let option of rolesDropdown.options) {
                option.selected = option.value == userRole;
            }
            rolesDropdown.disabled = userRole === '1' && userId === {{ Auth::id() }};
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }

        function openDeleteModal(userId, userName) {
            document.getElementById('deleteUserForm').action = `/admin/users/${userId}`;
            document.getElementById('deleteUserName').innerText = userName;
            new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
        }

        function copyToClipboard() {
            const memberIdInput = document.getElementById("editMemberId");
            memberIdInput.select();
            document.execCommand("copy");
            const copyAlert = document.getElementById("copyAlert");
            copyAlert.classList.add("show");
            setTimeout(() => { copyAlert.classList.remove("show"); }, 3000);
        }
    </script>
@endsection
