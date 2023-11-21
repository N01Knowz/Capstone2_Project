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
use PhpOffice\PhpSpreadsheet\IOFactory;

class mtfTestbankController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('isTeacher');
    }

    public function publish(string $id)
    {
        $test = mtftests::find($id);
        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $test->update([
            'mtfIsPublic' => 1,
        ]);
        return back()->with('publish', 'Record successfully published. Now it will be seen by students.');
    }


    public function add_multiple_store(Request $request, string $test_id)
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
            'mtf_items' => 'required|file|mimes:xlsx,xls',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $header = ['Question', 'Item Point(s)', 'Explanation Point(s)', 'Answer Number'];
        $file = $request->file('mtf_items');

        // Load the Excel file using IOFactory
        $spreadsheet = IOFactory::load($file);

        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $headerRow = true;
        $rowIndex = 0;
        $skippedRows = 0;

        foreach ($rows as $row) {
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

            if (is_null($row[0]) || is_null($row[3])) {
                $skippedRows++;
                break;
            }
            if (!in_array($row[3], ['1', '0'])) {
                $skippedRows++;
                break;
            }

            $total_points = 0;
            is_null($row[1]) ? $total_points += 1 : $total_points += $row[1];
            is_null($row[2]) ? $total_points += 1 : $total_points += $row[2];

            mtfitems::create([
                'itmPointsTotal' => $total_points,
                'mtfID' => $test_id,
                'itmQuestion' => $row[0],
                'itmAnswer' => $row[3] == '1' ? 1 : 2,
                'choices_number' => 2,
                'itmPoints1' => is_null($row[1]) ? 1 : $row[1],
                'itmPoints2' => is_null($row[2]) ? 1 : $row[2],
            ]);

            $rowIndex++;
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

        return redirect()->back()->with('success', 'Items added succesfully. Only ' . $skippedRows . ' skipped.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $currentUserId = Auth::user()->id;
        $testsQuery = mtftests::leftJoin('subjects', 'mtftests.subjectID', '=', 'subjects.subjectID')
            ->select('mtftests.*', 'subjects.*')
            ->withCount('mtfItems')
            ->where('mtftests.user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('test_title', 'LIKE', "%$search%")
                    ->orWhere('test_instruction', 'LIKE', "%$search%");
            });
        }
        $testPage = 'mtf';

        $subjects = subjects::all();
        $filterSubjects = [];

        $testsQuery->where(function ($query) use ($subjects, $request, &$filterSubjects) {
            foreach ($subjects as $subject) {
                $subjectInputName = $subject->subjectID . 'subject';

                if ($request->input($subjectInputName)) {
                    $filterSubjects[] = $subject->subjectID;
                    $query->orWhere('mtftests.subjectID', $subject->subjectID);
                }
            }
        });

        $published =  is_null($request->input('sort-publish')) ? 2 : $request->input('sort-publish');

        if (in_array($request->input('sort-publish'), ['0', '1'])) {
            $testsQuery = $testsQuery->where('mtfIsPublic', $published);
        }

        $sortDate =  is_null($request->input('sort-date')) ? 'desc' : $request->input('sort-date');
        $tests = $testsQuery->orderBy('mtfID', $sortDate)
            ->paginate(13);
        return view('testbank.mtf.mtf', [
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

        mtftests::create([
            'user_id' => Auth::id(),
            'mtfTitle' => $request->input('title'),
            'mtfDescription' => $request->input('description'),
            'subjectID' => $request->input('subject'),
            'mtfIsPublic' => 0,
        ]);

        return redirect('/mtf');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = mtftests::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
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


        $uniqueSubjects = subjects::all();


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

        $testbank->update([
            'mtfTitle' => $request->input('title'),
            'mtfDescription' => $request->input('description'),
            'subjectID' => $request->input('subject'),
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
            $request->file('imageInput')->move(public_path('user_upload_images/' . Auth::id()), $randomName);
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
        $test = mtftests::find($question->mtfID);
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

        $questions = mtfitems::where("mtfID", "=", $test->id)->get();

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
                    $randomName = 'mtf_' . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'qst.' . $request->file('imageInput')->getClientOriginalExtension();
                    $existingImage = mtfitems::where('itmImage', $randomName)->first();
                } while ($existingImage);
                $request->file('imageInput')->move(public_path('user_upload_images/' . Auth::id()), $randomName);
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
