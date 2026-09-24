<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('economic_projects', function (Blueprint $table) {
            $table->index('owner_name');
            $table->index('id_number');
            $table->index('project_name');
            $table->index('funder');
        });

        Schema::table('legal_consultations', function (Blueprint $table) {
            $table->index('full_name');
            $table->index('id_number');
            $table->index('phone');
        });

        Schema::table('psychological_consultations', function (Blueprint $table) {
            $table->index('full_name');
            $table->index('id_number');
            $table->index('mobile');
        });

        Schema::table('judicial_representations', function (Blueprint $table) {
            $table->index('beneficiary_name');
            $table->index('id_number');
            $table->index('mobile');
        });

        Schema::table('legal_representations', function (Blueprint $table) {
            $table->index('beneficiary_name');
            $table->index('id_number');
            $table->index('mobile');
        });

        Schema::table('mediations', function (Blueprint $table) {
            $table->index('beneficiary_name');
            $table->index('id_number');
            $table->index('mobile');
        });

        Schema::table('individual_support_sessions', function (Blueprint $table) {
            $table->index('beneficiary_name');
            $table->index('mobile');
        });

        Schema::table('attendees', function (Blueprint $table) {
            $table->index('name');
            $table->index('id_number');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::table('economic_projects', function (Blueprint $table) {
            $table->dropIndex(['owner_name']);
            $table->dropIndex(['id_number']);
            $table->dropIndex(['project_name']);
            $table->dropIndex(['funder']);
        });

        Schema::table('legal_consultations', function (Blueprint $table) {
            $table->dropIndex(['full_name']);
            $table->dropIndex(['id_number']);
            $table->dropIndex(['phone']);
        });

        Schema::table('psychological_consultations', function (Blueprint $table) {
            $table->dropIndex(['full_name']);
            $table->dropIndex(['id_number']);
            $table->dropIndex(['mobile']);
        });

        Schema::table('judicial_representations', function (Blueprint $table) {
            $table->dropIndex(['beneficiary_name']);
            $table->dropIndex(['id_number']);
            $table->dropIndex(['mobile']);
        });

        Schema::table('legal_representations', function (Blueprint $table) {
            $table->dropIndex(['beneficiary_name']);
            $table->dropIndex(['id_number']);
            $table->dropIndex(['mobile']);
        });

        Schema::table('mediations', function (Blueprint $table) {
            $table->dropIndex(['beneficiary_name']);
            $table->dropIndex(['id_number']);
            $table->dropIndex(['mobile']);
        });

        Schema::table('individual_support_sessions', function (Blueprint $table) {
            $table->dropIndex(['beneficiary_name']);
            $table->dropIndex(['mobile']);
        });

        Schema::table('attendees', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['id_number']);
            $table->dropIndex(['phone']);
        });
    }
};
