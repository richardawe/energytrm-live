<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('index_definitions', function (Blueprint $table) {
            if (!Schema::hasColumn('index_definitions', 'index_subgroup'))
                $table->string('index_subgroup', 100)->nullable()->after('index_group');
            if (!Schema::hasColumn('index_definitions', 'label'))
                $table->string('label', 100)->nullable()->after('index_name');
            if (!Schema::hasColumn('index_definitions', 'delivery_unit'))
                $table->string('delivery_unit', 50)->nullable()->after('class');
            if (!Schema::hasColumn('index_definitions', 'date_sequence'))
                $table->string('date_sequence', 50)->nullable()->after('delivery_unit');
            if (!Schema::hasColumn('index_definitions', 'payment_convention'))
                $table->string('payment_convention', 50)->nullable()->after('date_sequence');
            if (!Schema::hasColumn('index_definitions', 'coverage_end_date'))
                $table->date('coverage_end_date')->nullable()->after('payment_convention');
            if (!Schema::hasColumn('index_definitions', 'interpolation'))
                $table->string('interpolation', 50)->nullable()->after('coverage_end_date')->comment('Back-Step, Front-Step, Linear');
            if (!Schema::hasColumn('index_definitions', 'inheritance'))
                $table->boolean('inheritance')->default(false)->after('interpolation');
            if (!Schema::hasColumn('index_definitions', 'discount_index_id')) {
                $table->unsignedBigInteger('discount_index_id')->nullable()->after('inheritance');
                $table->foreign('discount_index_id')->references('id')->on('index_definitions')->nullOnDelete();
            }
            if (!Schema::hasColumn('index_definitions', 'reference_source'))
                $table->string('reference_source', 100)->nullable()->after('discount_index_id');
            if (!Schema::hasColumn('index_definitions', 'projection_method'))
                $table->string('projection_method', 100)->nullable()->after('reference_source');
            if (!Schema::hasColumn('index_definitions', 'day_start_time'))
                $table->time('day_start_time')->nullable()->after('projection_method');
            if (!Schema::hasColumn('index_definitions', 'holiday_schedule'))
                $table->string('holiday_schedule', 100)->nullable()->after('day_start_time');
            if (!Schema::hasColumn('index_definitions', 'index_type'))
                $table->string('index_type', 20)->nullable()->after('holiday_schedule')->comment('Standard or Composite');
            if (!Schema::hasColumn('index_definitions', 'version_status'))
                $table->string('version_status', 30)->default('Pending')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('index_definitions', function (Blueprint $table) {
            if (Schema::hasColumn('index_definitions', 'discount_index_id'))
                $table->dropForeign(['discount_index_id']);
            $table->dropColumn(array_filter([
                Schema::hasColumn('index_definitions', 'index_subgroup')   ? 'index_subgroup'    : null,
                Schema::hasColumn('index_definitions', 'label')            ? 'label'             : null,
                Schema::hasColumn('index_definitions', 'delivery_unit')    ? 'delivery_unit'     : null,
                Schema::hasColumn('index_definitions', 'date_sequence')    ? 'date_sequence'     : null,
                Schema::hasColumn('index_definitions', 'payment_convention')? 'payment_convention': null,
                Schema::hasColumn('index_definitions', 'coverage_end_date')? 'coverage_end_date' : null,
                Schema::hasColumn('index_definitions', 'interpolation')    ? 'interpolation'     : null,
                Schema::hasColumn('index_definitions', 'inheritance')      ? 'inheritance'       : null,
                Schema::hasColumn('index_definitions', 'discount_index_id')? 'discount_index_id' : null,
                Schema::hasColumn('index_definitions', 'reference_source') ? 'reference_source'  : null,
                Schema::hasColumn('index_definitions', 'projection_method')? 'projection_method' : null,
                Schema::hasColumn('index_definitions', 'day_start_time')   ? 'day_start_time'    : null,
                Schema::hasColumn('index_definitions', 'holiday_schedule') ? 'holiday_schedule'  : null,
                Schema::hasColumn('index_definitions', 'index_type')       ? 'index_type'        : null,
                Schema::hasColumn('index_definitions', 'version_status')   ? 'version_status'    : null,
            ]));
        });
    }
};
