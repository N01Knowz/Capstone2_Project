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
use App\Models\tmEt;
use App\Models\tmEtItemsAnswers;
use App\Models\tmMt;
use App\Models\tmMtItemsAnswers;
use App\Models\tmQuizItems;
use App\Models\tmQuizItemsAnswers;
use App\Models\tmtests;
use App\Models\tmTestsTaken;
use App\Models\tmTfItems;
use App\Models\tmTfItemsAnswers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

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
            'quizzes.updated_at',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            'users.id as creatorID',
            DB::raw("'MCQ' as type"),
            DB::raw('(SELECT COUNT(*) FROM quizitems WHERE quizitems.qzID = quizzes.qzID) as itemCount')
        )
            ->where('qzIsPublic', 1)
            ->where('isHidden', 0)
            ->leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'quizzes.user_id', '=', 'users.id');

        $et = ettests::select(
            'ettests.etID as id',
            'ettests.etTitle as title',
            'ettests.etDescription as description',
            'ettests.etTotal as total',
            'ettests.etIsPublic as public',
            'ettests.subjectID',
            'ettests.updated_at',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            'users.id as creatorID',
            DB::raw("'ET' as type"),
            DB::raw('(SELECT COUNT(*) FROM etitems WHERE etitems.etID = ettests.etID) as itemCount')
        )
            ->where('etIsPublic', 1)
            ->where('isHidden', 0)
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
            'mttests.updated_at',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            'users.id as creatorID',
            DB::raw("'MT' as type"),
            DB::raw('(SELECT COUNT(*) FROM mtitems WHERE mtitems.mtID = mttests.mtID) as itemCount')
        )
            ->where('mtIsPublic', 1)
            ->where('isHidden', 0)
            ->leftJoin('subjects', 'mttests.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'mttests.user_id', '=', 'users.id');

        $tf = tftests::select(
            'tftests.tfID as id',
            'tftests.tfTitle as title',
            'tftests.tfDescription as description',
            'tftests.tfTotal as total',
            'tftests.tfIsPublic as public',
            'tftests.subjectID',
            'tftests.updated_at',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            'users.id as creatorID',
            DB::raw("'TF' as type"),
            DB::raw('(SELECT COUNT(*) FROM tfitems WHERE tfitems.tfID = tftests.tfID) as itemCount')
        )
            ->where('tfIsPublic', 1)
            ->where('isHidden', 0)
            ->leftJoin('subjects', 'tftests.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'tftests.user_id', '=', 'users.id');

        // $mtf = mtftests::select(
        //     'mtftests.mtfID as id',
        //     'mtftests.mtfTitle as title',
        //     'mtftests.mtfDescription as description',
        //     'mtftests.mtfTotal as total',
        //     'mtftests.mtfIsPublic as public',
        //     'mtftests.subjectID',
        //     'mtftests.updated_at',
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

        $mixed = tmtests::select(
            'tmtests.tmID as id',
            'tmtests.tmTitle as title',
            'tmtests.tmDescription as description',
            'tmtests.tmTotal as total',
            'tmtests.tmIsPublic as public',
            DB::raw("'0' as subjectID"),
            'tmtests.updated_at',
            DB::raw("'No Subject' as subjectName"),
            'users.first_name',
            'users.last_name',
            'users.user_image',
            'users.id as creatorID',
            DB::raw("'MIXED' as type"),
            DB::raw(
                '
            (SELECT COUNT(*) FROM tm_tf_items WHERE tm_tf_items.tmID = tmtests.tmID) +
            (SELECT COUNT(*) FROM tm_quiz_items WHERE tm_quiz_items.tmID = tmtests.tmID) +
            (SELECT COUNT(*) FROM mtitems WHERE mtitems.mtID IN (SELECT mtID FROM tm_mts WHERE tm_mts.tmID = tmtests.tmID)) +
            (SELECT COUNT(*) FROM etitems WHERE etitems.etID IN (SELECT etID FROM tm_ets WHERE tm_ets.tmID = tmtests.tmID)) as itemCount'
            )
            // DB::raw('(SELECT COUNT(*) FROM tmitems WHERE tmitems.tmID = tmtests.tmID) as itemCount')
        )
            ->where('tmIsPublic', 1)
            ->where('isHidden', 0)
            ->leftJoin('users', 'tmtests.user_id', '=', 'users.id');
        // dd($mixed);

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

            $mixed->where(function ($query) use ($search) {
                $query->where('tmtests.tmTitle', 'LIKE', "%$search%")
                    ->orWhere('tmtests.tmDescription', 'LIKE', "%$search%");
            });
        }

        $filter = [];
        if ($request->input('mcq-filter') || $request->input('tf-filter') || $request->input('matching-filter') || $request->input('enumeration-filter') || $request->input('mixed-filter')) {
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

            if ($request->input('mixed-filter')) {
                $result = $result ? $result->union($mixed) : $mixed;
                $filter[] = 'mixed';
            }
        } else {
            $result = $mcq
                ->union($et)
                ->union($mt)
                ->union($tf)
                ->union($mixed);
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
            ->orderBy('updated_at', $sortDate)
            ->paginate(13);

        // dd($result);
        // dd($result);
        
        if (session()->has('success')) {
            return view('students.taketest.index', [
                'page' => $page,
                'sortDate' => $sortDate,
                'tests' => $result,
                'subjects' => $subjects,
                'filterSubjects' => $filterSubjects,
                'filterType' => $filter,
                'searchInput' => $search,
            ])->with('success', session('success'));
        }
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
        if ($type == 'mixed') {
            $questions = tmQuizItems::where('tmID', $testID)->get();
            $answers = [];

            foreach ($questions as $question) {
                $answers[$question->itmID] = 0;
            }

            session([$type . 'quiz' . $testID => $answers]);

            $questions = tmTfItems::where('tmID', $testID)->get();
            $answers = [];

            foreach ($questions as $question) {
                $answers[$question->itmID] = 0;
            }

            session([$type . 'tf' . $testID => $answers]);

            $test = tmMt::where('tmID', $testID)->get();
            foreach ($test as $questions) {
                $mtQuestions = mtitems::where('mtID', $questions->mtID)->get();
                $answers = [];
                foreach ($mtQuestions as $question) {
                    $answers[$question->itmID] = '';
                }
                session([$type . 'mt' . $questions->mtID . $testID => $answers]);
            }


            $test = tmEt::where('tmID', $testID)->get();
            foreach ($test as $questions) {
                $etAnswers = etitems::where('etID', $questions->etID)->get();
                $answers = [];
                foreach ($etAnswers as $answer) {
                    $answers[] = '';
                }
                // echo($questions->etID . '<br>');
                session([$type . 'et' . $questions->etID . $testID => $answers]);
            }
            // dd(session($type . 'et' . 3 . $testID));
        }
    }

    private function saveAnswerToSession($type, $testID, $itemId = null, $answer = null, $tmType = null, $tmTest = null)
    {
        if ($type == 'mixed') {
            if (in_array($tmType, ['quiz', 'tf'])) {
                // Retrieve existing answers from the session
                $answers = session($type . $tmType . $testID, []);


                // Save the new answer
                $answers[$itemId] = $answer;
                // dd(session($type . $tmType . $testID), $answers, $itemId);

                // Save the updated answers to the session
                session([$type . $tmType . $testID => $answers]);
            }
            if ($tmType == 'mt') {
                // dd($answer);
                session([$type . 'mt' . $tmTest[0]->mtID . $testID => $answer]);
                // dd(session($type . 'mt' . $tmTest[0]->mtID . $testID));
            }
            if ($tmType == 'et') {
                session([$type . 'et' . $tmTest[0]->etID . $testID => $answer]);
                // dd(session($type . 'et' . $tmTest[0]->etID . $testID), $answer);
                // dd(session($type . 'mt' . $tmTest[0]->mtID . $testID));
            }
        } else {
            // Retrieve existing answers from the session
            $answers = session($type . $tmType . $testID, []);

            // Save the new answer
            $answers[$itemId] = $answer;

            // Save the updated answers to the session
            session([$type . $tmType . $testID => $answers]);
        }
    }


    public function taketest(Request $request, $type, $id, $creatorID)
    {
        $testType = ['mcq', 'et', 'mt', 'tf', 'mixed'];
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

        if ($type == 'mixed') {
            $tmquiz = tmQuizItems::leftJoin('quizitems', 'quizitems.itmID', '=', 'tm_quiz_items.itmID')->select(
                DB::raw("'quiz' as type"),
                'quizitems.itmID as id'
            )->where('tmID', $id);
            $tmtf = tmTfItems::leftJoin('tfitems', 'tfitems.itmID', '=', 'tm_tf_items.itmID')->select(
                DB::raw("'tf' as type"),
                'tfitems.itmID as id'
            )->where('tmID', $id);
            $tmmt = tmMt::leftJoin('mttests', 'mttests.mtID', '=', 'tm_mts.mtID')->select(
                DB::raw("'mt' as type"),
                'mttests.mtID as id'
            )->where('tmID', $id);
            $tmet = tmet::leftJoin('ettests', 'ettests.etID', '=', 'tm_ets.etID')->select(
                DB::raw("'et' as type"),
                'ettests.etID as id'
            )->where('tmID', $id);

            // dd($tmquiz, $tmtf, $tmmt, $tmet);
            $question = $tmquiz->union($tmtf)->union($tmmt)->union($tmet)->paginate(1);

            if (!session()->has($type . $id)) {
                session([$type . $id => 1]);
                $this->initializeAnswerSession($type, $id);
            }
            // dd($request->input('oldTmType'));
            if (in_array($request->input('oldTmType'), ['quiz', 'tf'])) {
                // dd($request->input('questionID'));
                $this->saveAnswerToSession($type, $id, $request->input('questionID'), $request->input('options'), $request->input('oldTmType'));
            }
            if ($request->input('oldTmType') == 'mt') {
                $answers = $request->input('selects');
                $tmMTTest = mttests::where('mtID', $request->input('questionID'))->get();
                $this->saveAnswerToSession($type, $id, null, $answers, $request->input('oldTmType'), $tmMTTest);
            }
            if ($request->input('oldTmType') == 'et') {
                $answers = $request->input('answers');
                $tmETTest = ettests::where('etID', $request->input('questionID'))->get();
                // dd($request->input('questionID'));
                // dd($tmETTest);
                $this->saveAnswerToSession($type, $id, null, $answers, $request->input('oldTmType'), $tmETTest);
            }
            foreach ($question as $res) {
                $tmType = $res->type;
                if ($res->type == 'quiz') {
                    $item = quizitems::find($res->id);
                    // dd($item);
                    // dd($question);
                }
                if ($res->type == 'tf') {
                    $item = tfitems::find($res->id);
                    // dd($question);
                }
                if ($res->type == 'mt') {
                    $testItem = mttests::find($res->id);
                    $items = mtitems::where('mtID', $testItem->mtID)
                        ->get();
                    $itemQuestion = [];
                    $itemAnswer = [];
                    foreach ($items as $item) {
                        $itemQuestion[$item->itmID] = $item->itmQuestion;
                        $itemAnswer[] = $item->itmAnswer;
                    }
                    shuffle($itemAnswer);
                    // dd($tmType);
                }
                if ($res->type == 'et') {
                    $testItem = ettests::find($res->id);
                    $item = etitems::where('etID', $testItem->etID)
                        ->get();
                }
            }
            if (!empty($question)) {
                if (in_array($question[0]->type, ['quiz', 'tf'])) {
                    $studentAnswers = session($type . $question[0]->type . $id);
                    // dd($type, session()->has($type . $id), $id, session($type . $id));
                } else {
                    $studentAnswers = session($type . $question[0]->type . $question[0]->id . $id);
                }
            }
        }
        // dd($itemQuestion, $studentAnswers);
        // dd($question);
        // dd($item);
        // dd($testItem);
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
                'creatorID' => $creatorID,
                'tmType' =>  $tmType ?? null,
                'tmItem' =>  $item ?? null,
            ]);
        }
        // dd("HELLo");
        return redirect()->route('finish-test', ['type' => $type, 'id' => $id]);
    }

    public function finishtest(Request $request, $type, $id)
    {
        $testType = ['mcq', 'et', 'mt', 'tf', 'mixed'];
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
        if ($type == 'mixed') {
            $test = tmTestsTaken::create([
                'user_id' => Auth::id(),
                'tmID' => $id,
            ]);
            $resultID = $test->tmttID;
            $tmquiz = tmQuizItems::leftJoin('quizitems', 'quizitems.itmID', '=', 'tm_quiz_items.itmID')->select(
                DB::raw("'quiz' as type"),
                'quizitems.itmID as id'
            )->where('tmID', $id);
            $tmtf = tmTfItems::leftJoin('tfitems', 'tfitems.itmID', '=', 'tm_tf_items.itmID')->select(
                DB::raw("'tf' as type"),
                'tfitems.itmID as id'
            )->where('tmID', $id);
            $tmmt = tmMt::leftJoin('mttests', 'mttests.mtID', '=', 'tm_mts.mtID')->select(
                DB::raw("'mt' as type"),
                'mttests.mtID as id'
            )->where('tmID', $id);
            $tmet = tmet::leftJoin('ettests', 'ettests.etID', '=', 'tm_ets.etID')->select(
                DB::raw("'et' as type"),
                'ettests.etID as id'
            )->where('tmID', $id);
            // dd($tmquiz, $tmtf, $tmmt, $tmet);
            $question = $tmquiz->union($tmtf)->union($tmmt)->union($tmet)->get();
            $doneTypes = [];
            foreach ($question as $res) {
                if ($res->type == 'quiz' && !in_array('quiz', $doneTypes)) {
                    $answers = session($type . $res->type . $id);
                    foreach ($answers as $key => $value) {
                        tmQuizItemsAnswers::create([
                            'tmttID' => $test->tmttID,
                            'itmID' => $key,
                            'qzStudentItemAnswer' =>  is_null($value) ? '0' : $value,
                        ]);
                    }
                    session()->forget($type . $res->type . $id);
                    $doneTypes[] = 'quiz';
                    // session()->forget($type . $question[0]->type . $id);
                } elseif ($res->type == 'tf' && !in_array('tf', $doneTypes)) {
                    $answers = session($type . $res->type . $id);
                    foreach ($answers as $key => $value) {
                        tmTfItemsAnswers::create([
                            'tmttID' => $test->tmttID,
                            'itmID' => $key,
                            'tfStudentItemAnswer' =>  is_null($value) ? '0' : $value,
                        ]);
                    }
                    session()->forget($type . $res->type . $id);
                    $doneTypes[] = 'tf';
                    // session()->forget($type . $question[0]->type . $id);
                } elseif ($res->type == 'mt') {
                    $answers = session($type . $res->type . $res->id . $id);
                    foreach ($answers as $key => $value) {
                        tmMtItemsAnswers::create([
                            'tmttID' => $test->tmttID,
                            'itmID' => $key,
                            'mtStudentItemAnswer' => $value,
                        ]);
                    }
                    session()->forget($type . $res->type . $res->id . $id);
                } elseif ($res->type == 'et') {
                    $answers = session($type . $res->type . $res->id . $id);
                    foreach ($answers as $key => $value) {
                        tmEtItemsAnswers::create([
                            'tmttID' => $test->tmttID,
                            'etID' => $res->id,
                            'etStudentItemAnswer' => $value,
                        ]);
                    }
                    session()->forget($type . $res->type . $res->id . $id);
                }
                // dd("HELLO");
            }
            // dd("HELLO");
            // if (in_array($question[0]->type, ['quiz', 'tf'])) {
            //     $studentAnswers = session($type . $question[0]->type . $id);
            // } else {
            //     $studentAnswers = session($type . $question[0]->type . $question[0]->id . $id);
            // }
            // dd($question);
            session()->forget($type . $id);
        }
        // dd("Asdasda");
        return redirect('/taketest/' . $type . "/" . $resultID . "/result");
    }

    public function seeresult($type, $id)
    {
        $points = 0;
        $total = 0;
        $testType = ['mcq', 'et', 'mt', 'tf', 'mixed'];
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
        if ($type == 'mixed') {
            $tmTestTaken = tmTestsTaken::find($id);
            $tmID = $tmTestTaken->tmID;
            $tmquiz = tmQuizItems::leftJoin('quizitems', 'quizitems.itmID', '=', 'tm_quiz_items.itmID')->select(
                DB::raw("'quiz' as type"),
                'quizitems.itmID as id'
            )->where('tmID', $tmID);
            $tmtf = tmTfItems::leftJoin('tfitems', 'tfitems.itmID', '=', 'tm_tf_items.itmID')->select(
                DB::raw("'tf' as type"),
                'tfitems.itmID as id'
            )->where('tmID', $tmID);
            $tmmt = tmMt::leftJoin('mttests', 'mttests.mtID', '=', 'tm_mts.mtID')->select(
                DB::raw("'mt' as type"),
                'mttests.mtID as id'
            )->where('tmID', $tmID);
            $tmet = tmet::leftJoin('ettests', 'ettests.etID', '=', 'tm_ets.etID')->select(
                DB::raw("'et' as type"),
                'ettests.etID as id'
            )->where('tmID', $tmID);
            // dd($tmquiz, $tmtf, $tmmt, $tmet);
            $question = $tmquiz->union($tmtf)->union($tmmt)->union($tmet)->get();
            // dd($question);
            foreach ($question as $res) {
                if ($res->type == 'quiz') {
                    $items = quizitems::leftJoin('tm_quiz_items_answers', 'tm_quiz_items_answers.itmID', '=', 'quizitems.itmID')->where('quizitems.itmID', $res->id)
                        ->where('tm_quiz_items_answers.tmttID', $id)->get();
                    $total += $items[0]->itmPoints;
                    if ($items[0]->itmAnswer == $items[0]->qzStudentItemAnswer) {
                        $points += $items[0]->itmPoints;
                        // echo("Correct: " . $items[0]->itmAnswer . ' = ' . $items[0]->qzStudentItemAnswer);
                    } else {
                        // echo("Wrong: " . $items[0]->itmAnswer . ' != ' . $items[0]->qzStudentItemAnswer);
                    }
                    // echo("<br>");

                    // session()->forget($type . $question[0]->type . $id);
                } elseif ($res->type == 'tf') {
                    $items = tfitems::leftJoin('tm_tf_items_answers', 'tm_tf_items_answers.itmID', '=', 'tfitems.itmID')->where('tfitems.itmID', $res->id)
                        ->where('tm_tf_items_answers.tmttID', $id)->get();
                    $total += $items[0]->itmPoints;
                    if ($items[0]->itmAnswer == $items[0]->tfStudentItemAnswer) {
                        $points += $items[0]->itmPoints;
                        // echo("Correct: " . $items[0]->itmAnswer . ' = ' . $items[0]->tfStudentItemAnswer);
                    } else {
                        // echo("Wrong: " . $items[0]->itmAnswer . ' != ' . $items[0]->tfStudentItemAnswer);
                    }
                    // echo("<br>");
                    // session()->forget($type . $question[0]->type . $id);
                } elseif ($res->type == 'mt') {
                    $items = mtitems::leftJoin('tm_mt_items_answers', 'tm_mt_items_answers.itmID', '=', 'mtitems.itmID')->where('mtitems.mtID', $res->id)
                        ->where('tm_mt_items_answers.tmttID', $id)->get();
                    foreach ($items as $item) {
                        $total += $item->itmPoints;
                        if ($item->itmAnswer == $item->mtStudentItemAnswer) {
                            $points += $item->itmPoints;

                            // echo("Correct: " . $item->itmAnswer . ' = ' . $item->mtStudentItemAnswer);
                        } else {
                            // echo("Wrong: " . $item->itmAnswer . ' != ' . $item->mtStudentItemAnswer);
                        }
                        // echo("<br>");
                    }
                } elseif ($res->type == 'et') {
                    $items = etitems::where('etID', $res->id)->get();
                    $studentAnswers = tmEtItemsAnswers::where('tmttID', $id)->get();
                    // dd($items, $studentAnswers);
                    $correctAnswers = [];
                    foreach ($items as $item) {
                        $correctAnswers[$item->itmAnswer] = $item->itmIsCaseSensitive;
                        $total += 1;
                    }
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
                            // echo("Answer: " . $crctAnswer . " | Student Answer:" . $stdntAnswer);
                            // echo("<br>");
                        }
                    }
                }
            }
        }

        // dd($total, $points);
        $page = 'take test';
        return view('students.taketest.finish', [
            'page' => $page,
            'points' => $points,
            'total' => $total,
        ]);
    }
}
