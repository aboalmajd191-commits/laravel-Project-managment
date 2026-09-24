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
        if (!Schema::hasColumn('projects', 'sector_type')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->enum('sector_type', ['protection', 'education', 'health'])->after('service_type')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('projects', 'sector_type')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('sector_type');
            });
        }
    }
};
