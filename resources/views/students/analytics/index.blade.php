@extends('layouts.student_navigation')
@section('title', 'Analytics')

@push('styles')
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/analyticsIndex.css">
@endpush
@section('modal-contents')
@endsection

@section('content')
<div class="body-content">
    <div class="riasec-container">
        <div>
            <p class="user-riasec-score">Your RIASEC Scores</p>
            <table class="table-bargraph">
                <tbody>
                    <tr>
                        <td class="tag-label">Realistic </td>
                        <td>
                            <div style="background-color: #ff8051; width: {{ ($tags['Realistic'][1] != 0) ? number_format($tags['Realistic'][0] / $tags['Realistic'][1] * 100, 2) . '%' : '0%' }};"><p class="tags-percentage">{{ ($tags['Realistic'][1] != 0) ? number_format($tags['Realistic'][0] / $tags['Realistic'][1] * 100, 2) . '%' : '0%' }}</p></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tag-label">Investigative </td>
                        <td>
                            <div style="background-color: #88cffa; width: {{ ($tags['Investigative'][1] != 0) ? number_format($tags['Investigative'][0] / $tags['Investigative'][1] * 100, 2) . '%' : '0%' }};"><p class="tags-percentage">{{ ($tags['Investigative'][1] != 0) ? number_format($tags['Investigative'][0] / $tags['Investigative'][1] * 100, 2) . '%' : '0%' }}</p></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tag-label">Artistic </td>
                        <td>
                            <div style="background-color: #68ceac; width: {{ ($tags['Artistic'][1] != 0) ? number_format($tags['Artistic'][0] / $tags['Artistic'][1] * 100, 2) . '%' : '0%' }};"><p class="tags-percentage">{{ ($tags['Artistic'][1] != 0) ? number_format($tags['Artistic'][0] / $tags['Artistic'][1] * 100, 2) . '%' : '0%' }}</p></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tag-label">Social </td>
                        <td>
                            <div style="background-color: #ef83ef; width: {{ ($tags['Social'][1] != 0) ? number_format($tags['Social'][0] / $tags['Social'][1] * 100, 2) . '%' : '0%' }};"><p class="tags-percentage">{{ ($tags['Social'][1] != 0) ? number_format($tags['Social'][0] / $tags['Social'][1] * 100, 2) . '%' : '0%' }}</p></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tag-label">Enterprising </td>
                        <td>
                            <div style="background-color: #f4a561; width: {{ ($tags['Enterprising'][1] != 0) ? number_format($tags['Enterprising'][0] / $tags['Enterprising'][1] * 100, 2) . '%' : '0%' }};"><p class="tags-percentage">{{ ($tags['Enterprising'][1] != 0) ? number_format($tags['Enterprising'][0] / $tags['Enterprising'][1] * 100, 2) . '%' : '0%' }}</p></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tag-label">Conventional </td>
                        <td>
                            <div style="background-color: #6b8e23; width: {{ ($tags['Conventional'][1] != 0) ? number_format($tags['Conventional'][0] / $tags['Conventional'][1] * 100, 2) . '%' : '0%' }};"><p class="tags-percentage">{{ ($tags['Conventional'][1] != 0) ? number_format($tags['Conventional'][0] / $tags['Conventional'][1] * 100, 2) . '%' : '0%' }}</p></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="riasec-description">
            <p class="riasec-question-label">What is RIASEC?</p>
            <p>The RIASEC model is a career choice theory developed by John Holland. It's based on six interest types: Realistic, Investigative, Artistic, Social, Enterprising, and Conventional.
            </p>
            <a href="/analytics/riasec">
                <p>Learn more about RIASEC and its corresponding careers here.</p>
            </a>
        </div>
    </div>
    <div class="test-taken-container">
        <strong>Tests Taken</strong>
        <table class="test-taken-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Subject</th>
                    <th>Test Type</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testsTaken as $test)
                <tr>
                    <td>{{$test->title}}</td>
                    <td>{{$test->description}}</td>
                    <td>{{$test->subjectName}}</td>
                    <td>{{$test->type}}</td>
                    <td>{{$test->score}}/{{$test->totalScore}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@if(session('success'))
<script>
    var message = "{{ session('success') }}";
    alert(message);
</script>
@endif