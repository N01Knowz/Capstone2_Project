<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mttests;
use App\Models\mtitems;
use App\Models\subjects;
use App\Models\analyticmttags;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class matchingTestbankController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('isTeacher');
    }

    public function publish(string $id)
    {
        $test = mttests::find($id);
        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $test->update([
            'mtIsPublic' => 1,
        ]);
        return back()->with('publish', 'Record successfully published. Now it will be seen by students.');
    }

    public function add_multiple_store(Request $request, string $test_id)
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
            'matching_items' => 'required|file|mimes:xlsx,xls',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $header = ['Item Text', 'Item Answer', 'Item Points'];
        $file = $request->file('matching_items');

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

            if (is_null($row[1])) {
                $skippedRows++;
                continue;
            }

            mtitems::create([
                'mtID' => $test_id,
                'itmQuestion' => $row[0],
                'itmAnswer' => $row[1],
                'itmPoints' => is_null($row[2]) ? 1 : $row[2],
            ]);

            $rowIndex++;
        }

        $questions = mtitems::where("mtID", "=", $test->mtID)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'mtTotal' => $total_points,
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
        $testsQuery = mttests::leftJoin('subjects', 'mttests.subjectID', '=', 'subjects.subjectID')
            ->select('mttests.*', 'subjects.*')
            ->withCount('mtItems')
            ->where('mttests.user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('mtTitle', 'LIKE', "%$search%")
                    ->orWhere('mtDescription', 'LIKE', "%$search%");
            });
        }

        $subjects = subjects::all();
        $filterSubjects = [];

        $testsQuery->where(function ($query) use ($subjects, $request, &$filterSubjects) {
            foreach ($subjects as $subject) {
                $subjectInputName = $subject->subjectID . 'subject';

                if ($request->input($subjectInputName)) {
                    $filterSubjects[] = $subject->subjectID;
                    $query->orWhere('mttests.subjectID', $subject->subjectID);
                }
            }
        });

        $published =  is_null($request->input('sort-publish')) ? 2 : $request->input('sort-publish');

        if (in_array($request->input('sort-publish'), ['0', '1'])) {
            $testsQuery = $testsQuery->where('mtIsPublic', $published);
        }

        $sortDate =  is_null($request->input('sort-date')) ? 'desc' : $request->input('sort-date');
        $tests = $testsQuery->orderBy('mtID', $sortDate)
            ->paginate(13);


        // $tests->each(function ($tests) {
        //     $tags = analyticmttags::join('analytictags', 'analytictags.tagID', '=', 'analyticmttags.tagID')
        //         ->where('analyticmttags.mtID', $tests->mtID)
        //         ->get();
        //     // dd($tests->mtID);

        //     $tagData = [];
        //     foreach ($tags as $tag) {
        //         $tagData[$tag->tagName] = $tag->similarity;
        //     }


        //     $tests->tags = $tagData;
        // });


        $testPage = 'matching';
        return view('testbank.matching.matching', [
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

        $testPage = 'matching';
        return view('testbank.matching.matching_add', [
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

        // $hasAtLeastOneItemText = false;

        // for ($i = 1; $i <= intval($request->input('numChoicesInput')); $i++) {
        //     if ($request->input('item_text_' . $i)) {
        //         $hasAtLeastOneItemText = true;
        //         break;
        //     }
        // }

        // if (!$hasAtLeastOneItemText) {
        //     return redirect()->back()->withErrors(['no_item' => 'There should be at least 1 text item'])->withInput();
        // }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $testbank = mttests::create([
            'user_id' => Auth::id(),
            'mtTitle' => $request->input('title'),
            'mtDescription' => $request->input('description') ? $request->input('description') : '',
            'subjectID' =>  $request->input('subject'),
            'mtIsPublic' => 0,
        ]);

        // for ($i = 1; $i <= intval($request->input('numChoicesInput')); $i++) {
        //     $question = mtitems::create([
        //         'mtID' => $testbank->mtID,
        //         'itmAnswer' => $request->input('item_answer_' . $i),
        //         'itmQuestion' => $request->input('item_text_' . $i),
        //         'itmPoints' => $request->input('item_point_' . $i),
        //     ]);
        // }

        // $questions = mtitems::where("mtID", "=", $testbank->mtID)->get();

        // $total_points = 0;

        // foreach ($questions as $question) {
        //     $total_points += $question->itmPoints;
        // }

        // $testbank->update([
        //     'mtTotal' => $total_points,
        // ]);

        return redirect('/matching');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = mttests::find($id);
        // dd($test->user_id != Auth::id() && !$isShared);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $questions = mtitems::where('mtID', '=', $id)
            ->get();
        $questions->each(function ($questions) {
            $tags = analyticmttags::join('analytictags', 'analytictags.tagID', '=', 'analyticmttags.tagID')
                ->where('analyticmttags.itmID', $questions->itmID)
                ->get();
            // dd($questions->itmID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }
            $questions->tags = $tagData;
        });
        $testPage = 'matching';
        return view('testbank.matching.matching_test-description', [
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
        $test = mttests::leftJoin('subjects', 'mttests.subjectID', '=', 'subjects.subjectID')->where('mtID', $id)->select('mttests.*', 'subjectName')->first();


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $uniqueSubjects = subjects::all();


        $testPage = 'matching';
        return view('testbank.matching.matching_edit', [
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

        $testbank = mttests::find($id);
        if (is_null($testbank)) {
            abort(404); // User does not own the test
        }
        if ($testbank->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        // $subjectID = null;

        // if ($request->input('subject')) {
        //     $subjectName = strtolower($request->input('subject'));

        //     $subject = subjects::whereRaw('LOWER(subjectName) = ?', [$subjectName])
        //         ->where('user_id', Auth::id())
        //         ->first();
        //     if ($subject) {
        //         $subjectID = $subject->subjectID;
        //     } else {
        //         $createSubject = subjects::create([
        //             'subjectName' => ucfirst($request->input('subject')),
        //             'user_id' => Auth::id(),
        //         ]);
        //         $subjectID = $createSubject->subjectID;
        //     }
        // }

        $testbank->update([
            'mtTitle' => $request->input('title'),
            'mtDescription' => $request->input('description') ? $request->input('description') : '',
            'subjectID' => $request->input('subject'),
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


        $testPage = 'matching';
        return view('testbank/matching/matching_add_question', [
            'testPage' => $testPage,
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


        $testPage = 'matching';
        return view('testbank.matching.matching_edit_question', [
            'test' => $test,
            'question' => $question,
            'testPage' => $testPage,
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
