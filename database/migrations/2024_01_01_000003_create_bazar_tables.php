<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bazar_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->default('fas fa-tag');
            $table->string('color')->default('#3b82f6');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bazar_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('bazar_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // purchaser
            $table->foreignId('category_id')->nullable()->constrained('bazar_categories')->onDelete('set null');
            $table->foreignId('vendor_id')->nullable()->constrained('bazar_vendors')->onDelete('set null');
            $table->string('item_name');
            $table->decimal('amount', 10, 2);
            $table->string('unit')->nullable(); // kg, litre, piece, etc.
            $table->decimal('quantity', 8, 2)->nullable();
            $table->decimal('unit_price', 8, 2)->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('receipt_image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['entry_date', 'user_id']);
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bazar_entries');
        Schema::dropIfExists('bazar_vendors');
        Schema::dropIfExists('bazar_categories');
    }
};
