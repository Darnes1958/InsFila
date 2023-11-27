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
        Schema::connection('other')->create('trans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_id')->constrained('mains')->cascadeOnDelete();
            $table->foreignId('ksm_type_id')->constrained('ksm_types')->cascadeOnDelete();
            $table->integer('ser');
            $table->date('kst_date');
            $table->date('ksm_date');
            $table->decimal('ksm',12,3);
            $table->string('ksm_notes')->nullable();
            $table->integer('h_no')->default(0);
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans');
    }
};
