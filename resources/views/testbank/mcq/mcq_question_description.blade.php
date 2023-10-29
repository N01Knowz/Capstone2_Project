@extends('layouts.navigation')
@section('title', 'Multiple Choices')

@push('styles')
<!-- include libraries(jQuery, bootstrap) -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<link rel="stylesheet" href="/css/add_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/mcq_add_question.css">
@endpush
@section('content')


<div class="test-body-header">
    <a href="/mcq/{{$test->qzID}}" class="add-test-button-anchor">
        <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
            <p>Back</p>
        </button>
    </a>
    <div class="searchbar-container">
    </div>
</div>
<div class="test-body-content">
    <div class="test-profile-container">
        <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->qzTitle}}</span></p>
        <p class="test-profile-label">Test description: <span class="test-profile-value">{{$test->qzDescription}}</span></p>
        <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->qzTotal}}</span></p>
    </div>
    <form method="GET" action="/mcq/{{$test->qzID}}/{{$question->itmID}}/edit" class="test-add-question-container">
        <p class="text-input-label">Item Question <span class="red-asterisk">*</span></p>
        <textarea class="text-input" name="item_question" readonly>{{$question->itmQuestion}}</textarea>
        @error('item_question')
        <div class="alert alert-danger red-asterisk">{{ $message }}</div>
        @enderror
        <p class="text-input-label">Attach an Image(Optional)</p>
        <div>
            <input type="text" class="text-input-attach-image" name="itmImage" id="photoName" value="{{$question->itmImage}}" readonly>
            <button class="text-input-image-button" type="button" id="browseButton" disabled>Browse</button>
        </div>
        <p class="text-supported-format">Supported formats: .jpg, .png, .gif</p>
        <div id="imageContainer" @if(is_null($question->itmImage) || empty($question->itmImage))
            style="display: none;"
            @else
            style="display: flex;"
            @endif class="image-preview-container">
            <img id="selectedImage" src="/user_upload_images/{{$question->itmImage}}" alt="Selected Image" class="image-preview">
        </div>
        <p class="text-input-label">Number of Choices/Options(Max. 10)</p>
        <input type="text" class="text-input-choices" id="numChoicesInput" value="{{$question->choices_number}}" name="number_of_choices" readonly>
        @error('number_of_choices')
        <div class="alert alert-danger red-asterisk">{{ $message }}</div>
        @enderror
        @for($i = 1; $i <= $question->choices_number; $i++)
            <div id="optionsContainer">
                <p class="text-input-label">Option {{$i}}</p>
                <textarea class="summernote" readonly disabled name="option_{{$i}}" id="option_{{$i}}">{{data_get($question, 'itmOption' . $i )}}</textarea>
                @error('option_1')
                <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                @enderror
            </div>
            @endfor
            <div class="item-answer-points-container">
                <div class="correct-answer-container">
                    <p class="text-input-label">Answer <span class="red-asterisk">*</span></p>
                    <input class="select-option" type="text" readonly value="Option {{$question->itmAnswer}}">
                    <!-- <select class="select-option" id="option-select" name="question_answer" readonly>
                            @for($i = 1; $i <= $question->choices_number; $i++)
                                <option value="{{$i}}" @if($i == $question->question_answer) selected @endif>Option {{$i}}</option>
                            @endfor -->
                    </select>
                </div>
                <div class="item-point-container">
                    <p class="text-input-label">Item Point(s) <span class="red-asterisk">*</span></p>
                    <input type="text" class="point-input" value="{{$question->itmPoints}}" name="question_point" readonly>
                </div>
            </div>
            <button class="save-test-button">Edit Quiz Item</button>
    </form>
</div>
<script>
    $('.summernote').summernote({
        placeholder: 'Enter Option...',
        tabsize: 2,
        height: 100,
        toolbar: [

        ]
    });
    var numChoicesInput = parseInt(document.getElementById('numChoicesInput').value);

    for (var i = 1; i <= numChoicesInput; i++) {
        $('#option_' + i).summernote('disable');
    }
</script>
@endsection