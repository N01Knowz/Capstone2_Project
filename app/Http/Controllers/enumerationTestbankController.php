<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ettests;
use App\Models\etitems;
use App\Models\subjects;
use App\Models\analyticettags;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class enumerationTestbankController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('isTeacher');
    }

    public function publish(string $id)
    {
        $test = ettests::find($id);
        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $test->update([
            'etIsPublic' => 1,
        ]);
        return back()->with('publish', 'Record successfully published. Now it will be seen by students.');
    }

    public function add_multiple_store(Request $request, string $test_id)
    {
        $input = $request->all();
        $test = ettests::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $validator = Validator::make($input, [
            'enumeration_items' => 'required|file|mimes:xlsx,xls',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $header = ['Answer', 'Case Sensitive'];
        $file = $request->file('enumeration_items');

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

            if (is_null($row[0])) {
                $skippedRows++;
                break;
            }

            etitems::create([
                'etID' => $test_id,
                'itmAnswer' => $row[0],
                'itmIsCaseSensitive' => !in_array($row[1], ['0', '1']) ? 0 : $row[1],
            ]);

            $rowIndex++;
        }

        $questions = etitems::where("etID", "=", $test->etID)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += 1;
        }

        $test->update([
            'etTotal' => $total_points,
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
        $testsQuery = ettests::leftJoin('subjects', 'ettests.subjectID', '=', 'subjects.subjectID')
            ->select('ettests.*', 'subjects.*')
            ->withCount('etItems')
            ->where('ettests.user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('etTitle', 'LIKE', "%$search%")
                    ->orWhere('etDescription', 'LIKE', "%$search%");
            });
        }

        $subjects = subjects::all();
        $filterSubjects = [];

        $testsQuery->where(function ($query) use ($subjects, $request, &$filterSubjects) {
            foreach ($subjects as $subject) {
                $subjectInputName = $subject->subjectID . 'subject';

                if ($request->input($subjectInputName)) {
                    $filterSubjects[] = $subject->subjectID;
                    $query->orWhere('ettests.subjectID', $subject->subjectID);
                }
            }
        });

        $published =  is_null($request->input('sort-publish')) ? 2 : $request->input('sort-publish');

        if (in_array($request->input('sort-publish'), ['0', '1'])) {
            $testsQuery = $testsQuery->where('etIsPublic', $published);
        }

        $sortDate =  is_null($request->input('sort-date')) ? 'desc' : $request->input('sort-date');
        $tests = $testsQuery->orderBy('etID', $sortDate)
            ->paginate(13);

        $tests->each(function ($tests) {
            $tags = analyticettags::join('analytictags', 'analytictags.tagID', '=', 'analyticettags.tagID')
                ->where('analyticettags.etID', $tests->etID)
                ->get();
            // dd($tests->etID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }


            $tests->tags = $tagData;
        });


        $testPage = 'enumeration';
        return view('testbank.enumeration.enumeration', [
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

        $testPage = 'enumeration';
        return view('testbank.enumeration.enumeration_add', [
            'uniqueSubjects' => $uniqueSubjects
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

        $testbank = ettests::create([
            'user_id' => Auth::id(),
            'etTitle' => $request->input('title'),
            'etDescription' => $request->input('description'),
            'subjectID' => $request->input('subject'),
            'etIsPublic' => 0,
        ]);

        return redirect('/enumeration');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = ettests::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $questions = etitems::where('etID', '=', $id)
            ->get();

        $testPage = 'enumeration';
        return view('testbank.enumeration.enumeration_test-description', [
            'test' => $test,
            'questions' => $questions,
            'testPage' => $testPage
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $test = ettests::leftJoin('subjects', 'ettests.subjectID', '=', 'subjects.subjectID')->where('etID', $id)->select('ettests.*', 'subjectName')->first();

        if (is_null($test)) {
            abort(404); // User does not own the test
        }

        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        // $uniqueSubjects = subjects::where('user_id', Auth::id())
        //     ->where('subjectName', '!=', 'No Subject') // Exclude rows with 'No Subject'
        //     ->distinct('subjectName')
        //     ->pluck('subjectName')
        //     ->toArray();

        $uniqueSubjects = subjects::all();

        $testPage = 'enumeration';
        return view('testbank.enumeration.enumeration_edit', [
            'uniqueSubjects' => $uniqueSubjects,
            'test' => $test,
            'testPage' => $testPage
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

        $testbank = ettests::find($id);
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
            'etTitle' => $request->input('title'),
            'etDescription' => $request->input('description'),
            'subjectID' => $request->input('subject'),
        ]);

        return redirect('/enumeration');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = ettests::find($id);

        if (is_null($test)) {
            abort(404); // User does not own the test
        }

        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        etitems::where('etID', $id)->delete();
        $test->delete();

        return back();
    }

    public function add_question_store(Request $request, string $test_id)
    {
        $test = ettests::find($test_id);


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

        etitems::create([
            'etID' => $test_id,
            'itmAnswer' => $request->input('answer_text'),
            'itmIsCaseSensitive' => $request->has('case_sensitive_text') ? "1" : "0",
        ]);


        $questions = etitems::where("etID", "=", $test->etID)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += 1;
        }

        $test->update([
            'etTotal' => $total_points,
        ]);

        return redirect('/enumeration/' . $test_id);
    }

    public function add_question_destroy(string $id)
    {
        $question = etitems::find($id);
        if (is_null($question)) {
            abort(404); // User does not own the test
        }
        $test = ettests::find($question->etID);
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question->delete();

        $questions = etitems::where("etID", "=", $test->etID)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += 1;
        }

        $test->update([
            'etTotal' => $total_points,
        ]);

        return back();
    }
}
