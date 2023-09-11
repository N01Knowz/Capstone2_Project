<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use App\Models\questions;
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
        $testsQuery = testbank::where('test_type', '=', 'mcq')
            ->where('user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('test_title', 'LIKE', "%$search%")
                    ->orWhere('test_instruction', 'LIKE', "%$search%");
            });
        }

        $tests = $testsQuery->orderBy('id', 'desc')
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
        return view('testbank.mcq.mcq_add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'instruction' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testbank = testbank::create([
            'user_id' => Auth::id(),
            'test_type' => 'mcq',
            'test_title' => $request->input('title'),
            'test_question' => '',
            'test_instruction' => $request->input('instruction'),
            'test_image' => '',
            'test_total_points' => 0,
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ]);

        return redirect('/mcq');
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
        $test = testbank::find($id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        return view('testbank.mcq.mcq_edit', [
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
            'test_instruction' => $request->input('instruction'),
            'test_visible' => $request->has('share'),
        ]);

        return redirect('/mcq');
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

        questions::where('testbank_id', $id)->delete();
        $test->delete();

        return back();
    }

    public function add_question_index(string $test_id)
    {
        $test = testbank::find($test_id);


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
        $test = testbank::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = questions::find($question_id);

        return view('testbank.mcq.mcq_question_description', [
            'test' => $test,
            'question' => $question,
        ]);

        return back();
    }

    public function add_question_store(Request $request, string $test_id)
    {
        $input = $request->all();

        $test = testbank::find($test_id);


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
                $randomName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . '.' . $request->file('imageInput')->getClientOriginalExtension();
                $existingImage = questions::where('question_image', $randomName)->first();
            } while ($existingImage);
            $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
        }


        $question = questions::create([
            'testbank_id' => $test_id,
            'question_active' => 1,
            'item_question' => $request->input('item_question'),
            'question_image' => $request->hasFile('imageInput') ? $randomName : null,
            'choices_number' => $request->input('number_of_choices'),
            'question_answer' => $request->input('question_answer'),
            'question_point' => $request->input('question_point'),
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
                'option_' . $i => $updatedHTML,
            ]);
        }

        $questions = questions::where("testbank_id", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->question_point;
        }

        $test->update([
            'test_total_points' => $total_points,
        ]);

        return redirect('/mcq/' . $test_id);

        // $test = testbank::find($test_id);
        // return view('testbank/mcq/mcq_add_question', [
        //     'test' => $test,
        // ]);
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

    public function add_question_edit(string $test_id, string $question_id)
    {
        $test = testbank::find($test_id);


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        $question = questions::find($question_id);

        return view('testbank.mcq.mcq_edit_question', [
            'test' => $test,
            'question' => $question,
        ]);

        return back();
    }

    public function add_question_update(Request $request, string $test_id, string $question_id)
    {
        $test = testbank::find($test_id);


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
        $question = questions::find($question_id);

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
                    $randomName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . '.' . $request->file('imageInput')->getClientOriginalExtension();
                    $existingImage = questions::where('question_image', $randomName)->first();
                } while ($existingImage);
                $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
            }
        }

        $dataToUpdate = [
            'item_question' => $request->input('item_question'),
            'choices_number' => $request->input('number_of_choices'),
            'question_answer' => $request->input('question_answer'),
            'question_point' => $request->input('question_point'),
        ];


        if ($request->input('imageChanged')) {
            $dataToUpdate['question_image'] = $request->hasFile('imageInput') ? $randomName : null;
        }

        $question->update($dataToUpdate);

        for ($i = 1; $i <= 10; $i++) {

            $option = $request->input('option_' . $i);
            $oldOption = questions::where('id', $question_id)
                ->value('option_' . $i);

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

        $questions = questions::where("testbank_id", "=", $test_id)->get();

        $total_points = 0;

        foreach ($questions as $question) {
            $total_points += $question->question_point;
        }

        $test->update([
            'test_total_points' => $total_points,
        ]);

        return redirect('/mcq/' . $test_id);
    }
}
