@extends('layouts.navigation')
@section('title', 'Multiple Choices')

@push('styles')
<link rel="stylesheet" href="/css/add_page.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/navigator.css">
@endpush
@section('content')

<div class="test-body-header">
    <a href="/mcq" class="add-test-button-anchor">
        <button class="add-test-button"><img src="/images/back-icon.png" class="add-test-button-icon">
            <p>Back</p>
        </button>
    </a>
    <div class="searchbar-container">
    </div>
</div>
<form method="POST" action="/mcq/{{$test->qzID}}" class="test-body-content">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{auth()->user()->id;}}">
    <p class="text-input-label">Title<span class="red-asterisk"> *</span></p>
    <input type="text" class="textinput-base textarea-title text-input-background" name="title" value="{{$test->qzTitle}}">
    @error('title')
    <div class="alert alert-dange red-asterisk">{{ $message }}</div>
    @enderror
    <p class="text-input-label label-margin-top">Description<span class="red-asterisk"> *</span></p>
    <textarea class="textinput-base textarea-question text-input-background" name="description">{{$test->qzDescription}}</textarea>
    @error('description')
    <div class="alert alert-danger red-asterisk">{{ $message }}</div>
    @enderror
    <p class="text-input-label label-margin-top">Subject</p>
    <div style="position: relative; width: 100%;">
        <!-- <input type="text" id="searchInput" class="textinput-base textarea-title text-input-background" name="subject" value="{{$test->subjectName}}">
        <ul id="suggestions" style="position: absolute; top: 100%; left: 0; z-index: 1;" data-unique-subjects="{{ json_encode($uniqueSubjects) }}"></ul> -->
        <select name="subject" class="textinput-base textarea-title text-input-background">
            @foreach($uniqueSubjects as $subject)
            <option value="{{$subject->subjectID}}" @if($subject->subjectID == $test->subjectID) selected @endif>{{$subject->subjectName}}</option>
            @endforeach
        </select>
    </div>
    <!-- <div class="criteria-point-container">
                    <div class="criteria-point-sub-container">
                        <p class="text-input-label">Criteria<span class="red-asterisk"> *</span></p>
                        <input type="text" class="text-input-background critera-point-input">
                    </div>
                    <div class="criteria-point-sub-container">
                        <div>
                            <p class="text-input-label">Point(s)</p>
                            <input type="text" class="text-input-background critera-point-input">
                        </div>
                    </div>
                </div> -->
    <div class="add-test-button-anchor">
        <button class="save-test-button" style="margin-top:1em;">Save Changes</button>
    </div>
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
</script>
@endsection