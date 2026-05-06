<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tintuc', function (Blueprint $table) {
            if (!Schema::hasColumn('tintuc', 'attachment_path')) {
                $table->string('attachment_path')->nullable();
            }

            if (!Schema::hasColumn('tintuc', 'attachment_name')) {
                $table->string('attachment_name')->nullable();
            }

            if (!Schema::hasColumn('tintuc', 'is_khai_bao_noi_tru')) {
                $table->boolean('is_khai_bao_noi_tru')->default(false);
            }

            if (!Schema::hasColumn('tintuc', 'khai_bao_start_at')) {
                $table->dateTime('khai_bao_start_at')->nullable();
            }

            if (!Schema::hasColumn('tintuc', 'khai_bao_end_at')) {
                $table->dateTime('khai_bao_end_at')->nullable();
            }

            if (!Schema::hasColumn('tintuc', 'attachment_label')) {
                $table->string('attachment_label')->nullable();
            }

            if (!Schema::hasColumn('tintuc', 'attachments')) {
                $table->json('attachments')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tintuc', function (Blueprint $table) {
            foreach (['attachment_path', 'attachment_name', 'is_khai_bao_noi_tru', 'khai_bao_start_at', 'khai_bao_end_at', 'attachment_label', 'attachments'] as $column) {
                if (Schema::hasColumn('tintuc', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};