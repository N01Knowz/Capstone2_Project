<?php

namespace App\Http\Controllers;

use App\Models\analyticquizitemtags;
use Illuminate\Http\Request;
use App\Models\mttests;
use App\Models\ettests;
use App\Models\quizzes;
use App\Models\tftests;
// use App\Models\mtftests;
use App\Models\mtitems;
use App\Models\etitems;
use App\Models\quizitems;
use App\Models\tfitems;
use App\Models\quizItemsAnswers;
use App\Models\quizTestsTaken;
use App\Models\tfTestsTaken;
use App\Models\tfItemsAnswers;
use App\Models\matchingTestsTaken;
use App\Models\matchingItemsAnswers;
use App\Models\enumerationTestsTaken;
use App\Models\enumerationItemsAnswers;

class analyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('isStudent');
    }

    public function index()
    {
        $pageType = 'analytics';
        // hits and the total
        $tags = [
            'Realistic' => [0, 0],
            'Investigative' => [0, 0],
            'Artistic' => [0, 0],
            'Social' => [0, 0],
            'Enterprising' => [0, 0],
            'Conventional' => [0, 0],
        ];

        $mcq = quizItemsAnswers::leftJoin('quizitems', 'quiz_items_answers.itmID', '=', 'quizitems.itmID')->get();
        // leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID');
        $mcq->each(function ($mcq) {
            $tags = analyticquizitemtags::join('analytictags', 'analytictags.tagID', '=', 'analyticquizitemtags.tagID')
                ->where('analyticquizitemtags.itmID', $mcq->itmID)
                ->get();
            // dd($mcq->itmID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }


            $mcq->tags = $tagData;
        });

        foreach ($mcq as $item) {
            foreach ($item->tags as $key => $value) {
                if ($item->qzStudentItemAnswer == $item->itmAnswer) {
                    $tags[$key][0] += 1;
                }
                $tags[$key][1] += 1;
            }
        }
        // dd($tags);


        $tf = tfItemsAnswers::leftJoin('tfitems', 'tf_items_answers.itmID', '=', 'tfitems.itmID')->get();
        // dd($tf);
        // leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID');
        $tf->each(function ($tf) {
            $tags = analyticquizitemtags::join('analytictags', 'analytictags.tagID', '=', 'analyticquizitemtags.tagID')
                ->where('analyticquizitemtags.itmID', $tf->itmID)
                ->get();
            // dd($tf->itmID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }


            $tf->tags = $tagData;
        });

        foreach ($tf as $item) {
            foreach ($item->tags as $key => $value) {
                if ($item->qzStudentItemAnswer == $item->itmAnswer) {
                    $tags[$key][0] += 1;
                }
                $tags[$key][1] += 1;
            }
        }
        // dd($tags);

        
        // if ($type == 'mt') {
        //     $test = matchingTestsTaken::find($id);
        //     $mtID = $test->mtID;
        //     $mtitems = mtitems::where('mtID', $mtID)->get();
        //     $correctAnswers = [];
        //     foreach ($mtitems as $item) {
        //         $correctAnswers[$item->itmID] = [$item->itmAnswer, $item->itmPoints];
        //         $total += $item->itmPoints;
        //     }
        //     $studentAnswers = matchingItemsAnswers::where('mtttID', $id)->get();
        //     foreach ($studentAnswers as $answer) {
        //         if ($correctAnswers[$answer->itmID][0] == $answer->mtStudentItemAnswer) {
        //             $points += $correctAnswers[$answer->itmID][1];
        //         }
        //     }
        // }
        // if ($type == 'et') {
        //     $test = enumerationTestsTaken::find($id);
        //     $etID = $test->etID;
        //     $etitems = etitems::where('etID', $etID)->get();
        //     $correctAnswers = [];
        //     foreach ($etitems as $item) {
        //         $correctAnswers[$item->itmAnswer] = $item->itmIsCaseSensitive;
        //         $total += 1;
        //     }
        //     $studentAnswers = enumerationItemsAnswers::where('etttID', $id)->get();
        //     foreach ($studentAnswers as $answer) {
        //         foreach($correctAnswers as $key => $value){
        //             if($value) {
        //                 $key = strtolower($key);
        //                 $stdntAnswer = $answer->etStudentItemAnswer;
        //             }
        //             if($key == $stdntAnswer){
        //                 $points += 1;
        //             }
        //         }
        //     }
        // }

        return view('students.analytics.index', [
            'page' => $pageType,
            'tags' => $tags,
        ]);
    }
}
