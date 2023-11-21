<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\essays;
use App\Models\subjects;
use App\Models\analyticessaytags;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class essayTestbankController extends Controller
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
        $testsQuery = essays::leftJoin('subjects', 'essays.subjectID', '=', 'subjects.subjectID')
            ->where('essays.user_id', '=', $currentUserId);


        if (!empty($search)) {
            $testsQuery->where(function ($query) use ($search) {
                $query->where('essTitle', 'LIKE', "%$search%")
                    ->orWhere('essInstruction', 'LIKE', "%$search%");
            });
        }

        $tests = $testsQuery->orderBy('essID', 'desc')
            ->get();


        $tests->each(function ($tests) {
            $tags = analyticessaytags::join('analytictags', 'analytictags.tagID', '=', 'analyticessaytags.tagID')
                ->where('analyticessaytags.essID', $tests->essID)
                ->get();
            // dd($tests->essID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }


            $tests->tags = $tagData;
        });

        $testPage = 'essay';

        return view('testbank.essay.essay', [
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
        $testPage = 'essay';

        return view('testbank.essay.essay_add', [
            'uniqueSubjects' => $uniqueSubjects,
            'testPage' => $testPage
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
                $randomName = 'ess_' . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'tst.' . $request->file('imageInput')->getClientOriginalExtension();
                $existingImage = essays::where('essImage', $randomName)->first();
            } while ($existingImage);
            $request->file('imageInput')->move(public_path('user_upload_images/' . Auth::id()), $randomName);
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



        $testbank = essays::create([
            'user_id' => Auth::id(),
            'subjectID' => $subjectID,
            'essTitle' => $request->input('title'),
            'essQuestion' => $request->input('question'),
            'essInstruction' => $request->input('instruction') ? $request->input('instruction') : '',
            'essCriteria1' => $request->input('criteria_1'),
            'essScore1' => $request->input('criteria_point_1'),
            'essCriteria2' => $request->input('criteria_2'),
            'essScore2' => $request->input('criteria_2') ? $request->input('criteria_point_2') : 0,
            'essCriteria3' => $request->input('criteria_3'),
            'essScore3' => $request->input('criteria_3') ? $request->input('criteria_point_3') : 0,
            'essCriteria4' => $request->input('criteria_4'),
            'essScore4' => $request->input('criteria_4') ? $request->input('criteria_point_4') : 0,
            'essCriteria5' => $request->input('criteria_5'),
            'essScore5' => $request->input('criteria_5') ? $request->input('criteria_point_5') : 0,
            'essImage' => $request->hasFile('imageInput') ? $randomName : null,
            'essScoreTotal' => $request->input('total_points'),
            'essIsPublic' => $request->has('share'),
        ]);


        // $subject = subjects::where('subjectName', $request->input('subject'))
        //     ->where('user_id', Auth::id())
        //     ->first();

        // $subjectID = null;

        // if ($subject) {
        //     $subjectID = $subject->subjectID;
        // } else {
        //     if ($request->input('subject')) {
        //         // This block creates a subject with a custom name
        //         $createSubject = subjects::create([
        //             'subjectName' => $request->input('subject'),
        //             'user_id' => Auth::id(),
        //         ]);
        //         $subjectID = $createSubject->subjectID;
        //     } else {
        //         $checkNoSubject = subjects::where('subjectName', 'No Subject')
        //             ->where('user_id', Auth::id())
        //             ->first();
        //         if ($checkNoSubject) {
        //             $subjectID = $checkNoSubject->subjectID;
        //         } else {
        //             // Changed variable name from $createNoSubject to $createSubject
        //             $createNoSubject = subjects::create([
        //                 'subjectName' => 'No Subject',
        //                 'user_id' => Auth::id(),
        //             ]);
        //             $subjectID = $createNoSubject->subjectID;
        //         }
        //     }
        // }

        return redirect('/essay')->with('store_success', "Test added successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = essays::where('essID', $id)->first();
        $isShared = $test->essIsPublic;


        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id() && !$isShared) {
            abort(403); // User does not own the test
        }
        $testPage = 'essay';
        return view('testbank.essay.essay_test-description', [
            'test' => $test,
            'testPage' => $testPage
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $test = essays::leftJoin('subjects', 'essays.subjectID', '=', 'subjects.subjectID')->where('essID', $id)->select('essays.*', 'subjectName')->first();
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
        $testPage = 'essay';

        // dd($uniqueSubjects);
        return view('testbank.essay.essay_edit', [
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
        $testbank = essays::where('essID', $id)->first();
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
            $testImage = $testbank->essImage;

            $folderPath = public_path('user_upload_images/' . Auth::id());

            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }
            $imagePath = public_path('user_upload_images/' . Auth::id() . '/' . $testImage);
            if (File::exists($imagePath)) {
                // Delete the image file
                File::delete($imagePath);

                // Optionally, you can also remove the image filename from the database or update the test record here
            }
            if ($request->hasFile('imageInput')) {
                do {
                    $randomName = 'ess_' . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 30) . 'tst.' . $request->file('imageInput')->getClientOriginalExtension();
                    $existingImage = essays::where('essImage', $randomName)->first();
                } while ($existingImage);
                $request->file('imageInput')->move(public_path('user_upload_images/' . Auth::id()), $randomName);
            }
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

        $dataToUpdate = [
            'essTitle' => $request->input('title'),
            'essQuestion' => $request->input('question'),
            'essInstruction' => $request->input('instruction') ? $request->input('instruction') : '',
            'essScoreTotal' => $request->input('total_points'),
            'essIsPublic' => $request->has('share'),
            'essCriteria1' => $request->input('criteria_1'),
            'essScore1' => $request->input('criteria_point_1'),
            'essCriteria2' => $request->input('criteria_2'),
            'essScore2' => $request->input('criteria_2') ? $request->input('criteria_point_2') : 0,
            'essCriteria3' => $request->input('criteria_3'),
            'essScore3' => $request->input('criteria_3') ? $request->input('criteria_point_3') : 0,
            'essCriteria4' => $request->input('criteria_4'),
            'essScore4' => $request->input('criteria_4') ? $request->input('criteria_point_4') : 0,
            'essCriteria5' => $request->input('criteria_5'),
            'essScore5' => $request->input('criteria_5') ? $request->input('criteria_point_5') : 0,
            'subjectID' => $subjectID,
        ];


        if ($request->input('imageChanged')) {
            $dataToUpdate['essImage'] = $request->hasFile('imageInput') ? $randomName : null;
        }

        $testbank->update($dataToUpdate);

        return redirect('/essay')->with('update_success', "Test updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = essays::where('essID', $id)->first();
        // dd($test);

        if (is_null($test)) {
            abort(404); // User does not own the test
        }
        if ($test->user_id != Auth::id()) {
            abort(403); // User does not own the test
        }

        $testImage = $test->essImage;
        $folderPath = public_path('user_upload_images/' . Auth::id());

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $imagePath = public_path('user_upload_images/' . Auth::id() . '/' . $testImage);
        if (File::exists($imagePath)) {
            // Delete the image file
            File::delete($imagePath);
            // Optionally, you can also remove the image filename from the database or update the question record here
        }

        $test->delete();

        return back();
    }
}
