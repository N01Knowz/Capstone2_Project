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
<form method="POST" class="test-body-content" id="add-form">
    @csrf
    <input type="hidden" name="id" value="{{auth()->user()->id;}}">
    <p class="text-input-label">Title<span class="red-asterisk"> *</span></p>
    <input type="text" class="textinput-base textarea-title text-input-background" name="title" value="{{$test->mtTitle}}" readonly>
    @error('title')
    <div class="alert alert-dange red-asterisk">{{ $message }}</div>
    @enderror
    <p class="text-input-label label-margin-top">Description<span class="red-asterisk"> *</span></p>
    <textarea class="textinput-base textarea-question text-input-background" name="description" readonly>{{$test->mtDescription}}</textarea>
    @error('description')
    <div class="alert alert-danger red-asterisk">{{ $message }}</div>
    @enderror
    <p class="text-input-label">Number of Choices/Options(Max. 10)</p>
    <input type="number" class="text-input-choices" value="{{ old('numChoicesInput') ? old('numChoicesInput') : 1 }}" id="numChoicesInput" name="numChoicesInput">
    <p class="mt-note">Note: you may add extra choices (distractors) by adding an answer with a blank item text. Blank item test will not be added to the list of answerable items (including points).</p>
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

        </tbody>
    </table>
    @error('item_text_1')
    <div class="alert alert-danger red-asterisk">There should at least be 1 item</div>
    @enderror
    <button class="save-test-button" id="save-quiz-button">Save Quiz Item</button>
</form>
<script>
    var save_button = document.getElementById("save-quiz-button");

    // Add a click event listener to the button
    save_button.addEventListener("click", function() {
        // Disable the button
        save_button.disabled = true;
        document.getElementById("add-form").submit();
    });


    document.addEventListener("DOMContentLoaded", function() {
        const numChoicesInput = document.getElementById("numChoicesInput");
        const itemsContainer = document.getElementById("itemsContainer");

        numChoicesInput.addEventListener("input", function() {
            updateRows();
        });

        // Initial execution when the page loads
        const initialNumChoices = parseInt(numChoicesInput.value);
        putRows(initialNumChoices);
    });

    function putRows(numChoices) {
        const itemsContainer = document.getElementById("itemsContainer");

        if (!isNaN(numChoices) && numChoices >= 1 && numChoices <= 10) {
            for (let i = 1; i <= numChoices; i++) {
                const row = `
                <tr>
                    <td><input class="mt-inputs item_text" type="text" name="item_text_${i}"></td>
                    <td><input class="mt-inputs item_answer" type="text" name="item_answer_${i}"></td>
                    <td><input class="mt-inputs item_point" type="text" placeholder="0.00" name="item_point_${i}" value="1.00"></td>
                </tr>
            `;
                itemsContainer.innerHTML += row;
            }
        }
    }

    function updateRows() {
        const numChoicesInput = document.getElementById("numChoicesInput");
        const numChoices = parseInt(numChoicesInput.value);
        const itemsContainer = document.getElementById("itemsContainer");

        // Clear existing options
        itemsContainer.innerHTML = "";

        // Create components based on the updated numChoices value
        putRows(numChoices);
    }
</script>
@endsection