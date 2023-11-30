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
        Schema::connection('other')->table('mains', function (Blueprint $table) {
            $table->bigInteger('last_cont')->default('0');
            $table->integer('over_count')->default(0);
            $table->decimal('over_kst',12,3)->default(0);
            $table->integer('tar_count')->default(0);
            $table->decimal('tar_kst',12,3)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mains', function (Blueprint $table) {

            $table->dropColumn(['last_cont',  'over_count',  'over_kst',  'tar_count',  'tar_kst']);


        });
    }
};
