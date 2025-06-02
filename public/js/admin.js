// Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    const sidebarToggle = document.querySelector('.js-sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('toggled');
            document.querySelector('.main').classList.toggle('toggled');
        });
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // User status toggle
    const statusToggles = document.querySelectorAll('.toggle-status');
    if (statusToggles.length > 0) {
        statusToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.dataset.userId;
                const url = `/admin/users/${userId}/toggle-status`;
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        const statusBadge = document.querySelector(`#user-status-${userId}`);
                        if (statusBadge) {
                            if (data.is_active) {
                                statusBadge.className = 'badge bg-success';
                                statusBadge.textContent = 'Activo';
                                this.querySelector('i').className = 'fas fa-toggle-on';
                            } else {
                                statusBadge.className = 'badge bg-danger';
                                statusBadge.textContent = 'Inactivo';
                                this.querySelector('i').className = 'fas fa-toggle-off';
                            }
                        }
                        
                        // Show notification
                        showNotification('success', data.message);
                    } else {
                        showNotification('error', 'Error al cambiar el estado');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Error al procesar la solicitud');
                });
            });
        });
    }

    // User filter form
    const userFilterForm = document.getElementById('userFilterForm');
    if (userFilterForm) {
        userFilterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const searchParams = new URLSearchParams();
            const search = document.getElementById('search').value;
            const role = document.getElementById('role').value;
            const status = document.getElementById('status').value;
            
            if (search) searchParams.append('search', search);
            if (role) searchParams.append('role', role);
            if (status !== '') searchParams.append('status', status);
            
            window.location.href = `${window.location.pathname}?${searchParams.toString()}`;
        });
    }

    // Delete user confirmation
    const deleteButtons = document.querySelectorAll('.delete-user');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                
                if (confirm(`¿Estás seguro de que deseas eliminar al usuario ${userName}?`)) {
                    document.getElementById(`delete-form-${userId}`).submit();
                }
            });
        });
    }

    // Show notification
    function showNotification(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertIcon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alert.setAttribute('role', 'alert');
        alert.style.zIndex = '9999';
        
        alert.innerHTML = `
            <i class="fas ${alertIcon} me-2"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        document.body.appendChild(alert);
        
        // Auto dismiss after 3 seconds
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 3000);
    }

    // Modal functionality for creating/editing users
    const userModal = document.getElementById('userModal');
    if (userModal) {
        userModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const action = button.getAttribute('data-action');
            const userId = button.getAttribute('data-user-id');
            
            const modalTitle = userModal.querySelector('.modal-title');
            const modalForm = userModal.querySelector('form');
            const submitBtn = userModal.querySelector('.btn-primary[type="submit"]');
            
            if (action === 'create') {
                modalTitle.textContent = 'Crear Nuevo Usuario';
                modalForm.action = '/admin/users';
                modalForm.method = 'POST';
                submitBtn.textContent = 'Crear Usuario';
                
                // Clear form fields
                modalForm.reset();
            } else if (action === 'edit') {
                modalTitle.textContent = 'Editar Usuario';
                modalForm.action = `/admin/users/${userId}`;
                modalForm.method = 'POST';
                modalForm.innerHTML += `<input type="hidden" name="_method" value="PUT">`;
                submitBtn.textContent = 'Actualizar Usuario';
                
                // Fetch user data and populate form
                fetch(`/admin/users/${userId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.user) {
                        const user = data.user;
                        modalForm.querySelector('#name').value = user.name;
                        modalForm.querySelector('#email').value = user.email;
                        
                        // Set roles
                        const roleCheckboxes = modalForm.querySelectorAll('input[name="roles[]"]');
                        roleCheckboxes.forEach(checkbox => {
                            checkbox.checked = user.roles.some(role => role.id == checkbox.value);
                        });
                        
                        // Set active status
                        modalForm.querySelector('#is_active').checked = user.is_active;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Error al cargar datos del usuario');
                });
            }
        });
    }
});
