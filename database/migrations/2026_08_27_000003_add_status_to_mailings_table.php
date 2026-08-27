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
        Schema::table('mailings', function (Blueprint $table) {
            // Существующие записи были отправлены синхронно ещё до появления очереди,
            // поэтому для них status по умолчанию — 'completed'.
            $table->string('status')->default('completed')->after('account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mailings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
