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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname', 255)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('city', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nickname');
            $table->dropColumn('avatar');
            $table->dropColumn('phone_number');
            $table->dropColumn('city');
        });
    }
};
