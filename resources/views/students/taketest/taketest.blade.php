@extends('layouts.student_navigation')
@section('title', 'Take Test')

@push('styles')
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/taketest.css">
@endpush

@section('modal-contents')
@endsection
@section('content')
@if(in_array($type, ['mcq', 'tf']))
<form method="get" id="pageForm" class="body-content">
    @foreach($questions as $question)
    <div class="take-test-body">
        <div>
            <p>Question {{$questions->currentPage()}}.</p>
            <p><strong>{{$question->itmQuestion}}</strong></p>
            @for($i = 1; $i <= 4; $i++) @if($question->{'itmOption' . $i})
                <div class="item-options">
                    <input type="radio" name="options" value="{{$i}}" form="pageForm" @if($studentAnswers[$question->itmID] == $i) checked @endif>
                    <p>{{ chr(ord('A') + $i - 1) }}. {!! $question->{'itmOption' . $i} !!}</p>
                </div>
                @endif
                @endfor
        </div>
        <div class="item-options-form">
            <input type="hidden" name="questionID" value="{{$question->itmID}}">
            <input type="hidden" name="page" value="" id="pageInput">
            <button type="button" data-page="{{$questions->currentPage() - 1}}" onclick="switchPage(event)" @if($questions->currentPage() == 1) disabled @endif>Previous</button>
            <button type="button" data-page="{{$questions->currentPage() + 1}}" onclick="switchPage(event)" @if($questions->currentPage() == $questions->lastPage()) disabled @endif>Next</button>
        </div>
    </div>
    @endforeach
    <input type="hidden" name="finish" value="0" id="finishInput">
    <button type="button" onclick="finishTest()" class="finish-test-button">Finish Test</button>
</form>
<script>
    function switchPage(event) {
        var element = event.target;

        var pageValue = element.getAttribute('data-page');
        const pageInput = document.getElementById('pageInput');
        pageInput.value = pageValue;

        const pageForm = document.getElementById('pageForm');
        pageForm.submit();
    }

    function finishTest() {
        var finishInput = document.getElementById('finishInput');
        finishInput.value = 1;
        const pageForm = document.getElementById('pageForm');
        pageForm.submit();
    }
</script>
@endif

@if(in_array($type, ['mt']))
<form method="GET" action="/taketest/{{$type}}/{{$test->mtID}}/finish" id="pageForm" class="body-content">
    <div class="take-test-body">
        <div class="test-content">
            <p> <strong>{{$test->mtDescription}}</strong> </p>
            <table>
                <tbody>
                    @foreach($itemQuestion as $key => $value)
                    <tr>
                        <td class="test-item-cell">
                            <p>{{$value}}</p>
                        </td>
                        <td>
                            <select name="selects[{{ $key }}]">
                                @foreach($itemAnswers as $answer)
                                <option value="{{$answer}}">{{$answer}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <button type="submit" class="finish-test-button">Finish Test</button>
</form>
@endif
@if(in_array($type, ['et']))
<form method="GET" action="/taketest/{{$type}}/{{$test->etID}}/finish" id="pageForm" class="body-content">
    <div class="take-test-body">
        <div class="test-content">
            <p> <strong>{{$test->etDescription}}</strong></p>
            @foreach($questions as $question)
            <input class="et-answers" type="text" name="answers[]">
            @endforeach
        </div>
    </div>
    <button type="submit" class="finish-test-button">Finish Test</button>
</form>
@endif
@endsection