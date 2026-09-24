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
        Schema::table('economic_projects', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('coordinator_name');
            $table->date('end_date')->nullable()->after('start_date');
            $table->date('project_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('economic_projects', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
