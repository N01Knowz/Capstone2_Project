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
    <form method="POST" class="test-add-question-container" enctype="multipart/form-data" id="add-form">
        @csrf
        <p class="text-input-label">Item Question <span class="red-asterisk">*</span></p>
        <textarea class="text-input" name="item_question"></textarea>
        @error('item_question')
        <div class="alert alert-danger red-asterisk">{{ $message }}</div>
        @enderror
        <p class="text-input-label">Attach an Image(Optional)</p>
        <div>
            <input type="text" class="text-input-attach-image" name="question_image" id="photoName" readonly>
            <input type="file" id="imageInput" style="display:none;" name="imageInput" accept="image/*">
            <button class="text-input-image-button" type="button" id="clearButton" style="display: none;">Clear</button>
            <button class="text-input-image-button" type="button" id="browseButton">Browse</button>
        </div>
        <p class="text-supported-format">Supported formats: .jpg, .png, .gif</p>
        <div id="imageContainer" style="display: none;" class="image-preview-container">
            <img id="selectedImage" src="#" alt="Selected Image" class="image-preview">
        </div>
        <p class="text-input-label">Number of Choices/Options(Max. 10)</p>
        <input type="number" class="text-input-choices" id="numChoicesInput" value="2" name="number_of_choices">
        @error('number_of_choices')
        <div class="alert alert-danger red-asterisk">{{ $message }}</div>
        @enderror
        <div id="optionsContainer">
            <p class="text-input-label">Option 1</p>
            <textarea class="summernote" name="option_1"></textarea>
            @error('option_1')
            <div class="alert alert-danger red-asterisk">{{ $message }}</div>
            @enderror
            <p class="text-input-label">Option 2</p>
            <textarea class="summernote" name="option_2"></textarea>
        </div>
        <div class="item-answer-points-container">
            <div class="correct-answer-container">
                <p class="text-input-label">Answer <span class="red-asterisk">*</span></p>
                <select class="select-option" id="option-select" name="question_answer">
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                </select>
            </div>
            <div class="item-point-container">
                <p class="text-input-label">Item Point(s) <span class="red-asterisk">*</span></p>
                <input type="text" class="point-input" value="1.00" name="question_point">
            </div>
        </div>
        <button class="save-test-button" id="save-quiz-button">Save Quiz Item</button>
    </form>
</div>
<script>
    var save_button = document.getElementById("save-quiz-button");

    // Add a click event listener to the button
    save_button.addEventListener("click", function() {
        // Disable the button
        save_button.disabled = true;
        document.getElementById("add-form").submit();
    });

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
    document.addEventListener("DOMContentLoaded", function() {

        // Get references to the text input, button, and file input
        const photoNameInput = document.getElementById('photoName');
        const choosePhotoButton = document.getElementById('browseButton');
        const imageInput = document.getElementById('imageInput');
        const selectedImage = document.getElementById('selectedImage');
        const imageContainer = document.getElementById('imageContainer');
        const clearButton = document.getElementById('clearButton');

        clearButton.addEventListener('click', () => {
            photoNameInput.value = '';
            imageInput.value = '';
            selectedImage.src = '';
            imageContainer.style.display = 'none';
            clearButton.style.display = 'none';
            choosePhotoButton.style.display = 'inline-block';
        });


        // Add a click event listener to the button
        choosePhotoButton.addEventListener('click', () => {
            // Trigger a click event on the file input
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

        numChoicesInput.addEventListener("input", function() {
            const numChoices = parseInt(numChoicesInput.value);

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