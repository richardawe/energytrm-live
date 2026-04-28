<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add first_name / last_name to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 100)->nullable()->after('name');
            $table->string('last_name', 100)->nullable()->after('first_name');
        });

        // Functional groups master table
        Schema::create('functional_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // user_functional_groups pivot
        Schema::create('user_functional_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('functional_group_id');
            $table->primary(['user_id', 'functional_group_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('functional_group_id')->references('id')->on('functional_groups')->cascadeOnDelete();
        });

        // user_legal_entities pivot (parties with party_type = 'LE')
        Schema::create('user_legal_entities', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('party_id');
            $table->boolean('is_default')->default(false);
            $table->primary(['user_id', 'party_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('party_id')->references('id')->on('parties')->cascadeOnDelete();
        });

        // user_secured_indices pivot
        Schema::create('user_secured_indices', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('index_id');
            $table->primary(['user_id', 'index_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('index_id')->references('id')->on('index_definitions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_secured_indices');
        Schema::dropIfExists('user_legal_entities');
        Schema::dropIfExists('user_functional_groups');
        Schema::dropIfExists('functional_groups');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};
