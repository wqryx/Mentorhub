@foreach($permissions as $permission)
    <tr>
        <td>
            <div class="fw-bold">{{ $permission->display_name ?? $permission->name }}</div>
            <small class="text-muted">{{ $permission->name }}</small>
            @if($permission->description)
                <div class="small text-muted">{{ $permission->description }}</div>
            @endif
        </td>
        
        @foreach($roles as $role)
            <td class="text-center align-middle">
                <div class="form-check d-flex justify-content-center">
                    @php
                        $isChecked = $permission->hasRole($role->name);
                        $isDisabled = $role->is_system && $role->name === 'admin';
                    @endphp
                    
                    <input class="form-check-input permission-checkbox" 
                           type="checkbox" 
                           {{ $isChecked ? 'checked' : '' }}
                           {{ $isDisabled ? 'disabled' : '' }}
                           data-permission-id="{{ $permission->id }}"
                           data-role-id="{{ $role->id }}">
                           
                    @if($isDisabled)
                        <input type="hidden" 
                               name="permissions[{{ $permission->id }}][roles][]" 
                               value="{{ $role->id }}">
                    @endif
                </div>
            </td>
        @endforeach
    </tr>
@endforeach
