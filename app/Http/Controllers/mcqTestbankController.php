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
    public function index()
    {
        $currentUserId = Auth::user()->id;
        $tests = testbank::where('test_type', '=', 'mcq')
            ->where('user_id', '=', $currentUserId)
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
            'user_id' => $request->input('id'),
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

        $test->update([
            'test_active' => '0'
        ]);

        return back();
    }

    public function add_question_index(string $test_id)
    {
        $test = testbank::find($test_id);
        return view('testbank/mcq/mcq_add_question', [
            'test' => $test,
        ]);
    }

    public function add_question_show(string $test_id, string $question_id)
    {
        $test = testbank::find($test_id);
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

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'number_of_choices' => 'required|numeric|gte:1|lt:11',
            'option_1' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $question = questions::create([
            'testbank_id' => $test_id,
            'question_active' => 1,
            'item_question' => $request->input('item_question'),
            'question_image' => $request->input('question_image', null),
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

        return redirect('/mcq/' . $test_id);

        // $test = testbank::find($test_id);
        // return view('testbank/mcq/mcq_add_question', [
        //     'test' => $test,
        // ]);
    }

    public function add_question_destroy(string $id)
    {
        $question = questions::find($id);

        $question->update([
            'question_active' => '0'
        ]);

        return back();
    }

    public function add_question_edit(string $test_id, string $question_id)
    {
        $test = testbank::find($test_id);
        $question = questions::find($question_id);

        return view('testbank.mcq.mcq_edit_question', [
            'test' => $test,
            'question' => $question,
        ]);

        return back();
    }

    public function add_question_update(Request $request, string $test_id, string $question_id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'item_question' => 'required',
            'number_of_choices' => 'required|numeric|gte:1|lt:11',
            'option_1' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $question = questions::find($question_id);

        $question->update([
            'item_question' => $request->input('item_question'),
            'question_image' => $request->input('question_image', null),
            'choices_number' => $request->input('number_of_choices'),
            'question_answer' => $request->input('question_answer'),
            'question_point' => $request->input('question_point'),
        ]);

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
                    }
                    else {
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
        return redirect('/mcq/' . $test_id);
    }
}
