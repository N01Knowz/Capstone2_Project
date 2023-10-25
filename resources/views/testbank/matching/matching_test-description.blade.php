
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matching</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/add_page.css">
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/mcq_add_question.css">
    <link rel="stylesheet" href="/css/mt_add_questions.css">
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
    <link rel="stylesheet" href="/css/mt_add_questions.css">
    <link rel="stylesheet" href="/css/matching_test_description.css">
    <link rel="stylesheet" href="/css/test_description.css">
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
                    @if(auth()->user()->id == $test->user_id)
                    <button class="add-test-question-button" id="add_item_button"><img src="/images/add-test-icon.png">
                        <p>Add Test Item</p>
                    </button>
                    @endif
                </div>
            </div>
            <div class="test-body-content">
                <div class="test-profile-container">
                    <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->mtTitle}}</span></p>
                    <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->mtTotal}}</span></p>
                    <p class="test-profile-label">Description: <span class="test-profile-value">{{$test->mtDescription}}</span></p>
                </div>
                <div class="test-add-question-container">
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
                        <tbody>
                            @foreach($questions as $question)
                            <tr>
                                <td>
                                    <input class="mt-inputs" readonly type="text" value="{{$question->itmQuestion}}">
                                </td>
                                <td><input class="mt-inputs" readonly type="text" value="{{$question->itmAnswer}}"></td>
                                <td><input class="mt-inputs" readonly type="text" placeholder="0.00" value="{{$question->itmPoints}}"></td>
                                @if(auth()->user()->id == $test->user_id)
                                <td class="buttons-column">
                                    <div class="questions-table-buttons-column-div">
                                        <form action="/matching/{{$test->mtID}}/{{$question->itmID}}/edit" method="GET" class="question-table-button-form">
                                            <button class="questions-table-buttons buttons-edit-button"><img src="/images/edit-icon.png">
                                                <p>Edit</p>
                                            </button>
                                        </form>
                                        <form action="/matching/{{$question->itmID}}/delete_question" method="POST" class="question-table-button-form" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('delete')
                                            <button class="questions-table-buttons buttons-delete-button"><img src="/images/delete-icon.png">
                                                <p>Delete</p>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
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
        document.getElementById('add_item_button').addEventListener('click', function() {
            window.location.href = window.location.href + "/create_question";
        });

        function confirmDelete() {
            if (confirm("Are you sure you want to delete this record?")) {
                // User clicked OK, proceed with form submission
                return true;
            } else {
                // User clicked Cancel, prevent form submission
                return false;
            }
        }


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
    </script>
</body>

</html>
