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
        'birth_date',
        'gender',
        'is_active',
        'verification_code',
        'verification_code_expires_at',
        'email_verified_at',
        // 'registration_step', // No longer needed - commented out
        'access_code',
        'member_id',
        'first_login',
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
            'birth_date' => 'date',
            'is_active' => 'boolean',
            'first_login' => 'boolean',
        ];
    }

    public function propertyMembers()
    {
        return $this->hasMany(PropertyMember::class);
    }

    public function unitAssignments()
    {
        return $this->hasMany(UnitAssignment::class);
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_members')
            ->withPivot('role_id', 'is_active')
            ->withTimestamps();
    }

    // /**
    //  * Check if user has completed registration
    //  */
    // public function hasCompletedRegistration(): bool
    // {
    //     return $this->registration_step === 'completed';
    // }

    // /**
    //  * Check if user has created a property
    //  */
    // public function hasProperty(): bool
    // {
    //     return in_array($this->registration_step, [
    //         'property_created',
    //         'attachments_uploaded',
    //         'building_added',
    //         'completed'
    //     ]);
    // }

    /**
     * Get user's active property
     */
    public function getActiveProperty()
    {
        return $this->properties()
            ->wherePivot('is_active', true)
            ->first();
    }

    // /**
    //  * Update registration step
    //  */
    // public function updateRegistrationStep(string $step): void
    // {
    //     $this->update(['registration_step' => $step]);
    // }

    // /**
    //  * Registration steps constants
    //  */
    // const STEP_REGISTERED = 'registered';
    // const STEP_PROPERTY_CREATED = 'property_created';
    // const STEP_ATTACHMENTS_UPLOADED = 'attachments_uploaded';
    // const STEP_BUILDING_ADDED = 'building_added';
    // const STEP_COMPLETED = 'completed';
}
