<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Essay</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/essay_add_page.css">
    <link rel="stylesheet" href="/css/add_page.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/navigator.css">
</head>

<body>
    <div class="test-container">
        <div class="navigator">
            <div id="logo-container">
                <img src="/images/logo.png" id="logo">
                <p>Test Bank</p>
            </div>
            <div class="test-type chosen-type" id="essay-test" data-icon-id="essay-icon">
                <a class="test-link" href="/essay" onclick="chosenTestType('essay-test')">
                    <img src="/images/essay-icon-dark.png" class="test-icon" data-icon-light="/images/essay-icon-light.png" data-icon-dark="/images/essay-icon-dark.png" id="essay-icon">
                    <p>Essay Tests</p>
                </a>
            </div>
            <div class="test-type" id="mcq-test" data-icon-id="mcq-icon">
                <a class="test-link" href="/mcq" onclick="chosenTestType('mcq-test')">
                    <img src="/images/mcq-icon-light.png" class="test-icon" data-icon-light="/images/mcq-icon-light.png" data-icon-dark="/images/mcq-icon-dark.png" id="mcq-icon">
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
                <img @if(is_null(auth()->user()->user_image)) src="/images/profile.png" @else src="/user_upload_images/{{auth()->user()->user_image}}" @endif id="profile-pic">
                <div class="info">
                    <p id="profile-name">{{auth()->user()->first_name;}} {{auth()->user()->last_name;}}</p>
                    <p id="profile-email">{{auth()->user()->email;}}</p>
                </div>
                <div class="setting-container">
                    <img src="/images/icons8-gear-50.png" id="profile-setting-icon" onclick="toggleDropdown()">
                    <div class="setting-dropdown-menu" id="dropdown-menu">
                        <form action="/profile" method="get">
                            <button class="setting-profile">Profile</button>
                        </form>
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
                <a href="/essay" class="add-test-button-anchor">
                    <button class="add-test-button"><img src="/images/back-icon.png" class="add-test-button-icon">
                        <p>Back</p>
                    </button>
                </a>
                <div class="searchbar-container">
                </div>
            </div>
            <form method="POST" action="/essay/{{$test->id}}" class="test-body-content" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{auth()->user()->id;}}">
                <p class="text-input-label">Title<span class="red-asterisk"> *</span></p>
                <input type="text" class="textinput-base textarea-title text-input-background" name="title" required value="{{$test->test_title}}">
                @error('title')
                <div class="alert alert-dange red-asterisk">{{ $message }}</div>
                @enderror
                <p class="text-input-label label-margin-top">Question<span class="red-asterisk"> *</span></p>
                <textarea class="textinput-base textarea-question text-input-background" name="question" required>{{$test->test_question}}</textarea>
                @error('question')
                <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                @enderror
                <p class="text-input-label label-margin-top">Instructions</p>
                <textarea class="textinput-base textarea-instruction text-input-background" name="instruction">{{$test->test_instruction}}</textarea>
                <p class="text-input-label label-margin-top">Attach an Image(Optional)</p>
                <div>
                    <input type="text" class="text-input-background text-input-attach-image" name="question_image" id="photoName" value="{{$test->test_image}}" readonly>
                    <input type="file" id="imageInput" style="display: none;" name="imageInput" value="{{ $test->test_image }}">
                    <input type="hidden" name="imageChanged" id="imageChanged" value="0">
                    <button class="text-input-image-button" type="button" id="clearButton" @unless(!is_null($test->test_image))
                        style="display: none;"
                        @endunless>Clear</button>
                    <button class="text-input-image-button" type="button" id="browseButton" @unless(is_null($test->test_image))
                        style="display: none;"
                        @endunless>Browse</button>
                </div>
                <p class="text-supported-format">Supported formats: .jpg, .png, .gif</p>
                <div id="imageContainer" @if(is_null($test->test_image) || empty($test->test_image))
                    style="display: none;"
                    @else
                    style="display: flex;"
                    @endif class="image-preview-container">
                    <img id="selectedImage" src="/user_upload_images/{{$test->test_image}}" alt="Selected Image" class="image-preview">
                </div>
                <div class="share-container">
                    <input type="checkbox" class="share-checkbox" name="share">
                    <p class="text-input-label">Share with other faculties</p>
                </div>
                <table class="criteria-points-table">
                    <thead>
                        <tr>
                            <th class="criteria-column">
                                <p class="text-input-label">Criteria<span class="red-asterisk"> *</span></p>
                            </th>
                            <th class="points-column">
                                <p class="text-input-label">Point(s)</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input type="text" class="criteria-point-input criteria-input" required placeholder="E.g Content" name="criteria_1" value="{{$question->item_question}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" required min="0" name="criteria_point_1" value="{{$question->question_point}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" name="criteria_2" value="{{$question->option_1}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" min="0" name="criteria_point_2" value="{{$question->option_2}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" name="criteria_3" value="{{$question->option_3}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" min="0" name="criteria_point_3" value="{{$question->option_4}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" name="criteria_4" value="{{$question->option_5}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" min="0" name="criteria_point_4" value="{{$question->option_6}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" name="criteria_5" value="{{$question->option_7}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" min="0" name="criteria_point_5" value="{{$question->option_8}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-input-label">Total:</p>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" id="total_points" min="0" name="total_points" value="{{$test->test_total_points}}" readonly>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="add-test-button-anchor">
                    <button class="save-test-button">Save Test</button>
                </div>
            </form>
        </div>
    </div>
    <script>
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

        function toggleDropdown() {
            var dropdown = document.getElementById("dropdown-menu");
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        }
        const pointInputs = document.querySelectorAll(".point-input");
        pointInputs.forEach(pointInput => {
            pointInput.addEventListener('input', handleTotalPoints);
        });

        function handleTotalPoints() {
            var total_points = document.getElementById("total_points");
            const pointInputs = document.querySelectorAll(".point-input");
            total_points.value = 0;
            let sum = 0;
            pointInputs.forEach(pointInput => {
                sum += parseInt(pointInput.value) || 0;
            });
            total_points.value = sum;
        }
    </script>
</body>

</html>