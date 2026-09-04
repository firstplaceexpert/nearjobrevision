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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('item_type'); // 'credit_topup', 'cv_generator', 'bundle'
            $table->string('package_key')->nullable(); // 'starter', 'popular', 'intensive', 'cv_ats', 'bundle_komplit'
            $table->string('item_name');
            $table->unsignedInteger('credits_amount')->default(0);
            $table->decimal('original_amount', 12, 2)->default(0); // Harga coret
            $table->decimal('gross_amount', 12, 2); // Harga bayar aktual
            $table->string('status')->default('pending'); // 'pending', 'settlement', 'cancel', 'expire', 'failure'
            $table->string('snap_token')->nullable();
            $table->string('payment_type')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
