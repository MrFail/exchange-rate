<?php

use App\Models\Bank;
use App\Models\Currency;
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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->date('exchange_rate_date')->nullable();
            $table->decimal('value', 8, 4);
            $table->decimal('change_since_yesterday', 8, 4);
            $table->foreignIdFor(Currency::class)->constrained();
            $table->foreignIdFor(Bank::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
