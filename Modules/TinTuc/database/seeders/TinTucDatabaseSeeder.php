<?php

namespace Modules\TinTuc\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TinTucDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================
        // BẢNG LOẠI TIN - 15 LOẠI TIN
        // ============================================
        $loaiTins = [
            ['name' => 'Tin tức chung'],
            ['name' => 'Học bổng'],
            ['name' => 'Hoạt động sinh viên'],
            ['name' => 'Tuyển dụng'],
            ['name' => 'Sự kiện'],
            ['name' => 'Hướng nghiệp'],
            ['name' => 'Công nghệ'],
            ['name' => 'Thể thao'],
            ['name' => 'Văn hóa - Nghệ thuật'],
            ['name' => 'Khoa học'],
            ['name' => 'Giáo dục'],
            ['name' => 'Kỹ năng mềm'],
            ['name' => 'Du học'],
            ['name' => 'Môi trường'],
            ['name' => 'An toàn - PCCC'],
        ];

        DB::table('loaitin')->insert($loaiTins);

        // ============================================
        // BẢNG TIN TỨC - 40 TIN TỨC MẪU VỚI ẢNH
        // ============================================
        $tintucs = [
            // TIN TỨC CHUNG (loaitin_id = 1)
            [
                'title' => 'Lễ khai giảng năm học 2026-2027: Chào đón 15.000 tân sinh viên',
                'content' => 'Ngày 05/09/2026, trường đã tổ chức Lễ khai giảng năm học 2026-2027 với sự tham gia của hơn 15.000 tân sinh viên đến từ 50 tỉnh thành trên cả nước. Tham dự buổi lễ có đại diện lãnh đạo Bộ GD&ĐT, lãnh đạo thành phố cùng các doanh nghiệp đối tác. Năm nay, trường đón nhận thêm 5 chương trình đào tạo mới: Trí tuệ nhân tạo, Khoa học dữ liệu, An toàn thông tin, Kinh tế số và Thiết kế đồ họa. Hiệu trưởng nhà trường nhấn mạnh: "Chúng tôi cam kết đồng hành cùng các em trên con đường học vấn, giúp các em trở thành những công dân toàn cầu có ích cho xã hội."',
                'loaitin_id' => 1,
                'img' => 'https://picsum.photos/seed/khaigiang2026/800/500',
                'created_at' => '2026-09-05 08:00:00',
            ],
            [
                'title' => 'Trường công bố xếp hạng Top 50 đại học tốt nhất Đông Nam Á 2026',
                'content' => 'Trong bảng xếp hạng QS World University Rankings 2026, trường đã tăng 23 bậc so với năm 2025, vươn lên vị trí thứ 47/200 trường đại học tốt nhất Đông Nam Á. Đặc biệt, các tiêu chí về "Triển vọng việc làm của sinh viên" và "Giảng viên quốc tế" được đánh giá cao với điểm số 85/100. Năm 2026, trường cũng đạt chứng nhận AUN-QA cho 12 chương trình đào tạo, nâng tổng số chương trình đạt chuẩn lên 35.',
                'loaitin_id' => 1,
                'img' => 'https://picsum.photos/seed/xephang2026/800/500',
                'created_at' => '2026-08-20 10:30:00',
            ],
            [
                'title' => 'Thông báo nghỉ Tết Nguyên Đán 2026 cho sinh viên',
                'content' => 'Nhà trường thông báo lịch nghỉ Tết Nguyên Đán Bính Thân 2026 như sau: Sinh viên nghỉ từ ngày 28/01/2026 (mùng 1 Tết) đến hết ngày 10/02/2026 (mùng 13 Tết). Sinh viên nội trú được phép ở lại ký túc xá, phòng tự học mở cửa từ 7h-22h hàng ngày. Các lớp học bù sẽ được sắp xếp vào tuần cuối của học kỳ. Phụ huynh liên hệ phòng Công tác sinh viên qua số hotline 1900.xxxx để được hỗ trợ.',
                'loaitin_id' => 1,
                'img' => 'https://picsum.photos/seed/tet2026/800/500',
                'created_at' => '2026-01-15 14:00:00',
            ],
            [
                'title' => 'Ra mắt ứng dụng "Smart Campus 2026" cho sinh viên',
                'content' => 'Nhà trường chính thức ra mắt ứng dụng "Smart Campus 2026" phiên bản 3.0 với nhiều tính năng mới: đăng ký học phần trực tuyến, tra cứu điểm real-time, đặt lịch hẹn giảng viên, thanh toán học phí qua ví điện tử, bản đồ số nội khu, thông báo thông minh. Ứng dụng hiện có trên App Store và Google Play, đã có hơn 40.000 lượt tải trong tuần đầu ra mắt. Đặc biệt, tính năng AI Assistant trợ giảng 24/7 được sinh viên đánh giá rất cao.',
                'loaitin_id' => 1,
                'img' => 'https://picsum.photos/seed/smartcampus/800/500',
                'created_at' => '2026-03-01 09:00:00',
            ],

            // HỌC BỔNG (loaitin_id = 2)
            [
                'title' => 'Học bổng Toyota 2026: 50 suất 50 triệu đồng cho sinh viên xuất sắc',
                'content' => 'Công ty Toyota Việt Nam phối hợp với trường triển khai chương trình học bổng "Cùng em đến trường 2026" với 50 suất học bổng trị giá 50 triệu đồng/suất. Đối tượng: sinh viên năm 2-4 có GPA từ 3.2/4.0, hoàn cảnh khó khăn, có ý tưởng nghiên cứu hoặc dự án cộng đồng. Hạn nộp hồ sơ: 30/04/2026. Sinh viên quan tâm nộp hồ sơ tại Phòng Đào tạo - Tầng 3 nhà A hoặc qua email: hotro.daotao@truong.edu.vn. Vòng phỏng vấn dự kiến tổ chức vào ngày 15/05/2026.',
                'loaitin_id' => 2,
                'img' => 'https://picsum.photos/seed/toyota2026/800/500',
                'created_at' => '2026-03-10 11:00:00',
            ],
            [
                'title' => 'Học bổng Fulbright 2026-2027: Cơ hội du học Mỹ toàn phần',
                'content' => 'Chương trình học bổng Fulbright Việt Nam 2026-2027 mở đơn tuyển cho sinh viên muốn theo học thạc sĩ tại Mỹ. Gói học bổng bao gồm học phí 2 năm, sinh hoạt phí, bảo hiểm, vé máy bay khứ hồi. Yêu cầu: GPA từ 3.5, IELTS 7.0 hoặc TOEFL iBT 94, có kế hoạch nghiên cứu rõ ràng. Năm 2026, chương trình dành 30 suất cho sinh viên các trường đại học Việt Nam. Thông tin chi tiết tại: www.fulbright.edu.vn. Hạn nộp: 01/06/2026.',
                'loaitin_id' => 2,
                'img' => 'https://picsum.photos/seed/fulbright2026/800/500',
                'created_at' => '2026-02-15 08:30:00',
            ],
            [
                'title' => 'Học bổng Google Developer Students Club 2026',
                'content' => 'Google DSC trường công bố 20 suất học bổng cho sinh viên IT năm 2026. Mỗi suất trị giá 15 triệu đồng, bao gồm khóa học Google Cloud, chứng chỉ Google, và hỗ trợ dự án cá nhân. Đặc biệt, 5 sinh viên xuất sắc nhất sẽ được tham gia Google I/O Extended 2026 tại Singapore. Điều kiện: sinh viên ngành CNTT, Khoa học máy tính, đã hoàn thành ít nhất 1 dự án Android/Web. Đăng ký tại gdsc.truong.edu.vn trước 20/04/2026.',
                'loaitin_id' => 2,
                'img' => 'https://picsum.photos/seed/google2026/800/500',
                'created_at' => '2026-03-20 15:00:00',
            ],
            [
                'title' => 'Học bổng "Vì tương lai xanh" cho sinh viên ngành Môi trường 2026',
                'content' => 'Tổ chức Green Future Foundation trao 30 suất học bổng "Vì tương lai xanh" trị giá 20 triệu đồng/suất cho sinh viên ngành Môi trường, Khoa học trái đất. Ưu tiên sinh viên có nghiên cứu về biến đổi khí hậu, năng lượng tái tạo, kinh tế tuần hoàn. Ngoài học bổng, sinh viên được tham gia 1 tháng thực tập tại các tổ chức môi trường quốc tế. Hạn nộp: 15/05/2026. Liên hệ: PGS.TS. Nguyễn Thị Lan - Khoa Môi trường.',
                'loaitin_id' => 2,
                'img' => 'https://picsum.photos/seed/xanh2026/800/500',
                'created_at' => '2026-04-01 10:00:00',
            ],
            [
                'title' => 'Học bổng ngành Kinh tế 2026 từ Ngân hàng Ngoại thương Việt Nam',
                'content' => 'Ngân hàng TMCP Ngoại thương Việt Nam (Vietcombank) tài trợ 25 suất học bổng cho sinh viên ngành Kinh tế, Tài chính, Ngân hàng. Mỗi suất 25 triệu đồng, kèm cơ hội thực tập và tuyển dụng tại Vietcombank. Điều kiện: sinh viên năm 3-4, GPA từ 3.0, không nợ môn, có chứng chỉ TOEIC 650 trở lên. Hồ sơ nộp tại Phòng Công tác sinh viên hoặc email: vietcombank.scholarship@truong.edu.vn. Hạn chót: 30/04/2026.',
                'loaitin_id' => 2,
                'img' => 'https://picsum.photos/seed/vietcombank2026/800/500',
                'created_at' => '2026-03-25 13:00:00',
            ],
            [
                'title' => 'Học bổng ASEAN 2026: Cơ hội học tập tại Singapore, Malaysia, Thái Lan',
                'content' => 'Quỹ ASEAN trao 40 suất học bổng trao đổi học kỳ 2 năm 2026-2027 cho sinh viên các ngành: Kinh doanh quốc tế, Luật quốc tế, Quan hệ quốc tế. Sinh viên được học 1 học kỳ tại Đại học Quốc gia Singapore (NUS), Đại học Malaya hoặc Đại học Chulalongkorn. Học bổng bao gồm vé máy bay, học phí, sinh hoạt phí 800 USD/tháng. Yêu cầu: GPA 3.3, ngoại ngữ tốt, có khả năng thích nghi với môi trường quốc tế. Hạn nộp: 15/06/2026.',
                'loaitin_id' => 2,
                'img' => 'https://picsum.photos/seed/asean2026/800/500',
                'created_at' => '2026-02-28 09:00:00',
            ],

            // HOẠT ĐỘNG SINH VIÊN (loaitin_id = 3)
            [
                'title' => 'Chiến dịch "Mùa hè xanh 2026": 5.000 sinh viên tham gia tình nguyện',
                'content' => 'Đoàn Thanh niên trường phát động Chiến dịch "Mùa hè xanh 2026" với chủ đề "Tuổi trẻ vì cộng đồng". Dự kiến thu hút 5.000 sinh viên tình nguyện tham gia tại 20 tỉnh thành phía Nam. Các hoạt động gồm: dạy học miễn phí cho trẻ em vùng sâu, hiến máu nhân đạo, xây dựng nhà tình nghĩa, bảo vệ môi trường biển, chuyển đổi số cho người cao tuổi. Sinh viên đăng ký tại: mauhèxanh.truong.edu.vn. Thời gian: 01/06 - 31/08/2026. Đoàn viên hoàn thành tốt sẽ được cộng điểm rèn luyện.',
                'loaitin_id' => 3,
                'img' => 'https://picsum.photos/seed/muhexanh2026/800/500',
                'created_at' => '2026-04-10 08:00:00',
            ],
            [
                'title' => 'CLB Tiếng Anh E-Club tuyển thành viên năm 2026',
                'content' => 'E-Club - Câu lạc bộ Tiếng Anh uy tín nhất trường - chính thức tuyển thành viên năm 2026. CLB tổ chức Speaking Corner hàng tuần, tham gia các cuộc thi Tiếng Anh cấp thành phố, giao lưu với sinh viên quốc tế, workshop kỹ năng phỏng vấn bằng tiếng Anh. Năm 2025, E-Club có 3 thành viên đạt giải nhất cuộc thi English Olympics toàn quốc. Đăng ký online tại eclub.phanmem.com hoặc gặp trực tiếp tại P.301 nhà B. Hạn đăng ký: 20/03/2026.',
                'loaitin_id' => 3,
                'img' => 'https://picsum.photos/seed/eclub2026/800/500',
                'created_at' => '2026-03-01 14:00:00',
            ],
            [
                'title' => 'Sinh viên trường đạt giải Nhất cuộc thi "Sinh viên tài năng 2026"',
                'content' => 'Nguyễn Văn Minh, sinh viên năm 4 ngành Kỹ thuật phần mềm, vừa đạt giải Nhất cuộc thi "Sinh viên tài năng 2026" cấp quốc gia với dự án "Ứng dụng AI chẩn đoán bệnh cây trồng cho nông dân Việt Nam". Dự án được đánh giá cao bởi tính ứng dụng thực tiễn và khả năng mở rộng quy mô. Minh nhận giải thưởng 100 triệu đồng và gói hỗ trợ khởi nghiệp 500 triệu đồng từ Quỹ Đổi mới sáng tạo. Đây là năm thứ 3 liên tiếp sinh viên trường đạt giải cao tại cuộc thi này.',
                'loaitin_id' => 3,
                'img' => 'https://picsum.photos/seed/tailenh2026/800/500',
                'created_at' => '2026-04-15 16:00:00',
            ],
            [
                'title' => 'Festival Sinh viên 2026: "Tôi yêu Tổ quốc" với quy mô hoành tráng',
                'content' => 'Festival Sinh viên lần thứ 15 với chủ đề "Tôi yêu Tổ quốc" sẽ diễn ra từ 20-25/04/2026 tại Sân vận động trường. Chương trình gồm: Lễ diễu hành với 5.000 sinh viên, Hội thi nhảy đống, Cuộc thi âm nhạc "Giọng hát sinh viên", Triển lãm "Tuổi trẻ sáng tạo", Đêm nhạc hòa nhịp "Mùa xuân tuổi trẻ" với sự tham gia của ca sĩ nổi tiếng. Vé được phát miễn phí tại VP Đoàn trường. Đặc biệt, chương trình được phát sóng trực tiếp trên VTV6.',
                'loaitin_id' => 3,
                'img' => 'https://picsum.photos/seed/festival2026/800/500',
                'created_at' => '2026-04-01 10:00:00',
            ],

            // TUYỂN DỤNG (loaitin_id = 4)
            [
                'title' => 'FPT Software tuyển 500 sinh viên IT với mức lương khởi điểm 15 triệu',
                'content' => 'FPT Software - đối tác chiến lược của trường - công bố kế hoạch tuyển dụng 500 sinh viên IT cho năm 2026. Vị trí: Lập trình viên Java, Python, React, DevOps, QA. Mức lương khởi điểm: 15-20 triệu/tháng, thưởng theo dự án, bảo hiểm cao cấp, lộ trình thăng tiến rõ ràng. Đặc biệt, sinh viên được đào tạo chứng chỉ FPT Cloud miễn phí trước khi đi làm. Ngày tuyển dụng: 15/05/2026 tại Trung tâm Hướng nghiệp. Đăng ký tại: fptcareer.phanmem.com. Số lượng có hạn!',
                'loaitin_id' => 4,
                'img' => 'https://picsum.photos/seed/fpt2026/800/500',
                'created_at' => '2026-03-20 09:00:00',
            ],
            [
                'title' => 'Viettel, VNPT, Mobifone đồng loạt tuyển dụng sinh viên viễn thông 2026',
                'content' => 'Ba tập đoàn viễn thông lớn Viettel, VNPT, Mobifone phối hợp tổ chức "Ngày hội việc làm Viễn thông 2026" vào ngày 22/05/2026. Tổng số vị trí tuyển: 800, gồm: Kỹ sư mạng, Chuyên viên CNTT, Kỹ thuật viên, Nhân viên kinh doanh. Yêu cầu chung: sinh viên năm 4 hoặc đã tốt nghiệp, GPA từ 2.5, có chứng chỉ chuyên ngành. Mức lương: 12-25 triệu/tháng tùy vị trí. Ứng viên mang theo CV và bằng cấp bản gốc. Đăng ký online trước tại vieclamvienthong.phanmem.com.',
                'loaitin_id' => 4,
                'img' => 'https://picsum.photos/seed/vienthong2026/800/500',
                'created_at' => '2026-04-05 11:00:00',
            ],
            [
                'title' => 'Cơ hội việc làm tại các công ty Fintech hàng đầu Việt Nam 2026',
                'content' => 'Hiệp hội Fintech Việt Nam phối hợp với trường tổ chức buổi gặp gỡ doanh nghiệp Fintech ngày 10/05/2026. Tham gia: VNPay, MoMo, ZaloPay,VNDC, Finhay, K，红Bank. Các vị trí tuyển: Backend Developer, Mobile Developer, Data Analyst, Risk Management, Compliance. Yêu cầu: sinh viên ngành CNTT, Tài chính, Toán Ứng dụng. Đặc biệt, ứng viên quan tâm đến Blockchain/Crypto sẽ được ưu tiên. Đăng ký tại: fintechcareer.phanmem.com. Số lượng ghế ngồi: 200.',
                'loaitin_id' => 4,
                'img' => 'https://picsum.photos/seed/fintech2026/800/500',
                'created_at' => '2026-04-12 14:00:00',
            ],
            [
                'title' => 'Grab, Shopee, Lazada tuyển sinh viên thực tập mùa hè 2026',
                'content' => 'Nhân dịp mùa hè 2026, Grab, Shopee, Lazada mở chương trình thực tập "Summer Internship 2026" với 300 vị trí. Sinh viên năm 2-3 được đăng ký thực tập tại các phòng ban: Công nghệ, Marketing, Vận hành, Tài chính, Nhân sự. Thời gian: 01/06 - 31/08/2026. Phúc lợi: lương 8-10 triệu/tháng, bữa trưa miễn phí, hỗ trợ di chuyển. Sinh viên hoàn thành tốt có cơ hội nhận full-time offer. Hạn nộp: 30/04/2026 tại internship.phanmem.com.',
                'loaitin_id' => 4,
                'img' => 'https://picsum.photos/seed/thuctap2026/800/500',
                'created_at' => '2026-03-25 10:00:00',
            ],

            // SỰ KIỆN (loaitin_id = 5)
            [
                'title' => 'Tuần lễ "Đổi mới sáng tạo và Khởi nghiệp 2026"',
                'content' => 'Trường đăng cai Tuần lễ "Đổi mới sáng tạo và Khởi nghiệp 2026" từ 10-15/10/2026. Chương trình gồm: Hội thảo với doanh nhân thành đạt, Workshop thiết kế MVP, Pitching day với 50 nhà đầu tư, Demo Day với 30 dự án startup, Giải thưởng Khởi nghiệp trị giá 1 tỷ đồng. Đặc biệt, có buổi talk show "Từ sinh viên đến CEO" với sự tham gia của các founder nổi tiếng. Sinh viên đăng ký tham gia miễn phí tại: startupweek.phanmem.com.',
                'loaitin_id' => 5,
                'img' => 'https://picsum.photos/seed/startup2026/800/500',
                'created_at' => '2026-04-01 09:00:00',
            ],
            [
                'title' => 'Hội thảo "AI và Tương lai của Giáo dục" với chuyên gia quốc tế',
                'content' => 'Ngày 25/05/2026, trường tổ chức hội thảo "AI và Tương lai của Giáo dục" với sự tham gia của GS. Andrew Ng (Stanford), GS. Yoshua Bengio (MILA), và các chuyên gia từ Google, Microsoft. Nội dung: AI trong giảng dạy, ChatGPT trong học tập, đạo đức AI trong giáo dục, công cụ AI cho giáo viên. Đăng ký tại: ai-edu.phanmem.com. Phí tham dự: 200.000đ (sinh viên), 500.000đ (người ngoài). Sinh viên được cộng 10 điểm chuyên cần.',
                'loaitin_id' => 5,
                'img' => 'https://picsum.photos/seed/ai2026/800/500',
                'created_at' => '2026-04-10 11:00:00',
            ],
            [
                'title' => 'Ngày hội việc làm "Future Career 2026" với 100+ doanh nghiệp',
                'content' => 'Ngày 18/05/2026, trường tổ chức Ngày hội việc làm "Future Career 2026" với sự tham gia của 100+ doanh nghiệp lớn: Samsung, Intel, Bosch, Deloitte, PwC, KPMG, VinGroup, FPT. Tổng số vị trí tuyển: 5.000. Hoạt động: Tuyển dụng trực tiếp, Tư vấn nghề nghiệp 1-1, Workshop viết CV, Mock interview. Thời gian: 8h-17h, Địa điểm: Nhà thi đấu đa năng. Sinh viên mặc đồng phục, mang CV 20 bản. Đăng ký online bắt buộc tại: careerday.phanmem.com.',
                'loaitin_id' => 5,
                'img' => 'https://picsum.photos/seed/careerday2026/800/500',
                'created_at' => '2026-04-08 14:00:00',
            ],

            // HƯỚNG NGHIỆP (loaitin_id = 6)
            [
                'title' => 'Workshop "Lộ trình sự nghiệp Data Analyst cho sinh viên IT 2026"',
                'content' => 'Clb Data Science tổ chức workshop "Lộ trình sự nghiệp Data Analyst" vào ngày 20/04/2026. Diễn giả: anh Trần Đức Long - Senior Data Scientist tại Google Singapore, cựu sinh viên K14. Nội dung: Roadmap học tập Data Analysis, Các công cụ SQL, Python, Power BI, Tableau, Kinh nghiệm phỏng vấn Big Tech, Lương và cơ hội thăng tiến. Thời gian: 14h-17h, Địa điểm: P.201 nhà B. Đăng ký tại: dsclub.phanmem.com. Số lượng: 100. Đặc biệt, có phần hỏi đáp trực tiếp 30 phút.',
                'loaitin_id' => 6,
                'img' => 'https://picsum.photos/seed/dataanalyst2026/800/500',
                'created_at' => '2026-03-28 10:00:00',
            ],
            [
                'title' => 'Chương trình "Coffee with CEO" - Gặp gỡ doanh nhân thành đạt',
                'content' => 'Trung tâm Hướng nghiệp tổ chức series "Coffee with CEO" mỗi tháng, nơi sinh viên được trò chuyện trực tiếp với các CEO/Founders. Tháng 4: Bà Nguyễn Thị Phương - CEO Công ty Thế giới Di động (MWG). Tháng 5: Ông Nguyễn Thanh Nghị - CEO Viettel Construction. Tháng 6: Bà Mai Lan - HR Director Microsoft Vietnam. Thời gian: 15h-17h thứ 7 hàng tuần, Quán Cà phê Thanh Xuân. Số lượng: 30 sinh viên/session. Đăng ký tại: career.phanmem.com/coffee-with-ceo. Miễn phí, ưu tiên sinh viên năm 3-4.',
                'loaitin_id' => 6,
                'img' => 'https://picsum.photos/seed/coffeeceo2026/800/500',
                'created_at' => '2026-04-01 09:00:00',
            ],
            [
                'title' => 'Tư vấn nghề nghiệp 1-1 với chuyên gia HR từ Google, Facebook',
                'content' => 'Trường mời các chuyên gia HR từ Google Vietnam, Meta (Facebook), LinkedIn tư vấn nghề nghiệp 1-1 cho sinh viên. Mỗi sinh viên được tư vấn 30 phút về: Định hướng nghề nghiệp, Review CV, Mock interview, Kỹ năng phỏng vấn, Cơ hội việc làm tại Big Tech. Ngày: 12-13/05/2026. Địa điểm: Trung tâm Hướng nghiệp, Tầng 2 nhà A. Đăng ký bắt buộc tại: hr-consultation.phanmem.com. Phí: Miễn phí cho sinh viên trường. Số lượng có hạn: 50 suất.',
                'loaitin_id' => 6,
                'img' => 'https://picsum.photos/seed/hrmanager2026/800/500',
                'created_at' => '2026-04-05 11:00:00',
            ],

            // CÔNG NGHỆ (loaitin_id = 7)
            [
                'title' => 'Lập trình viên AI: Xu hướng nghề nghiệp hot nhất 2026',
                'content' => 'Theo khảo sát của TopDev 2026, Lập trình viên AI/ML đứng đầu danh sách "Top 10 nghề nghiệp được săn đón nhất" với mức lương trung bình 35 triệu đồng/tháng, tăng 40% so với 2025. Các kỹ năng được yêu cầu: Python, TensorFlow, PyTorch, scikit-learn, prompt engineering. Dự kiến đến 2028, Việt Nam cần thêm 50.000 kỹ sư AI. Trường đã mở 3 chứng chỉ mới về AI: AI Fundamentals, Machine Learning Engineer, Deep Learning Specialization. Sinh viên quan tâm liên hệ Khoa CNTT.',
                'loaitin_id' => 7,
                'img' => 'https://picsum.photos/seed/ailaptrinh2026/800/500',
                'created_at' => '2026-01-20 10:00:00',
            ],
            [
                'title' => 'Cuộc thi "Code Challenge 2026" với giải thưởng 200 triệu đồng',
                'content' => 'Trường phối hợp với FPT Software tổ chức Cuộc thi "Code Challenge 2026" cho sinh viên IT toàn quốc. Vòng loại online: 15/05/2026, vòng chung kết tại trường: 05/06/2026. Các track: Backend (Java/Node.js), Frontend (React/Vue), Mobile (Flutter/React Native), DevOps (Docker/K8s), AI/ML. Giải thưởng: Nhất 50 triệu, Nhì 30 triệu, Ba 15 triệu, và 10 giải khuyến khích 5 triệu. Top 50 sẽ được nhận offer từ FPT Software. Đăng ký tại: codechallenge.phanmem.com. Hạn: 30/04/2026.',
                'loaitin_id' => 7,
                'img' => 'https://picsum.photos/seed/codechallenge2026/800/500',
                'created_at' => '2026-03-15 14:00:00',
            ],
            [
                'title' => 'Khóa học "Blockchain Developer" miễn phí cho sinh viên 2026',
                'content' => 'Khoa CNTT mở khóa học "Blockchain Developer" hoàn toàn miễn phí cho sinh viên năm 2026. Thời lượng: 40 giờ (8 tuần), học thứ 4 và thứ 6 hàng tuần. Giảng viên: anh Lê Minh Tuấn - Blockchain Lead tại Axie Infinity. Nội dung: Smart Contract (Solidity), DApps, NFT, DeFi, Web3.js. Sau khóa học, sinh viên có thể thực tập tại các công ty blockchain Việt Nam với mức lương 15-25 triệu/tháng. Số lượng: 50. Đăng ký tại: blockchain-course.phanmem.com. Hạn: 20/03/2026.',
                'loaitin_id' => 7,
                'img' => 'https://picsum.photos/seed/blockchain2026/800/500',
                'created_at' => '2026-03-01 09:00:00',
            ],

            // THỂ THAO (loaitin_id = 8)
            [
                'title' => 'Giải bóng đá sinh viên "Mùa xuân 2026" - Cúp trường',
                'content' => 'Phong trào Thể dục Thể thao tổ chức Giải bóng đá nam sinh viên "Mùa xuân 2026" - Cúp trường lần thứ 12. Thời gian: 15/03 - 30/04/2026. 32 đội đến từ các khoa tham gia. Thể thức: Vòng bảng - Tứ kết - Bán kết - Chung kết. Địa điểm: Sân cỏ nhân tạo và Sân bóng đá mini. Giải nhất: Cúp vô địch + 20 triệu đồng. Đăng ký theo khoa tại Phòng Quản lý Thể chất. Hạn đăng ký: 28/02/2026. Trận khai mạc: 15/03/2026 lúc 16h.',
                'loaitin_id' => 8,
                'img' => 'https://picsum.photos/seed/bongda2026/800/500',
                'created_at' => '2026-02-15 10:00:00',
            ],
            [
                'title' => 'Giải cầu lông sinh viên "Vô địch trường 2026"',
                'content' => 'Giải cầu lông đơn nam, đơn nữ "Vô địch trường 2026" dành cho sinh viên toàn trường. Thời gian: 20-25/04/2026. Địa điểm: Nhà thi đấu thể thao. Đối tượng: sinh viên đang học tại trường (không chuyên nghiệp). Thể thức: Loại trực tiếp. Giải thưởng: Nhất 5 triệu + huy chương, Nhì 3 triệu, Ba 2 triệu, Best Spirit 1 triệu. Đăng ký tại: phongtrao.phanmem.com từ 01-15/04/2026. Số lượng: 128 nam, 64 nữ. Lệ phí: 50.000đ/người.',
                'loaitin_id' => 8,
                'img' => 'https://picsum.photos/seed/caulong2026/800/500',
                'created_at' => '2026-03-20 14:00:00',
            ],
            [
                'title' => 'CLB Yoga "Zen Space" mở lớp học mới cho sinh viên',
                'content' => 'CLB Yoga Zen Space khai giảng lớp học mùa 2026 với 3 ca học: Sáng thứ 3-5 (6h-7h30), Chiều thứ 7 (14h-16h), Tối thứ 4-6 (19h-20h30). Giảng viên: cô Sarah Chen - chứng chỉ Yoga Alliance 500h. Nội dung: Hatha Yoga, Vinyasa Flow, Meditation, Breathing techniques. Phí: 300.000đ/tháng (sinh viên), miễn phí cho Hội viên CLB Thể thao. Địa điểm: Phòng Yoga, Tầng 3 Nhà thi đấu. Liên hệ: Trưởng CLB Lê Thị Mai - 09xxxxxxx. Ưu đãi: 3 buổi miễn phí cho sinh viên mới.',
                'loaitin_id' => 8,
                'img' => 'https://picsum.photos/seed/yoga2026/800/500',
                'created_at' => '2026-01-10 11:00:00',
            ],

            // VĂN HÓA - NGHỆ THUẬT (loaitin_id = 9)
            [
                'title' => 'Đêm nhạc "Mùa xuân sinh viên 2026" với ca sĩ nổi tiếng',
                'content' => 'Đêm nhạc "Mùa xuân sinh viên 2026" là sự kiện lớn nhất trong năm của sinh viên, diễn ra vào ngày 20/03/2026 tại Trung tâm Hội chợ Triển lãm TP.HCM. Nghệ sĩ tham gia: Hòa Minzy, B Ray, Slim V, và các band nhạc sinh viên trường. Vé: Miễn phí cho sinh viên (đăng ký online), 200.000đ cho khách ngoài. Chương trình gồm: Band competition, Dance battle, Fashion show, Lighting show. Đăng ký vé tại: ve.mauxuansv.phanmem.com. Số lượng vé sinh viên: 3.000.',
                'loaitin_id' => 9,
                'img' => 'https://picsum.photos/seed/amnhac2026/800/500',
                'created_at' => '2026-03-01 15:00:00',
            ],
            [
                'title' => 'CLB Nhiếp ảnh "Khoảnh khắc" triển lãm "Việt Nam 2026"',
                'content' => 'CLB Nhiếp ảnh "Khoảnh khắc" tổ chức triển lãm "Việt Nam 2026" tại Bảo tàng TP.HCM từ 10-25/04/2026. 80 tác phẩm được tuyển chọn từ 500 bài dự thi của sinh viên toàn trường. Chủ đề: Đời sống sinh viên, Phong cảnh Việt Nam, Di sản văn hóa, Bảo vệ môi trường. Giải Nhất: 15 triệu đồng + chuyến đi chụp ảnh tại Sa Pa. Đặc biệt, 10 tác phẩm xuất sắc sẽ được đăng trên tạp chí Nhiếp ảnh Việt Nam. Lễ khai mạc: 10/04/2026 lúc 19h. Vào cửa tự do.',
                'loaitin_id' => 9,
                'img' => 'https://picsum.photos/seed/chupanh2026/800/500',
                'created_at' => '2026-03-25 13:00:00',
            ],
            [
                'title' => 'Workshop làm phim ngắn với đạo diễn hollywood tại trường',
                'content' => 'CLB Điện ảnh mời đạo diễn Victor Vũ ( tác phẩm "Tết Ở Làng Địa Ngục", "Quỷ Ám") dạy workshop làm phim ngắn cho sinh viên. Thời gian: 2 ngày (25-26/05/2026), 9h-17h. Nội dung: Viết kịch bản, Đạo diễn, Quay phim, Dựng phim, Âm thanh. Học phí: Miễn phí cho 50 sinh viên xuất sắc, 500.000đ cho sinh viên khác. Yêu cầu: Mang theo laptop, có sẵn ý tưởng phim ngắn. Sản phẩm cuối khóa: 1 phim ngắn 3-5 phút. Đăng ký tại: filmmaking.phanmem.com. Hạn: 15/05/2026.',
                'loaitin_id' => 9,
                'img' => 'https://picsum.photos/seed/phim2026/800/500',
                'created_at' => '2026-04-05 10:00:00',
            ],

            // KHOA HỌC (loaitin_id = 10)
            [
                'title' => 'Sinh viên nghiên cứu pin Lithium không gây ô nhiễm đạt giải nhất nghiên cứu khoa học',
                'content' => 'Nhóm nghiên cứu gồm 4 sinh viên K19 ngành Hóa học do TS. Trần Văn Hùng hướng dẫn vừa đạt giải Nhất Cuộc thi Nghiên cứu Khoa học sinh viên cấp quốc gia với đề tài "Pin Lithium không gây ô nhiễm môi trường sử dụng vật liệu sinh học". Nghiên cứu sử dụng vỏ trấu và bã mía để chế tạo electrode, thay thế kim loại quý hiếm. Dự án có tiềm năng ứng dụng lớn trong công nghiệp điện thoại và xe điện. Nhóm nhận tài trợ 500 triệu đồng để tiếp tục phát triển sản phẩm.',
                'loaitin_id' => 10,
                'img' => 'https://picsum.photos/seed/pin2026/800/500',
                'created_at' => '2026-04-12 16:00:00',
            ],
            [
                'title' => 'Hội thảo "Ứng dụng IoT trong Nông nghiệp thông minh"',
                'content' => 'Khoa Điện - Điện tử tổ chức hội thảo "Ứng dụng IoT trong Nông nghiệp thông minh" ngày 18/04/2026. Diễn giả: PGS.TS. Lê Đức Hùng (Viện Nông nghiệp Quốc gia), chuyên gia từ FAITH Việt Nam. Nội dung: Cảm biến độ ẩm, hệ thống tưới tự động, giám sát cây trồng bằng Drone, AI trong dự báo mùa vụ. Đặc biệt, có phần demo trực tiếp hệ thống Nông nghiệp thông minh tại farm công nghệ của trường. Thời gian: 8h-12h, Địa điểm: P.101 nhà C. Sinh viên đăng ký tại: iot-agri.phanmem.com. Miễn phí.',
                'loaitin_id' => 10,
                'img' => 'https://picsum.photos/seed/iot2026/800/500',
                'created_at' => '2026-04-01 11:00:00',
            ],
            [
                'title' => 'Ngày Khoa học Sinh viên 2026: 200 nghiên cứu được trưng bày',
                'content' => 'Ngày Khoa học Sinh viên năm 2026 diễn ra từ 05-07/05/2026 tại Trung tâm Triển lãm Văn hóa TP.HCM. 200 đề tài nghiên cứu từ sinh viên các khoa được trưng bày và bình chọn. Các lĩnh vực: Công nghệ thông tin, Kỹ thuật, Y sinh, Môi trường, Kinh tế, Xã hội học. Chương trình gồm: Trưng bày poster, Pitching competition, Diễn đàn khởi nghiệp, Gặp gỡ doanh nghiệp. Giải thưởng: Tổng giá trị 500 triệu đồng. Đăng ký tham dự tại: scidays.phanmem.com. Miễn phí cho sinh viên.',
                'loaitin_id' => 10,
                'img' => 'https://picsum.photos/seed/khoahoc2026/800/500',
                'created_at' => '2026-04-08 09:00:00',
            ],

            // GIÁO DỤC (loaitin_id = 11)
            [
                'title' => 'Chuyển đổi tín chỉ giữa các trường đại học: Cơ hội mới cho sinh viên 2026',
                'content' => 'Bộ GD&ĐT công bố chính sách mới cho phép sinh viên chuyển tín chỉ giữa các trường đại học trong cùng hệ thống từ năm học 2026-2027. Sinh viên có thể học một số môn tại trường đối tác và chuyển tín chỉ về trường chính. Hiện tại, trường đã ký MOU với 15 trường đại học top đầu TP.HCM và miền Nam. Danh sách môn học chuyển đổi được công bố tại: transfer.phanmem.com. Sinh viên năm 2 trở lên được đăng ký.',
                'loaitin_id' => 11,
                'img' => 'https://picsum.photos/seed/tinchi2026/800/500',
                'created_at' => '2026-01-15 10:00:00',
            ],
            [
                'title' => 'Chuẩn đầu ra mới 2026: Sinh viên phải có chứng chỉ kỹ năng số',
                'content' => 'Từ năm học 2026-2027, sinh viên tốt nghiệp phải đạt chuẩn đầu ra về kỹ năng số: sử dụng thành thạo Microsoft Office, Google Workspace, có chứng chỉ ICDL hoặc tương đương. Ngoài ra, sinh viên cần có chứng chỉ Tiếng Anh tối thiểu TOEIC 500 (bậc 3) hoặc IELTS 5.0. Trường sẽ tổ chức thi chứng chỉ ICDL 2 lần/năm vào tháng 4 và tháng 9. Phí thi: 300.000đ (được trường hỗ trợ 50% cho sinh viên chính quy).',
                'loaitin_id' => 11,
                'img' => 'https://picsum.photos/seed/chungchi2026/800/500',
                'created_at' => '2026-02-01 14:00:00',
            ],

            // KỸ NĂNG MỀM (loaitin_id = 12)
            [
                'title' => 'Khóa học "Thuyết trình chuyên nghiệp" - Đăng ký ngay!',
                'content' => 'Trung tâm Kỹ năng mềm mở khóa học "Thuyết trình chuyên nghiệp" cho sinh viên năm 2026. Thời lượng: 12 giờ (4 buổi), vào thứ 7 hàng tuần. Giảng viên: chị Nguyễn Thị Lan Anh - former Trainer tại Dale Carnegie Vietnam. Nội dung: Cấu trúc bài thuyết trình, Kỹ thuật giao tiếp, Xử lý câu hỏi khó, Sử dụng PowerPoint hiệu quả, Ngôn ngữ cơ thể. Phí: 800.000đ (sinh viên), miễn phí cho sinh viên nghèo. Đăng ký tại: skills.phanmem.com. Lớp khai giảng: 15/03/2026. Số lượng: 40.',
                'loaitin_id' => 12,
                'img' => 'https://picsum.photos/seed/thuyettrinh2026/800/500',
                'created_at' => '2026-03-01 10:00:00',
            ],
            [
                'title' => 'Workshop "Tìm việc trên LinkedIn" - Công thức săn job đỉnh cao',
                'content' => 'Clb Nghề nghiệp tổ chức workshop "Tìm việc trên LinkedIn" với diễn giả là anh David Nguyễn - Senior Recruiter tại Deloitte Vietnam. Thời gian: 19h-21h ngày 28/04/2026. Nội dung: Tối ưu hồ sơ LinkedIn, Viết headline thu hút, Networking hiệu quả, Tìm recruiter đúng người, Sử dụng LinkedIn Premium miễn phí cho sinh viên. Đặc biệt, diễn giả sẽ review profile trực tiếp cho 10 bạn may mắn. Địa điểm: P.301 nhà B. Đăng ký tại: linkedin-workshop.phanmem.com. Miễn phí!',
                'loaitin_id' => 12,
                'img' => 'https://picsum.photos/seed/linkedin2026/800/500',
                'created_at' => '2026-04-10 15:00:00',
            ],

            // DU HỌC (loaitin_id = 13)
            [
                'title' => 'Chương trình du học Nhật Bản 2026: Học bổng 100% JICE',
                'content' => 'Phòng Quốc tế thông báo chương trình du học Nhật Bản 2026 với học bổng JICE 100% (bao gồm học phí, sinh hoạt phí, vé máy bay). Đối tượng: sinh viên năm 2-3, GPA 3.0, có JLPT N4 hoặc học tiếng Nhật 200 giờ. Thời gian: 1 năm (tháng 10/2026 - tháng 9/2027). Đại học đối tác: Đại học Ritsumeikan, Waseda, Tokyo University. Đăng ký thi JLPT tháng 7 tại trường. Hạn nộp hồ sơ: 30/05/2026. Thông tin chi tiết: international.phanmem.com.',
                'loaitin_id' => 13,
                'img' => 'https://picsum.photos/seed/nhatban2026/800/500',
                'created_at' => '2026-04-01 09:00:00',
            ],
            [
                'title' => 'Trao đổi sinh viên tại Đài Loan 2026 - Đại học Quốc gia Chengchi',
                'content' => 'Trường ký MOU với Đại học Quốc gia Chengchi (Đài Loan) - top 200 thế giới - cho chương trình trao đổi sinh viên 2026. Số lượng: 10 suất/học kỳ. Học phí tại trường mẹ được miễn, sinh viên tự chi trả sinh hoạt phí khoảng 500 USD/tháng. Yêu cầu: GPA 3.0, Tiếng Anh IELTS 6.0 hoặc HSK 4. Thời gian: Học kỳ 1 (09/2026-01/2027) hoặc Học kỳ 2 (02/2027-06/2027). Hạn nộp: 15/05/2026. Liên hệ: P.203 nhà A - Phòng Quốc tế.',
                'loaitin_id' => 13,
                'img' => 'https://picsum.photos/seed/dailoan2026/800/500',
                'created_at' => '2026-03-20 11:00:00',
            ],

            // MÔI TRƯỜNG (loaitin_id = 14)
            [
                'title' => 'Chiến dịch "Giữ xanh thành phố" - 10.000 cây xanh được trồng năm 2026',
                'content' => 'Đoàn Thanh niên phát động chiến dịch "Giữ xanh thành phố" với mục tiêu trồng 10.000 cây xanh tại các quận huyện TP.HCM trong năm 2026. Sinh viên tham gia được cộng 20 điểm rèn luyện. Các hoạt động: Trồng cây tại trường học, công viên; Dọn rác bờ sông; Workshop tái chế; thi thiết kế sản phẩm từ vật liệu tái chế. Đăng ký tại: xanh.phanmem.com. Ngày trồng cây đầu tiên: 22/04/2026 (Ngày Trái Đất). Giao lưu với các trường ĐH RMIT, UEH.',
                'loaitin_id' => 14,
                'img' => 'https://picsum.photos/seed/cayxanh2026/800/500',
                'created_at' => '2026-04-05 10:00:00',
            ],
            [
                'title' => 'Cuộc thi "Ý tưởng xanh" - Giải thưởng 50 triệu cho sinh viên 2026',
                'content' => 'Trường phối hợp với Sở TN&MT TP.HCM tổ chức cuộc thi "Ý tưởng xanh 2026" cho sinh viên. Chủ đề: Giải pháp xanh cho đô thị, Năng lượng tái tạo, Xử lý rác thải, Bảo tồn đa dạng sinh học. Vòng 1 (hồ sơ): nộp trước 15/05/2026. Vòng 2 (pitching): 05/06/2026. Giải Nhất: 50 triệu + gói hỗ trợ khởi nghiệp xanh 200 triệu. Top 10 được tham gia Hội nghị Môi trường Quốc tế tại Singapore. Đăng ký tại: greenidea.phanmem.com.',
                'loaitin_id' => 14,
                'img' => 'https://picsum.photos/seed/yTuongXanh2026/800/500',
                'created_at' => '2026-03-15 14:00:00',
            ],

            // AN TOÀN - PCCC (loaitin_id = 15)
            [
                'title' => 'Tập huấn PCCC cho sinh viên nội trú năm 2026',
                'content' => 'Phòng Công an trường tổ chức tập huấn Phòng cháy chữa cháy và cứu nạn cứu hộ cho sinh viên nội trú KTX. Thời gian: 02-03/03/2026. Nội dung: Phát hiện và xử lý cháy nổ sơ bộ, Sử dụng bình chữa cháy, Thoát hiểm an toàn, Sơ cấp cứu ban đầu. Đặc biệt, Cảnh sát PCCC Quận 9 hướng dẫn thực hành sử dụng bình chữa cháy CO2 và bình bột. Tất cả sinh viên KTX bắt buộc tham dự. Sau tập huấn, sinh viên được cấp chứng chỉ PCCC cơ bản. Điểm danh tại: pcchc.phanmem.com.',
                'loaitin_id' => 15,
                'img' => 'https://picsum.photos/seed/pccc2026/800/500',
                'created_at' => '2026-02-20 09:00:00',
            ],
            [
                'title' => 'An toàn giao thông: Sinh viên tham gia dự án "Vì cuộc sống an toàn"',
                'content' => 'Trường phối hợp với Công an TP.HCM triển khai dự án "Vì cuộc sống an toàn" năm 2026. Sinh viên tình nguyện tham gia: Cổ động an toàn giao thông tại các ngã tư, Phát tặng mũ bảo hiểm, Dán decal nhắc nhở, Workshop kỹ năng lái xe an toàn. Đặc biệt, 500 sinh viên được tặng mũ bảo hiểm đạt chuẩn. Đăng ký tại: giaothong.phanmem.com. Thời gian: thứ 7 hàng tuần từ 15/03. Điểm rèn luyện: 10 điểm/tháng tham gia. Tổng cộng: 200 sinh viên.',
                'loaitin_id' => 15,
                'img' => 'https://picsum.photos/seed/giaothong2026/800/500',
                'created_at' => '2026-03-01 11:00:00',
            ],
        ];

        // Thêm timestamp cho tất cả tin tức
        foreach ($tintucs as &$tintuc) {
            $tintuc['updated_at'] = $tintuc['created_at'];
        }
        unset($tintuc);

        // Chèn dữ liệu vào bảng tintuc
        DB::table('tintuc')->insert($tintucs);

        $this->command->info('Da tao 15 loai tin va 40 tin tuc mau voi anh!');
    }
}
