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
        // 1. Tables that have 'age' -> change to 'birth_date'
        $tablesWithAge = [
            'attendees',
            'individual_support_sessions',
            'psychological_consultations',
            'group_support_session_attendees'
        ];

        foreach ($tablesWithAge as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'birth_date')) {
                    $afterColumn = Schema::hasColumn($tableName, 'id_number') ? 'id_number' : (Schema::hasColumn($tableName, 'case_code') ? 'case_code' : null);
                    if ($afterColumn) {
                        $table->date('birth_date')->nullable()->after($afterColumn);
                    } else {
                        $table->date('birth_date')->nullable();
                    }
                }
            });
        }

        // 2. Add 'name_en' and 'description' to all primary service tables and attendee tables
        $allPrimaryTables = [
            'trainings',
            'economic_projects',
            'awareness_workshops',
            'individual_support_sessions',
            'group_support_sessions',
            'legal_consultations',
            'psychological_consultations',
            'judicial_representations',
            'legal_representations',
            'mediations',
        ];

        foreach ($allPrimaryTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'name_en')) {
                    $table->string('name_en')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'birth_date') && $tableName !== 'trainings' && $tableName !== 'awareness_workshops' && $tableName !== 'group_support_sessions') {
                    $table->date('birth_date')->nullable();
                }
            });
        }

        // 3. Attendee/Beneficiary specific tables
        $beneficiaryTables = ['attendees', 'group_support_session_attendees'];
        foreach ($beneficiaryTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'name_en')) {
                    $table->string('name_en')->nullable();
                }
            });
        }

        // 4. Special case: individual_support_sessions needs a name field if it doesn't have one
        Schema::table('individual_support_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('individual_support_sessions', 'beneficiary_name')) {
                $table->string('beneficiary_name')->nullable()->after('case_code');
            }
        });
    }

    public function down(): void
    {
        // Simple rollback (might be incomplete but safe)
        $allTables = [
            'attendees', 'economic_projects', 'awareness_workshops',
            'individual_support_sessions', 'group_support_sessions',
            'legal_consultations', 'psychological_consultations',
            'judicial_representations', 'legal_representations', 'mediations',
            'group_support_session_attendees'
        ];

        foreach ($allTables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $columns = [];
                    if (Schema::hasColumn($tableName, 'name_en')) $columns[] = 'name_en';
                    if (Schema::hasColumn($tableName, 'description')) $columns[] = 'description';
                    if (Schema::hasColumn($tableName, 'birth_date')) $columns[] = 'birth_date';
                    if (Schema::hasColumn($tableName, 'beneficiary_name')) $columns[] = 'beneficiary_name';
                    
                    if (!empty($columns)) {
                        $table->dropColumn($columns);
                    }
                });
            }
        }
    }
};
