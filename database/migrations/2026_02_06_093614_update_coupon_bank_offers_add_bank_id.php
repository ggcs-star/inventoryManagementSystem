<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupon_bank_offers', function (Blueprint $table) {

            $table->foreignId('bank_id')
                ->after('coupon_id')
                ->constrained('banks')
                ->cascadeOnDelete();

            $table->dropColumn('bank_name');
        });
    }

    public function down(): void
    {
        Schema::table('coupon_bank_offers', function (Blueprint $table) {

            // rollback
            $table->string('bank_name')->after('coupon_id');
            $table->dropForeign(['bank_id']);
            $table->dropColumn('bank_id');
        });
    }
};
