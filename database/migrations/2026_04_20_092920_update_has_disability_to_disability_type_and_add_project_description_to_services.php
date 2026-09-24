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
        // 1. Tables with 'has_disability' -> change to 'disability_type' (string)
        $tablesWithDisability = [
            'attendees',
            'legal_consultations',
            'judicial_representations',
            'legal_representations',
            'mediations',
        ];

        foreach ($tablesWithDisability as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'has_disability')) {
                    $table->dropColumn('has_disability');
                }
                if (!Schema::hasColumn($tableName, 'disability_type')) {
                    $table->string('disability_type')->nullable();
                }
            });
        }

        // 2. Tables that need 'disability_type' added (if not present)
        $additionalTables = [
            'group_support_session_attendees',
            'individual_support_sessions',
            'psychological_consultations', 
            'economic_projects',
        ];

        foreach ($additionalTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'disability_type')) {
                    $table->string('disability_type')->nullable();
                }
            });
        }

        // 3. Add 'project_description' specifically where requested (or ensure 'description' exists)
        $tablesForProjectDesc = [
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

        foreach ($tablesForProjectDesc as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'project_description')) {
                    $table->text('project_description')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablesForProjectDesc = [
            'awareness_workshops',
            'trainings',
            'group_support_sessions',
            'economic_projects',
        ];
        foreach ($tablesForProjectDesc as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'project_description')) {
                    $table->dropColumn('project_description');
                }
            });
        }

        $allTables = [
            'attendees',
            'legal_consultations',
            'judicial_representations',
            'legal_representations',
            'mediations',
            'group_support_session_attendees',
            'individual_support_sessions',
            'psychological_consultations',
            'economic_projects',
        ];
        foreach ($allTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'disability_type')) {
                    $table->dropColumn('disability_type');
                }
            });
        }
    }
};
