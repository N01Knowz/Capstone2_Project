<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\questions;

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
        ]);

        $hasAtLeastOneItemText = false;

        for ($i = 1; $i <= intval($request->input('numChoicesInput')); $i++) {
            if ($request->input('item_text_' . $i)) {
                $hasAtLeastOneItemText = true;
                break;
            }
        }
        
        if (!$hasAtLeastOneItemText) {
            return redirect()->back()->withErrors(['no_item' => 'There should be at least 1 text item'])->withInput();
        }

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


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
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


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
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

        return redirect('/matching');
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


    public function add_question_index(string $test_id)
    {
        $test = testbank::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        return view('testbank/matching/matching_add_question', [
            'test' => $test,
        ]);
    }
    public function add_question_store(Request $request, string $test_id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'numChoicesInput' => 'required|numeric|gte:1|lt:11',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        for ($i = 1; $i <= intval($request->input('numChoicesInput')); $i++) {
            $question = questions::create([
                'testbank_id' => $test_id,
                'question_active' => 1,
                'item_question' => $request->input('item_answer_' . $i),
                'choices_number' => 1,
                'question_answer' => 1,
                'option_1' => $request->input('item_text_' . $i),
                'question_point' => $request->input('item_point_' . $i) ? $request->input('item_point_' . $i) : 0,
            ]);
        }

        return redirect('/matching/' . $test_id);

        // $test = testbank::find($test_id);
        // return view('testbank/matching/matching_add_question', [
        //     'test' => $test,
        // ]);
    }

    public function add_question_destroy(string $id)
    {
        $question = questions::find($id)->delete();
        if(is_null($question)){
            abort(404); // User does not own the test
        }
        $test = testbank::find($question->testbank_id);
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        return back();
    }

    public function add_question_edit(string $test_id, string $question_id)
    {
        $test = testbank::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = questions::find($question_id);

        return view('testbank.matching.matching_edit_question', [
            'test' => $test,
            'question' => $question,
        ]);

        return back();
    }

    public function add_question_update(Request $request, string $test_id, string $question_id)
    {
        $input = $request->all();

        $test = testbank::find($test_id);

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $validator = Validator::make($input, [
            'item_answer' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $question = questions::find($question_id);

        $question->update([
            'item_question' => $request->input('item_answer'),
            'option_1' => $request->input('item_text'),
            'question_point' => $request->input('item_point'),
        ]);

        return redirect('/matching/' . $test_id);
    }
}
