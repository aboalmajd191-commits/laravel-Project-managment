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
        $tables = [
            'awareness_workshops',
            'trainings',
            'group_support_sessions',
            'economic_projects',
            'legal_consultations',
            'judicial_representations',
            'legal_representations',
            'mediations',
            'individual_support_sessions',
            'psychological_consultations',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'sector_type')) {
                    $table->enum('sector_type', ['protection', 'education', 'health'])->nullable()->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'awareness_workshops',
            'trainings',
            'group_support_sessions',
            'economic_projects',
            'legal_consultations',
            'judicial_representations',
            'legal_representations',
            'mediations',
            'individual_support_sessions',
            'psychological_consultations',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'sector_type')) {
                    $table->dropColumn('sector_type');
                }
            });
        }
    }
};
