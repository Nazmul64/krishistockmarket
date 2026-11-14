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
        if (!Schema::hasTable('career_applications')) {
            Schema::create('career_applications', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->text('address');
                $table->string('resume'); // Path to PDF file
                $table->text('cover_letter')->nullable();
                $table->string('position_applied')->default('General Career Application');
                $table->string('status')->default('pending'); // pending, reviewed, shortlisted, rejected
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_applications');
    }
};

