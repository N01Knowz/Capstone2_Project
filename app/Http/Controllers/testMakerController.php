<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use App\Models\questions;
use App\Models\testMaker;
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
        $testsQuery = testbank::where('test_type', '=', 'testMaker')
            ->where('user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('test_title', 'LIKE', "%$search%")
                    ->orWhere('test_instruction', 'LIKE', "%$search%");
            });
        }

        $tests = $testsQuery->orderBy('id', 'desc')
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
            'instruction' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = testbank::create([
            'user_id' => Auth::id(),
            'test_type' => 'testMaker',
            'test_title' => $request->input('title'),
            'test_question' => '',
            'test_instruction' => $request->input('instruction'),
            'test_image' => '',
            'test_total_points' => 0,
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ]);

        return redirect('/test');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = testbank::find($id);
        
        $currentUserId = Auth::user()->id;


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $questions = questions::where('testbank_id', '=', $id)
            ->get();

        $allTestQuery = testMaker::from(DB::raw('test_makers AS tm'))
        ->join('testbanks AS t', 't.id', '=', 'tm.test_id')
        ->join('questions AS q', 'q.testbank_id', '=', 'tm.test_id')
        ->where('user_id', $currentUserId)
        ->where('tm.testbank_id', $id)
        ->get();

        return view('testbank.test_maker.description', [
            'test' => $test,
            'questions' => $questions,
            'allTestQuery' => $allTestQuery,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $test = testbank::find($id);


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
            'instruction' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = testbank::find($id);
        if (is_null($testbank)) {
            abort(404); // User does not own the test
        }
        if ($testbank->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $testbank->update([
            'test_title' => $request->input('title'),
            'test_instruction' => $request->input('instruction'),
            'test_visible' => $request->has('share'),
        ]);

        return redirect('/test');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = testbank::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        
        testMaker::where('testbank_id', $id)->delete();
        $test->delete();

        return back();
    }

    public function add_test_index(Request $request, string $id, string $test_type)
    {
        $test = testbank::find($id);
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

        $allTestQuery = testbank::where('testbanks.test_type', '=', $test_type)
            ->where('testbanks.user_id', '=', $currentUserId);
        if (in_array($test_type, ['essay', 'matching', 'enumeration'])) {
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $allTestQuery = $allTestQuery->whereNotNull($value);
                }
            }
        }

        if ($searchTitle) {
            $allTestQuery = $allTestQuery->where('test_title', 'LIKE', "%$searchTitle%");
        }
        if ($subjectFilter) {
            $allTestQuery = $allTestQuery->where('test_subject', $subjectFilter);
        }

        $allTestQuery = $allTestQuery->orderBy('testbanks.id', 'desc')
            ->get();


        // dd($allTestQuery);

        $allQuestionQuery = questions::from(DB::raw('questions AS q'))
            ->join('testbanks AS t', 't.id', '=', 'q.testbank_id')
            ->where('t.test_type', '=', $test_type)
            ->where('t.user_id', '=', $currentUserId);

        if (in_array($test_type, ['mcq', 'tf', 'mtf'])) {
            foreach ($filterLabel as $key => $value) {
                if ($request->input($key)) {
                    $allQuestionQuery = $allQuestionQuery->whereNotNull("q.$value");
                }
            }
        }

        if ($searchTitle) {
            $allQuestionQuery = $allQuestionQuery->where('t.test_title', 'LIKE', "%$searchTitle%");
        }

        if ($subjectFilter) {
            $allQuestionQuery = $allQuestionQuery->where('t.test_subject', $subjectFilter ? $subjectFilter : "No Subject");
        }

        $allQuestionQuery = $allQuestionQuery->orderBy('t.id', 'desc')
            ->select('q.*', 't.test_title as test_title_alias', 't.test_subject as test_subject_alias')
            ->get();
        // dd($allQuestionQuery);


        $uniqueSubjects = testbank::where('user_id', $currentUserId)
            ->where('test_subject', '!=', 'No Subject') // Exclude rows with 'No Subject'
            ->distinct('test_subject')
            ->pluck('test_subject')
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
        $test = testbank::find($id);
        $currentUserId = Auth::user()->id;
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
                        'testbank_id' => $id,
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
                        'testbank_id' => $id,
                        'test_id' => $checkbox,
                    ]);
                }
            }
        }

        return redirect('/test/' . $id);
    }
}
