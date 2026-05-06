<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etp_form_detail', function (Blueprint $table) {
            $table->integer('fdetailid')->autoIncrement()->primary();
            $table->integer('formid')->nullable()->index();
            $table->string('label', 255)->nullable();
            $table->integer('fdetail_order')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etp_form_detail');
    }
};