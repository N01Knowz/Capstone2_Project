<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\questions;

class enumerationTestbankController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUserId = Auth::user()->id;
        $tests = testbank::where('test_type', '=', 'enumeration')
            ->where('user_id', '=', $currentUserId)
            ->get();
        return view('testbank.enumeration.enumeration', [
            'tests' => $tests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('testbank.enumeration.enumeration_add');
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
            'user_id' => $request->input('id'),
            'test_type' => 'enumeration',
            'test_title' => $request->input('title'),
            'test_question' => '',
            'test_instruction' => $request->input('instruction'),
            'test_image' => '',
            'test_total_points' => 0,
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ]);

        return redirect('/enumeration');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = testbank::find($id);

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $questions = questions::where('testbank_id', '=', $id)
            ->get();
        return view('testbank.enumeration.enumeration_test-description', [
            'test' => $test,
            'questions' => $questions,
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
        return view('testbank.enumeration.enumeration_edit', [
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

        return redirect('/enumeration');
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
        
        questions::where('testbank_id', $id)->delete();
        $test->delete();

        return back();
    }

    public function add_question_store(Request $request, string $test_id)
    {
        $test = testbank::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $input = $request->all();

        $validator = Validator::make($input, [
            'answer_text' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $question = questions::create([
            'testbank_id' => $test_id,
            'question_active' => 1,
            'item_question' => $request->input('answer_text'),
            'question_image' => null,
            'choices_number' => 1,
            'question_answer' => 1,
            'question_point' => 1,
            'option_1' => $request->has('case_sensitive_text') ? "1" : "0",
        ]);



        return redirect('/enumeration/' . $test_id);
    }

    public function add_question_destroy(string $id)
    {
        $question = questions::find($id);
        if (is_null($question)) {
            abort(404); // User does not own the test
        }
        $test = testbank::find($question->testbank_id);
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question->delete();
        return back();
    }
}
