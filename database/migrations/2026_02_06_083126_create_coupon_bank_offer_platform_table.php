<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('coupon_bank_offers', function (Blueprint $table) {
    $table->id();

    $table->foreignId('coupon_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('bank_name');                 // HDFC
    $table->enum('card_type', ['credit', 'debit', 'emi']);

    $table->enum('type', ['fixed', 'percentage']);
    $table->decimal('value', 10, 2);
    $table->decimal('max_discount', 10, 2)->nullable();

    $table->boolean('is_active')->default(true);

    $table->timestamp('starts_at')->nullable();
    $table->timestamp('expires_at')->nullable();

    $table->timestamps();
});

    }
    public function down(): void
    {
        Schema::dropIfExists('coupon_bank_offer_platform');
    }
};
