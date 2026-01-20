<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('application_status')->default('pending')->after('share_certificate_agreed'); // pending, accepted, rejected
            $table->string('certificate_path')->nullable()->after('application_status');
            $table->string('certificate_number')->nullable()->after('certificate_path');
            $table->timestamp('certificate_issued_date')->nullable()->after('certificate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['application_status', 'certificate_path', 'certificate_number', 'certificate_issued_date']);
        });
    }
};
