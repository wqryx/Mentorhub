// DataTables Custom Configuration

document.addEventListener('DOMContentLoaded', function() {
    // Check if the DataTable element exists
    const dataTableElement = document.getElementById('usersTable');
    if (!dataTableElement) return;
    
    // Initialize DataTable with custom configuration
    const table = $('#usersTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex"f>>t<"d-flex justify-content-between align-items-center mt-3"<"d-flex align-items-center"i><"d-flex"p>>',
        initComplete: function() {
            // Add custom styling to the DataTable elements
            $('.dataTables_length select').addClass('form-select form-select-sm');
            $('.dataTables_filter input').addClass('form-control form-control-sm ms-2');
            $('.dataTables_filter input').attr('placeholder', 'Buscar...');
            $('.dataTables_info').addClass('text-muted');
            $('.pagination').addClass('pagination-sm mb-0');
        }
    });

    // Handle status toggle
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function(e) {
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
                            this.innerHTML = '<i class="fas fa-toggle-on"></i>';
                        } else {
                            statusBadge.className = 'badge bg-danger';
                            statusBadge.textContent = 'Inactivo';
                            this.innerHTML = '<i class="fas fa-toggle-off"></i>';
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

    // Handle delete confirmation
    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            
            if (confirm(`¿Estás seguro de que deseas eliminar al usuario ${userName}?`)) {
                document.getElementById(`delete-form-${userId}`).submit();
            }
        });
    });

    // Show notification function
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
            alert.remove();
        }, 3000);
    }
});
