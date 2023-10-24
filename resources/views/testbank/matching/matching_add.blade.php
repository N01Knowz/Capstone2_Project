<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matching</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/add_page.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/mcq_add_question.css">
    <link rel="stylesheet" href="/css/mt_add_questions.css">
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
            <div class="test-type chosen-type" id="matching-test" data-icon-id="matching-icon">
                <a class="test-link" href="/matching" onclick="chosenTestType('matching-test')">
                    <img src="/images/matching-icon-dark.png" class="test-icon" data-icon-light="/images/matching-icon-light.png" data-icon-dark="/images/matching-icon-dark.png" id="matching-icon">
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
                <img @if(is_null(auth()->user()->user_image)) src="/images/profile.png" @else src="/user_upload_images/{{auth()->user()->user_image}}" @endif style="
    height: 60px;
    width: 60px;" id="profile-pic">
                <div class="info">
                    <p id="profile-name">{{auth()->user()->first_name;}} {{auth()->user()->last_name;}}</p>
                    <p id="profile-email">{{auth()->user()->email;}}</p>
                </div>
                <div class="setting-container">
                    <img src="/images/icon-settings.png" id="profile-setting-icon" onclick="toggleDropdown()">
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
                    <input type="text" id="searchInput" class="textinput-base textarea-title text-input-background" name="subject">
                    <ul id="suggestions" style="position: absolute; top: 100%; left: 0; z-index: 1;" data-unique-subjects="{{ json_encode($uniqueSubjects) }}"></ul>
                </div>
                <p class="text-supported-format">Leave blank for no subject.</p>
                <div class="share-container">
                    <input type="checkbox" class="share-checkbox" name="share">
                    <p class="text-input-label">Share with other users</p>
                </div>
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
                @error('no_item')
                <div class="alert alert-danger red-asterisk">There should at least be 1 item</div>
                @enderror
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
        </div>
    </div>
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

        function toggleDropdown() {
            var dropdown = document.getElementById("dropdown-menu");
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        }


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
</body>

</html>