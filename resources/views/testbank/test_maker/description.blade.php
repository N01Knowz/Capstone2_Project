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
    <link rel="stylesheet" href="/css/test_maker_description.css">
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
                    <a href="/test/{{$test->id}}/essay">
                        <button class="select-type-test-button">Essay</button>
                    </a>
                    <a href="/test/{{$test->id}}/mcq">
                        <button class="select-type-test-button">MCQ</button>
                    </a>
                    <a href="/test/{{$test->id}}/tf">
                        <button class="select-type-test-button">True or False</button>
                    </a>
                    <a href="/test/{{$test->id}}/mtf">
                        <button class="select-type-test-button">Modified True or False</button>
                    </a>
                    <a href="/test/{{$test->id}}/matching">
                        <button class="select-type-test-button">Matching</button>
                    </a>
                    <a href="/test/{{$test->id}}/enumeration">
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
                <a href="/enumeration" class="add-test-button-anchor">
                    <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
                        <p>Back</p>
                    </button>
                </a>
                <div class="searchbar-container">
                </div>
            </div>
            <div class="test-body-content">
                <div class="test-profile-container">
                    <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->test_title}}</span></p>
                    <p class="test-profile-label">Test description: <span class="test-profile-value">{{$test->test_instruction}}</span></p>
                    <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->test_total_points}}</span></p>
                </div>
                <div class="test-questions-container">
                    <div class="test-questions-table-container">
                        <p class="test-question-label">Enumeration Answers</p>
                        <table class="questions-table">
                            <thead>
                                <tr>
                                    <th class="enumeration-questions-table-no-column">No.</th>
                                    <th class="enumeration-questions-table-answer-column">Answer(s)</th>
                                    <th class="enumeration-questions-table-sensitive-column">Case Sensitive</th>
                                    <th class="enumeration-questions-table-buttons-column"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $question)
                                <tr>
                                    <td>
                                        <p>{{$loop->iteration}}</p>
                                    </td>
                                    <td>
                                        <p>{{$question->item_question}}</p>
                                    </td>
                                    <td>
                                        <p>@if($question->option_1 == "0")
                                            No
                                            @else
                                            Yes
                                            @endif
                                        </p>
                                    </td>
                                    <td>
                                        <form action="/enumeration/{{$question->id}}/delete_question" method="POST" class="questions-table-buttons-column-div" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('delete')
                                            <button class="questions-table-buttons buttons-delete-button"><img src="/images/delete-icon.png">
                                                <p>Delete</p>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button class="add-test-question-button" id="add-test-button"><img src="/images/add-test-icon.png">
                            <p>Add Item</p>
                        </button>
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
    </script>

</body>

</html>