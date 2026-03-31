<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            // Skip Header or Empty Rows
            if (empty($row[0]) || in_array(strtolower($row[0]), ['name', 'full name', 'full_name'])) {
                return null;
            }

            // 1. Determine Email (Column 1)
            $email = !empty($row[1]) ? strtolower(trim($row[1])) : ('user_' . uniqid() . '@office.com');

            // 2. Simplified Upsert (3 Basic Fields)
            return User::updateOrCreate(
                ['email' => $email],
                [
                    'name'         => $email, // Username is Email
                    'full_name'    => $row[0] ?? 'Staff Member',
                    'phone_number' => $row[2] ?? null,
                    'password'     => Hash::make('12345678'),
                    'role'         => 'user',
                ]
            );

        } catch (\Exception $e) {
            Log::error('Simplified Import Failure: ' . $e->getMessage());
            return null;
        }
    }
}