<?php

namespace Modules\ThiTracNghiem\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ThiTracNghiem\Services\QuizDataService;
use Modules\ThiTracNghiem\Models\SavsoftQbank;
use Modules\ThiTracNghiem\Models\SavsoftQuiz;
use Modules\ThiTracNghiem\Models\SavsoftAnsver;
use Modules\ThiTracNghiem\Models\SavsoftResult;


class ThiTracNghiemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->getCurrentUser();
        return view('thitracnghiem::index', compact('user'));
    }

    private function getCurrentUser()
    {
        $authUser = Auth::user();

        if (! $authUser) {
            return null;
        }

        return [
            'uid' => $authUser->uid,
            'name' => trim($authUser->first_name . ' ' . $authUser->last_name),
            'studentid' => $authUser->studentid,
            'email' => $authUser->email,
            'classid' => $authUser->classid,
            'facultyid' => $authUser->facultyid,
            'gid' => $authUser->gid,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('thitracnghiem::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('thitracnghiem::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('thitracnghiem::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    public function quizList(QuizDataService $quizDataService)
    {
        $user = $this->getCurrentUser();

        // Kiểm tra xem người dùng có phải admin không
        $authUser = Auth::user();
        if ($authUser && $authUser->isAdmin()) {
            return view('thitracnghiem::admin_denied', compact('user'));
        }

        $quizzes = $quizDataService->getQuizList($user['gid'] ?? null);

        return view('thitracnghiem::quiz_list', compact('user', 'quizzes'));
    }

    public function quizShow(int $quid, QuizDataService $quizDataService)
    {
        $user = $this->getCurrentUser();

        $data = $quizDataService->getQuizDetailWithQuestionsAndAnswers($quid);

        return view('thitracnghiem::quiz_show', [
            'user' => $user,
            'quiz' => $data['quiz'],
            'questions' => $data['questions'],
            'answersByQid' => $data['answersByQid'],
        ]);
    }

    public function quyChe()
    {
        $user = $this->getCurrentUser();

        $rules = DB::table('etp_course')
            ->limit(10)
            ->get();

        return view('thitracnghiem::quyche', compact('user', 'rules'));
    }

    public function kiemDinh()
    {
        $user = $this->getCurrentUser();

        $data = DB::table('savsoft_notification')
            ->limit(10)
            ->get();

        return view('thitracnghiem::kiemdinh', compact('user', 'data'));
    }

    public function thongTinDaoTao()
    {
        $user = $this->getCurrentUser();

        $courses = DB::table('etp_course')
            ->limit(10)
            ->get();

        return view('thitracnghiem::thongtin', compact('user', 'courses'));
    }

    public function bieuDoHocTap()
    {
        $user = $this->getCurrentUser();

        $chartData = [
            ['semester' => 'HK1', 'score' => 7.5],
            ['semester' => 'HK2', 'score' => 8.0],
            ['semester' => 'HK3', 'score' => 8.3],
            ['semester' => 'HK4', 'score' => 8.8],
        ];

        return view('thitracnghiem::bieudo', compact('user', 'chartData'));
    }




    public function quizStart(int $quid, \Modules\ThiTracNghiem\Services\QuizDataService $quizDataService)
    {
        $user = $this->getCurrentUser();

        \Log::debug('=== QUIZ START DEBUG ===', [
            'user' => $user,
            'user_id' => $user['uid'] ?? 'NULL',
            'quid' => $quid
        ]);

        // Kiểm tra bài thi tồn tại
        $quiz = SavsoftQuiz::find($quid);
        if (!$quiz) {
            return redirect()->route('thitracnghiem.quiz.list')
                ->with('error', 'Bài thi không tồn tại.');
        }

        // Kiểm tra xem bài thi đã hết hạn hay chưa
        if ($quiz->end_date && $quiz->end_date !== '0000-00-00 00:00:00') {
            $endDate = \Carbon\Carbon::parse($quiz->end_date);
            if (now() > $endDate) {
                return redirect()->route('thitracnghiem.quiz.list')
                    ->with('error', 'Bài thi này đã hết hạn. Không thể làm bài.');
            }
        }

        // === KIỂM TRA: Có bài thi khác đang chạy không ===
        $sessionKey = 'current_quiz_' . $user['uid'];
        $currentQuizId = session($sessionKey);

        \Log::debug('Session Check', [
            'session_key' => $sessionKey,
            'current_quiz_id_from_session' => $currentQuizId,
            'all_session_keys' => array_keys(session()->all()),
            'requested_quid' => $quid
        ]);

        $currentQuizId = session('current_quiz_' . ($user['uid'] ?? ''));

        if (!empty($currentQuizId) && (int)$currentQuizId !== (int)$quid) {

            \Log::warning('BLOCKING - Different Quiz Running', [
                'user_id' => $user['uid'] ?? null,
                'current_quid' => $currentQuizId,
                'requested_quid' => $quid
            ]);

            return redirect()
                ->route('thitracnghiem.quiz.start', ['quid' => $currentQuizId])
                ->with('swal_warning', [
                    'icon' => 'warning',
                    'title' => 'Bạn chưa nộp bài thi trước!',
                    'text' => 'Bạn đang làm một bài thi khác. Vui lòng nộp bài đó trước.'
                ]);
        }

        // Kiểm tra số lần làm bài tối đa
        $attemptCount = SavsoftResult::where('uid', $user['uid'])
            ->where('quid', $quid)
            ->count();

        if ($attemptCount >= $quiz->maximum_attempts) {
            return redirect()->route('thitracnghiem.quiz.list')
                ->with('error', " Bạn đã hết số lần làm bài. Tối đa: {$quiz->maximum_attempts} lần");
        }

        // Tính lần còn lại
        $remainingAttempts = $quiz->maximum_attempts - $attemptCount;

        // Lưu bài thi hiện tại
        session(['current_quiz_' . $user['uid'] => $quid]);
        session()->save(); // Force save session immediately

        $sessionKey = "quiz_seed_" . ($user['uid'] ?? 0) . "_" . $quid;
        if (!session()->has($sessionKey)) {
            session([$sessionKey => rand(1, 999999)]);
        }
        $seed = session($sessionKey);

        $data = $quizDataService->getQuizDetailWithQuestionsAndAnswers($quid, $seed);

        // Lấy thời gian thi từ group (valid_for_days), nếu không có thì dùng duration của quiz
        $quizDuration = $quiz->duration ?? 40;
        if ($quiz->group && $quiz->group->valid_for_days) {
            $quizDuration = $quiz->group->valid_for_days;
        }

        return view('thitracnghiem::quiz_start', [
            'user' => $user,
            'quiz' => $data['quiz'],
            'questions' => $data['questions'],
            'answersByQid' => $data['answersByQid'],
            'attemptCount' => $attemptCount,
            'maxAttempts' => $quiz->maximum_attempts,
            'remainingAttempts' => $remainingAttempts,
            'showWarning' => $remainingAttempts <= 2,
            'warningMessage' => $remainingAttempts == 1
                ? " Đây là lần cuối cùng! Bạn chỉ còn {$remainingAttempts} lần làm bài."
                : " Bạn còn {$remainingAttempts} lần làm bài.",
            'quizDuration' => $quizDuration,
        ]);
    }

    public function submit(Request $request, $quid)
    {
        $data = $request->except('_token');

        $score = 0;
        $total = 0;

        $user = $this->getCurrentUser();
        $sessionKey = "quiz_seed_" . ($user['uid'] ?? 0) . "_" . $quid;
        $seed = session($sessionKey);

        $quizDataService = app(\Modules\ThiTracNghiem\Services\QuizDataService::class);
        $quizData = $quizDataService->getQuizDetailWithQuestionsAndAnswers($quid, $seed);

        $questions = $quizData['questions'];
        $answersByQid = $quizData['answersByQid'];

        foreach ($questions as $question) {
            $total++;

            $key = 'question_' . $question->qid;

            if (isset($data[$key])) {
                $userAnswer = $data[$key];

                $answers = $answersByQid[$question->qid] ?? collect();

                foreach ($answers as $ans) {
                    if ($ans->score == 1 && $ans->oid == $userAnswer) {
                        $score++;
                    }
                }
            }
        }

        $finalScore = $total > 0 ? round(($score / $total) * 10, 2) : 0;

        // Lưu điểm
        $user = $this->getCurrentUser();

        if ($user) {
            $status = ($finalScore >= 5) ? 'Pass' : 'Fail';

            SavsoftResult::insert([
                'uid' => $user['uid'],
                'quid' => $quid,
                'score_obtained' => $score,
                'percentage_obtained' => $finalScore,
                'result_status' => $status,
                'start_time' => now()->timestamp,
                'end_time' => now()->timestamp,
                'categories' => '',
                'category_range' => '',
                'r_qids' => '',
                'individual_time' => '',
                'attempted_ip' => $request->ip() ?? '127.0.0.1',
                'score_individual' => '',
                'photo' => ''
            ]);


            session()->forget($sessionKey);
            session()->forget('current_quiz_' . $user['uid']);

            \Log::info('Quiz Submitted - Session Cleared', [
                'user_id' => $user['uid'],
                'quid' => $quid,
                'session_key' => $sessionKey,
                'current_quiz_key' => 'current_quiz_' . $user['uid']
            ]);
        }

        return view('thitracnghiem::result', [
            'score' => $score,
            'total' => $total,
            'finalScore' => $finalScore,
            'quid' => $quid
        ]);
    }

    public function history()
    {
        $user = $this->getCurrentUser();

        $history = \Modules\ThiTracNghiem\Models\SavsoftResult::with('quiz')
            ->where('uid', $user['uid'])
            ->orderBy('end_time', 'desc')
            ->get();

        return view('thitracnghiem::history', compact('user', 'history'));
    }
}
