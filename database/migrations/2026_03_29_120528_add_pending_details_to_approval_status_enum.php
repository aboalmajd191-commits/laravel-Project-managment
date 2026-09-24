<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE trainings MODIFY COLUMN approval_status ENUM('pending', 'approved', 'rejected', 'pending_details') DEFAULT 'pending_details'");
        DB::statement("ALTER TABLE economic_projects MODIFY COLUMN approval_status ENUM('pending', 'approved', 'rejected', 'pending_details') DEFAULT 'pending_details'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE trainings MODIFY COLUMN approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        DB::statement("ALTER TABLE economic_projects MODIFY COLUMN approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
