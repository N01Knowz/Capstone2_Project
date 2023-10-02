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
                <a href="/matching/{{$test->id}}" class="add-test-button-anchor">
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
                <input type="text" class="textinput-base textarea-title text-input-background" name="title" value="{{$test->test_title}}" readonly>
                @error('title')
                <div class="alert alert-dange red-asterisk">{{ $message }}</div>
                @enderror
                <p class="text-input-label label-margin-top">Question/Instruction<span class="red-asterisk"> *</span></p>
                <textarea class="textinput-base textarea-question text-input-background" name="instruction" readonly>{{$test->test_instruction}}</textarea>
                @error('instruction')
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
        </div>
    </div>
    <script>
        
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
                    <td><input class="mt-inputs item_answer" type="text" name="item_answer_${i}"></td>
                    <td><input class="mt-inputs item_point" type="text" placeholder="0.00" name="item_point_${i}" value="0"></td>
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