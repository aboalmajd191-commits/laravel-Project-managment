<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('parent_project')->nullable()->after('name');
        });

        Schema::table('economic_projects', function (Blueprint $table) {
            $table->string('parent_project')->nullable()->after('project_name');
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('parent_project');
        });

        Schema::table('economic_projects', function (Blueprint $table) {
            $table->dropColumn('parent_project');
        });
    }
};
