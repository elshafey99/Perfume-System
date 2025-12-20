<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
        'phone',
        'image',
        'status',
        'type',
        'role_id',
        'position',
        'verification_code',
        'verification_code_expires_at',
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
            'verification_code_expires_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }
    /**
     * Get the role that belongs to the user
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if user has access to a specific permission
     */
    public function hasAccess($config_permission): bool
    {
        $role = $this->role;
        if (!$role) {
            return false;
        }

        $permissions = json_decode($role->permession, true) ?? [];
        return in_array($config_permission, $permissions);
    }
}
