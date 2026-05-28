@extends('layouts.app')

@section('title', 'Role Permissions')
@section('page_title', 'Role & Permission Management')

@section('content')
    <div class="row row-cards">
        <!-- Toast Notification Container -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
            <div id="statusToast" class="toast align-items-center text-white border-0 shadow-lg" role="alert"
                aria-live="assertive" aria-atomic="true"
                style="display: none; min-width: 250px; border-radius: 8px; transition: all 0.3s ease;">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <span id="toastIcon" class="me-2"></span>
                        <span id="toastMessage"></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="hideToast()"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title text-primary font-weight-bold">
                    Role Base Access Control
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter table-mobile-md card-table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="w-50 py-3">Permissions</th>
                                    @foreach ($roles as $role)
                                        <th class="text-center py-3">
                                            <span class="badge bg-purple-lt text-uppercase px-2.5 py-1.5"
                                                style="font-size: 0.75rem;">
                                                {{ $role->name }}
                                            </span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <strong
                                                    class="text-dark">{{ ucwords(str_replace('-', ' ', $permission->name)) }}</strong>
                                                <span class="text-muted"
                                                    style="font-size: 0.75rem;">{{ $permission->name }}</span>
                                            </div>
                                        </td>
                                        @foreach ($roles as $role)
                                            <td class="text-center py-3">
                                                <label class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input permission-toggle" type="checkbox"
                                                        data-role-id="{{ $role->id }}"
                                                        data-permission-id="{{ $permission->id }}"
                                                        data-role-name="{{ $role->name }}"
                                                        data-permission-name="{{ $permission->name }}"
                                                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                        {{ $role->name === 'owner' && $permission->name === 'manage-permissions' ? 'disabled' : '' }}>
                                                    <span class="form-check-label d-none">Toggle</span>
                                                </label>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.permission-toggle');

            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const roleId = this.getAttribute('data-role-id');
                    const permissionId = this.getAttribute('data-permission-id');
                    const roleName = this.getAttribute('data-role-name');
                    const permissionName = this.getAttribute('data-permission-name');
                    const status = this.checked ? 1 : 0;

                    // Keep reference to switch in case we need to revert it on error
                    const toggleSwitch = this;

                    // Send AJAX request
                    fetch("{{ route('role-permissions.toggle') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                role_id: roleId,
                                permission_id: permissionId,
                                status: status
                            })
                        })
                        .then(response => response.json().then(data => ({
                            status: response.status,
                            body: data
                        })))
                        .then(({
                            status,
                            body
                        }) => {
                            if (status === 200 && body.success) {
                                showToast(body.message, 'success');
                            } else {
                                // Revert the checkbox state
                                toggleSwitch.checked = !toggleSwitch.checked;
                                showToast(body.message || 'Failed to update permission.',
                                    'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error toggling permission:', error);
                            toggleSwitch.checked = !toggleSwitch.checked;
                            showToast('An error occurred. Please try again.', 'danger');
                        });
                });
            });
        });

        let toastTimeout;

        function showToast(message, type = 'success') {
            const toast = document.getElementById('statusToast');
            const icon = document.getElementById('toastIcon');
            const msg = document.getElementById('toastMessage');

            // Clear previous timeout
            clearTimeout(toastTimeout);

            // Reset classes
            toast.className = 'toast align-items-center text-white border-0 shadow-lg';

            if (type === 'success') {
                toast.classList.add('bg-success');
                icon.innerHTML = '<i class="ti ti-check" style="font-size: 1.1rem;"></i>';
            } else {
                toast.classList.add('bg-danger');
                icon.innerHTML = '<i class="ti ti-alert-triangle" style="font-size: 1.1rem;"></i>';
            }

            msg.textContent = message;
            toast.style.display = 'block';
            toast.style.opacity = '1';

            // Auto hide after 3.5 seconds
            toastTimeout = setTimeout(() => {
                hideToast();
            }, 3500);
        }

        function hideToast() {
            const toast = document.getElementById('statusToast');
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 300);
        }
    </script>
@endsection
