<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Maker</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/add_page.css">
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/test_description.css">
    <link rel="stylesheet" href="/css/enumeration-test_description.css">
    <link rel="stylesheet" href="/css/tm_description.css">
</head>

<body>
    <div class="add-item-container" id="add_item_container">
        <div class="add-item-sub-container" id="add_item_sub_container">
            <div class="add-item-modal-header">
                <p class="add-item-enter-answer">Select Type</p>
                <button class="add-item-modal-header-close" id="add_item_modal_header_close">x</button>
            </div>
            <div class="add-item-modal-body">
                <div class="test-select-body-container">
                    <a href="/test/{{$test->tmID}}/essay">
                        <button class="select-type-test-button">Essay</button>
                    </a>
                    <a href="/test/{{$test->tmID}}/mcq">
                        <button class="select-type-test-button">MCQ</button>
                    </a>
                    <a href="/test/{{$test->tmID}}/tf">
                        <button class="select-type-test-button">True or False</button>
                    </a>
                    <a href="/test/{{$test->tmID}}/mtf">
                        <button class="select-type-test-button">Modified True or False</button>
                    </a>
                    <a href="/test/{{$test->tmID}}/matching">
                        <button class="select-type-test-button">Matching</button>
                    </a>
                    <a href="/test/{{$test->tmID}}/enumeration">
                        <button class="select-type-test-button">Enumeration</button>
                    </a>
                </div>
            </div>
            <div class="add-item-modal-footer">
                <div class="add-item-buttons-container">
                    <button id="add_item_close_button" class="add-item-close-button add-item-modal-button" style="cursor:pointer;">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="add-random-item-container" id="add_random_item_container">
        <div class="add-random-item-sub-container" id="add_random_item_sub_container">
            <div class="add-random-item-modal-header">
                <p class="add-random-item-enter-answer">Select Filters</p>
                <button class="add-random-item-modal-header-close" id="add_random_item_modal_header_close">x</button>
            </div>
            <form method="post" id="add_random_form" action="/test/{{$test->tmID}}/essay/add" class="add-random-item-modal-body">
                @csrf
                <div class="random-item-filter">
                    <label for="random_item_filter" class="random-item-filter-input-label">Test Type:</label>
                    <select name="" id="random_select_option" class="random-item-filter-input" data-test-id="{{$test->tmID}}" onchange="updateFormAction()">
                        <option value="essay">Essay</option>
                        <option value="mcq">MCQ</option>
                        <option value="tf">True or False</option>
                        <option value="mtf">Modified True or False</option>
                        <option value="matching">Matching</option>
                        <option value="enumeration">Enumeration</option>
                    </select>
                </div>
                <div class="random-item-filter">
                    <label for="random_item_filter" class="random-item-filter-input-label">Number of Questions:</label>
                    <input type="number" name="random_item_number" id="" class="random-item-filter-input" min="1" value="1">
                </div>
                <div class="random-item-filter">
                    <label for="random_item_test_type" class="random-item-filter-input-label">Subject:</label>
                    <select name="random_item_subject" id="" class="random-item-filter-input">
                        <option value=""></option>
                        @foreach($subjects as $subject)
                        <option value="{{$subject->subjectName}}">{{$subject->subjectName}}</option>
                        @endforeach
                    </select>
                    <p class="text-supported-format">Leave blank for no subject.</p>
                </div>
                <div>
                    <label for="random_item_test_type" class="random-item-filter-input-label">Label:</label>
                    <div class="random-item-label-button-container">
                        <button type="button" class="filter-label-buttons" onclick="triggerLabelFilter(this)" data-input="realistic_filter" id="realistic-button">Realistic</button>
                        <input type="hidden" value="0" id="realistic_filter" name="realistic_filter">
                        <button type="button" class="filter-label-buttons" onclick="triggerLabelFilter(this)" data-input="investigative_filter" id="investigative-button">Investigative</button>
                        <input type="hidden" value="0" id="investigative_filter" name="investigative_filter">
                        <button type="button" class="filter-label-buttons" onclick="triggerLabelFilter(this)" data-input="artistic_filter" id="artistic-button">Artistic</button>
                        <input type="hidden" value="0" id="artistic_filter" name="artistic_filter">
                        <button type="button" class="filter-label-buttons" onclick="triggerLabelFilter(this)" data-input="social_filter" id="social-button">Social</button>
                        <input type="hidden" value="0" id="social_filter" name="social_filter">
                        <button type="button" class="filter-label-buttons" onclick="triggerLabelFilter(this)" data-input="enterprising_filter" id="enterprising-button">Enterprising</button>
                        <input type="hidden" value="0" id="enterprising_filter" name="enterprising_filter">
                        <button type="button" class="filter-label-buttons" onclick="triggerLabelFilter(this)" data-input="conventional_filter" id="conventional-button">Conventional</button>
                        <input type="hidden" value="0" id="conventional_filter" name="conventional_filter">
                    </div>
                </div>
            </form>
            <div class="add-random-item-modal-footer">
                <div class="add-item-buttons-container">
                    <button form="add_random_form" class="add-item-save-button add-item-modal-button" id="save-quiz-button">Add Items</button>
                    <button id="add_random_item_close_button" class="add-random-item-close-button add-random-item-modal-button" style="cursor:pointer;">Close</button>
                </div>
            </div>
        </div>
    </div>
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
                <a href="/test" class="add-test-button-anchor">
                    <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
                        <p>Back</p>
                    </button>
                </a>
                <div class="add-item-button-container">
                    @if(auth()->user()->id == $test->user_id)
                    <button class="add-test-question-button" id="add-test-button"><img src="/images/add-test-icon.png">
                        <p>Add Item</p>
                    </button>
                    <button class="add-test-question-button" id="add-random-test-button"><img src="/images/add-test-icon.png">
                        <p>Add Random Items</p>
                    </button>
                    @endif
                </div>
            </div>
            <div class="test-body-content">
                <div class="test-profile-container">
                    <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->tmTitle}}</span></p>
                    <p class="test-profile-label">Test description: <span class="test-profile-value">{{$test->tmDescription}}</span></p>
                    <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->tmTotal}}</span></p>
                </div>
                <div class="test-maker-tests-container">
                    <div class="test-maker-questions-container">
                        <div class="dropdown-header">
                            <button class="dropdown-title" type="button" onclick="showDropdown('dropdown-content-essay')">
                                Essay Tests Questions
                                <span class="dropdown-icon">▼</span>
                            </button>
                        </div>
                        <div class="dropdown-content" id="dropdown-content-essay">
                            @php
                            $hasEssay = false;
                            @endphp
                            @foreach($essayQuestions as $testQuery)
                            @php
                            $hasEssay = true;
                            @endphp
                            <div class="dropdown-question-container">
                                <div class="dropdown-question-content">
                                    {{$testQuery->essQuestion}}
                                </div>
                                @if(auth()->user()->id == $test->user_id)
                                <form class="dropdown-del-btn-container" method="POST" action="/test/essay/{{$test->tmID}}/{{$testQuery->tmessID}}/delete" onsubmit="return confirmDelete();">
                                    @csrf
                                    @method('delete')
                                    <button class="dropdown-del-btn"><img src="/images/delete-icon.png" class="dropdown-del-btn-img"></button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                            @if(!$hasEssay)
                            <p class="no-question-sentence">
                                No questions added.
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="test-maker-questions-container">
                        <div class="dropdown-header">
                            <button class="dropdown-title" type="button" onclick="showDropdown('dropdown-content-mcq')">
                                MCQ Tests Questions
                                <span class="dropdown-icon">▼</span>
                            </button>
                        </div>
                        <div class="dropdown-content" id="dropdown-content-mcq">
                            @php
                            $hasMCQ = false;
                            @endphp
                            @foreach($quizQuestions as $questionQuery)
                            @php
                            $hasMCQ = true;
                            @endphp
                            <div class="dropdown-question-container">
                                <div class="dropdown-question-content">
                                    {{$questionQuery->itmQuestion}}
                                </div>
                                @if(auth()->user()->id == $test->user_id)
                                <form class="dropdown-del-btn-container" method="POST" action="/test/mcq/{{$test->tmID}}/{{$questionQuery->tmquizID}}/delete" onsubmit="return confirmDelete();">
                                    @csrf
                                    @method('delete')
                                    <button class="dropdown-del-btn"><img src="/images/delete-icon.png" class="dropdown-del-btn-img"></button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                            @if(!$hasMCQ)
                            <p class="no-question-sentence">
                                No questions added.
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="test-maker-questions-container">
                        <div class="dropdown-header">
                            <button class="dropdown-title" type="button" onclick="showDropdown('dropdown-content-tf')">
                                True or False Tests Questions
                                <span class="dropdown-icon">▼</span>
                            </button>
                        </div>
                        <div class="dropdown-content" id="dropdown-content-tf">
                            @php
                            $hasTF = false;
                            @endphp
                            @foreach($tfQuestions as $questionQuery)
                            @php
                            $hasTF = true;
                            @endphp
                            <div class="dropdown-question-container">
                                <div class="dropdown-question-content">
                                    {{$questionQuery->itmQuestion}}
                                </div>
                                @if(auth()->user()->id == $test->user_id)
                                <form class="dropdown-del-btn-container" method="POST" action="/test/tf/{{$test->tmID}}/{{$questionQuery->tmtfID}}/delete" onsubmit="return confirmDelete();">
                                    @csrf
                                    @method('delete')
                                    <button class="dropdown-del-btn"><img src="/images/delete-icon.png" class="dropdown-del-btn-img"></button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                            @if(!$hasTF)
                            <p class="no-question-sentence">
                                No questions added.
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="test-maker-questions-container">
                        <div class="dropdown-header">
                            <button class="dropdown-title" type="button" onclick="showDropdown('dropdown-content-mtf')">
                                Modified True or False Tests Questions
                                <span class="dropdown-icon">▼</span>
                            </button>
                        </div>
                        <div class="dropdown-content" id="dropdown-content-mtf">
                            @php
                            $hasMTF = false;
                            @endphp
                            @foreach($mtfQuestions as $questionQuery)
                            @php
                            $hasMTF = true;
                            @endphp
                            <div class="dropdown-question-container">
                                <div class="dropdown-question-content">
                                    {{$questionQuery->itmQuestion}}
                                </div>
                                @if(auth()->user()->id == $test->user_id)
                                <form class="dropdown-del-btn-container" method="POST" action="/test/mtf/{{$test->tmID}}/{{$questionQuery->tmmtfID}}/delete" onsubmit="return confirmDelete();">
                                    @csrf
                                    @method('delete')
                                    <button class="dropdown-del-btn"><img src="/images/delete-icon.png" class="dropdown-del-btn-img"></button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                            @if(!$hasMTF)
                            <p class="no-question-sentence">
                                No questions added.
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="test-maker-questions-container">
                        <div class="dropdown-header">
                            <button class="dropdown-title" type="button" onclick="showDropdown('dropdown-content-matching')">
                                Matching Type Questions
                                <span class="dropdown-icon">▼</span>
                            </button>
                        </div>
                        <div class="dropdown-content" id="dropdown-content-matching">
                            @php
                            $hasMatching = false;
                            @endphp
                            @foreach($matchingQuestions as $testQuery)
                            @php
                            $hasMatching = true;
                            @endphp
                            <div class="dropdown-question-container">
                                <div class="dropdown-question-content">
                                    {{$testQuery->mtDescription}}
                                </div>
                                @if(auth()->user()->id == $test->user_id)
                                <form class="dropdown-del-btn-container" method="POST" action="/test/matching/{{$test->tmID}}/{{$testQuery->tmmtID}}/delete" onsubmit="return confirmDelete();">
                                    @csrf
                                    @method('delete')
                                    <button class="dropdown-del-btn"><img src="/images/delete-icon.png" class="dropdown-del-btn-img"></button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                            @if(!$hasMatching)
                            <p class="no-question-sentence">
                                No questions added.
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="test-maker-questions-container">
                        <div class="dropdown-header">
                            <button class="dropdown-title" type="button" onclick="showDropdown('dropdown-content-enumeration')">
                                Enumeration Questions
                                <span class="dropdown-icon">▼</span>
                            </button>
                        </div>
                        <div class="dropdown-content" id="dropdown-content-enumeration">
                            @php
                            $hasEnum = false;
                            @endphp
                            @foreach($enumerationQuestions as $testQuery)
                            @php
                            $hasEnum = true;
                            @endphp
                            <div class="dropdown-question-container">
                                <div class="dropdown-question-content">
                                    {{$testQuery->etDescription}}
                                </div>
                                @if(auth()->user()->id == $test->user_id)
                                <form class="dropdown-del-btn-container" method="POST" action="/test/enumeration/{{$test->tmID}}/{{$testQuery->tmetID}}/delete" onsubmit="return confirmDelete();">
                                    @csrf
                                    @method('delete')
                                    <button class="dropdown-del-btn"><img src="/images/delete-icon.png" class="dropdown-del-btn-img"></button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                            @if(!$hasEnum)
                            <p class="no-question-sentence">
                                No questions added.
                            </p>
                            @endif
                        </div>
                    </div>
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
            </div>
        </div>
    </div>
    <script>
        function confirmDelete() {
            if (confirm("Are you sure you want to delete this record?")) {
                // User clicked OK, proceed with form submission
                return true;
            } else {
                // User clicked Cancel, prevent form submission
                return false;
            }
        }

        function updateFormAction() {
            const selectElement = document.getElementById('random_select_option');
            const selectedValue = selectElement.value;
            const testID = selectElement.getAttribute('data-test-id')
            const form = document.getElementById('add_random_form');

            // Set the form's action attribute based on the selected option
            form.action = `/test/${testID}/${selectedValue}/add`;
        }

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

        function showDropdown(contentID) {
            const content = document.getElementById(contentID);
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'flex';
            } else {
                content.style.display = 'none';
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

        function confirmDelete() {
            if (confirm("Are you sure you want to delete this record?")) {
                // User clicked OK, proceed with form submission
                return true;
            } else {
                // User clicked Cancel, prevent form submission
                return false;
            }
        }
        const add_item_container = document.getElementById('add_item_container');
        const add_item_sub_container = document.getElementById('add_item_sub_container');
        const add_item_modal_header_close = document.getElementById('add_item_modal_header_close');
        const add_item_close_button = document.getElementById('add_item_close_button');
        document.getElementById('add-test-button').addEventListener('click', function() {
            add_item_container.style.display = "flex";
            setTimeout(() => {
                add_item_sub_container.classList.add("show");
            }, 10);
        });

        add_item_container.addEventListener("click", function(event) {
            if (event.target === add_item_container || event.target === add_item_modal_header_close || event.target === add_item_close_button) {
                add_item_container.style.display = "none";
                add_item_sub_container.classList.remove("show");
            }
        });


        const add_random_item_container = document.getElementById('add_random_item_container');
        const add_random_item_sub_container = document.getElementById('add_random_item_sub_container');
        const add_random_item_modal_header_close = document.getElementById('add_random_item_modal_header_close');
        const add_random_item_close_button = document.getElementById('add_random_item_close_button');
        document.getElementById('add-random-test-button').addEventListener('click', function() {
            add_random_item_container.style.display = "flex";
            setTimeout(() => {
                add_random_item_sub_container.classList.add("show");
            }, 10);
        });

        add_random_item_container.addEventListener("click", function(event) {
            if (event.target === add_random_item_container || event.target === add_random_item_modal_header_close || event.target === add_random_item_close_button) {
                add_random_item_container.style.display = "none";
                add_random_item_sub_container.classList.remove("show");
            }
        });
    </script>

    @if(session('lackingRows'))
    <script>
        // Display a JavaScript alert with the same message
        alert("Not enough rows.");
    </script>
    @endif
</body>

</html>