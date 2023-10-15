<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mttests;
use App\Models\mtitems;
use App\Models\subjects;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
    public function index(Request $request)
    {
        $search = $request->input('search');

        $currentUserId = Auth::user()->id;
        $testsQuery = mttests::leftJoin('subjects', 'mttests.subjectID', '=', 'subjects.subjectID')
        ->where('mttests.user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('mtTitle', 'LIKE', "%$search%")
                    ->orWhere('mtDescription', 'LIKE', "%$search%");
            });
        }

        $tests = $testsQuery->orderBy('mtID', 'desc')
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
        $currentUserId = Auth::user()->id;
        $uniqueSubjects = subjects::where('user_id', $currentUserId)
            ->where('subjectName', '!=', 'No Subject') // Exclude rows with 'No Subject'
            ->distinct('subjectName')
            ->pluck('subjectName')
            ->toArray();
        return view('testbank.matching.matching_add', ['uniqueSubjects' => $uniqueSubjects]);
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

        $subjectID = null;

        if ($request->input('subject')) {
            $subject = subjects::where('subjectName', $request->input('subject'))
                ->where('user_id', Auth::id())
                ->first();
            if ($subject) {
                $subjectID = $subject->subjectID;
            } else {
                $createSubject = subjects::create([
                    'subjectName' => $request->input('subject'),
                    'user_id' => Auth::id(),
                ]);
                $subjectID = $createSubject->subjectID;
            }
        }

        $testbank = mttests::create([
            'user_id' => Auth::id(),
            'mtTitle' => $request->input('title'),
            'mtDescription' => $request->input('description') ? $request->input('description') : '',
            'subjectID' =>  $subjectID,
            'mtIsPublic' => $request->has('share'),
        ]);

        for ($i = 1; $i <= intval($request->input('numChoicesInput')); $i++) {
            $question = mtitems::create([
                'mtID' => $testbank->mtID,
                'itmAnswer' => $request->input('item_answer_' . $i),
                'itmQuestion' => $request->input('item_text_' . $i),
                'itmPoints' => $request->input('item_point_' . $i),
            ]);
        }

        $questions = mtitems::where("mtID", "=", $testbank->mtID)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $testbank->update([
            'mtTotal' => $total_points,
        ]);

        return redirect('/matching');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = mttests::find($id);
        $isShared = $test->mtIsPublic;
        // dd($test->user_id != Auth::id() && !$isShared);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id() && !$isShared) {
            abort(403); // User does not own the test
        }
        $questions = mtitems::where('mtID', '=', $id)
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
        $test = mttests::find($id);


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
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = mttests::find($id);
        if (is_null($testbank)) {
            abort(404); // User does not own the test
        }
        if ($testbank->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $testbank->update([
            'mtTitle' => $request->input('title'),
            'mtDescription' => $request->input('description') ? $request->input('description') : '',
            'mtIsPublic' => $request->has('share'),
        ]);

        return redirect('/matching');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = mttests::find($id);

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        mtitems::where('mtID', $id)->delete();
        $test->delete();

        return back();
    }


    public function add_question_index(string $test_id)
    {
        $test = mttests::find($test_id);


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

        $test = mttests::find($test_id);
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }


        for ($i = 1; $i <= intval($request->input('numChoicesInput')); $i++) {
            $question = mtitems::create([
                'mtID' => $test_id,
                'itmAnswer' => $request->input('item_answer_' . $i),
                'itmQuestion' => $request->input('item_text_' . $i),
                'itmPoints' => $request->input('item_point_' . $i) ? $request->input('item_point_' . $i) : 0,
            ]);
        }

        $questions = mtitems::where("mtID", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'mtTotal' => $total_points,
        ]);

        return redirect('/matching/' . $test_id);

        // $test = mttests::find($test_id);
        // return view('testbank/matching/matching_add_question', [
        //     'test' => $test,
        // ]);
    }

    public function add_question_destroy(string $id)
    {
        $question = mtitems::find($id);
        if (is_null($question)) {
            abort(404); // User does not own the test
        }
        $test = mttests::find($question->mtID);
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $question->delete();

        $questions = mtitems::where("mtID", "=", $test->mtID)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'mtTotal' => $total_points,
        ]);

        return back();
    }

    public function add_question_edit(string $test_id, string $question_id)
    {
        $test = mttests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = mtitems::find($question_id);

        return view('testbank.matching.matching_edit_question', [
            'test' => $test,
            'question' => $question,
        ]);

        return back();
    }

    public function add_question_update(Request $request, string $test_id, string $question_id)
    {
        $input = $request->all();

        $test = mttests::find($test_id);

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

        $question = mtitems::find($question_id);

        $question->update([
            'itmAnswer' => $request->input('item_answer'),
            'itmQuestion' => $request->input('item_text'),
            'itmPoints' => $request->input('item_point'),
        ]);

        $questions = mtitems::where("mtID", "=", $test->mtID)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'mtTotal' => $total_points,
        ]);

        return redirect('/matching/' . $test_id);
    }
}
