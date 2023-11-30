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
        Schema::connection('other')->create('overkst_arcs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_id')->constrained('mains')->cascadeOnDelete();
            $table->date('over_date');
            $table->decimal('kst',12,3);
            $table->string('status')->default('غير مرجع');
            $table->bigInteger('tar_id')->default(0);
            $table->bigInteger('haf_id')->default(0);
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overkst_arcs');
    }
};
