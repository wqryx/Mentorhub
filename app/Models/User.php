<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'course',
        'cycle',
        'academic_year',
        'identification_type',
        'identification_number',
        'birth_date',
        'phone',
        'address',
        'emergency_contact',
        'emergency_phone',
        'photo',
        'calendar_token',
        'calendar_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    /**
     * Get all roles for the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
            ->withTimestamps();
    }

    /**
     * Get the modules the user is enrolled in.
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_user', 'user_id', 'module_id')
            ->withPivot('enrollment_date')
            ->withTimestamps();
    }

    /**
     * Get the tasks assigned to the user.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the grades for the user.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the messages for the user.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the user's photo URL.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/photos/' . $this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }
    
    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }
    
    /**
     * Check if user is a mentor
     */
    public function isMentor(): bool
    {
        return $this->hasRole('Mentor');
    }
    
    /**
     * Check if user is a student
     */
    public function isEstudiante(): bool
    {
        return $this->hasRole('Estudiante');
    }
    
    /**
     * Check if user is a guest
     */
    public function isGuest(): bool
    {
        return $this->hasRole('Guest');
    }
}
