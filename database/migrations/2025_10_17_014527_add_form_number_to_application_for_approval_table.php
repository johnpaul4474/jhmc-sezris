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
        Schema::table('application_for_approval', function (Blueprint $table) {
            $table->string('form_number')->nullable()->after('approver_group_id');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_for_approval', function (Blueprint $table) {
            $table->dropColumn('form_number');
        });
    }
};
