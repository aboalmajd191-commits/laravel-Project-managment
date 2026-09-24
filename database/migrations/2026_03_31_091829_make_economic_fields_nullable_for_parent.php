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
            $table->string('owner_name')->nullable()->change();
            $table->decimal('grant_value', 10, 2)->nullable()->change();
            $table->date('grant_date')->nullable()->change();
            $table->unsignedInteger('individuals_count')->nullable()->change();
            $table->string('id_number', 20)->nullable()->change();
            $table->string('marital_status')->nullable()->change();
            $table->string('governorate', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('economic_projects', function (Blueprint $table) {
            $table->string('owner_name')->nullable(false)->change();
            $table->decimal('grant_value', 10, 2)->nullable(false)->change();
            $table->date('grant_date')->nullable(false)->change();
            $table->unsignedInteger('individuals_count')->nullable(false)->change();
            $table->string('id_number', 20)->nullable(false)->change();
            $table->string('marital_status')->nullable(false)->change();
            $table->string('governorate', 100)->nullable(false)->change();
        });
    }
};
