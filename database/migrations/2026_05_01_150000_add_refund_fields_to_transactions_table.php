<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('refunded_amount', 10, 2)->default(0)->after('paid');
            $table->timestamp('refunded_at')->nullable()->after('refunded_amount');
            $table->string('refund_reason')->nullable()->after('refunded_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['refunded_amount', 'refunded_at', 'refund_reason']);
        });
    }
};
