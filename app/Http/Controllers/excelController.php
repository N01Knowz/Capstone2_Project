<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class excelController extends Controller
{
    public function mcq()
    {
        $file = public_path('excel/MCQ_template.xlsx');

        return Response::download($file, 'MCQ_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function tf()
    {
        $file = public_path('excel/TF_template.xlsx');

        return Response::download($file, 'TF_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function mtf()
    {
        $file = public_path('excel/MTF_template.xlsx');

        return Response::download($file, 'MTF_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function matching()
    {
        $file = public_path('excel/Matching_template.xlsx');

        return Response::download($file, 'Matching_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
    public function enumeration()
    {
        $file = public_path('excel/Enumeration_template.xlsx');

        return Response::download($file, 'Enumeration_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
