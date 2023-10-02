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
            <div class="test-type" id="test-test" data-icon-id="test-icon">
                <a class="test-link" href="/test" onclick="chosenTestType('test-test')">
                    <img src="/images/test-icon-light.png" class="test-icon" data-icon-light="/images/test-icon-light.png" data-icon-dark="/images/test-icon-dark.png" id="test-icon">
                    <p>Test</p>
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
            <form method="POST" action="/essay" class="test-body-content" enctype="multipart/form-data" id="add-form">
                @csrf
                <input type="hidden" name="id" value="{{auth()->user()->id;}}">
                <p class="text-input-label">Title<span class="red-asterisk"> *</span></p>
                <input type="text" class="textinput-base textarea-title text-input-background" name="title" required>
                @error('title')
                <div class="alert alert-dange red-asterisk">{{ $message }}</div>
                @enderror
                <p class="text-input-label label-margin-top">Question<span class="red-asterisk"> *</span></p>
                <textarea class="textinput-base textarea-question text-input-background" name="question" required></textarea>
                @error('question')
                <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                @enderror
                <p class="text-input-label label-margin-top">Instructions</p>
                <textarea class="textinput-base textarea-instruction text-input-background" name="instruction" required></textarea>
                <p class="text-input-label label-margin-top">Subject</p>
                <div style="position: relative; width: 100%;">
                    <input type="text" id="searchInput" class="textinput-base textarea-title text-input-background" name="subject">
                    <ul id="suggestions" style="position: absolute; top: 100%; left: 0; z-index: 1;" data-unique-subjects="{{ json_encode($uniqueSubjects) }}"></ul>
                </div>
                <p class="text-supported-format">Leave blank for no subject.</p>
                <p class="text-input-label label-margin-top">Attach an Image(Optional)</p>
                <div>
                    <input type="text" class="text-input-background text-input-attach-image" name="question_image" id="photoName" readonly>
                    <input type="file" id="imageInput" style="display:none;" name="imageInput">
                    <button class="text-input-image-button" type="button" id="clearButton" style="display: none;">Clear</button>
                    <button class="text-input-image-button" type="button" id="browseButton">Browse</button>
                </div>
                <p class="text-supported-format">Supported formats: .jpg, .png, .gif</p>
                <div id="imageContainer" style="display: none;" class="image-preview-container">
                    <img id="selectedImage" src="#" alt="Selected Image" class="image-preview">
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
                                    <input type="text" class="criteria-point-input criteria-input" required placeholder="E.g Content" id="criteria_1" name="criteria_1">
                                </div>

                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" required value="0" id="criteria_point_1" min="0" name="criteria_point_1">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" id="criteria_2" name="criteria_2">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" value="0" id="criteria_point_2" min="0" name="criteria_point_2" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" id="criteria_3" name="criteria_3">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" value="0" id="criteria_point_3" min="0" name="criteria_point_3" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" id="criteria_4" name="criteria_4">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" value="0" id="criteria_point_4" min="0" name="criteria_point_4" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" id="criteria_5" name="criteria_5">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" value="0" id="criteria_point_5" min="0" name="criteria_point_5" readonly>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-input-label">Total:</p>
                            </td>
                            <td>
                                <div>
                                    <input type="number" class="criteria-point-input point-input" id="total_points" value="0" id="criteria_point_1" min="0" name="total_points" readonly>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                @error('criteria_1')
                <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                @enderror
                <div class="add-test-button-anchor">
                    <button class="save-test-button" id="save-quiz-button">Save Test</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const searchInput = document.getElementById('searchInput');
        const suggestionsList = document.getElementById('suggestions');

        // Preload the suggestions from the data attribute
        const suggestions = JSON.parse(suggestionsList.getAttribute('data-unique-subjects'));
        // const suggestions = ['Apple', 'Banana', 'Cherry', 'Date', 'Fig', 'Grape'];

        // Create the list items for suggestions and hide them initially
        const suggestionItems = suggestions.map(suggestion => {
            const li = document.createElement('li');
            li.textContent = suggestion;

            li.style.display = 'none'; // Hide initially
            suggestionsList.appendChild(li);

            return li;
        });

        function updateSuggestions() {
            const searchTerm = searchInput.value.toLowerCase();

            const filteredSuggestions = suggestions.filter(suggestion =>
                suggestion.toLowerCase().startsWith(searchTerm)
            );

            // Hide all suggestions by default
            suggestionItems.forEach(item => (item.style.display = 'none'));

            // Display filtered suggestions
            if (filteredSuggestions.length > 0) {
                filteredSuggestions.forEach(suggestion => {
                    // Show only the suggestions that match the filter
                    suggestionItems
                        .filter(item => item.textContent === suggestion)
                        .forEach(item => (item.style.display = 'block'));
                });
                suggestionsList.style.display = 'block'; // Show suggestions list
            } else {
                suggestionsList.style.display = 'none'; // Hide suggestions list
            }
        }

        // Delegate the click event to the suggestionsList and set the input value when a suggestion is clicked
        suggestionsList.addEventListener('click', (event) => {
            const target = event.target;
            if (target.nodeName === 'LI') {
                searchInput.value = target.textContent;
                suggestionsList.style.display = 'none'; // Hide suggestions list
            }
        });

        let blurTimer; // Initialize a timer variable
        // Add this event listener to hide suggestions when the input loses focus
        searchInput.addEventListener('blur', () => {
            // Delay the blur event for 200 milliseconds (adjust as needed)
            blurTimer = setTimeout(() => {
                suggestionsList.style.display = 'none'; // Hide suggestions list
            }, 100); // 200 milliseconds delay
        });

        // Listen for both input and focus events on the search input
        searchInput.addEventListener('input', updateSuggestions);
        searchInput.addEventListener('focus', updateSuggestions);

        var save_button = document.getElementById("save-quiz-button");

        // Add a click event listener to the button
        save_button.addEventListener("click", function() {
            // Disable the button
            save_button.disabled = true;
            document.getElementById("add-form").submit();
        });

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
            imageChangedInput.value = '1';
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
            console.log("There was a change");
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

        for (let i = 2; i <= 5; i++) {
            const criteriaInput = document.getElementById(`criteria_${i}`);
            const criteriaPointInput = document.getElementById(`criteria_point_${i}`);

            criteriaInput.addEventListener("input", function() {
                if (criteriaInput.value === "") {
                    criteriaPointInput.value = 0;
                    criteriaPointInput.setAttribute("readonly", "readonly");
                } else {
                    criteriaPointInput.removeAttribute("readonly");
                }

                // Calculate total points whenever any input changes
                calculateTotalPoints();
            });
        }

        function calculateTotalPoints() {
            let total = 0;

            for (let i = 2; i <= 5; i++) {
                const criteriaPointInput = document.getElementById(`criteria_point_${i}`);
                total += parseInt(criteriaPointInput.value) || 0;
            }

            totalPointsInput.value = total;
        }



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

    <style>
        #suggestions {
            list-style: none;
            padding: 0;
            border: 1px solid #ccc;
            background-color: white;
            /* Background color */
            max-height: 150px;
            overflow-y: auto;
            display: none;
        }

        #suggestions li {
            cursor: pointer;
            padding: 5px;
            border-bottom: 1px solid #ccc;
            /* Bottom border for each suggestion */
        }

        #suggestions li:last-child {
            border-bottom: none;
            /* Remove border for the last suggestion */
        }

        #suggestions li:hover {
            background-color: lightgray;
        }
    </style>
</body>

</html>