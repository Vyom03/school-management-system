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
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Tuition Fee", "Library Fee", "Sports Fee"
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2); // Fee amount
            $table->enum('frequency', ['one_time', 'monthly', 'quarterly', 'semester', 'yearly'])->default('one_time');
            $table->integer('grade_level')->nullable(); // Null means applies to all grades
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
