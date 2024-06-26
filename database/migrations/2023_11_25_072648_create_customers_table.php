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
        Schema::connection('other')->create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mdar')->nullable();
            $table->string('libyana')->nullable();
            $table->string('address')->nullable();
            $table->string('card_no')->nullable();
            $table->string('others')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
