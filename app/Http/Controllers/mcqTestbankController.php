<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use App\Models\questions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class mcqTestbankController extends Controller
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
        $tests = testbank::where('test_type', '=', 'mcq')
        ->where('user_id', '=', $currentUserId)
        ->get();
        return view('testbank.mcq.mcq', [
            'tests' => $tests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('testbank.mcq.mcq_add');
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
            'test_type' => 'mcq',
            'test_title' => $request->input('title'),
            'test_question' => '',
            'test_instruction' => $request->input('instruction'),
            'test_image' => '',
            'test_total_points' => 0,
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ]);

        return redirect('/mcq');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = testbank::find($id);
        $questions = questions::where('testbank_id', '=', $id)
        ->get();
        return view('testbank.mcq.mcq_test-description', [
            'test' => $test,
            'questions' => $questions,
        ]);
        // $test = testbank::find($id);
        // return view('testbank.mcq.mcq_test-description', [
        //     'test' => $test,
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $test = testbank::find($id);
        return view('testbank.mcq.mcq_edit', [
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

        return redirect('/mcq');
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

    public function add_question_index(string $test_id)
    { 
        $test = testbank::find($test_id);
        return view('testbank/mcq/mcq_add_question', [
            'test' => $test,
        ]);
    }

    public function add_question_store(Request $request, string $test_id)
    { 
        $input = $request->all();

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'number_of_choices' => 'required|numeric|gte:1|lt:11',
            'option_1' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        
        $question = questions::create([
            'testbank_id' => $test_id,
            'question_active' => 1,
            'item_question' => $request->input('item_question'),
            'question_image' => $request->input('question_image', null),
            'choices_number' => $request->input('number_of_choices'),
            'question_answer' => $request->input('question_answer'),
            'question_point' => $request->input('question_point'),
        ]);
        
        for($i = 1; $i <= intval($request->input('number_of_choices')); $i++) {
            $question->update([
                'option_' . $i => $request->input('option_'. $i),
            ]);
        }

        return redirect('/mcq/'. $test_id);

        // $test = testbank::find($test_id);
        // return view('testbank/mcq/mcq_add_question', [
        //     'test' => $test,
        // ]);
    }

    public function add_question_destroy(string $id)
    {
        $question = questions::find($id);

        $question->update([
            'question_active' => '0'
        ]);
        
        return back();
    }

}
