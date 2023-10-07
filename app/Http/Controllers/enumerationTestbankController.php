<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\questions;
use Illuminate\Support\Facades\File;

class enumerationTestbankController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $currentUserId = Auth::user()->id;
        $testsQuery = testbank::where('test_type', '=', 'enumeration')
            ->where('user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('test_title', 'LIKE', "%$search%")
                    ->orWhere('test_instruction', 'LIKE', "%$search%");
            });
        }

        $tests = $testsQuery->orderBy('id', 'desc')
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
        $currentUserId = Auth::user()->id;
        $uniqueSubjects = testbank::where('user_id', $currentUserId)
            ->where('test_subject', '!=', 'No Subject') // Exclude rows with 'No Subject'
            ->distinct('test_subject')
            ->pluck('test_subject')
            ->toArray();
        return view('testbank.enumeration.enumeration_add', ['uniqueSubjects' => $uniqueSubjects]);
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
            'instruction' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = testbank::create([
            'user_id' => Auth::id(),
            'test_type' => 'enumeration',
            'test_title' => $request->input('title'),
            'test_question' => $request->input('question'),
            'test_instruction' => $request->input('instruction'),
            'test_subject' => $request->input('subject') ? $request->input('subject') : "No Subject",
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
            'question' => 'required',
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
            'test_question' => $request->input('question'),
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
        $testImage = $test->test_image;
        $imagePath = public_path('user_upload_images/' . $testImage);
        if (File::exists($imagePath)) {
            // Delete the image file
            File::delete($imagePath);
            // Optionally, you can also remove the image filename from the database or update the question record here
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

        questions::create([
            'testbank_id' => $test_id,
            'question_active' => 1,
            'item_question' => $request->input('answer_text'),
            'question_image' => null,
            'choices_number' => 1,
            'question_answer' => 1,
            'question_point' => 1,
            'option_1' => $request->has('case_sensitive_text') ? "1" : "0",
        ]);


        $questions = questions::where("testbank_id", "=", $test->id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->question_point;
        }

        $test->update([
            'test_total_points' => $total_points,
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

        $questions = questions::where("testbank_id", "=", $test->id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->question_point;
        }

        $test->update([
            'test_total_points' => $total_points,
        ]);

        return back();
    }
}
