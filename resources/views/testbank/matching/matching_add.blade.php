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
    <a href="/matching" class="add-test-button-anchor">
        <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
            <p>Back</p>
        </button>
    </a>
    <div class="searchbar-container">
    </div>
</div>
<form method="POST" action="/matching" class="test-body-content" id="add-form">
    @csrf
    <input type="hidden" name="id" value="{{auth()->user()->id;}}">
    <p class="text-input-label">Title<span class="red-asterisk"> *</span></p>
    <input type="text" class="textinput-base textarea-title text-input-background" name="title" required>
    @error('title')
    <div class="alert alert-dange red-asterisk">{{ $message }}</div>
    @enderror
    <p class="text-input-label label-margin-top">Description</p>
    <textarea class="textinput-base textarea-instruction text-input-background" name="description" required></textarea>
    @error('description')
    <div class="alert alert-danger red-asterisk">{{ $message }}</div>
    @enderror
    <p class="text-input-label label-margin-top">Subject</p>
    <div style="position: relative; width: 100%;">
        <select name="subject" class="textinput-base textarea-title text-input-background">
            @foreach($uniqueSubjects as $subject)
            <option value="{{$subject->subjectID}}">{{$subject->subjectName}}</option>
            @endforeach
        </select>
    </div>
    <p class="text-supported-format">Leave blank for no subject.</p>
    <div class="share-container">
        <input type="checkbox" class="share-checkbox" name="share">
        <p class="text-input-label">Share with other users</p>
    </div>
    <!-- <p class="text-input-label">Number of Choices/Options(Max. 10)</p>
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
    @error('no_item')
    <div class="alert alert-danger red-asterisk">There should at least be 1 item</div>
    @enderror -->
    <button class="save-test-button" id="save-quiz-button">Save Test</button>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</form>
<script>
    // const searchInput = document.getElementById('searchInput');
    // const suggestionsList = document.getElementById('suggestions');

    // // Preload the suggestions from the data attribute
    // const suggestions = JSON.parse(suggestionsList.getAttribute('data-unique-subjects'));
    // // const suggestions = ['Apple', 'Banana', 'Cherry', 'Date', 'Fig', 'Grape'];

    // // Create the list items for suggestions and hide them initially
    // const suggestionItems = suggestions.map(suggestion => {
    //     const li = document.createElement('li');
    //     li.textContent = suggestion;

    //     li.style.display = 'none'; // Hide initially
    //     suggestionsList.appendChild(li);

    //     return li;
    // });

    // function updateSuggestions() {
    //     const searchTerm = searchInput.value.toLowerCase();

    //     const filteredSuggestions = suggestions.filter(suggestion =>
    //         suggestion.toLowerCase().startsWith(searchTerm)
    //     );

    //     // Hide all suggestions by default
    //     suggestionItems.forEach(item => (item.style.display = 'none'));

    //     // Display filtered suggestions
    //     if (filteredSuggestions.length > 0) {
    //         filteredSuggestions.forEach(suggestion => {
    //             // Show only the suggestions that match the filter
    //             suggestionItems
    //                 .filter(item => item.textContent === suggestion)
    //                 .forEach(item => (item.style.display = 'block'));
    //         });
    //         suggestionsList.style.display = 'block'; // Show suggestions list
    //     } else {
    //         suggestionsList.style.display = 'none'; // Hide suggestions list
    //     }
    // }

    // // Delegate the click event to the suggestionsList and set the input value when a suggestion is clicked
    // suggestionsList.addEventListener('click', (event) => {
    //     const target = event.target;
    //     if (target.nodeName === 'LI') {
    //         searchInput.value = target.textContent;
    //         suggestionsList.style.display = 'none'; // Hide suggestions list
    //     }
    // });

    // let blurTimer; // Initialize a timer variable
    // // Add this event listener to hide suggestions when the input loses focus
    // searchInput.addEventListener('blur', () => {
    //     // Delay the blur event for 200 milliseconds (adjust as needed)
    //     blurTimer = setTimeout(() => {
    //         suggestionsList.style.display = 'none'; // Hide suggestions list
    //     }, 100); // 200 milliseconds delay
    // });

    // // Listen for both input and focus events on the search input
    // searchInput.addEventListener('input', updateSuggestions);
    // searchInput.addEventListener('focus', updateSuggestions);


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
                    <td><input class="mt-inputs item_answer" type="text" name="item_answer_${i}" required></td>
                    <td><input class="mt-inputs item_point" type="text" placeholder="0.00" name="item_point_${i}" required value="0"></td>
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