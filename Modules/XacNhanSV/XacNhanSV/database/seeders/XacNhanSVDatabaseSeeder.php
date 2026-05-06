<?php

namespace Modules\XacNhanSV\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class XacNhanSVDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed 5 mẫu giấy tờ
        $forms = [
            [
                'formid'      => 1,
                'name'        => '1. Đơn xin xác nhận hoãn nghĩa vụ quân sự',
                'description' => 'Các loại giấy tờ cần nộp khi tạo đơn. 1. Giấy chứng nhận của địa phương 2. Biên lai đóng học phí',
                'url'         => '1. Đơn nhập học,2. Giấy chứng nhận địa phương,3. Giấy mời của GVCN',
                'signtitle'   => 'Hiệu trưởng',
                'signname'    => 'PGS. TS. Cao Hào Thi',
            ],
            [
                'formid'      => 2,
                'name'        => '2. Đơn xin xác nhận khác',
                'description' => '1. Đơn nhập học 2. Giấy chứng nhận địa phương. 3. Giấy mời của GVCN',
                'url'         => '1. Đơn nhập học,2. Giấy chứng nhận địa phương,3. Giấy mời của GVCN',
                'signtitle'   => 'Hiệu trưởng',
                'signname'    => 'PGS. TS. Cao Hào Thi',
            ],
            [
                'formid'      => 3,
                'name'        => '3. Giấy xác nhận vay vốn sinh viên',
                'description' => 'Các lưu ý khi xin đơn này',
                'url'         => null,
                'signtitle'   => 'Hiệu trưởng',
                'signname'    => 'PGS. TS. Cao Hào Thi',
            ],
            [
                'formid'      => 4,
                'name'        => 'Giấy xác nhận không bị xử phạt hành chính',
                'description' => null,
                'url'         => null,
                'signtitle'   => 'Hiệu trưởng',
                'signname'    => 'PGS. TS. Cao Hào Thi',
            ],
            [
                'formid'      => 5,
                'name'        => 'Giấy xác nhận cho P LĐ TBXH ưu đãi trong chế độ giáo dục',
                'description' => null,
                'url'         => null,
                'signtitle'   => 'Hiệu trưởng',
                'signname'    => 'PGS. TS. Cao Hào Thi',
            ],
        ];

        foreach ($forms as $form) {
            DB::table('etp_form')->insertOrIgnore($form);
        }
    }
}