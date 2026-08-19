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
        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('contact_messages', 'phone')) {
                $table->string('phone')->after('name');
            }
            if (!Schema::hasColumn('contact_messages', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('contact_messages', 'subject')) {
                $table->string('subject')->nullable()->after('email');
            }
            if (!Schema::hasColumn('contact_messages', 'message')) {
                $table->text('message')->after('subject');
            }
            if (!Schema::hasColumn('contact_messages', 'status')) {
                $table->enum('status', ['unread', 'read', 'replied'])->default('unread')->after('message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $columns = [];
            foreach (['name', 'phone', 'email', 'subject', 'message', 'status'] as $col) {
                if (Schema::hasColumn('contact_messages', $col)) {
                    $columns[] = $col;
                }
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
