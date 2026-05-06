<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etp_form', function (Blueprint $table) {
            $table->integer('formid')->autoIncrement()->primary();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('url', 255)->nullable();
            $table->string('signtitle', 255)->nullable()->default('Hiệu trưởng');
            $table->string('signname', 255)->nullable()->default('PGS. TS Cao Hào Thi');
            $table->string('schoolid', 10)->nullable();
            $table->string('schoolname', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etp_form');
    }
};