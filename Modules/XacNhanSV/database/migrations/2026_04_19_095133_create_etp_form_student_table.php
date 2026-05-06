<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etp_form_student', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->index();
            $table->string('studentid', 10)->index();
            $table->integer('formid')->index();
            $table->dateTime('date1')->nullable();
            $table->dateTime('date2')->nullable();
            $table->text('note')->nullable();
            $table->longText('data')->nullable();
            $table->integer('status')->default(0); // 0=pending, 1=approved, 2=rejected
            $table->string('get_at', 10)->default('P/CTSV');
            $table->string('ReceivingAddress', 300);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etp_form_student');
    }
};