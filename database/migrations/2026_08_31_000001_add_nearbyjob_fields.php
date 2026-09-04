<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->unique()->after('name');
            $table->string('whatsapp', 20)->nullable()->after('nik');
            $table->date('date_of_birth')->nullable()->after('whatsapp');
        });

        // Extend applicant_profiles table
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->string('whatsapp', 20)->nullable()->after('photo');
            $table->string('salary_expectation')->nullable()->after('work_experience');
            $table->unsignedInteger('application_credits')->default(3)->after('salary_expectation');
            $table->boolean('cv_generated')->default(false)->after('application_credits');
            $table->json('cv_data')->nullable()->after('cv_generated');
        });

        // Extend companies table
        Schema::table('companies', function (Blueprint $table) {
            $table->string('owner_name')->nullable()->after('user_id');
            $table->string('nik', 16)->nullable()->after('owner_name');
            $table->string('whatsapp', 20)->nullable()->after('nik');
            $table->string('business_field')->nullable()->after('company_name');
            $table->string('nib')->nullable()->after('business_field');
            $table->enum('contact_method', ['whatsapp', 'email'])->default('whatsapp')->after('nib');
        });

        // Extend job_listings table
        Schema::table('job_listings', function (Blueprint $table) {
            $table->unsignedInteger('salary_min')->nullable()->after('job_category');
            $table->unsignedInteger('salary_max')->nullable()->after('salary_min');
            $table->string('work_duration')->nullable()->after('salary_max');
            $table->string('work_hours')->nullable()->after('work_duration');
            $table->enum('contact_method', ['whatsapp', 'email'])->default('whatsapp')->after('work_hours');
            $table->string('contact_whatsapp', 20)->nullable()->after('contact_method');
            $table->string('contact_email')->nullable()->after('contact_whatsapp');
        });

        // Extend applications table
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('contact_method', ['whatsapp', 'email'])->default('whatsapp')->after('status');
            $table->date('application_date')->nullable()->after('contact_method');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'whatsapp', 'date_of_birth']);
        });
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'salary_expectation', 'application_credits', 'cv_generated', 'cv_data']);
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['owner_name', 'nik', 'whatsapp', 'business_field', 'nib', 'contact_method']);
        });
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn(['salary_min', 'salary_max', 'work_duration', 'work_hours', 'contact_method', 'contact_whatsapp', 'contact_email']);
        });
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['contact_method', 'application_date']);
        });
    }
};
