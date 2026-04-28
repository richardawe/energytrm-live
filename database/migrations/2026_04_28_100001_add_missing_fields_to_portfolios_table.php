<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('long_name', 255)->nullable()->after('name');
            $table->string('type', 50)->nullable()->after('long_name')->comment('Trading, Hedging, Treasury, etc.');
            $table->unsignedBigInteger('currency_id')->nullable()->after('type');
            $table->boolean('requires_strategy')->default(false)->after('is_restricted');
            $table->unsignedBigInteger('linked_portfolio_id')->nullable()->after('requires_strategy');

            $table->foreign('currency_id')->references('id')->on('currencies')->nullOnDelete();
            $table->foreign('linked_portfolio_id')->references('id')->on('portfolios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropForeign(['linked_portfolio_id']);
            $table->dropColumn(['long_name', 'type', 'currency_id', 'requires_strategy', 'linked_portfolio_id']);
        });
    }
};
