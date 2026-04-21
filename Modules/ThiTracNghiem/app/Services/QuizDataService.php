<?php

namespace Modules\ThiTracNghiem\Services;

use Illuminate\Support\Facades\DB;
use Modules\ThiTracNghiem\Models\SavsoftAnsver;
use Modules\ThiTracNghiem\Models\SavsoftQuiz;
class QuizDataService
{
    public function getQuizList()
    {
        return SavsoftQuiz::query()
            ->orderByDesc('quid')
            ->get();
    }

    public function getQuizDetailWithQuestionsAndAnswers(int $quid, $seed = null): array
{
    $quiz = SavsoftQuiz::query()
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