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
            $table->foreignId('sell_id')->default(1)->constrained('sells')->cascadeOnDelete();
            $table->decimal('raseed',12,3)->default(0);
            $table->date('LastKsm')->nullable();
            $table->date('NextKst')->default(now());
            $table->integer('Late')->default(0);
            $table->date('LastUpd')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mains', function (Blueprint $table) {
            $table->dropColumn(['LastUpd',  'raseed',  'Late',  'NextKst',  'LastKsm','sell_id']);
        });
    }
};
