<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use App\Models\questions;
use DOMDocument;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class tfTestbankController extends Controller
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
        $tests = testbank::where('test_type', '=', 'tf')
        ->where('user_id', '=', $currentUserId)
        ->get();
        return view('testbank.tf.tf', [
            'tests' => $tests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('testbank.tf.tf_add');
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
            'test_type' => 'tf',
            'test_title' => $request->input('title'),
            'test_question' => '',
            'test_instruction' => $request->input('instruction'),
            'test_image' => '',
            'test_total_points' => 0,
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ]);

        return redirect('/tf');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = testbank::find($id);
        
        
        if(is_null($test)){
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $questions = questions::where('testbank_id', '=', $id)
            ->get();
        return view('testbank.tf.tf_test-description', [
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
        if(is_null($test)){
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        return view('testbank.tf.tf_edit', [
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
        if(is_null($testbank)){
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

        return redirect('/tf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = testbank::find($id);
        
        
        if(is_null($test)){
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        questions::where('testbank_id', $id)->delete();
        $test->delete();
        
        return back();
    }
    public function add_question_index(string $test_id)
    {
        $test = testbank::find($test_id);
        
        
        if(is_null($test)){
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        return view('testbank/tf/tf_add_question', [
            'test' => $test,
        ]);
    }

    public function add_question_show(string $test_id, string $question_id)
    {
        $test = testbank::find($test_id);
        
        if(is_null($test)){
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = questions::find($question_id);

        return view('testbank.tf.tf_question_description', [
            'test' => $test,
            'question' => $question,
        ]);

        return back();
    }

    public function add_question_store(Request $request, string $test_id)
    {
        $test = testbank::find($test_id);
        
        
        if(is_null($test)){
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $input = $request->all();

        $validator = Validator::make($input, [
            'item_question' => 'required',
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
            'choices_number' => 2,
            'question_answer' => $request->input('question_answer'),
            'question_point' => $request->input('question_point'),
        ]);

        for ($i = 1; $i <= 2; $i++) {
            $option = $request->input('option_' . $i);
            $question->update([
                'option_' . $i => $option,
            ]);
        }

        $questions = questions::where("testbank_id", "=", $test_id)->get();

        $total_points = 0;

        foreach($questions as $question){
            $total_points += $question->question_point;
        }

        $test->update([
            'test_total_points' => $total_points,
        ]);

        return redirect('/tf/' . $test_id);
    }

    public function add_question_destroy(string $id)
    {
        $question = questions::find($id);
        if(is_null($question)){
            abort(404); // User does not own the test
        }
        $test = testbank::find($question->testbank_id);
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $question->delete();

        $questions = questions::where("testbank_id", "=", $test->id)->get();

        $total_points = 0;

        foreach($questions as $question){
            $total_points += $question->question_point;
        }

        $test->update([
            'test_total_points' => $total_points,
        ]);

        return back();
    }

    public function add_question_edit(string $test_id, string $question_id)
    {
        $test = testbank::find($test_id);
        
        
        if(is_null($test)){
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = questions::find($question_id);

        return view('testbank.tf.tf_edit_question', [
            'test' => $test,
            'question' => $question,
        ]);

        $questions = questions::where("testbank_id", "=", $test->id)->get();

        $total_points = 0;

        foreach($questions as $question){
            $total_points += $question->question_point;
        }

        $test->update([
            'test_total_points' => $total_points,
        ]);

        return back();
    }

    public function add_question_update(Request $request, string $test_id, string $question_id)
    {
        $test = testbank::find($test_id);
        if(is_null($test)){
            abort(404); // User does not own the test
        }
        
        if(is_null($test)){
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $input = $request->all();

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'option_1' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $question = questions::find($question_id);

        $question->update([
            'item_question' => $request->input('item_question'),
            'question_image' => $request->input('question_image', null),
            'choices_number' => 2,
            'question_answer' => $request->input('question_answer'),
            'question_point' => $request->input('question_point'),
        ]);

        $questions = questions::where("testbank_id", "=", $test_id)->get();

        $total_points = 0;

        foreach($questions as $question){
            $total_points += $question->question_point;
        }

        $test->update([
            'test_total_points' => $total_points,
        ]);

        return redirect('/tf/' . $test_id);
    }
}
