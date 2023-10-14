<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\testbank;
use App\Models\questions;

class testPageController extends Controller
{
    public function testPage(string $id) {
        $test = testbank::find($id);
        $questions = questions::where('testbank_id', $id)->get();
        
        return view('print', [
            'questions' => $questions,
            'test' => $test,
        ]);
    }
}
