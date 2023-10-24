<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\quizzes;
use App\Models\quizitems;
use App\Models\tftests;
use App\Models\tfitems;
use App\Models\mtftests;
use App\Models\mtfitems;
use App\Models\tmtests;
use App\Models\tmEssay;
use App\Models\tmQuizItems;
use App\Models\tmTfItems;
use App\Models\tmMtfItems;
use App\Models\tmMt;
use App\Models\tmEt;

class testPageController extends Controller
{
    public function testPage(string $test_type, string $id)
    {
        $test_tables = [
            'mcq' => [
                'table' => quizzes::class,
                'questions' => quizitems::class,
                'id' => 'qzID',
            ],
            'tf' => [
                'table' => tftests::class, // Model class name as a string
                'questions' => tfitems::class, // Model class name as a string
                'id' => 'tfID',
            ],
            'mtf' => [
                'table' => mtftests::class, // Model class name as a string
                'questions' => mtfitems::class, // Model class name as a string
                'id' => 'mtfID',
            ],
        ];
        if (in_array($test_type, ['mcq', 'tf', 'mtf'])) {
            $test = $test_tables[$test_type]['table']::find($id);
            $questions = $test_tables[$test_type]['questions']::where($test_tables[$test_type]['id'], $id)->get();
            return view('print', [
                'test_type' => $test_type,
                'questions' => $questions,
                'test' => $test,
            ]);
        } elseif ($test_type == 'tm') {
            $test = tmtests::find($id);
            $essay_questions = tmEssay::join('essays', 'tm_essays.essID', '=', 'essays.essID' )->where('tmID', $id)->get();
            $enumeration_tests = tmEt::join('ettests', 'tm_ets.etID', '=', 'ettests.etID' )->where('tmID', $id)->orderBy('ettests.etID', 'asc')->get();
            $enumeration_questions = tmEt::join('ettests', 'tm_ets.etID', '=', 'ettests.etID' )->join('etitems', 'ettests.etID', '=', 'etitems.etID')->where('tmID', $id)->orderBy('etitems.etID', 'asc')->get();
            $matching_tests = tmMt::join('mttests', 'tm_mts.mtID', '=', 'mttests.mtID' )->where('tmID', $id)->orderBy('mttests.mtID', 'asc')->get();
            $matching_questions = tmMt::join('mttests', 'tm_mts.mtID', '=', 'mttests.mtID' )->join('mtitems', 'mttests.mtID', '=', 'mtitems.mtID')->where('tmID', $id)->orderBy('mtitems.mtID', 'asc')->get();
            $mcq_questions = tmQuizItems::join('quizitems', 'tm_quiz_items.itmID', '=', 'quizitems.itmID' )->where('tmID', $id)->get();
            $tf_questions = tmTfItems::join('tfitems', 'tm_tf_items.itmID', '=', 'tfitems.itmID' )->where('tmID', $id)->get();
            $mtf_questions = tmMtfItems::join('mtfitems', 'tm_mtf_items.itmID', '=', 'mtfitems.itmID' )->where('tmID', $id)->get();
            // dd($essay_questions, $enumeration_questions, $matching_questions, $mcq_questions, $tf_questions, $mtf_questions);
            // dd($enumeration_tests);
            // dd($mcq_questions);
            return view('print', [
                'test' => $test,
                'test_type' => $test_type,
                'essay_questions' => $essay_questions,
                'enumeration_tests' => $enumeration_tests,
                'enumeration_questions' => $enumeration_questions,
                'matching_tests' => $matching_tests,
                'matching_questions' => $matching_questions,
                'mcq_questions' => $mcq_questions,
                'tf_questions' => $tf_questions,
                'mtf_questions' => $mtf_questions,
            ]);
        } else {
            abort(404);
        }
    }
}
