<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modified True or False</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/add_page.css">
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/body.css">
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
            <div class="test-type chosen-type" id="mtf-test" data-icon-id="mtf-icon">
                <a class="test-link" href="/mtf" onclick="chosenTestType('mtf-test')">
                    <img src="/images/tf-icon-dark.png" class="test-icon" data-icon-light="/images/tf-icon-light.png" data-icon-dark="/images/tf-icon-dark.png" id="mtf-icon">
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
                <a href="/mtf" class="add-test-button-anchor">
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
                    <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->mtfTitle}}</span></p>
                    <p class="test-profile-label">Test description: <span class="test-profile-value">{{$test->mtfDescription}}</span></p>
                    <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->mtfTotal}}</span></p>
                </div>
                <div class="test-questions-container">
                    <div class="test-questions-table-container">
                        <p class="test-question-label">Test Items</p>
                        <table class="questions-table">
                            <thead>
                                <tr>
                                    <th class="questions-table-no-column">No.</th>
                                    <th class="questions-table-question-column">Question</th>
                                    <th class="questions-table-point-column">Point(s)</th>
                                    @if(auth()->user()->id == $test->user_id)
                                    <th class="questions-table-buttons-column"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $question)
                                <tr id="test-question-item-description">
                                    <td class="question-description" data-test-id="{{$test->mtfID}}" data-question-id="{{$question->itmID}}">
                                        <p>{{ $loop->index + 1}}</p>
                                    </td>
                                    <td class="question-description" data-test-id="{{$test->mtfID}}" data-question-id="{{$question->itmID}}">
                                        <p>{{$question->itmQuestion}}</p>
                                        <div class="question-labels">
                                            @isset(($question->tags['Realistic']))
                                            <div class="label r-label">Realistic</div>
                                            @endisset
                                            @isset(($question->tags['Investigative']))
                                            <div class="label i-label">Investigative</div>
                                            @endisset
                                            @isset(($question->tags['Artistic']))
                                            <div class="label a-label">Artistic</div>
                                            @endisset
                                            @isset(($question->tags['Social']))
                                            <div class="label s-label">Social</div>
                                            @endisset
                                            @isset(($question->tags['Enterprising']))
                                            <div class="label e-label">Enterprising</div>
                                            @endisset
                                            @isset(($question->tags['Conventional']))
                                            <div class="label c-label">Conventional</div>
                                            @endisset
                                            @isset(($question->tags['Unknown']))
                                            <div class="label u-label">Unknown</div>
                                            @endisset
                                        </div>
                                    </td>
                                    <td class="question-description" data-test-id="{{$test->mtfID}}" data-question-id="{{$question->itmID}}">
                                        <p>{{$question->itmPointsTotal}}</p>
                                    </td>
                                    @if(auth()->user()->id == $test->user_id)
                                    <td>
                                        <div class="questions-table-buttons-column-div">
                                            <form action="/mtf/{{$test->mtfID}}/{{$question->itmID}}/edit" method="GET" class="question-table-button-form">
                                                <button class="questions-table-buttons buttons-edit-button"><img src="/images/edit-icon.png">
                                                    <p>Edit</p>
                                                </button>
                                            </form>
                                            <form action="/mtf/{{$question->itmID}}/delete_question" method="POST" class="question-table-button-form" onsubmit="return confirmDelete();">
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
                <script>
                    document.getElementById('add_item_button').addEventListener('click', function() {
                        window.location.href = window.location.href + "/create_question";
                    });
                </script>
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

        function handleRowClick(event) {
            const clickedColumn = event.currentTarget;
            const questionID = clickedColumn.getAttribute('data-question-id');
            const testID = clickedColumn.getAttribute('data-test-id');
            window.location.href = "/mtf/" + testID + "/" + questionID;
        }

        const columns = document.querySelectorAll('.question-description');
        columns.forEach(column => {
            column.addEventListener('click', handleRowClick);
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
    </script>
</body>

</html>