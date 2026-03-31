<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Leave;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        // Core
        'name',
        'email',
        'password',
        'role',
        // Personal
        'full_name',
        'full_name_with_initials',
        'nic_number',
        'race_religion',
        'marital_status',
        'birthday',
        'address',
        'permanent_address',
        'profile_photo_path',
        // Contact
        'phone_number',
        'mobile_no',
        'whatsapp_no',
        // Professional
        'designation',
        'grade',
        'section',
        'workplace',
        'wop_no',
        'salary',
        // Dates
        'appointment_date',
        'assumed_duty_date',
        // Service & Career
        'service_history',
        'current_office_details',
        'confirmation_details',
        'eb_exams',
        // Qualifications
        'edu_qualifications',
        'prof_qualifications',
        'trainings',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday'          => 'date',
        'appointment_date'  => 'date',
        'assumed_duty_date' => 'date',
    ];

    /**
     * Computed age from birthday.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birthday ? $this->birthday->age : null;
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
