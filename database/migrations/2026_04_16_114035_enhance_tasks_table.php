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
        Schema::table('tasks', function (Blueprint $table) {
            $table->decimal('reward_amount', 12, 2)->default(0);
            $table->string('proof_file')->nullable();
            $table->boolean('extension_requested')->default(false);
            $table->text('extension_reason')->nullable();
            $table->timestamp('original_deadline')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['reward_amount', 'proof_file', 'extension_requested', 'extension_reason', 'original_deadline']);
        });
    }
};
