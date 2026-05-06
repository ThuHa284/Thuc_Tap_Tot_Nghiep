<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etp_form_student_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('form_student_id')->nullable()->index();
            $table->string('filename', 255)->nullable();
            $table->string('path', 255)->nullable();
            $table->string('original_name', 255)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etp_form_student_detail');
    }
};