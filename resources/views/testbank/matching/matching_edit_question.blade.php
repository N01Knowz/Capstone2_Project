@extends('layouts.navigation')
@section('title', 'Matching')

@push('styles')
<link rel="stylesheet" href="/css/add_page.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/mcq_add_question.css">
<link rel="stylesheet" href="/css/mt_add_questions.css">
@endpush
@section('content')
<div class="test-body-header">
    <a href="/matching/{{$test->mtID}}" class="add-test-button-anchor">
        <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
            <p>Back</p>
        </button>
    </a>
    <div class="searchbar-container">
    </div>
</div>
<form method="POST" class="test-body-content">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{auth()->user()->id;}}">
    <p class="text-input-label">Title<span class="red-asterisk"> *</span></p>
    <input type="text" class="textinput-base textarea-title text-input-background" name="title" value="{{$test->mtTitle}}" readonly>
    @error('title')
    <div class="alert alert-dange red-asterisk">{{ $message }}</div>
    @enderror
    <p class="text-input-label label-margin-top">Description</p>
    <textarea class="textinput-base textarea-question text-input-background" name="instruction" readonly>{{$test->mtDescription}}</textarea>
    <table>
        <thead>
            <tr>
                <th>
                    <p class="test-profile-label">Item Text <span class="red-asterisk">*</span></p>
                </th>
                <th>
                    <p class="test-profile-label">Answer <span class="red-asterisk">*</span></p>
                </th>
                <th>
                    <p class="test-profile-label">Point(s) <span class="red-asterisk">*</span></p>
                </th>
            </tr>
        </thead>
        <tbody id="itemsContainer">
            <tr>
                <td><input class="mt-inputs item_text" type="text" name="item_text" value="{{$question->itmQuestion}}"></td>
                <td><input class="mt-inputs item_answer" type="text" name="item_answer" value="{{$question->itmAnswer}}"></td>
                <td><input class="mt-inputs item_point" type="text" placeholder="0.00" name="item_point" value="{{$question->itmPoints}}"></td>
            </tr>
        </tbody>
    </table>
    @error('instruction')
    <div class="alert alert-danger red-asterisk">{{ $message }}</div>
    @enderror
    <button class="save-test-button">Save Quiz Item</button>
</form>
@endsection