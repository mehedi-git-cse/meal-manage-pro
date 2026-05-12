<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_costs', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month'); // 1-12
            $table->decimal('total_bazar_cost', 12, 2)->default(0); // total market cost for month
            $table->decimal('total_meals', 8, 2)->default(0);       // total meal count
            $table->decimal('cost_per_meal', 8, 2)->default(0);     // calculated rate
            $table->decimal('meal_rate', 8, 2)->default(0);         // configured rate
            $table->text('notes')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->foreignId('finalized_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();

            $table->unique(['year', 'month']);
            $table->index(['year', 'month']);
        });

        // User-wise monthly meal summary (calculated/cached)
        Schema::create('monthly_meal_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('meal_cost_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->integer('month');
            $table->decimal('total_meals', 8, 2)->default(0);
            $table->decimal('breakfast_count', 8, 2)->default(0);
            $table->decimal('lunch_count', 8, 2)->default(0);
            $table->decimal('dinner_count', 8, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->decimal('bazar_contribution', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0); // cost - contribution
            $table->timestamps();

            $table->unique(['user_id', 'year', 'month']);
            $table->index(['year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_meal_summaries');
        Schema::dropIfExists('meal_costs');
    }
};
