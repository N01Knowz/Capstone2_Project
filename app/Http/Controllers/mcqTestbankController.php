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

class mcqTestbankController extends Controller
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
        $testsQuery = quizzes::leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID')
            ->where('quizzes.user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('qzTitle', 'LIKE', "%$search%")
                    ->orWhere('qzDescription', 'LIKE', "%$search%");
            });
        }

        $tests = $testsQuery->orderBy('qzID', 'desc')
            ->get();
        return view('testbank.mcq.mcq', [
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
        return view('testbank.mcq.mcq_add', ['uniqueSubjects' => $uniqueSubjects]);
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

        $quizzes = quizzes::create([
            'user_id' => Auth::id(),
            'qzTitle' => $request->input('title'),
            'qzDescription' => $request->input('description'),
            'subjectID' => $subjectID,
            'qzIsPublic' => $request->has('share'),
        ]);

        return redirect('/mcq');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = quizzes::find($id);
        $isShared = $test->tfIsPublic;


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id() && !$isShared) {
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

        return view('testbank.mcq.mcq_test-description', [
            'test' => $test,
            'questions' => $questions,
        ]);
        // $test = testbank::find($id);
        // return view('testbank.mcq.mcq_test-description', [
        //     'test' => $test,
        // ]);
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

        $uniqueSubjects = subjects::where('user_id', Auth::id())
            ->where('subjectName', '!=', 'No Subject') // Exclude rows with 'No Subject'
            ->distinct('subjectName')
            ->pluck('subjectName')
            ->toArray();

        return view('testbank.mcq.mcq_edit', [
            'uniqueSubjects' => $uniqueSubjects,
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

        $testbank = quizzes::find($id);
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
            'qzTitle' => $request->input('title'),
            'qzDescription' => $request->input('description'),
            'qzIsPublic' => $request->has('share'),
            'subjectID' => $subjectID,
        ]);

        return redirect('/mcq');
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
            $imagePath = public_path('user_upload_images/' . $questionImage);
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
        return view('testbank/mcq/mcq_add_question', [
            'test' => $test,
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

        return view('testbank.mcq.mcq_question_description', [
            'test' => $test,
            'question' => $question,
        ]);

        return back();
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
            $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
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
                file_put_contents($uploadpath . '/' . $filename, $imageData);
                $image->setAttribute('src', '/user_upload_images/' . $filename);
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

        // $test = testbank::find($test_id);
        // return view('testbank/mcq/mcq_add_question', [
        //     'test' => $test,
        // ]);
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
        $imagePath = public_path('user_upload_images/' . $questionImage);
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

        return view('testbank.mcq.mcq_edit_question', [
            'test' => $test,
            'question' => $question,
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
            $imagePath = public_path('user_upload_images/' . $questionImage);
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
                $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
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
                        file_put_contents($uploadpath . '/' . $filename, $imageData);
                        $image->setAttribute('src', '/user_upload_images/' . $filename);
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
                'option_' . $i => $updatedHTML,
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
