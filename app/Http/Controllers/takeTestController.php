<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mttests;
use App\Models\ettests;
use App\Models\quizzes;
use App\Models\tftests;
// use App\Models\mtftests;
use App\Models\mtitems;
use App\Models\etitems;
use App\Models\quizitems;
use App\Models\tfitems;
use App\Models\quizItemsAnswers;
use App\Models\quizTestsTaken;
use App\Models\tfTestsTaken;
use App\Models\tfItemsAnswers;
use App\Models\matchingTestsTaken;
use App\Models\matchingItemsAnswers;
use App\Models\enumerationTestsTaken;
use App\Models\enumerationItemsAnswers;
use App\Models\subjects;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class takeTestController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('isStudent');
    }

    public function index(Request $request)
    {
        $page = 'take test';
        $sortDate =  is_null($request->input('sort-date')) ? 'desc' : $request->input('sort-date');
        $search = $request->input('search');


        $mcq = quizzes::select(
            'quizzes.qzID as id',
            'quizzes.qzTitle as title',
            'quizzes.qzDescription as description',
            'quizzes.qzTotal as total',
            'quizzes.qzIsPublic as public',
            'quizzes.subjectID',
            'quizzes.created_at',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            DB::raw("'MCQ' as type"),
            DB::raw('(SELECT COUNT(*) FROM quizitems WHERE quizitems.qzID = quizzes.qzID) as itemCount')
        )
            ->where('qzIsPublic', 1)
            ->leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'quizzes.user_id', '=', 'users.id');

        $et = ettests::select(
            'ettests.etID as id',
            'ettests.etTitle as title',
            'ettests.etDescription as description',
            'ettests.etTotal as total',
            'ettests.etIsPublic as public',
            'ettests.subjectID',
            'ettests.created_at',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            DB::raw("'ET' as type"),
            DB::raw('(SELECT COUNT(*) FROM etitems WHERE etitems.etID = ettests.etID) as itemCount')
        )
            ->where('etIsPublic', 1)
            ->leftJoin('subjects', 'ettests.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'ettests.user_id', '=', 'users.id');

        // dd($et);

        $mt = mttests::select(
            'mttests.mtID as id',
            'mttests.mtTitle as title',
            'mttests.mtDescription as description',
            'mttests.mtTotal as total',
            'mttests.mtIsPublic as public',
            'mttests.subjectID',
            'mttests.created_at',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            DB::raw("'MT' as type"),
            DB::raw('(SELECT COUNT(*) FROM mtitems WHERE mtitems.mtID = mttests.mtID) as itemCount')
        )
            ->where('mtIsPublic', 1)
            ->leftJoin('subjects', 'mttests.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'mttests.user_id', '=', 'users.id');

        $tf = tftests::select(
            'tftests.tfID as id',
            'tftests.tfTitle as title',
            'tftests.tfDescription as description',
            'tftests.tfTotal as total',
            'tftests.tfIsPublic as public',
            'tftests.subjectID',
            'tftests.created_at',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            DB::raw("'TF' as type"),
            DB::raw('(SELECT COUNT(*) FROM tfitems WHERE tfitems.tfID = tftests.tfID) as itemCount')
        )
            ->where('tfIsPublic', 1)
            ->leftJoin('subjects', 'tftests.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'tftests.user_id', '=', 'users.id');

        // $mtf = mtftests::select(
        //     'mtftests.mtfID as id',
        //     'mtftests.mtfTitle as title',
        //     'mtftests.mtfDescription as description',
        //     'mtftests.mtfTotal as total',
        //     'mtftests.mtfIsPublic as public',
        //     'mtftests.subjectID',
        //     'mtftests.created_at',
        //     'subjects.subjectName',
        //     'users.first_name',
        //     'users.last_name',
        //     'users.user_image',
        //     DB::raw("'MTF' as type"),
        //     DB::raw('(SELECT COUNT(*) FROM mtfitems WHERE mtfitems.mtfID = mtftests.mtfID) as itemCount')
        // )
        //     ->where('mtfIsPublic', 1)
        //     ->leftJoin('subjects', 'mtftests.subjectID', '=', 'subjects.subjectID')
        //     ->leftJoin('users', 'mtftests.user_id', '=', 'users.id');

        $search = $request->input('search');

        if (!empty($search)) {
            $mcq->where(function ($query) use ($search) {
                $query->where('quizzes.qzTitle', 'LIKE', "%$search%")
                    ->orWhere('quizzes.qzDescription', 'LIKE', "%$search%");
            });

            $et->where(function ($query) use ($search) {
                $query->where('ettests.etTitle', 'LIKE', "%$search%")
                    ->orWhere('ettests.etDescription', 'LIKE', "%$search%");
            });

            $mt->where(function ($query) use ($search) {
                $query->where('mttests.mtTitle', 'LIKE', "%$search%")
                    ->orWhere('mttests.mtDescription', 'LIKE', "%$search%");
            });

            $tf->where(function ($query) use ($search) {
                $query->where('tftests.tfTitle', 'LIKE', "%$search%")
                    ->orWhere('tftests.tfDescription', 'LIKE', "%$search%");
            });
        }

        $filter = [];
        if ($request->input('mcq-filter') || $request->input('tf-filter') || $request->input('matching-filter') || $request->input('enumeration-filter')) {
            $result = null;


            if ($request->input('mcq-filter')) {
                $result = $mcq;
                $filter[] = 'mcq';
            }

            if ($request->input('tf-filter')) {
                $result = $result ? $result->union($tf) : $tf;
                $filter[] = 'tf';
            }

            if ($request->input('matching-filter')) {
                $result = $result ? $result->union($mt) : $mt;
                $filter[] = 'matching';
            }

            if ($request->input('enumeration-filter')) {
                $result = $result ? $result->union($et) : $et;
                $filter[] = 'enumeration';
            }
        } else {
            $result = $mcq
                ->union($et)
                ->union($mt)
                ->union($tf);
            // ->union($mtf)
        }
        $subjects = subjects::all();
        $filterSubjects = [];


        $result->where(function ($query) use ($subjects, $request, &$filterSubjects) {
            foreach ($subjects as $subject) {
                $subjectInputName = $subject->subjectID . 'subject';

                if ($request->input($subjectInputName)) {
                    $filterSubjects[] = $subject->subjectID;
                    $query->orWhere('quizzes.subjectID', $subject->subjectID);
                }
            }
        });
        $result = $result
            ->orderBy('created_at', $sortDate)
            ->paginate(13);
        // dd($result);
        return view('students.taketest.index', [
            'page' => $page,
            'sortDate' => $sortDate,
            'tests' => $result,
            'subjects' => $subjects,
            'filterSubjects' => $filterSubjects,
            'filterType' => $filter,
            'searchInput' => $search,
        ]);
    }

    public function initializeAnswerSession($type, $testID)
    {
        // Initialize session for MCQ
        if ($type == 'mcq') {
            $questions = quizitems::where('qzID', $testID)->get();
            $answers = [];

            foreach ($questions as $question) {
                $answers[$question->itmID] = 0;
            }

            session([$type . $testID => $answers]);
        }
        // Initialize session for MCQ
        if ($type == 'tf') {
            $questions = tfitems::where('tfID', $testID)->get();
            $answers = [];

            foreach ($questions as $question) {
                $answers[$question->itmID] = 0;
            }

            session([$type . $testID => $answers]);
        }
    }

    private function saveAnswerToSession($type, $testID, $itemId, $answer)
    {

        // Retrieve existing answers from the session
        $answers = session($type . $testID, []);

        // Save the new answer
        $answers[$itemId] = $answer;

        // Save the updated answers to the session
        session([$type . $testID => $answers]);
    }


    public function taketest(Request $request, $type, $id)
    {
        $testType = ['mcq', 'et', 'mt', 'tf'];
        if (!in_array($type, $testType)) {
            abort(404);
        }

        if ($type == 'mcq') {
            if (!session()->has($type . $id)) {
                $this->initializeAnswerSession($type, $id);
            }
            if ($request->input('questionID') && $request->input('options')) {
                $this->saveAnswerToSession($type, $id, $request->input('questionID'), $request->input('options'));
                // dd(session($type . $id));
            }
            $studentAnswers = session($type . $id);
            $question = quizitems::where('qzID', $id)
                ->paginate(1);
        }

        if ($type == 'tf') {
            if (!session()->has($type . $id)) {
                $this->initializeAnswerSession($type, $id);
            }
            if ($request->input('questionID') && $request->input('options')) {
                $this->saveAnswerToSession($type, $id, $request->input('questionID'), $request->input('options'));
                // dd(session($type . $id));
            }
            $studentAnswers = session($type . $id);
            // dd($studentAnswers);
            $question = tfitems::where('tfID', $id)
                ->paginate(1);
        }

        if ($type == 'mt') {
            $testItem = mttests::find($id);
            $questions = mtitems::where('mtID', $id)
                ->get();
            $itemQuestion = [];
            $itemAnswer = [];
            foreach ($questions as $question) {
                $itemQuestion[$question->itmID] = $question->itmQuestion;
                $itemAnswer[] = $question->itmAnswer;
            }
            shuffle($itemAnswer);
        }


        if ($type == 'et') {
            $testItem = ettests::find($id);
            $question = etitems::where('etID', $id)
                ->get();
        }

        $page = 'take test';
        if (!$request->input('finish')) {
            return view('students.taketest.taketest', [
                'type' => $type,
                'page' => $page,
                'testID' => $id,
                'questions' => $question,
                'studentAnswers' => $studentAnswers ?? null,
                'itemQuestion' => $itemQuestion ?? null,
                'itemAnswers' => $itemAnswer ?? null,
                'test' =>  $testItem ?? null,
            ]);
        }
        return redirect()->route('finish-test', ['type' => $type, 'id' => $id]);
    }

    public function finishtest(Request $request, $type, $id)
    {
        $testType = ['mcq', 'et', 'mt', 'tf'];
        if (in_array($type, ['mcq', 'tf'])) {
            if (!session()->has($type . $id)) {
                abort(404);
            }
        }
        if (!in_array($type, $testType)) {
            abort(404);
        }

        if ($type == 'mcq') {
            $answers = session($type . $id);
            $test = quizTestsTaken::create([
                'user_id' => Auth::id(),
                'qzID' => $id,
            ]);
            foreach ($answers as $key => $value) {
                quizItemsAnswers::create([
                    'qzttID' => $test->qzttID,
                    'itmID' => $key,
                    'qzStudentItemAnswer' => $value,
                ]);
            }
            $resultID = $test->qzttID;
            session()->forget($type . $id);
        }
        if ($type == 'tf') {
            $answers = session($type . $id);
            $test = tfTestsTaken::create([
                'user_id' => Auth::id(),
                'tfID' => $id,
            ]);
            foreach ($answers as $key => $value) {
                tfItemsAnswers::create([
                    'tfttID' => $test->tfttID,
                    'itmID' => $key,
                    'tfStudentItemAnswer' => $value,
                ]);
            }
            $resultID = $test->tfttID;
            session()->forget($type . $id);
        }
        if ($type == 'mt') {
            $answers = $request->input('selects');
            $test = matchingTestsTaken::create([
                'user_id' => Auth::id(),
                'mtID' => $id,
            ]);
            foreach ($answers as $key => $value) {
                matchingItemsAnswers::create([
                    'mtttID' => $test->mtttID,
                    'itmID' => $key,
                    'mtStudentItemAnswer' => $value,
                ]);
            }
            $resultID = $test->mtttID;
        }
        if ($type == 'et') {
            $answers = $request->input('answers');
            $test = enumerationTestsTaken::create([
                'user_id' => Auth::id(),
                'etID' => $id,
            ]);
            foreach ($answers as $key => $value) {
                enumerationItemsAnswers::create([
                    'etttID' => $test->etttID,
                    'etStudentItemAnswer' => $value,
                ]);
            }
            $resultID = $test->etttID;
        }

        return redirect('/taketest/' . $type . "/" . $resultID . "/result");
    }

    public function seeresult($type, $id)
    {
        $points = 0;
        $total = 0;
        $testType = ['mcq', 'et', 'mt', 'tf'];
        if (!in_array($type, $testType)) {
            abort(404);
        }

        if ($type == 'mcq') {
            $test = quizTestsTaken::find($id);
            $mcqID = $test->qzID;
            $quizitems = quizitems::where('qzID', $mcqID)->get();
            $correctAnswers = [];
            foreach ($quizitems as $item) {
                $correctAnswers[$item->itmID] = [$item->itmAnswer, $item->itmPoints];
                $total += $item->itmPoints;
            }
            $studentAnswers = quizItemsAnswers::where('qzttID', $id)->get();
            foreach ($studentAnswers as $answer) {
                if ($correctAnswers[$answer->itmID][0] == $answer->qzStudentItemAnswer) {
                    $points += $correctAnswers[$answer->itmID][1];
                }
            }
        }

        if ($type == 'tf') {
            $test = tfTestsTaken::find($id);
            $mcqID = $test->tfID;
            $tfitems = tfitems::where('tfID', $mcqID)->get();
            $correctAnswers = [];
            foreach ($tfitems as $item) {
                $correctAnswers[$item->itmID] = [$item->itmAnswer, $item->itmPoints];
                $total += $item->itmPoints;
            }
            $studentAnswers = tfItemsAnswers::where('tfttID', $id)->get();
            foreach ($studentAnswers as $answer) {
                if ($correctAnswers[$answer->itmID][0] == $answer->tfStudentItemAnswer) {
                    $points += $correctAnswers[$answer->itmID][1];
                }
            }
        }
        if ($type == 'mt') {
            $test = matchingTestsTaken::find($id);
            $mtID = $test->mtID;
            $mtitems = mtitems::where('mtID', $mtID)->get();
            $correctAnswers = [];
            foreach ($mtitems as $item) {
                $correctAnswers[$item->itmID] = [$item->itmAnswer, $item->itmPoints];
                $total += $item->itmPoints;
            }
            $studentAnswers = matchingItemsAnswers::where('mtttID', $id)->get();
            foreach ($studentAnswers as $answer) {
                if ($correctAnswers[$answer->itmID][0] == $answer->mtStudentItemAnswer) {
                    $points += $correctAnswers[$answer->itmID][1];
                }
            }
        }
        if ($type == 'et') {
            $test = enumerationTestsTaken::find($id);
            $etID = $test->etID;
            $etitems = etitems::where('etID', $etID)->get();
            $correctAnswers = [];
            foreach ($etitems as $item) {
                $correctAnswers[$item->itmAnswer] = $item->itmIsCaseSensitive;
                $total += 1;
            }
            $studentAnswers = enumerationItemsAnswers::where('etttID', $id)->get();
            foreach ($studentAnswers as $answer) {
                foreach ($correctAnswers as $key => $value) {
                    $stdntAnswer = $answer->etStudentItemAnswer;
                    $crctAnswer = $key;
                    if (!$value) {
                        $crctAnswer = strtolower($crctAnswer);
                        $stdntAnswer = strtolower($answer->etStudentItemAnswer);
                    }
                    if ($crctAnswer == $stdntAnswer) {
                        $points += 1;
                    }
                }
            }
        }

        $page = 'take test';
        return view('students.taketest.finish', [
            'page' => $page,
            'points' => $points,
            'total' => $total,
        ]);
    }
}
