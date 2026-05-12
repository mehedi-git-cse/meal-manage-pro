<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('meal_date');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'custom'])->default('lunch');
            $table->decimal('meal_rate', 10, 2)->default(0); // per-meal rate override
            $table->decimal('quantity', 5, 2)->default(1.00); // support half-meals
            $table->string('note')->nullable();
            $table->boolean('is_guest')->default(false); // guest meal
            $table->string('guest_name')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'meal_date']);
            $table->index(['meal_date', 'meal_type']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_entries');
    }
};
