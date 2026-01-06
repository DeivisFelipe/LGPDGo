<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'is_super_user',
        'onboarding_completed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'is_super_user' => 'boolean',
            'onboarding_completed' => 'boolean',
        ];
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withTimestamps();
    }

    public function permissionGroups(): BelongsToMany
    {
        return $this->belongsToMany(PermissionGroup::class, 'user_permission_groups')
            ->withTimestamps();
    }

    // Helper methods
    public function hasPermission(string $permissionSlug): bool
    {
        if ($this->is_super_user) {
            return true;
        }

        // Check direct permissions
        if ($this->permissions()->where('slug', $permissionSlug)->exists()) {
            return true;
        }

        // Check permissions through groups
        return $this->permissionGroups()
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug);
            })
            ->exists();
    }

    public function hasAnyPermission(array $permissionSlugs): bool
    {
        if ($this->is_super_user) {
            return true;
        }

        foreach ($permissionSlugs as $slug) {
            if ($this->hasPermission($slug)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions(array $permissionSlugs): bool
    {
        if ($this->is_super_user) {
            return true;
        }

        foreach ($permissionSlugs as $slug) {
            if (!$this->hasPermission($slug)) {
                return false;
            }
        }

        return true;
    }

    public function isSuperUser(): bool
    {
        return $this->is_super_user;
    }
}
