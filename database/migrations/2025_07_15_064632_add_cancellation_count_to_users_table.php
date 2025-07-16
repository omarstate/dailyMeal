<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('daily_cancellations')->default(0);
            $table->timestamp('last_cancellation_at')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('block_expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('daily_cancellations');
            $table->dropColumn('last_cancellation_at');
            $table->dropColumn('is_blocked');
            $table->dropColumn('block_expires_at');
        });
    }
}; 