<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name_with_initials')->nullable()->after('full_name');
            $table->string('grade')->nullable()->after('designation');
            $table->string('section')->nullable()->after('grade');
            $table->text('permanent_address')->nullable()->after('address');
            $table->string('whatsapp_no')->nullable()->after('phone_number');
            $table->string('mobile_no')->nullable()->after('whatsapp_no');
            $table->date('birthday')->nullable()->after('appointment_date');
            $table->string('wop_no')->nullable()->after('workplace');
            $table->string('race_religion')->nullable()->after('nic_number');
            $table->string('marital_status')->nullable()->after('race_religion');
            $table->date('assumed_duty_date')->nullable()->after('appointment_date');
            $table->longText('service_history')->nullable();
            $table->text('current_office_details')->nullable();
            $table->text('confirmation_details')->nullable();
            $table->text('eb_exams')->nullable();
            $table->text('edu_qualifications')->nullable();
            $table->text('prof_qualifications')->nullable();
            $table->text('trainings')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'full_name_with_initials',
                'grade',
                'section',
                'permanent_address',
                'whatsapp_no',
                'mobile_no',
                'birthday',
                'wop_no',
                'race_religion',
                'marital_status',
                'assumed_duty_date',
                'service_history',
                'current_office_details',
                'confirmation_details',
                'eb_exams',
                'edu_qualifications',
                'prof_qualifications',
                'trainings',
            ]);
        });
    }
};
