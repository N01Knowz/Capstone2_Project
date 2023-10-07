<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use App\Models\questions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class essayTestbankController extends Controller
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
        $testsQuery = testbank::where('test_type', '=', 'essay')
            ->where('user_id', '=', $currentUserId);

        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('test_title', 'LIKE', "%$search%")
                    ->orWhere('test_instruction', 'LIKE', "%$search%");
            });
        }

        $tests = $testsQuery->orderBy('id', 'desc')
            ->get();
        return view('testbank.essay.essay', [
            'tests' => $tests,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentUserId = Auth::user()->id;
        $uniqueSubjects = testbank::where('user_id', $currentUserId)
            ->where('test_subject', '!=', 'No Subject') // Exclude rows with 'No Subject'
            ->distinct('test_subject')
            ->pluck('test_subject')
            ->toArray();

        return view('testbank.essay.essay_add', ['uniqueSubjects' => $uniqueSubjects]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'question' => 'required',
            'criteria_1' => 'required',
            'criteria_point_1' => 'required',
            'imageInput' => 'image|mimes:jpeg,png,jpg,gif', // Adjust the file types and size as needed
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $randomName = "";
        if ($request->hasFile('imageInput')) {
            do {
                $randomName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'tst.' . $request->file('imageInput')->getClientOriginalExtension();
                $existingImage = testbank::where('test_image', $randomName)->first();
            } while ($existingImage);
            $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
        }

        $testbank = testbank::create([
            'user_id' => Auth::id(),
            'test_type' => 'essay',
            'test_title' => $request->input('title'),
            'test_question' => $request->input('question'),
            'test_instruction' => $request->input('instruction') ? $request->input('instruction') : '',
            'test_subject' => $request->input('subject') ? $request->input('subject') : "No Subject",
            'test_image' => $request->hasFile('imageInput') ? $randomName : null,
            'test_total_points' => $request->input('total_points'),
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ]);

        $question = questions::create([
            'testbank_id' => $testbank->id,
            'question_active' => 1,
            'item_question' => $request->input('criteria_1'),
            'question_image' => null,
            'choices_number' => 2,
            'question_answer' => 0,
            'question_point' => $request->input('criteria_point_1'),
            'option_1' => $request->input('criteria_2'),
            'option_2' => $request->input('criteria_2') ? $request->input('criteria_point_2') : 0,
            'option_3' => $request->input('criteria_3'),
            'option_4' => $request->input('criteria_3') ? $request->input('criteria_point_3') : 0,
            'option_5' => $request->input('criteria_4'),
            'option_6' => $request->input('criteria_4') ? $request->input('criteria_point_4') : 0,
            'option_7' => $request->input('criteria_5'),
            'option_8' => $request->input('criteria_5') ? $request->input('criteria_point_5') : 0,
        ]);


        return redirect('/essay');
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
        $question = questions::where('testbank_id', '=', $id)->first();
        return view('testbank.essay.essay_test-description', [
            'test' => $test,
            'question' => $question,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $test = testbank::find($id);
        $question = questions::where('testbank_id', '=', $id)->first();
        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        return view('testbank.essay.essay_edit', [
            'test' => $test,
            'question' => $question,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $testbank = testbank::find($id);
        if (is_null($testbank)) {
            abort(404); // User does not own the test
        }
        if ($testbank->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }
        // dd($request->input('imageChanged'));

        $validator = Validator::make($input, [
            'title' => 'required',
            'question' => 'required',
            'criteria_1' => 'required',
            'criteria_point_1' => 'required',
            'imageInput' => 'image|mimes:jpeg,png,jpg,gif', // Adjust the file types and size as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $randomName = "";
        if ($request->input('imageChanged')) {
            $testImage = $testbank->test_image;
            $imagePath = public_path('user_upload_images/' . $testImage);
            if (File::exists($imagePath)) {
                // Delete the image file
                File::delete($imagePath);

                // Optionally, you can also remove the image filename from the database or update the test record here
            }
            if ($request->hasFile('imageInput')) {
                do {
                    $randomName = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'tst.' . $request->file('imageInput')->getClientOriginalExtension();
                    $existingImage = testbank::where('test_image', $randomName)->first();
                } while ($existingImage);
                $request->file('imageInput')->move(public_path('user_upload_images'), $randomName);
            }
        }

        $dataToUpdate = [
            'user_id' => $request->input('id'),
            'test_type' => 'essay',
            'test_title' => $request->input('title'),
            'test_question' => $request->input('question'),
            'test_instruction' => $request->input('instruction'),
            'test_total_points' => $request->input('total_points'),
            'test_visible' => $request->has('share'),
            'test_active' => 1,
        ];

        
        if ($request->input('imageChanged')) {
            $dataToUpdate['test_image'] = $request->hasFile('imageInput') ? $randomName : null;
        }

        $testbank->update($dataToUpdate);


        $question = questions::where('testbank_id', '=', $id)->first();

        $question->update([
            'question_active' => 1,
            'item_question' => $request->input('criteria_1'),
            'question_image' => $request->input('question_image', null),
            'choices_number' => 2,
            'question_answer' => 0,
            'question_point' => $request->input('criteria_point_1'),
            'option_1' => $request->input('criteria_2'),
            'option_2' => $request->input('criteria_2') ? $request->input('criteria_point_2') : 0,
            'option_3' => $request->input('criteria_3'),
            'option_4' => $request->input('criteria_3') ? $request->input('criteria_point_3') : 0,
            'option_5' => $request->input('criteria_4'),
            'option_6' => $request->input('criteria_4') ? $request->input('criteria_point_4') : 0,
            'option_7' => $request->input('criteria_5'),
            'option_8' => $request->input('criteria_5') ? $request->input('criteria_point_5') : 0,
        ]);

        return redirect('/essay');
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

        $testImage = $test->test_image;
        $imagePath = public_path('user_upload_images/' . $testImage);
        if (File::exists($imagePath)) {
            // Delete the image file
            File::delete($imagePath);
            // Optionally, you can also remove the image filename from the database or update the question record here
        }
        
        questions::where('testbank_id', $id)->delete();
        $test->delete();

        return back();
    }
}
