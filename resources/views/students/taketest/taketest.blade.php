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
            <p class="question-number">Question {{$questions->currentPage()}}.</p>
            <p><strong>{{$question->itmQuestion}}</strong></p>
            @if(!is_null($question->itmImage) || !empty($question->itmImage))
            <img src="/user_upload_images/{{$creatorID}}/{{$question->itmImage}}" style="width: 200px; height: 200px;">
            @endif
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
            <button type="button" data-page="{{$questions->currentPage() - 1}}" onclick="switchPage(event)" @if($questions->currentPage() == 1) disabled @endif class="next-previous-button">Previous</button>
            <button type="button" data-page="{{$questions->currentPage() + 1}}" onclick="switchPage(event)" @if($questions->currentPage() == $questions->lastPage()) disabled @endif class="next-previous-button">Next</button>
        </div>
    </div>
    @endforeach
    <input type="hidden" name="finish" value="0" id="finishInput">
    <button type="button" onclick="finishTest()" class="finish-test-button">Finish Attempt</button>
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
    <button type="submit" class="finish-test-button">Finish Attempt</button>
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
    <button type="submit" class="finish-test-button">Finish Attempt</button>
</form>
@endif

@if(in_array($type, ['mixed']))
<form method="get" id="pageForm" class="body-content">
    @if(in_array($tmType, ['quiz', 'tf']))
    @foreach($questions as $question)
    <div class="take-test-body">
        <div>
            <p class="question-number">Question {{$questions->currentPage()}}.</p>
            <p><strong>{{$tmItem->itmQuestion}}</strong></p>
            @if(!is_null($tmItem->itmImage) || !empty($tmItem->itmImage))
            <img src="/user_upload_images/{{$creatorID}}/{{$tmItem->itmImage}}" style="width: 200px; height: 200px;">
            @endif
            @for($i = 1; $i <= 4; $i++) @if($tmItem->{'itmOption' . $i})
                <div class="item-options">
                    <input type="radio" name="options" value="{{$i}}" form="pageForm" @if($studentAnswers[$tmItem->itmID] == $i) checked @endif>
                    <p>{{ chr(ord('A') + $i - 1) }}. {!! $tmItem->{'itmOption' . $i} !!}</p>
                </div>
                @endif
                @endfor
        </div>
    </div>
    @endforeach
    @endif
    @if(in_array($tmType, ['mt']))
    <div class="take-test-body">
        <div class="test-content">
            <p>Question {{$questions->currentPage()}}.</p>
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
                                <option value="{{$answer}}" @if($studentAnswers[$key] == $answer) selected @endif>{{$answer}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @if(in_array($tmType, ['et']))
    <div class="take-test-body">
        <div class="test-content">
            <p> <strong>{{$test->etDescription}}</strong></p>
            @foreach($tmItem as $question)
            <input class="et-answers" type="text" name="answers[]" value="{{$studentAnswers[$loop->index]}}">
            @endforeach
        </div>
    </div>
    @endif
    <div class="item-options-form">
        @if(in_array($tmType, ['quiz', 'tf']))
        <input type="hidden" name="questionID" value="{{$tmItem->itmID}}">
        @endif
        @if(in_array($tmType, ['mt']))
        <input type="hidden" name="questionID" value="{{$test->mtID}}">
        @endif
        @if(in_array($tmType, ['et']))
        <input type="hidden" name="questionID" value="{{$test->etID}}">
        @endif
        <input type="hidden" name="oldTmType" value="{{$tmType}}">
        <input type="hidden" name="page" value="" id="pageInput">
        <button type="button" data-page="{{$questions->currentPage() - 1}}" onclick="switchPage(event)" @if($questions->currentPage() == 1) disabled @endif class="next-previous-button">Previous</button>
        <button type="button" data-page="{{$questions->currentPage() + 1}}" onclick="switchPage(event)" @if($questions->currentPage() == $questions->lastPage()) disabled @endif class="next-previous-button">Next</button>
    </div>
    <input type="hidden" name="finish" value="0" id="finishInput">
    <button type="button" onclick="finishTest()" class="finish-test-button">Finish Attempt</button>
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
@endsection