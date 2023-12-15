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
use PhpOffice\PhpSpreadsheet\IOFactory;


class tfTestbankController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('isTeacher');
    }

    public function publish(string $id)
    {
        $test = tftests::find($id);
        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $test->update([
            'tfIsPublic' => 1,
        ]);
        return back()->with('publish', 'Record successfully published. Now it will be seen by students.');
    }

    public function add_multiple_store(Request $request, string $test_id)
    {
        $input = $request->all();
        $test = tftests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $validator = Validator::make($input, [
            'tf_items' => 'required|file|mimes:xlsx,xls',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $header = ['Question', 'Item Point(s)', 'Answer Number'];
        $file = $request->file('tf_items');

        // Load the Excel file using IOFactory
        $spreadsheet = IOFactory::load($file);

        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $headerRow = true;
        $rowIndex = 0;
        $skippedRows = 0;

        foreach ($rows as $row) {
            if(count($rows) != count($header)){
                return redirect()->back()->with('wrong_template', 'There is a problem with the excel file uploaded. Template may not have been used.');
            }
            $columnIndex = -1;
            if ($headerRow) {
                foreach ($row as $cell) {
                    $columnIndex++;
                    if ($cell == $header[$columnIndex]) {
                        continue;
                    } else {
                        return redirect()->back()->with('wrong_template', 'There is a problem with the excel file uploaded. Template may not have been used.');
                    }
                }
                $headerRow = false;
                continue;
            }

            if (is_null($row[0]) || is_null($row[2])) {
                $skippedRows++;
                continue;
            }
            if (!in_array($row[2], ['1', '0'])) {
                $skippedRows++;
                continue;
            }

            tfitems::create([
                'tfID' => $test_id,
                'itmQuestion' => $row[0],
                'itmAnswer' => $row[2] == '1' ? 1 : 2,
                'itmPoints' => is_null($row[1]) ? 1 : $row[1],
                'itmOption1' => "True",
                'itmOption2' => "False",
            ]);
            $rowIndex++;
        }

        $questions = tfitems::where("tfID", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'tfTotal' => $total_points,
        ]);

        if($skippedRows == count($rows) - 1) {
            return redirect()->back()->with('success', 'There were no questions added');
        }
        return redirect()->back()->with('success', 'Items added succesfully. Only ' . $skippedRows . ' skipped.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $currentUserId = Auth::user()->id;
        $testsQuery = tftests::leftJoin('subjects', 'tftests.subjectID', '=', 'subjects.subjectID')
            ->select('tftests.*', 'subjects.*')
            ->withCount('tfItems')
            ->where('tftests.user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('tfTitle', 'LIKE', "%$search%")
                    ->orWhere('tfDescription', 'LIKE', "%$search%");
            });
        }

        $subjects = subjects::all();
        $filterSubjects = [];
        $testsQuery->where(function ($query) use ($subjects, $request, &$filterSubjects) {
            foreach ($subjects as $subject) {
                $subjectInputName = $subject->subjectID . 'subject';

                if ($request->input($subjectInputName)) {
                    $filterSubjects[] = $subject->subjectID;
                    $query->orWhere('tftests.subjectID', $subject->subjectID);
                }
            }
        });
        $published =  is_null($request->input('sort-publish')) ? 2 : $request->input('sort-publish');

        if (in_array($request->input('sort-publish'), ['0', '1'])) {
            $testsQuery = $testsQuery->where('tfIsPublic', $published);
        }


        $sortDate =  is_null($request->input('sort-date')) ? 'desc' : $request->input('sort-date');
        $tests = $testsQuery->orderBy('tfID', $sortDate)
            ->paginate(13);

        $testPage = 'tf';
        return view('testbank.tf.tf', [
            'tests' => $tests,
            'testPage' => $testPage,
            'searchInput' => $search,
            'subjects' => $subjects,
            'filterSubjects' => $filterSubjects,
            'sortDate' => $sortDate,
            'published' => $published,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentUserId = Auth::user()->id;

        $uniqueSubjects = subjects::all();

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


        tftests::create([
            'user_id' => Auth::id(),
            'tfTitle' => $request->input('title'),
            'tfDescription' => $request->input('description'),
            'subjectID' => $request->input('subject'),
            'tfIsPublic' => 0,
        ]);

        return redirect('/tf');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = tftests::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
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

        $uniqueSubjects = subjects::all();

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

        $testbank->update([
            'tfTitle' => $request->input('title'),
            'tfDescription' => $request->input('description'),
            'subjectID' => $request->input('subject'),
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
            $folderPath = public_path('user_upload_images/' . Auth::id());

            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }
            $imagePath = public_path('user_upload_images/' . Auth::id() . '/' . $questionImage);
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
            $request->file('imageInput')->move(public_path('user_upload_images/' . Auth::id()), $randomName);
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
        $folderPath = public_path('user_upload_images/' . Auth::id());

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $imagePath = public_path('user_upload_images/' . Auth::id() . '/' . $questionImage);
        if (File::exists($imagePath)) {
            // Delete the image file
            File::delete($imagePath);

            // Optionally, you can also remove the image filename from the database or update the question record here
        }

        $question->delete();

        $questions = tfitems::where("tfID", "=", $question->tfID)->get();

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
        $analytics = analytictfitemtags::where('itmID', $question->itmID)->delete();

        $randomName = "";
        if ($request->input('imageChanged')) {
            $questionImage = $question->question_image;
            $folderPath = public_path('user_upload_images/' . Auth::id());

            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }
            $imagePath = public_path('user_upload_images/' . Auth::id() . '/' . $questionImage);
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
                $request->file('imageInput')->move(public_path('user_upload_images/' . Auth::id()), $randomName);
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
