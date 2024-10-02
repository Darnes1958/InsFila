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
        Schema::connection('Yzen')->create('hafithas', function (Blueprint $table) {
            $table->id();
            $table->date('from_date');
            $table->date('to_date');
            $table->decimal('tot',12,3);
            $table->decimal('morahel',12,3);
            $table->decimal('over',12,3);
            $table->decimal('over_arc',12,3);
            $table->decimal('wrong',12,3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hafithas');
    }
};
