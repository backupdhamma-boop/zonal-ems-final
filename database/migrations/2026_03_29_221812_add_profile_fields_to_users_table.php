<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable();
            $table->string('nic_number')->nullable()->unique();
            $table->string('designation')->nullable();
            $table->string('workplace')->nullable();
            $table->date('appointment_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'nic_number',
                'designation',
                'workplace',
                'appointment_date',
                'phone_number',
                'address',
                'profile_photo_path',
            ]);
        });
    }
};
