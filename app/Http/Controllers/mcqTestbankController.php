<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\quizzes;
use App\Models\quizitems;
use App\Models\subjects;
use App\Models\analyticquizitemtags;
use DOMDocument;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;


class mcqTestbankController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('isTeacher');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $currentUserId = Auth::user()->id;
        $testsQuery = quizzes::leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID')
            ->select('quizzes.*', 'subjects.*')
            ->withCount('quizItems')
            ->where('quizzes.user_id', '=', $currentUserId);
        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('qzTitle', 'LIKE', "%$search%")
                    ->orWhere('qzDescription', 'LIKE', "%$search%");
            });
        }

        $testPage = 'mcq';

        $subjects = subjects::all();
        $filterSubjects = [];

        $testsQuery->where(function ($query) use ($subjects, $request, &$filterSubjects) {
            foreach ($subjects as $subject) {
                $subjectInputName = $subject->subjectID . 'subject';

                if ($request->input($subjectInputName)) {
                    $filterSubjects[] = $subject->subjectID;
                    $query->orWhere('quizzes.subjectID', $subject->subjectID);
                }
            }
        });

        $published =  is_null($request->input('sort-publish')) ? 2 : $request->input('sort-publish');

        if (in_array($request->input('sort-publish'), ['0', '1'])) {
            $testsQuery->where('qzIsPublic', $published);
        }

        $sortDate =  is_null($request->input('sort-date')) ? 'desc' : $request->input('sort-date');
        $tests = $testsQuery->orderBy('qzID', $sortDate)
            ->paginate(13);
        // dd($tests);

        // dd($tests);
        // dd($request->input('sort-date'));
        // dd(session('success'));
        if (session()->has('success')) {
            return view('testbank.mcq.mcq', [
                'tests' => $tests,
                'testPage' => $testPage,
                'searchInput' => $search,
                'subjects' => $subjects,
                'filterSubjects' => $filterSubjects,
                'sortDate' => $sortDate,
                'published' => $published,
            ])->with('success', session('success'));
        }
        return view('testbank.mcq.mcq', [
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

        $testPage = 'mcq';
        return view('testbank.mcq.mcq_add', [
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

        $quizzes = quizzes::create([
            'user_id' => Auth::id(),
            'qzTitle' => $request->input('title'),
            'qzDescription' => $request->input('description'),
            'subjectID' => $request->input('subject'),
            'qzIsPublic' => 0,
        ]);

        return redirect('/mcq');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = quizzes::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $questions = quizitems::where('qzID', '=', $id)
            ->get();

        $questions->each(function ($questions) {
            $tags = analyticquizitemtags::join('analytictags', 'analytictags.tagID', '=', 'analyticquizitemtags.tagID')
                ->where('analyticquizitemtags.itmID', $questions->itmID)
                ->get();
            // dd($questions->itmID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }


            $questions->tags = $tagData;
        });


        $testPage = 'mcq';
        return view('testbank.mcq.mcq_test-description', [
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
        $test = quizzes::leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID')->where('qzID', $id)->select('quizzes.*', 'subjectName')->first();


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $uniqueSubjects = subjects::all();

        $testPage = 'mcq';
        return view('testbank.mcq.mcq_edit', [
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

        $testbank = quizzes::find($id);
        if (is_null($testbank)) {
            abort(404); // User does not own the test
        }
        if ($testbank->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }


        $testbank->update([
            'qzTitle' => $request->input('title'),
            'qzDescription' => $request->input('description'),
            'subjectID' => $request->input('subject'),
        ]);

        return redirect('/mcq');
    }

    public function publish(string $id)
    {
        $test = quizzes::find($id);
        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $test->update([
            'qzIsPublic' => 1,
        ]);
        return back()->with('publish', 'Record successfully published. Now it will be seen by students.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = quizzes::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $questions = quizitems::where('qzID', $id)->get();
        foreach ($questions as $question) {

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
            for ($i = 1; $i <= 10; $i++) {
                $oldOption = quizitems::where('itmID', $question->itmID)
                    ->value('itmOption' . $i);

                if ($oldOption) {
                    $oldDom = new DOMDocument();
                    @$oldDom->loadHTML($oldOption);
                    $oldImageFile = $oldDom->getElementsByTagName('img');
                }

                if ($oldOption) {
                    foreach ($oldImageFile as $oldItem => $oldImage) {
                        $oldSrc = $oldImage->getAttribute('src');
                        $oldSrcWithoutLeadingSlash = ltrim($oldSrc, '/');
                        // $fileName = '1692509280_64e1a460a6004.jpeg';
                        $filePath = public_path($oldSrcWithoutLeadingSlash);
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                    }
                }
            }
            $question->delete();
        }

        $test->delete();

        return back();
    }

    public function add_question_index(string $test_id)
    {
        $test = quizzes::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $testPage = 'mcq';
        return view('testbank/mcq/mcq_add_question', [
            'test' => $test,
            'testPage' => $testPage,
        ]);
    }

    public function add_question_show(string $test_id, string $question_id)
    {
        $test = quizzes::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = quizitems::find($question_id);


        $testPage = 'mcq';
        return view('testbank.mcq.mcq_question_description', [
            'test' => $test,
            'question' => $question,
            'testPage' => $testPage,
        ]);

        return back();
    }

    public function add_multiple_store(Request $request, string $test_id)
    {
        $input = $request->all();
        $test = quizzes::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $validator = Validator::make($input, [
            'mcq_items' => 'required|file|mimes:xlsx,xls',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $header = ['Question', 'Item Point(s)', 'Answer Number', 'Option 1', 'Option 2', 'Option 3', 'Option 4', 'Option 5', 'Option 6', 'Option 7', 'Option 8', 'Option 9', 'Option 10'];
        $file = $request->file('mcq_items');

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

            if (is_null($row[0]) || is_null($row[2]) || is_null($row[3])) {
                $skippedRows++;
                continue;
            }
            $choices = 0;
            for ($i = 3; $i < 13; $i++) {
                if (!is_null($row[$i])) {
                    $choices++;
                }
            }

            quizitems::create([
                'qzID' => $test_id,
                'itmQuestion' => $row[0],
                'choices_number' => $choices,
                'itmAnswer' => $row[2],
                'itmPoints' => is_null($row[1]) ? 1 : $row[1],
                'itmOption1' => '<p>' . $row[3] . "</p>",
                'itmOption2' => is_null($row[4]) ? null : '<p>' . $row[4] . "</p>",
                'itmOption3' => is_null($row[5]) ? null : '<p>' . $row[5] . "</p>",
                'itmOption4' => is_null($row[6]) ? null : '<p>' . $row[6] . "</p>",
                'itmOption5' => is_null($row[7]) ? null : '<p>' . $row[7] . "</p>",
                'itmOption6' => is_null($row[8]) ? null : '<p>' . $row[8] . "</p>",
                'itmOption7' => is_null($row[9]) ? null : '<p>' . $row[9] . "</p>",
                'itmOption8' => is_null($row[10]) ? null : '<p>' . $row[10] . "</p>",
                'itmOption9' => is_null($row[11]) ? null : '<p>' . $row[11] . "</p>",
                'itmOption10' => is_null($row[12]) ? null : '<p>' . $row[12] . "</p>",
            ]);
            $rowIndex++;
        }
        $questions = quizitems::where("qzID", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'qzTotal' => $total_points,
        ]);

        if($skippedRows == count($rows) - 1) {
            return redirect()->back()->with('success', 'There were no questions added');
        }
        return redirect()->back()->with('success', 'Items added succesfully. Only ' . $skippedRows . ' skipped.');
    }

    public function add_question_store(Request $request, string $test_id)
    {
        $input = $request->all();

        $test = quizzes::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'number_of_choices' => 'required|numeric|gte:1|lt:11',
            'option_1' => 'required',
            'imageInput' => 'image|mimes:jpeg,png,jpg,gif', // Adjust the file types and size as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $randomName = "";
        if ($request->hasFile('imageInput')) {
            do {
                $randomName = 'mcq_' . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'qst.' . $request->file('imageInput')->getClientOriginalExtension();
                $existingImage = quizitems::where('itmImage', $randomName)->first();
            } while ($existingImage);
            $request->file('imageInput')->move(public_path('user_upload_images/' . Auth::id()), $randomName);
        }

        $question = quizitems::create([
            'qzID' => $test_id,
            'itmQuestion' => $request->input('item_question'),
            'itmImage' => $request->hasFile('imageInput') ? $randomName : null,
            'choices_number' => $request->input('number_of_choices'),
            'itmAnswer' => $request->input('question_answer'),
            'itmPoints' => $request->input('question_point'),
        ]);


        for ($i = 1; $i <= intval($request->input('number_of_choices')); $i++) {
            $option = $request->input('option_' . $i);

            $dom = new DOMDocument();
            @$dom->loadHTML($option);


            $imageFile = $dom->getElementsByTagName('img');


            foreach ($imageFile as $item => $image) {
                $src = $image->getAttribute('src');
                $dataParts = explode(';', $src);
                $mediaTypeParts = explode('/', $dataParts[0]);
                $imageType = end($mediaTypeParts);
                $dataUriParts = explode(',', $src, 2);
                $srcData = $dataUriParts[1];
                $imageData = base64_decode($srcData);
                $uploadpath = public_path('user_upload_images');
                $filename = time() . '_' . uniqid() . '.' . $imageType;
                file_put_contents($uploadpath . '/' . Auth::id() . '/'  . $filename, $imageData);
                $image->setAttribute('src', '/user_upload_images/' . Auth::id() . '/' . $filename);
            }

            $bodyContent = '';
            $bodyElement = $dom->getElementsByTagName('body')->item(0);
            if ($bodyElement) {
                foreach ($bodyElement->childNodes as $node) {
                    $bodyContent .= $dom->saveHTML($node);
                }
            }

            $updatedHTML = $bodyContent;


            $question->update([
                'itmOption' . $i => $updatedHTML,
            ]);
        }

        $questions = quizitems::where("qzID", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'qzTotal' => $total_points,
        ]);

        return redirect('/mcq/' . $test_id);
    }

    public function add_question_destroy(string $id)
    {
        $question = quizitems::find($id);
        if (is_null($question)) {
            abort(404); // User does not own the test
        }
        $test = quizzes::find($question->qzID);
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

        for ($i = 1; $i <= 10; $i++) {

            $oldOption = quizitems::where('itmID', $id)
                ->value('itmOption' . $i);

            if ($oldOption) {
                $oldDom = new DOMDocument();
                @$oldDom->loadHTML($oldOption);
                $oldImageFile = $oldDom->getElementsByTagName('img');
            }

            if ($oldOption) {
                foreach ($oldImageFile as $oldItem => $oldImage) {
                    $oldSrc = $oldImage->getAttribute('src');
                    $oldSrcWithoutLeadingSlash = ltrim($oldSrc, '/');
                    // $fileName = '1692509280_64e1a460a6004.jpeg';
                    $filePath = public_path($oldSrcWithoutLeadingSlash);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }
            }
        }

        $question->delete();

        $questions = quizitems::where("qzID", "=", $test->id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'qzTotal' => $total_points,
        ]);

        return back();
    }

    public function add_question_edit(string $test_id, string $question_id)
    {
        $test = quizzes::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = quizitems::find($question_id);


        $testPage = 'mcq';
        return view('testbank.mcq.mcq_edit_question', [
            'test' => $test,
            'question' => $question,
            'testPage' => $testPage,
        ]);
    }

    public function add_question_update(Request $request, string $test_id, string $question_id)
    {
        $test = quizzes::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $input = $request->all();
        // dd($request->input('imageChanged'));

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'number_of_choices' => 'required|numeric|gte:1|lt:11',
            'option_1' => 'required',
            'imageInput' => 'image|mimes:jpeg,png,jpg,gif', // Adjust the file types and size as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $question = quizitems::find($question_id);

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
                    $randomName = 'mcq_' . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'qst.' . $request->file('imageInput')->getClientOriginalExtension();
                    $existingImage = quizitems::where('itmImage', $randomName)->first();
                } while ($existingImage);
                $request->file('imageInput')->move(public_path('user_upload_images/' . Auth::id()), $randomName);
            }
        }

        $dataToUpdate = [
            'itmQuestion' => $request->input('item_question'),
            'choices_number' => $request->input('number_of_choices'),
            'itmAnswer' => $request->input('question_answer'),
            'itmPoints' => $request->input('question_point'),
        ];


        if ($request->input('imageChanged')) {
            $dataToUpdate['itmImage'] = $request->hasFile('imageInput') ? $randomName : null;
        }

        $question->update($dataToUpdate);

        for ($i = 1; $i <= 10; $i++) {

            $option = $request->input('option_' . $i);
            $oldOption = quizitems::where('itmID', $question_id)
                ->value('itmOption' . $i);

            if ($oldOption) {
                $oldDom = new DOMDocument();
                @$oldDom->loadHTML($oldOption);
                $oldImageFile = $oldDom->getElementsByTagName('img');
            }

            if ($option) {
                $dom = new DOMDocument();
                @$dom->loadHTML($option);
                $imageFile = $dom->getElementsByTagName('img');
            }


            if ($oldOption) {
                foreach ($oldImageFile as $oldItem => $oldImage) {
                    $oldSrc = $oldImage->getAttribute('src');
                    $oldSrcExist = false;
                    $oldSrcWithoutLeadingSlash = ltrim($oldSrc, '/');
                    // $fileName = '1692509280_64e1a460a6004.jpeg';
                    $filePath = public_path($oldSrcWithoutLeadingSlash);
                    $src = "";
                    if ($option) {
                        foreach ($imageFile as $item => $image) {
                            $src = $image->getAttribute('src');
                            if ($oldSrc == $src) {
                                $oldSrcExist = true;
                            }
                        }
                        // dd($oldSrc, $option, strpos($oldSrc, $option));
                        if ($oldSrcExist == false) {
                            // dd($oldSrcWithoutLeadingSlash, $option, strpos($oldSrc, $option), $filePath, File::exists($filePath));
                            if (File::exists($filePath)) {
                                File::delete($filePath);
                            }
                        }
                    } else {
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                    }
                }
            }
            if ($option) {
                foreach ($imageFile as $item => $image) {
                    $src = $image->getAttribute('src');
                    $uploadpath = public_path('user_upload_images');
                    if (strpos($src, 'data:') === 0) {
                        $dataParts = explode(';', $src);
                        $mediaTypeParts = explode('/', $dataParts[0]);
                        $imageType = end($mediaTypeParts);
                        $dataUriParts = explode(',', $src, 2);
                        $srcData = $dataUriParts[1];
                        $imageData = base64_decode($srcData);
                        $filename = time() . '_' . uniqid() . '.' . $imageType;
                        file_put_contents($uploadpath . '/' . Auth::id() . '/'  . $filename, $imageData);
                        $image->setAttribute('src', '/user_upload_images/' . Auth::id() . '/' . $filename);
                    }
                }
                $bodyContent = '';
                $bodyElement = $dom->getElementsByTagName('body')->item(0);
                if ($bodyElement) {
                    foreach ($bodyElement->childNodes as $node) {
                        $bodyContent .= $dom->saveHTML($node);
                    }
                }
            }


            if ($option) {
                $updatedHTML = $bodyContent;
            } else {
                $updatedHTML = null;
            }
            echo $updatedHTML;

            $question->update([
                'itmOption' . $i => $updatedHTML,
            ]);
        }

        $questions = quizitems::where("qzID", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->itmPoints;
        }

        $test->update([
            'qzTotal' => $total_points,
        ]);

        return redirect('/mcq/' . $test_id);
    }
}
