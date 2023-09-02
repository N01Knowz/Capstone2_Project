<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use App\Models\questions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class essayTestbankController extends Controller
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
        $tests = testbank::where('test_type', '=', 'essay')
            ->where('user_id', '=', $currentUserId)
            ->get();
        return view('testbank.essay.essay', [
            'tests' => $tests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('testbank.essay.essay_add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'question' => 'required',
            'criteria_1' => 'required',
            'criteria_point_1' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = testbank::create([
            'user_id' => Auth::id(),
            'test_type' => 'essay',
            'test_title' => $request->input('title'),
            'test_question' => $request->input('question'),
            'test_instruction' => $request->input('instruction'),
            'test_image' => $request->input('image'),
            'test_total_points' => $request->input('total_points'),
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ]);

        $question = questions::create([
            'testbank_id' => $testbank->id,
            'question_active' => 1,
            'item_question' => $request->input('criteria_1'),
            'question_image' => $request->input('question_image', null),
            'choices_number' => 2,
            'question_answer' => 0,
            'question_point' => $request->input('criteria_point_1'),
            'option_1' => $request->input('criteria_2'),
            'option_2' => $request->input('criteria_2') ? $request->input('criteria_point_2') : 0,
            'option_3' => $request->input('criteria_3'),
            'option_4' => $request->input('criteria_3') ? $request->input('criteria_point_3') : 0,
            'option_5' => $request->input('criteria_4'),
            'option_6' => $request->input('criteria_4') ? $request->input('criteria_point_4') : 0,
            'option_7' => $request->input('criteria_5'),
            'option_8' => $request->input('criteria_5') ? $request->input('criteria_point_5') : 0,
        ]);
        

        return redirect('/essay');
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
        $question = questions::where('testbank_id', '=', $id)->first();
        return view('testbank.essay.essay_test-description', [
            'test' => $test,
            'question' => $question,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $test = testbank::find($id);
        $question = questions::where('testbank_id', '=', $id)->first();
        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        return view('testbank.essay.essay_edit', [
            'test' => $test,
            'question' => $question,
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
            'question' => 'required',
            'criteria_1' => 'required',
            'criteria_point_1' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = testbank::find($id);
        $testbank->update([
            'user_id' => $request->input('id'),
            'test_type' => 'essay',
            'test_title' => $request->input('title'),
            'test_question' => $request->input('question'),
            'test_instruction' => $request->input('instruction'),
            'test_image' => $request->input('image'),
            'test_total_points' => $request->input('total_points'),
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ]);


        $question = questions::where('testbank_id', '=', $id)->first();

        $question->update([
            'question_active' => 1,
            'item_question' => $request->input('criteria_1'),
            'question_image' => $request->input('question_image', null),
            'choices_number' => 2,
            'question_answer' => 0,
            'question_point' => $request->input('criteria_point_1'),
            'option_1' => $request->input('criteria_2'),
            'option_2' => $request->input('criteria_2') ? $request->input('criteria_point_2') : 0,
            'option_3' => $request->input('criteria_3'),
            'option_4' => $request->input('criteria_3') ? $request->input('criteria_point_3') : 0,
            'option_5' => $request->input('criteria_4'),
            'option_6' => $request->input('criteria_4') ? $request->input('criteria_point_4') : 0,
            'option_7' => $request->input('criteria_5'),
            'option_8' => $request->input('criteria_5') ? $request->input('criteria_point_5') : 0,
        ]);

        return redirect('/essay');
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
}
