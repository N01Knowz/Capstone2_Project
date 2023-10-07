<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Maker</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/add_page.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/mcq_add_question.css">
    <link rel="stylesheet" href="/css/test_maker_add_question.css">
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
            <div class="test-type chosen-type" id="test-test" data-icon-id="test-icon">
                <a class="test-link" href="/test" onclick="chosenTestType('test-test')">
                    <img src="/images/test-icon-dark.png" class="test-icon" data-icon-light="/images/test-icon-light.png" data-icon-dark="/images/test-icon-dark.png" id="test-icon">
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
                <a href="/test/{{$test->id}}" class="add-test-button-anchor">
                    <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
                        <p>Back</p>
                    </button>
                </a>
                <div class="searchbar-container">
                </div>
            </div>
            <div class="test-body-content">
                <form method="get" id="filter_form">
                    <input type="hidden" name="id" value="{{auth()->user()->id;}}">
                    <p class="text-input-label">Test Type</p>
                    <input type="text" class="textinput-base textarea-title text-input-background" name="test_type" value="{{$testType}}" readonly>
                    @error('test_type')
                    <div class="alert alert-dange red-asterisk">{{ $message }}</div>
                    @enderror
                    <p class="text-input-label label-margin-top">Search Title</p>
                    <input type="text" class="textinput-base textarea-title text-search-background" name="search_title" id="search_title" value="{{ request()->has('search_title') ? request('search_title') : '' }}">
                    <p class="text-input-label label-margin-top">Subject</p>
                    <div style="position: relative; width: 100%;">
                        <input type="text" id="searchInput" class="textinput-base textarea-title text-input-background" name="subject" value="{{ request()->has('subject') ? request('subject') : '' }}">
                        <ul id="suggestions" style="position: absolute; top: 100%; left: 0; z-index: 1;" data-unique-subjects="{{ json_encode($uniqueSubjects) }}"></ul>
                    </div>
                    <p class="text-supported-format">Leave blank for no subject.</p>
                    <p class="text-input-label label-margin-top">Label</p>
                    <div class="filter-label-container">
                        <button type="button" class="filter-label-buttons {{ request('realistic_filter') ? 'chosen-label-button' : '' }}" onclick="triggerLabelFilter(this)" data-input="realistic_filter" id="realistic-button">Realistic</button>
                        <input type="hidden" value="{{ request('realistic_filter') ? '1' : '0' }}" id="realistic_filter" name="realistic_filter">
                        <button type="button" class="filter-label-buttons {{ request('investigative_filter') ? 'chosen-label-button' : '' }}" onclick="triggerLabelFilter(this)" data-input="investigative_filter" id="investigative-button">Investigative</button>
                        <input type="hidden" value="{{ request('investigative_filter') ? '1' : '0' }}" id="investigative_filter" name="investigative_filter">
                        <button type="button" class="filter-label-buttons {{ request('artistic_filter') ? 'chosen-label-button' : '' }}" onclick="triggerLabelFilter(this)" data-input="artistic_filter" id="artistic-button">Artistic</button>
                        <input type="hidden" value="{{ request('artistic_filter') ? '1' : '0' }}" id="artistic_filter" name="artistic_filter">
                        <button type="button" class="filter-label-buttons {{ request('social_filter') ? 'chosen-label-button' : '' }}" onclick="triggerLabelFilter(this)" data-input="social_filter" id="social-button">Social</button>
                        <input type="hidden" value="{{ request('social_filter') ? '1' : '0' }}" id="social_filter" name="social_filter">
                        <button type="button" class="filter-label-buttons {{ request('enterprising_filter') ? 'chosen-label-button' : '' }}" onclick="triggerLabelFilter(this)" data-input="enterprising_filter" id="enterprising-button">Enterprising</button>
                        <input type="hidden" value="{{ request('enterprising_filter') ? '1' : '0' }}" id="enterprising_filter" name="enterprising_filter">
                        <button type="button" class="filter-label-buttons {{ request('conventional_filter') ? 'chosen-label-button' : '' }}" onclick="triggerLabelFilter(this)" data-input="conventional_filter" id="conventional-button">Conventional</button>
                        <input type="hidden" value="{{ request('conventional_filter') ? '1' : '0' }}" id="conventional_filter" name="conventional_filter">
                    </div>
                </form>
                <div class="testmaker-buttons">
                    <button class="search-filter-button label-margin-top" form="filter_form">Search/Filter</button>
                    <button class="save-test-button" form="save_form">Save Item</button>
                </div>
                <form method="POST" id="save_form">
                    @csrf
                    @foreach($allTestQuery as $testQuery)
                    <div class="dropdown-container">
                        <div class="dropdown-header">
                            <button class="dropdown-title" type="button" data-dropdown-icon="{{'dropdown-icon-' . $testQuery->id}}" data-dropdown-id="{{'dropdown-content-' . $testQuery->id}}" onclick="showDropdown()">
                                {{$testQuery->test_title}}
                                <span class="dropdown-icon" id="{{'dropdown-icon-' . $testQuery->id}}">▼</span> <!-- Dropdown icon (downward-pointing arrow) -->
                            </button>
                            <input type="checkbox"value="{{$testQuery->id}}" id="parent-checkbox-{{$testQuery->id}}" class="dropdown-checkbox" data-dropdown-checkboxes="dropdown-questions-checkbox-{{$testQuery->id}}"  @if(!$testQuery->in_test_makers) name="test_checkbox_add[]" @endif  @if($testQuery->in_test_makers) checked disabled @endif @if(in_array($testQuery->test_type, ['mcq', 'tf', 'mtf'])) onclick="toggleCheckboxes(this)"@endif>
                        </div>
                        <div class="dropdown-content" id="{{'dropdown-content-' . $testQuery->id}}" style="background-color: white;">
                            @if($testQuery->test_type == 'essay')
                            @foreach($allQuestionQuery as $questionQuery)
                            @if($questionQuery->testbank_id == $testQuery->id)
                            <p class="text-input-label">Question: <span class="test-question-output">{{$testQuery->test_question}}</span></p>
                            <table class="essay-table">
                                <thead>
                                    <tr>
                                        <th class="essay-criteria-column">Criteria(s)</th>
                                        <th>Point(s)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$questionQuery->item_question}}</td>
                                        <td>{{$questionQuery->question_point}}</td>
                                    </tr>
                                    @if($questionQuery->option_1)
                                    <tr>
                                        <td>{{$questionQuery->option_1}}</td>
                                        <td>{{$questionQuery->option_2}}</td>
                                    </tr>
                                    @endif
                                    @if($questionQuery->option_3)
                                    <tr>
                                        <td>{{$questionQuery->option_3}}</td>
                                        <td>{{$questionQuery->option_4}}</td>
                                    </tr>
                                    @endif
                                    @if($questionQuery->option_5)
                                    <tr>
                                        <td>{{$questionQuery->option_5}}</td>
                                        <td>{{$questionQuery->option_6}}</td>
                                    </tr>
                                    @endif
                                    @if($questionQuery->option_7)
                                    <tr>
                                        <td>{{$questionQuery->option_7}}</td>
                                        <td>{{$questionQuery->option_8}}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            @endif
                            @endforeach
                            @endif
                            @if($testQuery->test_type == 'enumeration')
                            <p class="text-input-label">Question: <span class="test-question-output">{{$testQuery->test_question}}</span></p>
                            <table class="essay-table">
                                <thead>
                                    <tr>
                                        <th class="essay-criteria-column">Answer(s)</th>
                                        <th>Point(s)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allQuestionQuery as $questionQuery)
                                    @if($questionQuery->testbank_id == $testQuery->id)
                                    <tr>
                                        <td>{{$questionQuery->item_question}}</td>
                                        <td>{{$questionQuery->question_point}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                            @if($testQuery->test_type == 'matching')
                            <p class="text-input-label">Question: <span class="test-question-output">{{$testQuery->test_question}}</span></p>
                            <table class="essay-table">
                                <thead>
                                    <tr>
                                        <th>Item Text</th>
                                        <th>Answer(s)</th>
                                        <th>Point(s)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allQuestionQuery as $questionQuery)
                                    @if($questionQuery->testbank_id == $testQuery->id)
                                    <tr>
                                        <td>{{$questionQuery->option_1}}</td>
                                        <td>{{$questionQuery->item_question}}</td>
                                        <td>{{$questionQuery->question_point}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                            @if(in_array($testQuery->test_type, ['mcq', 'tf', 'mtf']))
                            @foreach($allQuestionQuery as $questionQuery)
                            @if($questionQuery->testbank_id == $testQuery->id)
                            <div class="dropdown-question-header">
                                <button class="dropdown-question-title dropdown-question-title-child" type="button" data-dropdown-icon="{{'dropdown-icon-' . $testQuery->id}}" data-dropdown-id="{{'dropdown-content-' . $testQuery->id}}">
                                    <p class="text-input-label">Question: <span class="test-question-output">{{$questionQuery->item_question}}</span></p>
                                </button>
                                <input type="checkbox" class="dropdown-checkbox  @if(!$questionQuery->in_test_makers) dropdown-questions-checkbox-{{$testQuery->id}} @endif" data-parent-checkbox="parent-checkbox-{{$testQuery->id}}" onclick="updateSelectAllState(this)" @if($questionQuery->in_test_makers) checked disabled @endif @if(!$questionQuery->in_test_makers) name="question_checkbox_add[]" @endif value="{{$questionQuery->id}}">
                            </div>
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>

    <script>
        function triggerLabelFilter(button) {
            const filterInputID = button.getAttribute('data-input');
            const filterInput = document.getElementById(filterInputID);
            if (filterInput.value == 0) {
                filterInput.value = 1;
                button.classList.add('chosen-label-button')
            } else {
                filterInput.value = 0;
                button.classList.remove('chosen-label-button')
            }
        }

        function showDropdown() {
            // Step 1: Retrieve the target ID from the button's data attribute
            const button = event.currentTarget; // Get the clicked button
            const targetElementId = button.getAttribute('data-dropdown-id');
            const dropdownIconID = button.getAttribute('data-dropdown-icon');

            // Step 2: Use the retrieved data to access and manipulate the target element
            const targetElement = document.getElementById(targetElementId);
            const dropdownIcon = document.getElementById(dropdownIconID);

            // Step 3: Toggle the element's visibility
            if (targetElement) {
                if (targetElement.style.display === 'block') {
                    targetElement.style.display = 'none';
                    dropdownIcon.textContent = '▼';
                } else {
                    targetElement.style.display = 'block';
                    dropdownIcon.textContent = '▲';
                }
            }
        }

        function toggleDropdown() {
            var dropdown = document.getElementById("dropdown-menu");
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }
        }

        // Function to toggle checkboxes based on the checkbox that triggered the function
        function toggleCheckboxes(checkbox) {
            const targetClass = checkbox.getAttribute('data-dropdown-checkboxes');
            const checkboxes = document.querySelectorAll('.' + targetClass);
            checkboxes.forEach(cbox => {
                cbox.checked = checkbox.checked;
            });
        }

        // Function to update the checkbox that triggered the function based on other checkboxes
        function updateSelectAllState(checkbox) {
            const targetClass = checkbox.getAttribute('data-parent-checkbox');
            const parentCheckbox = document.getElementById(targetClass);

            parentCheckbox.checked = false;
        }


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
    </script>
</body>

</html>