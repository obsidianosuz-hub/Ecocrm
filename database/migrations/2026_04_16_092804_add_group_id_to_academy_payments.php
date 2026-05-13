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
        Schema::table('academy_payments', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->after('student_id')->constrained()->onDelete('set null');
            $table->string('month', 10)->nullable()->after('comment');
            $table->integer('year')->nullable()->after('month');
            $table->boolean('is_full_payment')->default(true)->after('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academy_payments', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['group_id', 'month', 'year', 'is_full_payment']);
        });
    }
};
