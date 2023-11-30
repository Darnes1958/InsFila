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
        Schema::connection('other')->create('main_arcs', function (Blueprint $table) {
          $table->id();
          $table->foreignId('bank_id')->constrained('banks')->cascadeOnDelete();
          $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
          $table->string('acc');
          $table->date('sul_begin');
          $table->date('sul_end');
          $table->decimal('sul',12,3);
          $table->integer('kst_count');
          $table->decimal('kst',12,3);
          $table->decimal('pay',12,3)->default(0);
          $table->decimal('raseed',12,3)->default(0);
          $table->foreignId('sell_id')->default(1)->constrained('sells')->cascadeOnDelete();
          $table->text('notes')->nullable();


          $table->date('LastKsm')->nullable();
          $table->date('NextKst')->default(now());
          $table->date('LastUpd')->default(now());
          $table->integer('Late')->default(0);

          $table->integer('kst_baky')->default(0);
          $table->bigInteger('last_cont')->default('0');
          $table->integer('over_count')->default(0);
          $table->decimal('over_kst',12,3)->default(0);
          $table->integer('tar_count')->default(0);
          $table->decimal('tar_kst',12,3)->default(0);

          $table->bigInteger('user_id');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_arcs');
    }
};
