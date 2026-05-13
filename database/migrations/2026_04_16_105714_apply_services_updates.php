<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('services', 'company_id')) {
            Schema::table('services', function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('contracts', 'services_json')) {
            Schema::table('contracts', function (Blueprint $table) {
                $table->json('services_json')->nullable()->after('amount');
            });
        }
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('services_json');
        });
    }
};
