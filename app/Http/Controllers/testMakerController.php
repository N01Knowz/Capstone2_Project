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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class testMakerController extends Controller
{
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

        $tests = $testsQuery->orderBy('tmID', 'desc')
            ->get();

        return view('testbank.test_maker.index', [
            'tests' => $tests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('testbank.test_maker.add');
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
            'tmIsPublic' => $request->has('share'),
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
        $isShared = $test->test_visible;


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id() && !$isShared) {
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

        $uniqueSubjects = subjects::where('user_id', $currentUserId)
            ->where('subjectName', '!=', 'No Subject') // Exclude rows with 'No Subject'
            ->distinct('subjectName')
            ->select('subjectName')
            ->get();

        // dd($uniqueSubjects);

        $essayQuestions = tmEssay::join('essays', 'essays.essID', 'tm_essays.essID')
            ->get();

        $matchingQuestions = tmMt::join('mttests', 'mttests.mtID', 'tm_mts.mtID')
            ->get();

        $enumerationQuestions = tmEt::join('etitems', 'etitems.etID', 'tm_ets.etID')
            ->get();

        $quizQuestions = tmQuizItems::join('quizitems', 'quizitems.itmID', 'tm_quiz_items.itmID')
            ->get();

        $tfQuestions = tmTfItems::join('tfitems', 'tfitems.itmID', 'tm_tf_items.itmID')
            ->get();

        $mtfQuestions = tmMtfItems::join('mtfitems', 'mtfitems.itmID', 'tm_mtf_items.itmID')
            ->get();

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
        return view('testbank.test_maker.edit', [
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
            'tmIsPublic' => $request->has('share'),
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
        if ($subjectFilter) {
            // dd($subjectFilter);
        }


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
                ->where('essays.user_id', '=', $currentUserId)
                ->select('essays.*', 'subjects.subjectName')
                ->addSelect(DB::raw('CASE WHEN tm_essays.tmessID IS NOT NULL THEN 1 ELSE NULL END AS in_test_makers'));

            if ($searchTitle) {
                $allTestQuery = $allTestQuery->where('essTitle', 'LIKE', "%$searchTitle%");
            }
            if ($subjectFilter) {
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
                ->where('mttests.user_id', '=', $currentUserId)
                ->select('mttests.*', 'subjects.subjectName')
                ->addSelect(DB::raw('CASE WHEN tm_mts.tmmtID IS NOT NULL THEN 1 ELSE NULL END AS in_test_makers'));

            if ($searchTitle) {
                $allTestQuery = $allTestQuery->where('mtTitle', 'LIKE', "%$searchTitle%");
            }
            if ($subjectFilter) {
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
                ->where('ettests.user_id', '=', $currentUserId)
                ->select('ettests.*', 'subjects.subjectName')
                ->addSelect(DB::raw('CASE WHEN tm_ets.tmetID IS NOT NULL THEN 1 ELSE NULL END AS in_test_makers'));

            if ($searchTitle) {
                $allTestQuery = $allTestQuery->where('etTitle', 'LIKE', "%$searchTitle%");
            }
            if ($subjectFilter) {
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
        // dd($allQuestionQuery);


        $uniqueSubjects = subjects::where('user_id', $currentUserId)
            ->where('subjectName', '!=', 'No Subject') // Exclude rows with 'No Subject'
            ->distinct('subjectName')
            ->pluck('subjectName')
            ->toArray();


        return view('testbank.test_maker.add_question', [
            'test' => $test,
            'allTestQuery' => $allTestQuery,
            'allQuestionQuery' => $allQuestionQuery,
            'testType' => ucfirst($test_type),
            'uniqueSubjects' => $uniqueSubjects
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
                    testMaker::create([
                        'tmID' => $id,
                        'question_id' => $checkbox,
                    ]);
                }
            }
        }

        if (in_array($test_type, ['essay', 'enumeration', 'matching'])) {
            $checkboxes = $request->input('test_checkbox_add', []);
            if (!empty($checkboxes)) {
                foreach ($checkboxes as $checkbox) {
                    testMaker::create([
                        'tmID' => $id,
                        'test_id' => $checkbox,
                    ]);
                }
            }
        }

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
            $allQuestionQuery = questions::from(DB::raw('questions AS q'))
                ->join('testbanks AS t', 't.id', '=', 'q.tmID')
                ->where('t.test_type', '=', $test_type)
                ->where('t.user_id', '=', $currentUserId)
                ->leftJoin('test_makers AS tm', 'q.id', '=', 'tm.question_id')
                ->whereNull('tm.question_id');


            if ($subjectFilter) {
                $allQuestionQuery = $allQuestionQuery->where('test_subject', $subjectFilter);
            }
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $allQuestionQuery = $allQuestionQuery->whereNotNull("$value");
                }
            }
            $allQuestionQuery = $allQuestionQuery->inRandomOrder()->limit($numberOfRows)->select('q.id')->get();
            if ($allQuestionQuery->count() < $numberOfRows) {
                return redirect()->back()->with('lackingRows', 'Not enough rows found.');
            }

            foreach ($allQuestionQuery as $question) {
                testMaker::create([
                    'tmID' => $id,
                    'question_id' => $question->id,
                ]);
            }
            return redirect('/test/' . $id);
        } elseif (in_array($test_type, ['essay', 'enumeration', 'matching'])) {
            $allTestQuery = tmtests::where('testbanks.test_type', '=', $test_type)
                ->where('testbanks.user_id', '=', $currentUserId)
                ->leftJoin('test_makers AS tm', 'testbanks.id', '=', 'tm.test_id')
                ->whereNull('tm.test_id');

            if ($subjectFilter) {
                $allTestQuery = $allTestQuery->where('test_subject', $subjectFilter);
            }
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $allTestQuery = $allTestQuery->whereNotNull("$value");
                }
            }
            $allTestQuery = $allTestQuery->inRandomOrder()->limit($numberOfRows)->select('testbanks.id')->get();

            if ($allTestQuery->count() < $numberOfRows) {
                return redirect()->back()->with('lackingRows', 'Not enough rows found.');
            }

            foreach ($allTestQuery as $test) {
                testMaker::create([
                    'tmID' => $id,
                    'test_id' => $test->id,
                ]);
            }

            return redirect('/test/' . $id);
        } else {
            abort(404);
        }
    }
    public function destroy_question(string $test_id, string $test_makerID)
    {
        $test = tmtests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        testMaker::where('id', $test_makerID)->delete();

        return back();
    }
}
