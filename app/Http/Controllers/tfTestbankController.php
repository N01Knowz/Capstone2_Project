<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tftests;
use App\Models\tfitems;
use App\Models\subjects;
use App\Models\analytictfitemtags;
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
    public function index(Request $request)
    {
        $search = $request->input('search');

        $currentUserId = Auth::user()->id;
        $testsQuery = tftests::leftJoin('subjects', 'tftests.subjectID', '=', 'subjects.subjectID')
            ->where('tftests.user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('tfTitle', 'LIKE', "%$search%")
                    ->orWhere('tfDescription', 'LIKE', "%$search%");
            });
        }

        $tests = $testsQuery->orderBy('tfID', 'desc')
            ->get();


        $testPage = 'tf';
        return view('testbank.tf.tf', [
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

        $testPage = 'tf';
        return view('testbank.tf.tf_add', [
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

        tftests::create([
            'user_id' => Auth::id(),
            'tfTitle' => $request->input('title'),
            'tfDescription' => $request->input('description'),
            'subjectID' => $subjectID,
            'tfIsPublic' => $request->has('share'),
        ]);

        return redirect('/tf');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = tftests::find($id);
        $isShared = $test->tfIsPublic;


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id() && !$isShared) {
            abort(403); // User does not own the test
        }
        $questions = tfitems::where('tfID', '=', $id)
            ->get();

        $questions->each(function ($questions) {
            $tags = analytictfitemtags::join('analytictags', 'analytictags.tagID', '=', 'analytictfitemtags.tagID')
                ->where('analytictfitemtags.itmID', $questions->itmID)
                ->get();
            // dd($questions->itmID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }


            $questions->tags = $tagData;
        });


        $testPage = 'tf';
        return view('testbank.tf.tf_test-description', [
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
        $test = tftests::leftJoin('subjects', 'tftests.subjectID', '=', 'subjects.subjectID')->where('tfID', $id)->select('tftests.*', 'subjectName')->first();
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

        $testPage = 'tf';
        return view('testbank.tf.tf_edit', [
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

        $testbank = tftests::find($id);
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
            'tfTitle' => $request->input('title'),
            'tfDescription' => $request->input('description'),
            'tfIsPublic' => $request->has('share'),
            'subjectID' => $subjectID,
        ]);

        return redirect('/tf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = tftests::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $questions = tfitems::where('tfID', $id)->get();
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
        $test = tftests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $testPage = 'tf';
        return view('testbank/tf/tf_add_question', [
            'test' => $test,
            'testPage' => $testPage,
        ]);
    }

    public function add_question_show(string $test_id, string $question_id)
    {
        $test = tftests::find($test_id);

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = tfitems::find($question_id);


        $testPage = 'tf';
        return view('testbank.tf.tf_question_description', [
            'test' => $test,
            'question' => $question,
            'testPage' => $testPage,
        ]);

        return back();
    }

    public function add_question_store(Request $request, string $test_id)
    {
        $test = tftests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $input = $request->all();

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'option_1' => 'required',
            'imageInput' => 'image|mimes:jpeg,png,jpg,gif', // Adjust the file types and size as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $randomName = "";
        if ($request->hasFile('imageInput')) {
            do {
                $randomName = 'tf_' . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'qst.' . $request->file('imageInput')->getClientOriginalExtension();
                $existingImage = tfitems::where('itmImage', $randomName)->first();
            } while ($existingImage);
            $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
        }

        $question = tfitems::create([
            'tfID' => $test_id,
            'itmQuestion' => $request->input('item_question'),
            'itmImage' => $request->hasFile('imageInput') ? $randomName : null,
            'choices_number' => 2,
            'itmAnswer' => $request->input('question_answer'),
            'itmPoints' => $request->input('question_point'),
        ]);

        $questions = tfitems::where("tfID", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'tfTotal' => $total_points,
        ]);

        return redirect('/tf/' . $test_id);
    }

    public function add_question_destroy(string $id)
    {
        $question = tfitems::find($id);
        if (is_null($question)) {
            abort(404); // User does not own the test
        }
        $test = tftests::find($question->tfID);
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

        $questions = tfitems::where("tfID", "=", $test->id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'tfTotal' => $total_points,
        ]);

        return back();
    }

    public function add_question_edit(string $test_id, string $question_id)
    {
        $test = tftests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = tfitems::find($question_id);


        $testPage = 'tf';
        return view('testbank.tf.tf_edit_question', [
            'test' => $test,
            'question' => $question,
            'testPage' => $testPage,
        ]);
    }

    public function add_question_update(Request $request, string $test_id, string $question_id)
    {
        $test = tftests::find($test_id);
        if (is_null($test)) {
            abort(404); // User does not own the test
        }

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $input = $request->all();

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'option_1' => 'required',
            'imageInput' => 'image|mimes:jpeg,png,jpg,gif', // Adjust the file types and size as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $question = tfitems::find($question_id);

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
                    $randomName = 'tf_' . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'qst.' . $request->file('imageInput')->getClientOriginalExtension();
                    $existingImage = tfitems::where('itmImage', $randomName)->first();
                } while ($existingImage);
                $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
            }
        }

        $dataToUpdate = [
            'itmQuestion' => $request->input('item_question'),
            'choices_number' => 2,
            'itmAnswer' => $request->input('question_answer'),
            'itmPoints' => $request->input('question_point'),
        ];

        if ($request->input('imageChanged')) {
            $dataToUpdate['itmImage'] = $request->hasFile('imageInput') ? $randomName : null;
        }

        $question->update($dataToUpdate);

        $questions = tfitems::where("tfID", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'tfTotal' => $total_points,
        ]);

        return redirect('/tf/' . $test_id);
    }
}
