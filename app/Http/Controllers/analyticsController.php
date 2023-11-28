<?php

namespace App\Http\Controllers;

use App\Models\analyticquizitemtags;
use App\Models\analytictfitemtags;
use App\Models\analyticmttags;
use App\Models\analyticettags;
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
use App\ModelmatchingtfItemsAnswers;
use App\Models\matchingTestsTaken;
use App\Models\matchingItemsAnswers;
use App\Models\enumerationTestsTaken;
use App\Models\enumerationItemsAnswers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $tagsHits = [
            'Realistic' => [0, 0],
            'Investigative' => [0, 0],
            'Artistic' => [0, 0],
            'Social' => [0, 0],
            'Enterprising' => [0, 0],
            'Conventional' => [0, 0],
        ];

        $mcq = quizItemsAnswers::leftJoin('quizitems', 'quiz_items_answers.itmID', '=', 'quizitems.itmID')
            ->leftJoin('quiz_tests_takens', 'quiz_items_answers.qzttID', '=', 'quiz_tests_takens.qzttid')
            ->where('quiz_tests_takens.user_id', Auth::id())->get();
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
                    $tagsHits[$key][0] += 1;
                }
                $tagsHits[$key][1] += 1;
            }
        }
        // dd($tagsHits);
        // dd($tags);


        $tf = tfItemsAnswers::leftJoin('tfitems', 'tf_items_answers.itmID', '=', 'tfitems.itmID')
            ->leftJoin('tf_tests_takens', 'tf_items_answers.tfttID', '=', 'tf_tests_takens.tfttid')
            ->where('tf_tests_takens.user_id', Auth::id())->get();
        // dd($tf);
        // leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID');
        $tf->each(function ($tf) {
            $tags = analytictfitemtags::join('analytictags', 'analytictags.tagID', '=', 'analytictfitemtags.tagID')
                ->where('analytictfitemtags.itmID', $tf->itmID)
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
                if ($item->tfStudentItemAnswer == $item->itmAnswer) {
                    $tagsHits[$key][0] += 1;
                }
                $tagsHits[$key][1] += 1;
            }
        }
        // dd($tf, $tagsHits);
        // dd($tags);

        $mt = matchingItemsAnswers::leftJoin('mtitems', 'matching_items_answers.itmID', '=', 'mtitems.itmID')
            ->leftJoin('matching_tests_takens', 'matching_items_answers.mtttID', '=', 'matching_tests_takens.mtttid')
            ->where('matching_tests_takens.user_id', Auth::id())->get();

        $mt->each(function ($mt) {
            $tags = analyticmttags::join('analytictags', 'analytictags.tagID', '=', 'analyticmttags.tagID')
                ->where('analyticmttags.mtID', $mt->mtID)
                ->get();
            // dd($mt->itmID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }


            $mt->tags = $tagData;
        });

        foreach ($mt as $item) {
            foreach ($item->tags as $key => $value) {
                if ($item->mtStudentItemAnswer == $item->itmAnswer) {
                    $tagsHits[$key][0] += 1;
                }
                $tagsHits[$key][1] += 1;
            }
        }
        $et = enumerationItemsAnswers::leftJoin('enumeration_tests_takens', 'enumeration_items_answers.etttID', '=', 'enumeration_tests_takens.etttid')
            ->where('enumeration_tests_takens.user_id', Auth::id())->get();


        $et->each(function ($et) {
            $tags = analyticettags::join('analytictags', 'analytictags.tagID', '=', 'analyticettags.tagID')
                ->where('analyticettags.etID', $et->etID)
                ->get();
            // dd($et->itmID);

            $tagData = [];
            foreach ($tags as $tag) {
                $tagData[$tag->tagName] = $tag->similarity;
            }


            $et->tags = $tagData;
        });
        $currentID = 0;
        foreach ($et as $item) {
            if ($currentID != $item->etttID) {
                $currentID = $item->etttID;
                $etID = $item->etID;
                $etitems = etitems::where('etID', $etID)->get();
                $correctAnswers = [];
                foreach ($etitems as $itemAnswer) {
                    $correctAnswers[$itemAnswer->itmAnswer] = $itemAnswer->itmIsCaseSensitive;
                }
                $studentAnswers = enumerationItemsAnswers::where('etttID', $currentID)->get();
                foreach ($studentAnswers as $answer) {
                    foreach ($correctAnswers as $key => $value) {
                        $stdntAnswer = $answer->etStudentItemAnswer;
                        $crctAnswer = $key;
                        if (!$value) {
                            $crctAnswer = strtolower($crctAnswer);
                            $stdntAnswer = strtolower($answer->etStudentItemAnswer);
                        }
                        if ($crctAnswer == $stdntAnswer) {
                            foreach ($item->tags as $keyTags => $valueTags) {
                                $tagsHits[$keyTags][0] += 1;
                            }
                        }
                    }
                    foreach ($item->tags as $keyTags => $valueTags) {
                        // echo($keyTags);
                        $tagsHits[$keyTags][1] += 1;
                    }
                    // dd($studentAnswers);
                }
            }
        }
        $mcqTaken = quizTestsTaken::leftJoin('quizzes', 'quiz_tests_takens.qzID', '=', 'quizzes.qzID')
            ->where('quiz_tests_takens.user_id', Auth::id())
            ->leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID')
            ->select(
                'quiz_tests_takens.qzttID as ttID',
                'quizzes.qzID as id',
                'quizzes.qzTitle as title',
                'quizzes.qzDescription as description',
                'quiz_tests_takens.created_at',
                'subjects.subjectName',
                DB::raw("'MCQ' as type"),
            );

        $tfTaken = tfTestsTaken::leftJoin('tftests', 'tf_tests_takens.tfID', '=', 'tftests.tfID')
            ->where('tf_tests_takens.user_id', Auth::id())
            ->leftJoin('subjects', 'tftests.subjectID', '=', 'subjects.subjectID')
            ->select(
                'tf_tests_takens.tfttID as ttID',
                'tftests.tfID as id',
                'tftests.tfTitle as title',
                'tftests.tfDescription as description',
                'tf_tests_takens.created_at',
                'subjects.subjectName',
                DB::raw("'TF' as type"),
            );

        $mtTaken = matchingTestsTaken::leftJoin('mttests', 'matching_tests_takens.mtID', '=', 'mttests.mtID')
            ->where('matching_tests_takens.user_id', Auth::id())
            ->leftJoin('subjects', 'mttests.subjectID', '=', 'subjects.subjectID')
            ->select(
                'matching_tests_takens.mtttID as ttID',
                'mttests.mtID as id',
                'mttests.mtTitle as title',
                'mttests.mtDescription as description',
                'matching_tests_takens.created_at',
                'subjects.subjectName',
                DB::raw("'MT' as type"),
            );

        $etTaken = enumerationTestsTaken::leftJoin('ettests', 'enumeration_tests_takens.etID', '=', 'ettests.etID')
            ->where('enumeration_tests_takens.user_id', Auth::id())
            ->leftJoin('subjects', 'ettests.subjectID', '=', 'subjects.subjectID')
            ->select(
                'enumeration_tests_takens.etttID as ttID',
                'ettests.etID as id',
                'ettests.etTitle as title',
                'ettests.etDescription as description',
                'enumeration_tests_takens.created_at',
                'subjects.subjectName',
                DB::raw("'ET' as type"),
            );

        $result = $mtTaken
            ->union($mcqTaken)
            ->union($tfTaken)
            ->union($etTaken)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($result as $resultItem) {
            // dd($resultItem);
            $id = $resultItem->ttID; // Replace with the actual ID field of your result item

            $type = $resultItem->type;
            $testResult = $this->getTestResultAndScore($type, $id, Auth::id());
            // dd($testResult);
            if ($testResult !== null) {
                $resultItem->score = $testResult['points'];
                $resultItem->totalScore = $testResult['total'];
            }
        }
        // dd($result);

        // dd($result);
        return view('students.analytics.index', [
            'page' => $pageType,
            'tags' => $tagsHits,
            'testsTaken' => $result,
        ]);
    }

    public function getTestResultAndScore($type, $id, $userId)
    {
        $result = [];
        $total = 0;
        $points = 0;

        switch (strtolower($type)) {
            case 'mcq':
                $test = quizTestsTaken::find($id);
                $mcqID = $test->qzID;
                $quizitems = quizitems::where('qzID', $mcqID)->get();
                $correctAnswers = [];
                foreach ($quizitems as $item) {
                    $correctAnswers[$item->itmID] = [$item->itmAnswer, $item->itmPoints];
                    $total += $item->itmPoints;
                }
                $studentAnswers = quizItemsAnswers::where('qzttID', $id)->get();
                foreach ($studentAnswers as $answer) {
                    if ($correctAnswers[$answer->itmID][0] == $answer->qzStudentItemAnswer) {
                        $points += $correctAnswers[$answer->itmID][1];
                    }
                }

                break;

            case 'tf':
                $test = tfTestsTaken::find($id);
                $mcqID = $test->tfID;
                $tfitems = tfitems::where('tfID', $mcqID)->get();
                $correctAnswers = [];
                foreach ($tfitems as $item) {
                    $correctAnswers[$item->itmID] = [$item->itmAnswer, $item->itmPoints];
                    $total += $item->itmPoints;
                }
                $studentAnswers = tfItemsAnswers::where('tfttID', $id)->get();
                foreach ($studentAnswers as $answer) {
                    if ($correctAnswers[$answer->itmID][0] == $answer->tfStudentItemAnswer) {
                        $points += $correctAnswers[$answer->itmID][1];
                    }
                }

                break;

            case 'mt':
                $test = matchingTestsTaken::find($id);
                $mtID = $test->mtID;
                $mtitems = mtitems::where('mtID', $mtID)->get();
                $correctAnswers = [];
                foreach ($mtitems as $item) {
                    $correctAnswers[$item->itmID] = [$item->itmAnswer, $item->itmPoints];
                    $total += $item->itmPoints;
                }
                $studentAnswers = matchingItemsAnswers::where('mtttID', $id)->get();
                foreach ($studentAnswers as $answer) {
                    if ($correctAnswers[$answer->itmID][0] == $answer->mtStudentItemAnswer) {
                        $points += $correctAnswers[$answer->itmID][1];
                    }
                }

                break;

            case 'et':
                $test = enumerationTestsTaken::find($id);
                $etID = $test->etID;
                $etitems = etitems::where('etID', $etID)->get();
                $correctAnswers = [];
                foreach ($etitems as $item) {
                    $correctAnswers[$item->itmAnswer] = $item->itmIsCaseSensitive;
                    $total += 1;
                }
                $studentAnswers = enumerationItemsAnswers::where('etttID', $id)->get();
                foreach ($studentAnswers as $answer) {
                    foreach ($correctAnswers as $key => $value) {
                        $stdntAnswer = $answer->etStudentItemAnswer;
                        $crctAnswer = $key;
                        if (!$value) {
                            $crctAnswer = strtolower($crctAnswer);
                            $stdntAnswer = strtolower($answer->etStudentItemAnswer);
                        }
                        if ($crctAnswer == $stdntAnswer) {
                            $points += 1;
                        }
                    }
                }

                break;

            default:
                // Handle unknown test type
                return null;
        }

        $result['total'] = $total;
        $result['points'] = $points;

        return $result;
    }

    public function riasec()
    {
        $pageType = 'analytics';
        return view('students.analytics.riasec', [
            'page' => $pageType,
        ]);
    }
}