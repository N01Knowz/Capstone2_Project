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
                <a href="/matching/{{$test->id}}" class="add-test-button-anchor">
                    <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
                        <p>Back</p>
                    </button>
                </a>
                <div class="searchbar-container">
                </div>
            </div>
            <form method="POST" class="test-body-content">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{auth()->user()->id;}}">
                <p class="text-input-label">Test Type</p>
                <input type="text" class="textinput-base textarea-title text-input-background" name="test_type" value="{{$testType}}" readonly>
                @error('test_type')
                <div class="alert alert-dange red-asterisk">{{ $message }}</div>
                @enderror
                <p class="text-input-label label-margin-top">Search Keyword</p>
                <input type="text" class="textinput-base textarea-title text-search-background" name="question_image" id="photoName">
                <p class="text-input-label label-margin-top">Filter</p>
                <div>
                    <button type="button">Realistic</button>
                    <button type="button">Investigative</button>
                    <button type="button">Artistic</button>
                    <button type="button">Social</button>
                    <button type="button">Enterprising</button>
                    <button type="button">Conventional</button>
                </div>
                <button class="save-test-button label-margin-top">Search/Filter</button>
                @foreach($allTestQuery as $testQuery)
                <div class="dropdown-container">
                    <div class="dropdown-header">
                        <button class="dropdown-title" type="button" data-dropdown-icon="{{'dropdown-icon-' . $testQuery->id}}" data-dropdown-id="{{'dropdown-content-' . $testQuery->id}}" onclick="showDropdown()">
                            {{$testQuery->test_title}}
                            <span class="dropdown-icon" id="{{'dropdown-icon-' . $testQuery->id}}">▼</span> <!-- Dropdown icon (downward-pointing arrow) -->
                        </button>
                        <input type="checkbox" class="dropdown-checkbox">
                    </div>
                    <div class="dropdown-content" id="{{'dropdown-content-' . $testQuery->id}}">
                        @if($testQuery->test_type == 'essay')
                        @foreach($allQuestionQuery as $questionQuery)
                        @if($questionQuery->testbank_id == $testQuery->id)
                        <p class="text-input-label">Question: <span class="test-question-output">{{$testQuery->test_question}}</span></p>
                        <table class="essay-table">
                            <thead>
                                <tr>
                                    <th class="essay-criteria-column">Criteria</th>
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
                        <p>Hello enumeration</p>
                        @endif
                        @if($testQuery->test_type == 'matching')
                        <p>hello matching</p>
                        @endif
                        @if(in_array($testQuery->test_type, ['mcq', 'tf', 'mtf']))
                        @foreach($allQuestionQuery as $questionQuery)
                        @if($questionQuery->testbank_id == $testQuery->id)
                        <div class="dropdown-header">
                            <button class="dropdown-title" type="button" data-dropdown-icon="{{'dropdown-icon-' . $testQuery->id}}" data-dropdown-id="{{'dropdown-content-' . $testQuery->id}}" onclick="showDropdown()">
                                {{$testQuery->test_title}}
                                <span class="dropdown-icon" id="{{'dropdown-icon-' . $testQuery->id}}">▼</span> <!-- Dropdown icon (downward-pointing arrow) -->
                            </button>
                            <input type="checkbox" class="dropdown-checkbox">
                        </div>
                        <p class="text-input-label">Question: <span class="test-question-output">{{$questionQuery->item_question}}</span></p>
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
                @endforeach
                <button class="save-test-button label-margin-top">Save Item</button>
            </form>
        </div>
    </div>

    <script>
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
    </script>
</body>

</html>