<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'address',
        'designation',
        'salary',
        'photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
