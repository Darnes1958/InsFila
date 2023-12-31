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
        Schema::connection('other')->create('trans_arcs', function (Blueprint $table) {
          $table->id();
          $table->foreignId('main_id')->constrained('mains')->cascadeOnDelete();
          $table->foreignId('ksm_type_id')->constrained('ksmtypes')->cascadeOnDelete();
          $table->integer('ser');
          $table->date('kst_date');
          $table->date('ksm_date');
          $table->decimal('ksm',12,3);
          $table->string('ksm_notes')->nullable();
          $table->bigInteger('haf_id')->default(0);
          $table->bigInteger('over_id')->default(0);
          $table->decimal('baky',12,3)->default(0);

          $table->bigInteger('user_id');
          $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_arcs');
    }
};
