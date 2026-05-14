<?php

namespace Modules\TinTuc\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\TinTuc\Database\Factories\TinTucFactory;

class TinTuc extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tintuc';
    protected $fillable = [
        'title',
        'img',
        'content',
        'status',
        'date1',
        'loaitin_id',
        'attachment_path',
        'attachment_name',
        'attachment_label',
        'attachments',
        'is_khai_bao_noi_tru',
        'khai_bao_ky',
        'khai_bao_start_at',
        'khai_bao_end_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_khai_bao_noi_tru' => 'boolean',
    ];

    public function loaitin(){
        return $this->belongsTo(LoaiTin::class,'loaitin_id','id');
    }

    public function getAttachmentTypeLabelAttribute(): ?string
    {
        $source = $this->attachment_name ?: $this->attachment_path;

        if (empty($source)) {
            return null;
        }

        $extension = strtolower(pathinfo($source, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'PDF',
            'xls', 'xlsx' => 'Excel',
            'csv' => 'CSV',
            'doc', 'docx' => 'Word',
            default => 'Tệp',
        };
    }

    public function getAttachmentDisplayLabelAttribute(): ?string
    {
        return $this->attachment_label ?: $this->attachment_type_label;
    }

    public function getAttachmentItemsAttribute(): array
    {
        $items = [];

        if (!empty($this->attachment_path)) {
            $items[] = [
                'label' => $this->attachment_display_label ?: $this->attachment_type_label ?: 'Tệp đính kèm',
                'path' => $this->attachment_path,
                'name' => $this->attachment_name,
            ];
        }

        foreach (($this->attachments ?? []) as $attachment) {
            if (empty($attachment['path'])) {
                continue;
            }

            $items[] = [
                'label' => $attachment['label'] ?? 'Tệp đính kèm',
                'path' => $attachment['path'],
                'name' => $attachment['name'] ?? basename($attachment['path']),
            ];
        }

        return $items;
    }

    public static function currentKhaiBaoNoiTru()
    {
        return static::query()
            ->where('is_khai_bao_noi_tru', true)
            ->whereNotNull('khai_bao_start_at')
            ->whereNotNull('khai_bao_end_at')
            ->where('khai_bao_start_at', '<=', now())
            ->where('khai_bao_end_at', '>=', now())
            ->orderByDesc('khai_bao_start_at')
            ->first();
    }

    // protected static function newFactory(): TinTucFactory
    // {
    //     // return TinTucFactory::new();
    // }
}
