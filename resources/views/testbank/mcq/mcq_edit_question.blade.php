@extends('layouts.navigation')
@section('title', 'Multiple Choices' )

@push('styles') <!-- include libraries(jQuery, bootstrap) -->
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
    <form method="POST" class="test-add-question-container" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <p class="text-input-label">Item Question <span class="red-asterisk">*</span></p>
        <textarea class="text-input" name="item_question">{{$question->itmQuestion}}</textarea>
        @error('item_question')
        <div class="alert alert-danger red-asterisk">{{ $message }}</div>
        @enderror
        <p class="text-input-label">Attach an Image(Optional)</p>
        <div>
            <input type="text" class="text-input-attach-image" name="question_image" id="photoName" value="{{$question->itmImage}}" readonly>
            <input type="file" id="imageInput" style="display: none;" name="imageInput" value="{{ $question->itmImage }}" accept="image/*">
            <input type="hidden" name="imageChanged" id="imageChanged" value="0">
            <button class="text-input-image-button" type="button" id="clearButton" @unless(!is_null($question->itmImage))
                style="display: none;"
                @endunless>Clear</button>
            <button class="text-input-image-button" type="button" id="browseButton" @unless(is_null($question->itmImage))
                style="display: none;"
                @endunless>Browse</button>
        </div>
        <p class="text-supported-format">Supported formats: .jpg, .png, .gif</p>
        <div id="imageContainer" @if(is_null($question->itmImage) || empty($question->itmImage))
            style="display: none;"
            @else
            style="display: flex;"
            @endif class="image-preview-container">
            <img id="selectedImage" src="/user_upload_images/{{auth()->user()->id;}}/{{$question->itmImage}}" alt="Selected Image" class="image-preview">
        </div>
        <p class="text-input-label">Number of Choices/Options(Max. 10)</p>
        <input type="number" class="text-input-choices" id="numChoicesInput" value="{{$question->choices_number}}" name="number_of_choices">
        @error('number_of_choices')
        <div class="alert alert-danger red-asterisk">{{ $message }}</div>
        @enderror
        <div id="optionsContainer">
            @for($i = 1; $i <= $question->choices_number; $i++)
                <p class="text-input-label">Option {{$i}}</p>
                <textarea class="summernote" name="option_{{$i}}">{{data_get($question, 'itmOption' . $i )}}</textarea>
                @error('option_1')
                <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                @enderror
                @endfor
        </div>
        <div class="item-answer-points-container">
            <div class="correct-answer-container">
                <p class="text-input-label">Answer <span class="red-asterisk">*</span></p>
                <select class="select-option" id="option-select" name="question_answer">
                    @for($i = 1; $i <= $question->choices_number; $i++)
                        <option value="{{$i}}" @if($i==$question->itmAnswer) selected @endif>Option {{$i}}</option>
                        @endfor
                </select>
            </div>
            <div class="item-point-container">
                <p class="text-input-label">Item Point(s) <span class="red-asterisk">*</span></p>
                <input type="text" class="point-input" value="{{$question->itmPoints}}" name="question_point">
            </div>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <button class="save-test-button">Save Quiz Item</button>
    </form>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        $('.summernote').summernote({
            placeholder: 'Enter Option...',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // JavaScript Code

        // Get references to the text input, button, and file input
        const photoNameInput = document.getElementById('photoName');
        const choosePhotoButton = document.getElementById('browseButton');
        const imageInput = document.getElementById('imageInput');
        const selectedImage = document.getElementById('selectedImage');
        const imageContainer = document.getElementById('imageContainer');
        const imageChangedInput = document.getElementById('imageChanged');
        const clearButton = document.getElementById('clearButton');

        clearButton.addEventListener('click', () => {
            photoNameInput.value = '';
            imageInput.value = '';
            selectedImage.src = '';
            imageContainer.style.display = 'none';
            imageChangedInput.value = '1';
            clearButton.style.display = 'none';
            choosePhotoButton.style.display = 'inline-block';
        });

        // Add a click event listener to the button
        choosePhotoButton.addEventListener('click', () => {
            // Trigger a click event on the file input
            imageChangedInput.value = '1';
            imageInput.click();
        });

        // Listen for changes in the file input
        imageInput.addEventListener('change', () => {
            const selectedFile = imageInput.files[0];

            // Check if a file was selected
            if (selectedFile) {
                // Check the file extension
                const fileExtension = selectedFile.name.split('.').pop().toLowerCase();

                if (['gif', 'png', 'jpeg', 'jpg'].includes(fileExtension)) {
                    // Update the text input with the selected file's name
                    photoNameInput.value = selectedFile.name;

                    // Display the selected image
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        selectedImage.src = e.target.result;
                        imageContainer.style.display = 'flex';
                    };
                    reader.readAsDataURL(selectedFile);

                    clearButton.style.display = 'inline-block';
                    choosePhotoButton.style.display = 'none';

                    // Set the imageChanged input to 1 when a new file is selected
                } else {
                    // Display an error message or take appropriate action
                    alert('Please select a GIF, PNG, or JPEG image.');
                    imageInput.value = ''; // Clear the file input
                }
            } else {
                // Clear the text input and hide the image container if no file is selected
                photoNameInput.value = '';
                imageInput.value = '';
                selectedImage.src = '';
                imageContainer.style.display = 'none';
            }
        });


        const numChoicesInput = document.getElementById("numChoicesInput");
        const optionsContainer = document.getElementById("optionsContainer");
        const optionSelect = document.getElementById("option-select");

        const textareaValues = {};

        numChoicesInput.addEventListener("input", function() {
            const numChoices = parseInt(numChoicesInput.value);

            optionsContainer.querySelectorAll(".summernote").forEach((textarea, index) => {
                textareaValues[`option_${index + 1}`] = textarea.value;
            });

            // Clear existing options
            optionsContainer.innerHTML = "";
            optionSelect.innerHTML = "";

            // Validate input and create components
            if (!isNaN(numChoices) && numChoices >= 1 && numChoices <= 10) {
                for (let i = 1; i <= numChoices; i++) {
                    const optionContainer = document.createElement("div");
                    optionContainer.className = "option-container"; // You can style this container if needed

                    const label = document.createElement("p");
                    label.className = "text-input-label";
                    label.textContent = `Option ${i}`;

                    const textarea = document.createElement("textarea");
                    textarea.className = "summernote";
                    textarea.name = `option_${i}`;
                    textarea.value = textareaValues[`option_${i}`] || ""; // Restore old value if available

                    const optionElement = document.createElement("option");
                    optionElement.textContent = `Option ${i}`;
                    optionElement.value = `${i}`;
                    optionSelect.appendChild(optionElement);


                    optionContainer.appendChild(label);
                    optionContainer.appendChild(textarea);
                    optionsContainer.appendChild(optionContainer);
                    $(textarea).summernote();
                }
            }
        });
    });
</script>
@endsection