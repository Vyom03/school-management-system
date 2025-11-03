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
        Schema::create('parent_registration_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('code', 16)->unique();
            $table->string('email')->nullable(); // Optional: pre-approved email
            $table->string('relationship')->default('parent'); // parent, guardian, etc.
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Admin who created it
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            
            $table->index('code');
            $table->index(['student_id', 'used']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_registration_codes');
    }
};
