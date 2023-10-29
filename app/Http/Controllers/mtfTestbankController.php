<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mtftests;
use App\Models\mtfitems;
use App\Models\subjects;
use App\Models\analyticmtfitemtags;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DOMDocument;
use Illuminate\Support\Facades\File;

class mtfTestbankController extends Controller
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
        $testsQuery = mtftests::leftJoin('subjects', 'mtftests.subjectID', '=', 'subjects.subjectID')
            ->where('mtftests.user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('test_title', 'LIKE', "%$search%")
                    ->orWhere('test_instruction', 'LIKE', "%$search%");
            });
        }

        $tests = $testsQuery->orderBy('mtfID', 'desc')
            ->get();


        $testPage = 'mtf';
        return view('testbank.mtf.mtf', [
            'tests' => $tests,
            'testPage' => $testPage,
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

        $testPage = 'mtf';
        return view('testbank.mtf.mtf_add', [
            'uniqueSubjects' => $uniqueSubjects,
            'testPage' => $testPage,
        ]);
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

        $subjectID = null;

        if ($request->input('subject')) {
            $subjectName = strtolower($request->input('subject'));

            $subject = subjects::whereRaw('LOWER(subjectName) = ?', [$subjectName])
                ->where('user_id', Auth::id())
                ->first();
            if ($subject) {
                $subjectID = $subject->subjectID;
            } else {
                $createSubject = subjects::create([
                    'subjectName' => ucfirst($request->input('subject')),
                    'user_id' => Auth::id(),
                ]);
                $subjectID = $createSubject->subjectID;
            }
        }

        mtftests::create([
            'user_id' => Auth::id(),
            'mtfTitle' => $request->input('title'),
            'mtfDescription' => $request->input('description'),
            'subjectID' => $subjectID,
            'mtfIsPublic' => $request->has('share'),
        ]);

        return redirect('/mtf');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = mtftests::find($id);
        $isShared = $test->test_visible;


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id() && !$isShared) {
            abort(403); // User does not own the test
        }
        $questions = mtfitems::where('mtfID', '=', $id)
            ->get();

        $questions->each(function ($questions) {
            $tags = analyticmtfitemtags::join('analytictags', 'analytictags.tagID', '=', 'analyticmtfitemtags.tagID')
                ->where('analyticmtfitemtags.itmID', $questions->itmID)
                ->get();
            // dd($questions->itmID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }


            $questions->tags = $tagData;
        });


        $testPage = 'mtf';
        return view('testbank.mtf.mtf_test-description', [
            'test' => $test,
            'questions' => $questions,
            'testPage' => $testPage,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $test = mtftests::leftJoin('subjects', 'mtftests.subjectID', '=', 'subjects.subjectID')->where('mtfID', $id)->select('mtftests.*', 'subjectName')->first();

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $uniqueSubjects = subjects::where('user_id', Auth::id())
            ->where('subjectName', '!=', 'No Subject') // Exclude rows with 'No Subject'
            ->distinct('subjectName')
            ->pluck('subjectName')
            ->toArray();


        $testPage = 'mtf';
        return view('testbank.mtf.mtf_edit', [
            'uniqueSubjects' => $uniqueSubjects,
            'test' => $test,
            'testPage' => $testPage,
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

        $testbank = mtftests::find($id);
        if (is_null($testbank)) {
            abort(404); // User does not own the test
        }
        if ($testbank->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $subjectID = null;

        if ($request->input('subject')) {
            $subjectName = strtolower($request->input('subject'));

            $subject = subjects::whereRaw('LOWER(subjectName) = ?', [$subjectName])
                ->where('user_id', Auth::id())
                ->first();
            if ($subject) {
                $subjectID = $subject->subjectID;
            } else {
                $createSubject = subjects::create([
                    'subjectName' => ucfirst($request->input('subject')),
                    'user_id' => Auth::id(),
                ]);
                $subjectID = $createSubject->subjectID;
            }
        }

        $testbank->update([
            'mtfTitle' => $request->input('title'),
            'mtfDescription' => $request->input('description'),
            'mtfIsPublic' => $request->has('share'),
            'subjectID' => $subjectID,
        ]);

        return redirect('/mtf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = mtftests::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }


        $questions = mtfitems::where('mtfID', $id)->get();
        foreach ($questions as $question) {

            $questionImage = $question->question_image;
            $imagePath = public_path('user_upload_images/' . $questionImage);
            if (File::exists($imagePath)) {
                // Delete the image file
                File::delete($imagePath);

                // Optionally, you can also remove the image filename from the database or update the question record here
            }
            $question->delete();
        }

        $test->delete();

        return back();
    }
    public function add_question_index(string $test_id)
    {
        $test = mtftests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $testPage = 'mtf';
        return view('testbank/mtf/mtf_add_question', [
            'test' => $test,
            'testPage' => $testPage,
        ]);
    }

    public function add_question_show(string $test_id, string $question_id)
    {
        $test = mtftests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = mtfitems::find($question_id);


        $testPage = 'mtf';
        return view('testbank.mtf.mtf_question_description', [
            'test' => $test,
            'question' => $question,
            'testPage' => $testPage,
        ]);

        return back();
    }

    public function add_question_store(Request $request, string $test_id)
    {
        $input = $request->all();
        $test = mtftests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'question_point' => 'required',
            'explanation_point' => 'required',
            'imageInput' => 'image|mimes:jpeg,png,jpg,gif', // Adjust the file types and size as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $randomName = "";
        if ($request->hasFile('imageInput')) {
            do {
                $randomName = 'mtf_' . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'qst.' . $request->file('imageInput')->getClientOriginalExtension();
                $existingImage = mtfitems::where('question_image', $randomName)->first();
            } while ($existingImage);
            $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
        }


        $question = mtfitems::create([
            'mtfID' => $test_id,
            'itmQuestion' => $request->input('item_question'),
            'itmImage' => $request->hasFile('imageInput') ? $randomName : null,
            'choices_number' => 2,
            'itmAnswer' => $request->input('question_answer'),
            'itmPoints1' => $request->input('question_point'),
            'itmPoints2' => $request->input('explanation_point'),
            'itmPointsTotal' => $request->input('total_points'),
        ]);

        for ($i = 1; $i <= 2; $i++) {
            $option = $request->input('option_' . $i);
            $question->update([
                'itmOption' . $i => $option,
            ]);
        }

        $questions = mtfitems::where("mtfID", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_question_points = $question->itmPoints1 + $question->itmPoints2;
            $total_points += $total_question_points;
        }

        $test->update([
            'mtfTotal' => $total_points,
        ]);

        return redirect('/mtf/' . $test_id);
    }

    public function add_question_destroy(string $id)
    {
        $question = mtfitems::find($id);
        if (is_null($question)) {
            abort(404); // User does not own the test
        }
        $test = mtftests::find($question->testbank_id);
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $questionImage = $question->itmImage;
        $imagePath = public_path('user_upload_images/' . $questionImage);
        if (File::exists($imagePath)) {
            // Delete the image file
            File::delete($imagePath);

            // Optionally, you can also remove the image filename from the database or update the question record here
        }

        $question->delete();

        $questions = mtfitems::where("testbank_id", "=", $test->id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_question_points = $question->itmPoints1 + $question->itmPoints2;
            $total_points += $total_question_points;
        }

        $test->update([
            'mtfTotal' => $total_points,
        ]);

        return back();
    }

    public function add_question_edit(string $test_id, string $question_id)
    {
        $test = mtftests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = mtfitems::find($question_id);


        $testPage = 'mtf';
        return view('testbank.mtf.mtf_edit_question', [
            'test' => $test,
            'question' => $question,
            'testPage' => $testPage,
        ]);
    }

    public function add_question_update(Request $request, string $test_id, string $question_id)
    {
        $input = $request->all();
        $test = mtftests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'option_1' => 'required',
            'question_point' => 'required',
            'explanation_point' => 'required',
            'imageInput' => 'image|mimes:jpeg,png,jpg,gif', // Adjust the file types and size as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $question = mtfitems::find($question_id);

        $randomName = "";
        if ($request->input('imageChanged')) {
            $questionImage = $question->question_image;
            $imagePath = public_path('user_upload_images/' . $questionImage);
            if (File::exists($imagePath)) {
                // Delete the image file
                File::delete($imagePath);

                // Optionally, you can also remove the image filename from the database or update the question record here
            }
            if ($request->hasFile('imageInput')) {
                do {
                    $randomName = 'mtf_' . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'qst.' . $request->file('imageInput')->getClientOriginalExtension();
                    $existingImage = mtfitems::where('itmImage', $randomName)->first();
                } while ($existingImage);
                $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
            }
        }

        $dataToUpdate = [
            'itmQuestion' => $request->input('item_question'),
            'choices_number' => 2,
            'itmAnswer' => $request->input('question_answer'),
            'itmPoints1' => $request->input('question_point'),
            'itmPoints2' => $request->input('explanation_point'),
            'itmPointsTotal' => $request->input('explanation_point') + $request->input('question_point'),
        ];


        if ($request->input('imageChanged')) {
            $dataToUpdate['itmImage'] = $request->hasFile('imageInput') ? $randomName : null;
        }

        $question->update($dataToUpdate);

        $questions = mtfitems::where("mtfID", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_question_points = $question->itmPoints1 + $question->itmPoints2;
            $total_points += $total_question_points;
        }

        $test->update([
            'mtfTotal' => $total_points,
        ]);

        return redirect('/mtf/' . $test_id);
    }
}
