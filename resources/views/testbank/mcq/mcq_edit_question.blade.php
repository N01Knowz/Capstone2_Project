<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple Choices</title>
    <link rel="icon" href="/images/logo.png">
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
</head>

<body>
    <div class="test-container">
        <div class="navigator">
            <div id="logo-container">
                <img src="/images/logo.png" id="logo">
                <p>Test Bank</p>
            </div>
            <div class="test-type" id="essay-test" data-icon-id="essay-icon">
                <a class="test-link" href="/essay" onclick="chosenTestType('essay-test')">
                    <img src="/images/essay-icon-light.png" class="test-icon" data-icon-light="/images/essay-icon-light.png" data-icon-dark="/images/essay-icon-dark.png" id="essay-icon">
                    <p>Essay Tests</p>
                </a>
            </div>
            <div class="test-type chosen-type" id="mcq-test" data-icon-id="mcq-icon">
                <a class="test-link" href="/mcq" onclick="chosenTestType('mcq-test')">
                    <img src="/images/mcq-icon-dark.png" class="test-icon" data-icon-light="/images/mcq-icon-light.png" data-icon-dark="/images/mcq-icon-dark.png" id="mcq-icon">
                    <p>MCQ Tests</p>
                </a>
            </div>
            <div class="test-type" id="tf-test" data-icon-id="tf-icon">
                <a class="test-link" href="/tf" onclick="chosenTestType('tf-test')">
                    <img src="/images/tf-icon-light.png" class="test-icon" data-icon-light="/images/tf-icon-light.png" data-icon-dark="/images/tf-icon-dark.png" id="tf-icon">
                    <p>True or False Tests</p>
                </a>
            </div>
            <div class="test-type" id="mtf-test" data-icon-id="mtf-icon">
                <a class="test-link" href="/mtf" onclick="chosenTestType('mtf-test')">
                    <img src="/images/tf-icon-light.png" class="test-icon" data-icon-light="/images/tf-icon-light.png" data-icon-dark="/images/tf-icon-dark.png" id="mtf-icon">
                    <p>Modified True or False Tests</p>
                </a>
            </div>
            <div class="test-type" id="matching-test" data-icon-id="matching-icon">
                <a class="test-link" href="/matching" onclick="chosenTestType('matching-test')">
                    <img src="/images/matching-icon-light.png" class="test-icon" data-icon-light="/images/matching-icon-light.png" data-icon-dark="/images/matching-icon-dark.png" id="matching-icon">
                    <p>Matching Type</p>
                </a>
            </div>
            <div class="test-type" id="enumeration-test" data-icon-id="enumeration-icon">
                <a class="test-link" href="/enumeration" onclick="chosenTestType('enumeration-test')">
                    <img src="/images/enumeration-icon-light.png" class="test-icon" data-icon-light="/images/enumeration-icon-light.png" data-icon-dark="/images/enumeration-icon-dark.png" id="enumeration-icon">
                    <p>Enumeration</p>
                </a>
            </div>
            <div class="profile-container">
                <img src="/images/profile.png" id="profile-pic">
                <div class="info">
                    <p id="profile-name">{{auth()->user()->first_name;}} {{auth()->user()->last_name;}}</p>
                    <p id="profile-email">{{auth()->user()->email;}}</p>
                </div>
                <div class="setting-container">
                    <img src="/images/icons8-gear-50.png" id="profile-setting-icon" onclick="toggleDropdown()">
                    <div class="setting-dropdown-menu" id="dropdown-menu">
                        <button class="setting-profile">Profile</button>
                        <form action="/logout" method="POST" class="setting-logout-form">
                            @csrf
                            <button class="setting-logout">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="test-body">
            <div class="test-body-header">
                <div href="/mcq" class="add-test-button-anchor">
                    <button class="add-test-button" id="back-button" data-id="{{$test->id}}"><img src="/images/back-icon.png" class="add-test-button-icon">Back</button>
                </div>

                <div class="searchbar-container">
                </div>
            </div>
            <div class="test-body-content">
                <div class="test-profile-container">
                    <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->test_title}}</span></p>
                    <p class="test-profile-label">Test description: <span class="test-profile-value">{{$test->test_instruction}}</span></p>
                    <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->test_total_points}}</span></p>
                </div>
                <form method="POST" class="test-add-question-container">
                    @csrf
                    @method('PUT')
                    <p class="text-input-label">Item Question <span class="red-asterisk">*</span></p>
                    <textarea class="text-input" name="item_question">{{$question->item_question}}</textarea>
                    @error('item_question')
                    <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                    @enderror
                    <p class="text-input-label">Attach an Image(Optional)</p>
                    <div>
                        <input type="text" class="text-input-attach-image" name="question_image" id="photoName" value="{{$question->question_image}}" readonly>
                        <input type="file" id="imageInput" style="display: none;" name="imageInput" value="{{ $question->question_image }}">
                        <input type="hidden" name="imageChanged" id="imageChanged" value="0">
                        <button class="text-input-image-button" type="button" id="clearButton" @unless(!is_null($question->question_image))
                            style="display: none;"
                            @endunless>Clear</button>
                        <button class="text-input-image-button" type="button" id="browseButton" @unless(is_null($question->question_image))
                            style="display: none;"
                            @endunless>Browse</button>
                    </div>
                    <p class="text-supported-format">Supported formats: .jpg, .png, .gif</p>
                    <div id="imageContainer" style="display: flex;" class="image-preview-container">
                        <img id="selectedImage" src="/user_upload_images/{{$question->question_image}}" alt="Selected Image" class="image-preview">
                    </div>
                    <p class="text-input-label">Number of Choices/Options(Max. 10)</p>
                    <input type="number" class="text-input-choices" id="numChoicesInput" value="{{$question->choices_number}}" name="number_of_choices">
                    @error('number_of_choices')
                    <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                    @enderror
                    <div id="optionsContainer">
                        @for($i = 1; $i <= $question->choices_number; $i++)
                            <p class="text-input-label">Option {{$i}}</p>
                            <textarea class="summernote" name="option_{{$i}}">{{data_get($question, 'option_' . $i )}}</textarea>
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
                                    <option value="{{$i}}" @if($i==$question->question_answer) selected @endif>Option {{$i}}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="item-point-container">
                            <p class="text-input-label">Item Point(s) <span class="red-asterisk">*</span></p>
                            <input type="text" class="point-input" value="{{$question->question_point}}" name="question_point">
                        </div>
                    </div>
                    <button class="save-test-button">Save Quiz Item</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function toggleDropdown() {
                var dropdown = document.getElementById("dropdown-menu");
                if (dropdown.style.display === "none" || dropdown.style.display === "") {
                    dropdown.style.display = "block";
                } else {
                    dropdown.style.display = "none";
                }
            }
            document.getElementById('back-button').addEventListener('click', function() {
                // Get the data-id attribute value
                var dataId = this.getAttribute('data-id');

                // Navigate to a new URL using the data-id value
                window.location.href = "/mcq/" + dataId;
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
</body>

</html>