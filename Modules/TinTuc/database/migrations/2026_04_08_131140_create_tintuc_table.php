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
        if (!Schema::hasTable('tintuc')) {
            Schema::create('tintuc', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('img')->nullable();
                $table->text('content');
                $table->integer('status')->default(1);
                $table->date('date1')->nullable();

                $table->unsignedBigInteger('loaitin_id');
                $table->foreign('loaitin_id')->references('id')->on('loaitin')->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tintuc');
    }
};
