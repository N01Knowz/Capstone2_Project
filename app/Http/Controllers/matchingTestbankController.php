<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\questions;
use DOMDocument;
use Illuminate\Support\Facades\File;

class matchingTestbankController extends Controller
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
        $tests = testbank::where('test_type', '=', 'matching')
            ->where('user_id', '=', $currentUserId)
            ->get();
        return view('testbank.matching.matching', [
            'tests' => $tests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('testbank.matching.matching_add');
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
            'item_text_1' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = testbank::create([
            'user_id' => $request->input('id'),
            'test_type' => 'matching',
            'test_title' => $request->input('title'),
            'test_question' => '',
            'test_instruction' => $request->input('instruction'),
            'test_image' => '',
            'test_total_points' => 0,
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ]);

        for ($i = 1; $i <= intval($request->input('numChoicesInput')); $i++) {
            $question = questions::create([
                'testbank_id' => $testbank->id,
                'question_active' => 1,
                'item_question' => $request->input('item_answer_' . $i),
                'choices_number' => 1,
                'question_answer' => 1,
                'option_1' => $request->input('item_text_' . $i),
                'question_point' => $request->input('item_point_' . $i),
            ]);
        }

        return redirect('/matching');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = testbank::find($id);
        $questions = questions::where('testbank_id', '=', $id)
            ->get();
        return view('testbank.matching.matching_test-description', [
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
        return view('testbank.matching.matching_edit', [
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

        $testbank->update([
            'test_title' => $request->input('title'),
            'test_instruction' => $request->input('instruction'),
            'test_visible' => $request->has('share'),
        ]);

        return redirect('/matching');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = testbank::find($id);

        $test->update([
            'test_active' => '0'
        ]);

        return back();
    }
}
