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
        Schema::table('trainings', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft')->after('beneficiary_count');
            $table->foreign('parent_id')->references('id')->on('trainings')->onDelete('cascade');
        });

        Schema::table('economic_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->enum('status', ['draft', 'active', 'completed'])->default('draft')->after('total_grant_value');
            $table->foreign('parent_id')->references('id')->on('economic_projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'status']);
        });

        Schema::table('economic_projects', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'status']);
        });
    }
};
