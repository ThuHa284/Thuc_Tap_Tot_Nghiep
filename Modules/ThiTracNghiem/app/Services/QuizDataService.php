<?php

namespace Modules\ThiTracNghiem\Services;

use Illuminate\Support\Facades\DB;
use Modules\ThiTracNghiem\Models\SavsoftAnsver;
use Modules\ThiTracNghiem\Models\SavsoftQuiz;

class QuizDataService
{
    public function getQuizList($userGid = null)
    {
        $query = SavsoftQuiz::query()
            ->with('group')
            ->orderByDesc('quid');

        // Lọc đề thi theo nhóm/khóa của sinh viên
        // gids lưu danh sách gid phân tách bằng dấu phẩy (ví dụ: "0,1,2,3")
        if ($userGid !== null) {
            $query->whereRaw("FIND_IN_SET(?, gids)", [$userGid])
                // Join với bảng savsoft_time để kiểm tra thời gian khóa
                ->leftJoin('savsoft_time', function ($join) use ($userGid) {
                    $join->on('savsoft_time.gid', '=', DB::raw($userGid));
                })
                // Chỉ lấy đề thi khi hiện tại nằm trong khoảng thời gian khóa
                ->whereRaw("NOW() BETWEEN DATE(savsoft_time.start_date) AND DATE(savsoft_time.end_date)")
                ->select('savsoft_quiz.*');
        }

        return $query->get();
    }

    public function getQuizDetailWithQuestionsAndAnswers(int $quid, $seed = null): array
    {
        $quiz = SavsoftQuiz::query()
            ->with('group')
            ->where('quid', $quid)
            ->first();

        if (! $quiz) {
            return [
                'quiz' => null,
                'questions' => collect(),
                'answersByQid' => collect(),
            ];
        }

        // lấy cấu hình đề thi từ bảng savsoft_qcl
        $qclRows = DB::table('savsoft_qcl')
            ->where('quid', $quid)
            ->get();

        $questions = collect();

        foreach ($qclRows as $row) {
            $query = DB::table('savsoft_qbank')
                ->where('cid', $row->cid);

            if (!empty($row->lid)) {
                $query->where('lid', $row->lid);
            }

            // lấy số lượng câu theo noq
            if ($seed !== null) {
                $query->orderByRaw("RAND($seed)");
            } else {
                $query->inRandomOrder();
            }

            $rowQuestions = $query
                ->limit((int) $row->noq)
                ->get();

            $questions = $questions->merge($rowQuestions);
        }

        // bỏ trùng nếu có
        $questions = $questions->unique('qid')->values();

        $qids = $questions->pluck('qid')->all();

        // lấy đáp án từ bảng savsoft_options
        $answersByQid = DB::table('savsoft_options')
            ->whereIn('qid', $qids)
            ->orderBy('qid')
            ->orderBy('oid')
            ->get()
            ->groupBy('qid');

        return [
            'quiz' => $quiz,
            'questions' => $questions,
            'answersByQid' => $answersByQid,
        ];
    }
}
