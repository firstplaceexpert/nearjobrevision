<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('position');
            $table->text('description');
            $table->text('qualifications');
            $table->string('city');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('work_type', ['full_time', 'part_time', 'harian', 'kontrak', 'internship']);
            $table->string('job_category');
            $table->json('required_skills')->nullable();
            $table->enum('min_education', ['sma', 'd3', 's1', 's2', 's3'])->default('sma');
            $table->unsignedInteger('radius_km')->default(25);
            $table->enum('status', ['active', 'filled', 'closed'])->default('active');
            $table->timestamps();

            $table->index(['status', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
