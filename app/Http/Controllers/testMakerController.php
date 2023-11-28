<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tmtests;
use App\Models\essays;
use App\Models\mttests;
use App\Models\ettests;
use App\Models\quizzes;
use App\Models\tftests;
use App\Models\mtftests;
use App\Models\tmEssay;
use App\Models\tmQuizItems;
use App\Models\tmTfItems;
use App\Models\tmMtfItems;
use App\Models\tmMt;
use App\Models\tmEt;
use App\Models\subjects;
use App\Models\analyticessaytags;
use App\Models\analyticmttags;
use App\Models\analyticettags;
use App\Models\analyticquizitemtags;
use App\Models\analytictfitemtags;
use App\Models\analyticmtfitemtags;
use App\Models\mtfitems;
use App\Models\quizitems;
use App\Models\tfitems;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class testMakerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('isTeacher');
    }

    public function publish(string $id)
    {
        $test = tmtests::find($id);
        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $test->update([
            'tmIsPublic' => 1,
        ]);
        return back()->with('publish', 'Record successfully published. Now it will be seen by students.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $currentUserId = Auth::user()->id;
        $testsQuery = tmtests::where('user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('tmTitle', 'LIKE', "%$search%")
                    ->orWhere('tmDescription', 'LIKE', "%$search%");
            });
        }
        $sortDate =  is_null($request->input('sort-date')) ? 'desc' : $request->input('sort-date');

        $tests = $testsQuery->orderBy('tmID', $sortDate)
            ->paginate(13);


        $testPage = 'test';
        return view('testbank.test_maker.index', [
            'tests' => $tests,
            'testPage' => $testPage,
            'searchInput' => $search,
            'sortDate' => $sortDate,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $testPage = 'test';
        return view('testbank.test_maker.add', [
            'testPage' => $testPage,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = tmtests::create([
            'user_id' => Auth::id(),
            'tmTitle' => $request->input('title'),
            'tmDescription' => $request->input('description'),
            'tmIsPublic' => 0,
        ]);

        return redirect('/test');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = tmtests::find($id);

        $currentUserId = Auth::user()->id;


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }


        // $questions = questions::where('tmID', '=', $id)
        //     ->get();

        // $allTestQuery = testMaker::from(DB::raw('test_makers AS tm'))
        //     ->join('testbanks AS t', 't.id', '=', 'tm.test_id')
        //     ->where('tm.tmID', $id)
        //     ->select('t.*', 'tm.id as test_maker_ID')
        //     ->get();

        // dd($allTestQuery);

        // $allQuestionQuery = testMaker::join('questions', 'questions.id', '=', 'question_id')
        //     ->join('testbanks AS t', 't.id', '=', 'questions.tmID')
        //     ->where('test_makers.tmID', $id)
        //     ->select('questions.*', 'test_type', 'test_makers.id as test_maker_ID')
        //     ->get();

        // dd($allQuestionQuery);

        $uniqueSubjects = subjects::all();

        // dd($uniqueSubjects);

        $essayQuestions = tmEssay::join('essays', 'essays.essID', 'tm_essays.essID')->join('tmtests', 'tmtests.tmID', 'tm_essays.tmID')->where('tmtests.tmID', $id)
            ->get();

        $matchingQuestions = tmMt::join('mttests', 'mttests.mtID', 'tm_mts.mtID')->join('tmtests', 'tmtests.tmID', 'tm_mts.tmID')->where('tmtests.tmID', $id)
            ->get();

        $enumerationQuestions = tmEt::join('ettests', 'ettests.etID', 'tm_ets.etID')->join('tmtests', 'tmtests.tmID', 'tm_ets.tmID')->where('tmtests.tmID', $id)
            ->get();

        $quizQuestions = tmQuizItems::join('quizitems', 'quizitems.itmID', 'tm_quiz_items.itmID')->join('tmtests', 'tmtests.tmID', 'tm_quiz_items.tmID')->where('tmtests.tmID', $id)
            ->get();

        $tfQuestions = tmTfItems::join('tfitems', 'tfitems.itmID', 'tm_tf_items.itmID')->join('tmtests', 'tmtests.tmID', 'tm_tf_items.tmID')->where('tmtests.tmID', $id)
            ->get();

        $mtfQuestions = tmMtfItems::join('mtfitems', 'mtfitems.itmID', 'tm_mtf_items.itmID')->join('tmtests', 'tmtests.tmID', 'tm_mtf_items.tmID')->where('tmtests.tmID', $id)
            ->get();


        $testPage = 'test';
        return view('testbank.test_maker.description', [
            'test' => $test,
            // 'questions' => $questions,
            // 'allTestQuery' => $allTestQuery,
            // 'allQuestionQuery' => $allQuestionQuery,
            'essayQuestions' => $essayQuestions,
            'matchingQuestions' => $matchingQuestions,
            'enumerationQuestions' => $enumerationQuestions,
            'quizQuestions' => $quizQuestions,
            'tfQuestions' => $tfQuestions,
            'mtfQuestions' => $mtfQuestions,
            'subjects' => $uniqueSubjects,
            'testPage' => $testPage,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $test = tmtests::find($id);

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $testPage = 'test';
        return view('testbank.test_maker.edit', [
            'testPage' => $testPage,
            'test' => $test,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = tmtests::find($id);
        if (is_null($testbank)) {
            abort(404); // User does not own the test
        }
        if ($testbank->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $testbank->update([
            'tmTitle' => $request->input('title'),
            'tmDescription' => $request->input('description'),
        ]);

        return redirect('/test');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = tmtests::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }


        tmQuizItems::where('tmID', $id)->delete();
        tmTfItems::where('tmID', $id)->delete();
        tmMtfItems::where('tmID', $id)->delete();
        tmEssay::where('tmID', $id)->delete();
        tmEt::where('tmID', $id)->delete();
        tmMt::where('tmID', $id)->delete();
        $test->delete();

        return back();
    }

    public function add_test_index(Request $request, string $id, string $test_type)
    {

        $test = tmtests::find($id);
        $currentUserId = Auth::user()->id;
        $types_of_test = ['essay', 'tf', 'mtf', 'matching', 'enumeration', 'mcq'];
        $filterLabel = ['realistic_filter' => 'Realistic', 'investigative_filter' => 'Investigative', 'artistic_filter' => 'Artistic', 'social_filter' => 'Social', 'enterprising_filter' => 'Enterprising', 'conventional_filter' => 'Conventional'];

        $searchTitle = $request->input('search_title');
        $subjectFilter = $request->input('subject');
        foreach ($filterLabel as $label) {
            if ($request->input($label)) {
                // dd($request->input($label), $label);
            }
        }
        if ($searchTitle) {
            // dd($searchTitle);
        }

        // if ($subjectFilter == '1' || is_null($subjectFilter)) {
        //     dd($subjectFilter);
        // }


        if (!in_array($test_type, $types_of_test)) {
            abort(404, 'Page not found');
        }

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $allQuestionQuery = null;

        if ($test_type == 'essay') {
            $allTestQuery = essays::leftJoin('tm_essays', 'tm_essays.essID', '=', 'essays.essID')
                ->leftJoin('subjects', 'essays.subjectID', '=', 'subjects.subjectID')
                ->leftjoin('tmtests', 'tm_essays.tmID', '=', 'tmtests.tmID')
                ->where('essays.user_id', '=', $currentUserId)
                ->select('essays.*', 'subjects.subjectName')
                ->addSelect(DB::raw('CASE WHEN tm_essays.tmessID IS NOT NULL AND tmtests.tmID = ' . $id . ' THEN 1 ELSE NULL END AS in_test_makers'));

            if ($searchTitle) {
                $allTestQuery = $allTestQuery->where('essTitle', 'LIKE', "%$searchTitle%");
            }
            if (!($subjectFilter == '1' || is_null($subjectFilter))) {
                $allTestQuery = $allTestQuery->where('subjects.subjectName', $subjectFilter);
            }

            $allTestQuery = $allTestQuery->orderBy('essays.essID', 'desc')
                ->get();


            $allTestQuery->each(function ($allTestQuery) {
                $tags = analyticessaytags::join('analytictags', 'analytictags.tagID', '=', 'analyticessaytags.tagID')
                    ->where('analyticessaytags.essID', $allTestQuery->essID)
                    ->get();

                $tagData = [];
                foreach ($tags as $tag) {
                    $tagData[$tag->tagName] = $tag->similarity;
                }
                $allTestQuery->tags = $tagData;
            });

            $shouldFilter = false;
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $shouldFilter = true;
                    break;
                }
            }

            if ($shouldFilter) {
                $allTestQuery = $allTestQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                    // Apply additional filtering conditions here
                    $labelNumbers = 0;
                    $labelFiltered = 0;
                    foreach ($filterLabel as $key => $value) {
                        if ($request->input($key)) {
                            $labelNumbers += 1;
                            if (isset($item->tags[$value])) {
                                $labelFiltered += 1;
                            }
                        }
                    }
                    return $labelNumbers == $labelFiltered;
                });
            }
        }


        if ($test_type == 'matching') {
            $allTestQuery = mttests::leftJoin('tm_mts', 'tm_mts.mtID', '=', 'mttests.mtID')
                ->leftJoin('subjects', 'mttests.subjectID', '=', 'subjects.subjectID')
                ->leftjoin('tmtests', 'tm_mts.tmID', '=', 'tmtests.tmID')
                ->where('mttests.user_id', '=', $currentUserId)
                ->select('mttests.*', 'subjects.subjectName')
                ->addSelect(DB::raw('CASE WHEN tm_mts.tmmtID IS NOT NULL AND tmtests.tmID = ' . $id . ' THEN 1 ELSE NULL END AS in_test_makers'));

            if ($searchTitle) {
                $allTestQuery = $allTestQuery->where('mtTitle', 'LIKE', "%$searchTitle%");
            }
            if (!($subjectFilter == '1' || is_null($subjectFilter))) {
                $allTestQuery = $allTestQuery->where('subjects.subjectName', $subjectFilter);
            }

            $allTestQuery = $allTestQuery->orderBy('mttests.mtID', 'desc')
                ->get();


            $allTestQuery->each(function ($allTestQuery) {
                $tags = analyticmttags::join('analytictags', 'analytictags.tagID', '=', 'analyticmttags.tagID')
                    ->where('analyticmttags.mtID', $allTestQuery->mtID)
                    ->get();

                $tagData = [];
                foreach ($tags as $tag) {
                    $tagData[$tag->tagName] = $tag->similarity;
                }
                $allTestQuery->tags = $tagData;
            });

            $shouldFilter = false;
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $shouldFilter = true;
                    break;
                }
            }

            if ($shouldFilter) {
                $allTestQuery = $allTestQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                    // Apply additional filtering conditions here
                    $labelNumbers = 0;
                    $labelFiltered = 0;
                    foreach ($filterLabel as $key => $value) {
                        if ($request->input($key)) {
                            $labelNumbers += 1;
                            if (isset($item->tags[$value])) {
                                $labelFiltered += 1;
                            }
                        }
                    }
                    return $labelNumbers == $labelFiltered;
                });
            }
            $allQuestionQuery = mttests::join('mtitems', 'mttests.mtID', '=', 'mtitems.mtID')
                ->where('mttests.user_id', '=', $currentUserId)
                ->get();
        }


        if ($test_type == 'enumeration') {
            $allTestQuery = ettests::leftJoin('tm_ets', 'tm_ets.etID', '=', 'ettests.etID')
                ->leftJoin('subjects', 'ettests.subjectID', '=', 'subjects.subjectID')
                ->leftjoin('tmtests', 'tm_ets.tmID', '=', 'tmtests.tmID')
                ->where('ettests.user_id', '=', $currentUserId)
                ->select('ettests.*', 'subjects.subjectName')
                ->addSelect(DB::raw('CASE WHEN tm_ets.tmetID IS NOT NULL AND tmtests.tmID = ' . $id . ' THEN 1 ELSE NULL END AS in_test_makers'));

            if ($searchTitle) {
                $allTestQuery = $allTestQuery->where('etTitle', 'LIKE', "%$searchTitle%");
            }
            if (!($subjectFilter == '1' || is_null($subjectFilter))) {
                $allTestQuery = $allTestQuery->where('subjects.subjectName', $subjectFilter);
            }

            $allTestQuery = $allTestQuery->orderBy('ettests.etID', 'desc')
                ->get();


            $allTestQuery->each(function ($allTestQuery) {
                $tags = analyticettags::join('analytictags', 'analytictags.tagID', '=', 'analyticettags.tagID')
                    ->where('analyticettags.etID', $allTestQuery->etID)
                    ->get();

                $tagData = [];
                foreach ($tags as $tag) {
                    $tagData[$tag->tagName] = $tag->similarity;
                }
                $allTestQuery->tags = $tagData;
            });

            $shouldFilter = false;
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $shouldFilter = true;
                    break;
                }
            }

            if ($shouldFilter) {
                $allTestQuery = $allTestQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                    // Apply additional filtering conditions here
                    $labelNumbers = 0;
                    $labelFiltered = 0;
                    foreach ($filterLabel as $key => $value) {
                        if ($request->input($key)) {
                            $labelNumbers += 1;
                            if (isset($item->tags[$value])) {
                                $labelFiltered += 1;
                            }
                        }
                    }
                    return $labelNumbers == $labelFiltered;
                });
            }
            $allQuestionQuery = ettests::join('etitems', 'ettests.etID', '=', 'etitems.etID')
                ->where('ettests.user_id', '=', $currentUserId)
                ->get();
        }

        if ($test_type == 'mcq') {
            $allQuestionQuery = quizzes::join('quizitems', 'quizzes.qzID', '=', 'quizitems.qzID')
                ->leftJoin('tm_quiz_items', 'tm_quiz_items.itmID', '=', 'quizitems.itmID')
                ->leftjoin('tmtests', 'tm_quiz_items.tmID', '=', 'tmtests.tmID')
                ->where('quizzes.user_id', '=', $currentUserId)
                ->select('quizitems.*')
                ->addSelect(DB::raw('CASE WHEN tm_quiz_items.tmquizID IS NOT NULL AND tmtests.tmID = ' . $id . ' THEN 1 ELSE NULL END AS in_test_makers'))
                ->get();

            $allQuestionQuery->each(function ($allQuestionQuery) {
                $tags = analyticquizitemtags::join('analytictags', 'analytictags.tagID', '=', 'analyticquizitemtags.tagID')
                    ->where('analyticquizitemtags.itmID', $allQuestionQuery->itmID)
                    ->get();

                $tagData = [];
                foreach ($tags as $tag) {
                    $tagData[$tag->tagName] = $tag->similarity;
                }
                $allQuestionQuery->tags = $tagData;
            });

            $shouldFilter = false;
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $shouldFilter = true;
                    break;
                }
            }

            if ($shouldFilter) {
                $allQuestionQuery = $allQuestionQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                    // Apply additional filtering conditions here
                    $labelNumbers = 0;
                    $labelFiltered = 0;
                    foreach ($filterLabel as $key => $value) {
                        if ($request->input($key)) {
                            $labelNumbers += 1;
                            if (isset($item->tags[$value])) {
                                $labelFiltered += 1;
                            }
                        }
                    }
                    return $labelNumbers == $labelFiltered;
                });
            }
            $allTestQuery = quizzes::leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID')
                ->where('quizzes.user_id', '=', $currentUserId)
                ->whereIn('quizzes.qzID', $allQuestionQuery->pluck('qzID')->toArray())
                ->select('quizzes.*', 'subjects.subjectName');

            if ($searchTitle) {
                $allTestQuery = $allTestQuery->where('qzTitle', 'LIKE', "%$searchTitle%");
            }
            if (!($subjectFilter == '1' || is_null($subjectFilter))) {
                $allTestQuery = $allTestQuery->where('subjects.subjectName', $subjectFilter);
            }

            $allTestQuery = $allTestQuery->orderBy('quizzes.qzID', 'desc')
                ->get();
        }

        if ($test_type == 'tf') {

            $allQuestionQuery = tftests::join('tfitems', 'tftests.tfID', '=', 'tfitems.tfID')
                ->leftJoin('tm_tf_items', 'tm_tf_items.itmID', '=', 'tfitems.itmID')
                ->leftjoin('tmtests', 'tm_tf_items.tmID', '=', 'tmtests.tmID')
                ->where('tftests.user_id', '=', $currentUserId)
                ->select('tfitems.*')
                ->addSelect(DB::raw('CASE WHEN tm_tf_items.tmtfID IS NOT NULL AND tmtests.tmID = ' . $id . ' THEN 1 ELSE NULL END AS in_test_makers'))
                ->get();

            $allQuestionQuery->each(function ($allQuestionQuery) {
                $tags = analytictfitemtags::join('analytictags', 'analytictags.tagID', '=', 'analytictfitemtags.tagID')
                    ->where('analytictfitemtags.itmID', $allQuestionQuery->itmID)
                    ->get();

                $tagData = [];
                foreach ($tags as $tag) {
                    $tagData[$tag->tagName] = $tag->similarity;
                }
                $allQuestionQuery->tags = $tagData;
            });

            $shouldFilter = false;
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $shouldFilter = true;
                    break;
                }
            }

            if ($shouldFilter) {
                $allQuestionQuery = $allQuestionQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                    // Apply additional filtering conditions here
                    $labelNumbers = 0;
                    $labelFiltered = 0;
                    foreach ($filterLabel as $key => $value) {
                        if ($request->input($key)) {
                            $labelNumbers += 1;
                            if (isset($item->tags[$value])) {
                                $labelFiltered += 1;
                            }
                        }
                    }
                    return $labelNumbers == $labelFiltered;
                });
            }
            $allTestQuery = tftests::leftJoin('subjects', 'tftests.subjectID', '=', 'subjects.subjectID')
                ->where('tftests.user_id', '=', $currentUserId)
                ->whereIn('tftests.tfID', $allQuestionQuery->pluck('tfID')->toArray())
                ->select('tftests.*', 'subjects.subjectName');

            if ($searchTitle) {
                $allTestQuery = $allTestQuery->where('tfTitle', 'LIKE', "%$searchTitle%");
            }
            if (!($subjectFilter == '1' || is_null($subjectFilter))) {
                $allTestQuery = $allTestQuery->where('subjects.subjectName', $subjectFilter);
            }

            $allTestQuery = $allTestQuery->orderBy('tftests.tfID', 'desc')
                ->get();
        }

        if ($test_type == 'mtf') {
            $allQuestionQuery = mtftests::join('mtfitems', 'mtftests.mtfID', '=', 'mtfitems.mtfID')
                ->leftJoin('tm_mtf_items', 'tm_mtf_items.itmID', '=', 'mtfitems.itmID')
                ->leftjoin('tmtests', 'tm_mtf_items.tmID', '=', 'tmtests.tmID')
                ->where('mtftests.user_id', '=', $currentUserId)
                ->select('mtfitems.*')
                ->addSelect(DB::raw('CASE WHEN tm_mtf_items.tmmtfID IS NOT NULL AND tmtests.tmID = ' . $id . ' THEN 1 ELSE NULL END AS in_test_makers'))
                ->get();

            $allQuestionQuery->each(function ($allQuestionQuery) {
                $tags = analyticmtfitemtags::join('analytictags', 'analytictags.tagID', '=', 'analyticmtfitemtags.tagID')
                    ->where('analyticmtfitemtags.itmID', $allQuestionQuery->itmID)
                    ->get();

                $tagData = [];
                foreach ($tags as $tag) {
                    $tagData[$tag->tagName] = $tag->similarity;
                }
                $allQuestionQuery->tags = $tagData;
            });

            $shouldFilter = false;
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $shouldFilter = true;
                    break;
                }
            }

            if ($shouldFilter) {
                $allQuestionQuery = $allQuestionQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                    // Apply additional filtering conditions here
                    $labelNumbers = 0;
                    $labelFiltered = 0;
                    foreach ($filterLabel as $key => $value) {
                        if ($request->input($key)) {
                            $labelNumbers += 1;
                            if (isset($item->tags[$value])) {
                                $labelFiltered += 1;
                            }
                        }
                    }
                    return $labelNumbers == $labelFiltered;
                });
            }
            $allTestQuery = mtftests::leftJoin('subjects', 'mtftests.subjectID', '=', 'subjects.subjectID')
                ->where('mtftests.user_id', '=', $currentUserId)
                ->whereIn('mtftests.mtfID', $allQuestionQuery->pluck('mtfID')->toArray())
                ->select('mtftests.*', 'subjects.subjectName');

            if ($searchTitle) {
                $allTestQuery = $allTestQuery->where('mtfTitle', 'LIKE', "%$searchTitle%");
            }
            if (!($subjectFilter == '1' || is_null($subjectFilter))) {
                $allTestQuery = $allTestQuery->where('subjects.subjectName', $subjectFilter);
            }

            $allTestQuery = $allTestQuery->orderBy('mtftests.mtfID', 'desc')
                ->get();
        }
        // dd($allQuestionQuery);


        // $uniqueSubjects = subjects::where('subjectName', '!=', 'No Subject') // Exclude rows with 'No Subject'
        //     ->distinct('subjectName')
        //     ->pluck('subjectName')
        //     ->toArray();

        $uniqueSubjects = subjects::all();
        $testPage = 'test';
        return view('testbank.test_maker.add_question', [
            'test' => $test,
            'allTestQuery' => $allTestQuery,
            'allQuestionQuery' => $allQuestionQuery,
            'testType' => ucfirst($test_type),
            'uniqueSubjects' => $uniqueSubjects,
            'testPage' => $testPage,
            'subjectSelected' => $subjectFilter,
        ]);
    }

    public function add_test_store(Request $request, string $id, string $test_type)
    {
        $test = tmtests::find($id);
        $types_of_test = ['essay', 'tf', 'mtf', 'matching', 'enumeration', 'mcq'];

        if (!in_array($test_type, $types_of_test)) {
            abort(404, 'Page not found');
        }

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        if (in_array($test_type, ['tf', 'mtf', 'mcq'])) {
            $checkboxes = $request->input('question_checkbox_add', []);
            if (!empty($checkboxes)) {
                foreach ($checkboxes as $checkbox) {
                    if ($test_type == "mcq") {
                        tmQuizItems::create([
                            'tmID' => $id,
                            'itmID' => $checkbox,
                        ]);
                    }
                    if ($test_type == "tf") {
                        tmTfItems::create([
                            'tmID' => $id,
                            'itmID' => $checkbox,
                        ]);
                    }
                    if ($test_type == "mtf") {
                        tmMtfItems::create([
                            'tmID' => $id,
                            'itmID' => $checkbox,
                        ]);
                    }
                }
            }
        }

        if (in_array($test_type, ['essay', 'enumeration', 'matching'])) {
            $checkboxes = $request->input('test_checkbox_add', []);
            if (!empty($checkboxes)) {
                foreach ($checkboxes as $checkbox) {
                    if ($test_type == "essay") {
                        tmEssay::create([
                            'tmID' => $id,
                            'essID' => $checkbox,
                        ]);
                    }
                    if ($test_type == "enumeration") {
                        tmEt::create([
                            'tmID' => $id,
                            'etID' => $checkbox,
                        ]);
                    }
                    // dd($checkbox);
                    if ($test_type == "matching") {
                        tmMt::create([
                            'tmID' => $id,
                            'mtID' => $checkbox,
                        ]);
                    }
                }
            }
        }

        $this->update_score($id);
        return redirect('/test/' . $id);
    }

    public function random_test_store(Request $request, string $id, string $test_type)
    {
        $test = tmtests::find($id);
        $currentUserId = Auth::user()->id;
        $types_of_test = ['essay', 'tf', 'mtf', 'matching', 'enumeration', 'mcq'];
        $subjectFilter = $request->input('random_item_subject');
        $numberOfRows = $request->input('random_item_number');

        if (!in_array($test_type, $types_of_test)) {
            abort(404, 'Page not found');
        }

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $filterLabel = ['realistic_filter' => 'Realistic', 'investigative_filter' => 'Investigative', 'artistic_filter' => 'Artistic', 'social_filter' => 'Social', 'enterprising_filter' => 'Enterprising', 'conventional_filter' => 'Conventional'];


        if (in_array($test_type, ['tf', 'mtf', 'mcq'])) {
            if ($test_type == 'mcq') {
                $allQuestionQuery = quizzes::join('quizitems', 'quizzes.qzID', '=', 'quizitems.qzID')
                    ->leftJoin('tm_quiz_items', 'tm_quiz_items.itmID', '=', 'quizitems.itmID')
                    ->leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID')
                    ->where('quizzes.user_id', '=', $currentUserId)
                    ->whereNull('tm_quiz_items.tmquizID');

                if ($subjectFilter) {
                    $allQuestionQuery = $allQuestionQuery->where('subjectName', $subjectFilter);
                }
                $allQuestionQuery = $allQuestionQuery->inRandomOrder()->limit($numberOfRows)->select('quizitems.itmID')->get();


                $allQuestionQuery->each(function ($allQuestionQuery) {
                    $tags = analyticquizitemtags::join('analytictags', 'analytictags.tagID', '=', 'analyticquizitemtags.tagID')
                        ->where('analyticquizitemtags.itmID', $allQuestionQuery->itmID)
                        ->get();

                    $tagData = [];
                    foreach ($tags as $tag) {
                        $tagData[$tag->tagName] = $tag->similarity;
                    }
                    $allQuestionQuery->tags = $tagData;
                });

                $shouldFilter = false;
                foreach ($filterLabel as $key => $value) {
                    if ($request->input($key)) {
                        $shouldFilter = true;
                        break;
                    }
                }

                if ($shouldFilter) {
                    $allQuestionQuery = $allQuestionQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                        // Apply additional filtering conditions here
                        $labelNumbers = 0;
                        $labelFiltered = 0;
                        foreach ($filterLabel as $key => $value) {
                            if ($request->input($key)) {
                                $labelNumbers += 1;
                                if (isset($item->tags[$value])) {
                                    $labelFiltered += 1;
                                }
                            }
                        }
                        return $labelNumbers == $labelFiltered;
                    });
                }

                if ($allQuestionQuery->count() < $numberOfRows) {
                    return redirect()->back()->with('lackingRows', 'Not enough rows found.');
                }

                foreach ($allQuestionQuery as $question) {
                    tmQuizItems::create([
                        'tmID' => $id,
                        'itmID' => $question->itmID,
                    ]);
                }
            }

            if ($test_type == 'tf') {
                $allQuestionQuery = tftests::join('tfitems', 'tftests.tfID', '=', 'tfitems.tfID')
                    ->leftJoin('tm_tf_items', 'tm_tf_items.itmID', '=', 'tfitems.itmID')
                    ->leftJoin('subjects', 'tftests.subjectID', '=', 'subjects.subjectID')
                    ->where('tftests.user_id', '=', $currentUserId)
                    ->whereNull('tm_tf_items.tmtfID');

                if ($subjectFilter) {
                    $allQuestionQuery = $allQuestionQuery->where('subjectName', $subjectFilter);
                }
                $allQuestionQuery = $allQuestionQuery->inRandomOrder()->limit($numberOfRows)->select('tfitems.itmID')->get();

                $allQuestionQuery->each(function ($allQuestionQuery) {
                    $tags = analytictfitemtags::join('analytictags', 'analytictags.tagID', '=', 'analytictfitemtags.tagID')
                        ->where('analytictfitemtags.itmID', $allQuestionQuery->itmID)
                        ->get();

                    $tagData = [];
                    foreach ($tags as $tag) {
                        $tagData[$tag->tagName] = $tag->similarity;
                    }
                    $allQuestionQuery->tags = $tagData;
                });

                $shouldFilter = false;
                foreach ($filterLabel as $key => $value) {
                    if ($request->input($key)) {
                        $shouldFilter = true;
                        break;
                    }
                }

                if ($shouldFilter) {
                    $allQuestionQuery = $allQuestionQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                        // Apply additional filtering conditions here
                        $labelNumbers = 0;
                        $labelFiltered = 0;
                        foreach ($filterLabel as $key => $value) {
                            if ($request->input($key)) {
                                $labelNumbers += 1;
                                if (isset($item->tags[$value])) {
                                    $labelFiltered += 1;
                                }
                            }
                        }
                        return $labelNumbers == $labelFiltered;
                    });
                }

                if ($allQuestionQuery->count() < $numberOfRows) {
                    return redirect()->back()->with('lackingRows', 'Not enough rows found.');
                }

                foreach ($allQuestionQuery as $question) {
                    tmTfItems::create([
                        'tmID' => $id,
                        'itmID' => $question->itmID,
                    ]);
                }
            }

            if ($test_type == 'mtf') {
                $allQuestionQuery = mtftests::join('mtfitems', 'mtftests.mtfID', '=', 'mtfitems.mtfID')
                    ->leftJoin('tm_mtf_items', 'tm_mtf_items.itmID', '=', 'mtfitems.itmID')
                    ->leftJoin('subjects', 'mtftests.subjectID', '=', 'subjects.subjectID')
                    ->where('mtftests.user_id', '=', $currentUserId)
                    ->whereNull('tm_mtf_items.tmmtfID');

                if ($subjectFilter) {
                    $allQuestionQuery = $allQuestionQuery->where('subjectName', $subjectFilter);
                }
                $allQuestionQuery = $allQuestionQuery->inRandomOrder()->limit($numberOfRows)->select('mtfitems.itmID')->get();


                $allQuestionQuery->each(function ($allQuestionQuery) {
                    $tags = analyticmtfitemtags::join('analytictags', 'analytictags.tagID', '=', 'analyticmtfitemtags.tagID')
                        ->where('analyticmtfitemtags.itmID', $allQuestionQuery->itmID)
                        ->get();

                    $tagData = [];
                    foreach ($tags as $tag) {
                        $tagData[$tag->tagName] = $tag->similarity;
                    }
                    $allQuestionQuery->tags = $tagData;
                });

                $shouldFilter = false;
                foreach ($filterLabel as $key => $value) {
                    if ($request->input($key)) {
                        $shouldFilter = true;
                        break;
                    }
                }

                if ($shouldFilter) {
                    $allQuestionQuery = $allQuestionQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                        // Apply additional filtering conditions here
                        $labelNumbers = 0;
                        $labelFiltered = 0;
                        foreach ($filterLabel as $key => $value) {
                            if ($request->input($key)) {
                                $labelNumbers += 1;
                                if (isset($item->tags[$value])) {
                                    $labelFiltered += 1;
                                }
                            }
                        }
                        return $labelNumbers == $labelFiltered;
                    });
                }

                if ($allQuestionQuery->count() < $numberOfRows) {
                    return redirect()->back()->with('lackingRows', 'Not enough rows found.');
                }

                foreach ($allQuestionQuery as $question) {
                    tmMtfItems::create([
                        'tmID' => $id,
                        'itmID' => $question->itmID,
                    ]);
                }
            }
        } elseif (in_array($test_type, ['essay', 'enumeration', 'matching'])) {

            if ($test_type == 'essay') {
                $allTestQuery = essays::leftJoin('tm_essays', 'tm_essays.essID', '=', 'essays.essID')
                    ->leftJoin('subjects', 'essays.subjectID', '=', 'subjects.subjectID')
                    ->where('essays.user_id', '=', $currentUserId)
                    ->whereNull('tm_essays.tmessID');

                if ($subjectFilter) {
                    $allTestQuery = $allTestQuery->where('subjectName', $subjectFilter);
                }
                $allTestQuery = $allTestQuery->inRandomOrder()->limit($numberOfRows)->select('essays.essID')->get();

                $allTestQuery->each(function ($allTestQuery) {
                    $tags = analyticessaytags::join('analytictags', 'analytictags.tagID', '=', 'analyticessaytags.tagID')
                        ->where('analyticessaytags.essID', $allTestQuery->itmID)
                        ->get();

                    $tagData = [];
                    foreach ($tags as $tag) {
                        $tagData[$tag->tagName] = $tag->similarity;
                    }
                    $allTestQuery->tags = $tagData;
                });

                $shouldFilter = false;
                foreach ($filterLabel as $key => $value) {
                    if ($request->input($key)) {
                        $shouldFilter = true;
                        break;
                    }
                }

                if ($shouldFilter) {
                    $allTestQuery = $allTestQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                        // Apply additional filtering conditions here
                        $labelNumbers = 0;
                        $labelFiltered = 0;
                        foreach ($filterLabel as $key => $value) {
                            if ($request->input($key)) {
                                $labelNumbers += 1;
                                if (isset($item->tags[$value])) {
                                    $labelFiltered += 1;
                                }
                            }
                        }
                        return $labelNumbers == $labelFiltered;
                    });
                }

                if ($allTestQuery->count() < $numberOfRows) {
                    return redirect()->back()->with('lackingRows', 'Not enough rows found.');
                }

                foreach ($allTestQuery as $question) {
                    tmEssay::create([
                        'tmID' => $id,
                        'essID' => $question->essID,
                    ]);
                }
            }

            if ($test_type == 'enumeration') {
                $allTestQuery = ettests::leftJoin('tm_ets', 'tm_ets.etID', '=', 'ettests.etID')
                    ->leftJoin('subjects', 'ettests.subjectID', '=', 'subjects.subjectID')
                    ->where('ettests.user_id', '=', $currentUserId)
                    ->whereNull('tm_ets.tmetID');

                if ($subjectFilter) {
                    $allTestQuery = $allTestQuery->where('subjectName', $subjectFilter);
                }
                $allTestQuery = $allTestQuery->inRandomOrder()->limit($numberOfRows)->select('ettests.etID')->get();

                $allTestQuery->each(function ($allTestQuery) {
                    $tags = analyticettags::join('analytictags', 'analytictags.tagID', '=', 'analyticettags.tagID')
                        ->where('analyticettags.etID', $allTestQuery->itmID)
                        ->get();

                    $tagData = [];
                    foreach ($tags as $tag) {
                        $tagData[$tag->tagName] = $tag->similarity;
                    }
                    $allTestQuery->tags = $tagData;
                });

                $shouldFilter = false;
                foreach ($filterLabel as $key => $value) {
                    if ($request->input($key)) {
                        $shouldFilter = true;
                        break;
                    }
                }

                if ($shouldFilter) {
                    $allTestQuery = $allTestQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                        // Apply additional filtering conditions here
                        $labelNumbers = 0;
                        $labelFiltered = 0;
                        foreach ($filterLabel as $key => $value) {
                            if ($request->input($key)) {
                                $labelNumbers += 1;
                                if (isset($item->tags[$value])) {
                                    $labelFiltered += 1;
                                }
                            }
                        }
                        return $labelNumbers == $labelFiltered;
                    });
                }

                if ($allTestQuery->count() < $numberOfRows) {
                    return redirect()->back()->with('lackingRows', 'Not enough rows found.');
                }

                foreach ($allTestQuery as $question) {
                    tmEt::create([
                        'tmID' => $id,
                        'etID' => $question->etID,
                    ]);
                }
            }
            if ($test_type == 'matching') {
                $allTestQuery = mttests::leftJoin('tm_mts', 'tm_mts.mtID', '=', 'mttests.mtID')
                    ->leftJoin('subjects', 'mttests.subjectID', '=', 'subjects.subjectID')
                    ->where('mttests.user_id', '=', $currentUserId)
                    ->whereNull('tm_mts.tmmtID');

                if ($subjectFilter) {
                    $allTestQuery = $allTestQuery->where('subjectName', $subjectFilter);
                }
                $allTestQuery = $allTestQuery->inRandomOrder()->limit($numberOfRows)->select('mttests.mtID')->get();

                $allTestQuery->each(function ($allTestQuery) {
                    $tags = analyticmttags::join('analytictags', 'analytictags.tagID', '=', 'analyticmttags.tagID')
                        ->where('analyticmttags.mtID', $allTestQuery->mtID)
                        ->get();

                    $tagData = [];
                    foreach ($tags as $tag) {
                        $tagData[$tag->tagName] = $tag->similarity;
                    }
                    $allTestQuery->tags = $tagData;
                });

                $shouldFilter = false;
                foreach ($filterLabel as $key => $value) {
                    if ($request->input($key)) {
                        $shouldFilter = true;
                        break;
                    }
                }

                if ($shouldFilter) {
                    $allTestQuery = $allTestQuery->filter(function ($item) use ($test_type, $filterLabel, $request) {
                        // Apply additional filtering conditions here
                        $labelNumbers = 0;
                        $labelFiltered = 0;
                        foreach ($filterLabel as $key => $value) {
                            if ($request->input($key)) {
                                $labelNumbers += 1;
                                if (isset($item->tags[$value])) {
                                    $labelFiltered += 1;
                                }
                            }
                        }
                        return $labelNumbers == $labelFiltered;
                    });
                }

                if ($allTestQuery->count() < $numberOfRows) {
                    return redirect()->back()->with('lackingRows', 'Not enough rows found.');
                }
                foreach ($allTestQuery as $question) {
                    tmMt::create([
                        'tmID' => $id,
                        'mtID' => $question->mtID,
                    ]);
                }
            }
        } else {
            abort(404);
        }
        $this->update_score($id);
        return redirect('/test/' . $id);
    }
    public function destroy_question(string $test_type, string $test_id, string $test_makerID)
    {
        $test = tmtests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        if ($test_type == "essay") {
            tmEssay::where('tmessID', $test_makerID)->delete();
        }
        if ($test_type == "matching") {
            tmMt::where('tmmtID', $test_makerID)->delete();
        }
        if ($test_type == "enumeration") {
            tmEt::where('tmetID', $test_makerID)->delete();
        }
        if ($test_type == "mcq") {
            tmQuizItems::where('tmquizID', $test_makerID)->delete();
        }
        if ($test_type == "tf") {
            tmTfItems::where('tmtfID', $test_makerID)->delete();
        }
        if ($test_type == "mtf") {
            tmMtfItems::where('tmmtfID', $test_makerID)->delete();
        }
        $this->update_score($test_id);
        return back();
    }

    public function update_score(string $id)
    {
        $test_types = [
            [
                'table' => essays::class,
                'table_id' => 'essays.essID',
                'total_score' => 'essScoreTotal',
                'tm_table' => 'tm_essays',
                'tm_toTable_id' => 'tm_essays.essID'
            ], [
                'table' => ettests::class,
                'table_id' => 'ettests.etID',
                'total_score' => 'etTotal',
                'tm_table' => 'tm_ets',
                'tm_toTable_id' => 'tm_ets.etID'
            ], [
                'table' => mttests::class,
                'table_id' => 'mttests.mtID',
                'total_score' => 'mtTotal',
                'tm_table' => 'tm_mts',
                'tm_toTable_id' => 'tm_mts.mtID'
            ], [
                'table' => quizitems::class,
                'table_id' => 'quizitems.itmID',
                'total_score' => 'itmPoints',
                'tm_table' => 'tm_quiz_items',
                'tm_toTable_id' => 'tm_quiz_items.itmID'
            ], [
                'table' => tfitems::class,
                'table_id' => 'tfitems.itmID',
                'total_score' => 'itmPoints',
                'tm_table' => 'tm_tf_items',
                'tm_toTable_id' => 'tm_tf_items.itmID'
            ], [
                'table' => mtfitems::class,
                'table_id' => 'mtfitems.itmID',
                'total_score' => 'itmPointsTotal',
                'tm_table' => 'tm_mtf_items',
                'tm_toTable_id' => 'tm_mtf_items.itmID'
            ]
        ];
        $total_score = 0;
        foreach ($test_types as $test_type) {
            $scores = $test_type['table']::join($test_type['tm_table'], $test_type['table_id'], '=', $test_type['tm_toTable_id'])
                ->where($test_type['tm_table'] . '.tmID', $id)
                ->select($test_type['total_score'])
                ->get();
            foreach ($scores as $score) {
                $total_score += $score->{$test_type['total_score']};
            }
        }
        tmtests::find($id)->update([
            'tmTotal' => $total_score,
        ]);
    }
}
