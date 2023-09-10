<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>True or False</title>
    <link rel="icon" href="/images/logo.png">
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
            <div class="test-type chosen-type" id="tf-test" data-icon-id="tf-icon">
                <a class="test-link" href="/tf" onclick="chosenTestType('tf-test')">
                    <img src="/images/tf-icon-dark.png" class="test-icon" data-icon-light="/images/tf-icon-light.png" data-icon-dark="/images/tf-icon-dark.png" id="tf-icon">
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
                <img src="/images/profile.png" id="profile-pic">
                <div class="info">
                    <p id="profile-name">{{auth()->user()->first_name;}} {{auth()->user()->last_name;}}</p>
                    <p id="profile-email">{{auth()->user()->email;}}</p>
                </div>
                <div class="setting-container">
                    <img src="/images/icons8-gear-50.png" id="profile-setting-icon" onclick="toggleDropdown()">
                    <div class="setting-dropdown-menu" id="dropdown-menu">
                        <button class="setting-profile">Profile</button>
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
                <div href="/mcq" class="add-test-button-anchor">
                    <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">Back</button>
                </div>
                <div class="searchbar-container">
                </div>
            </div>
            <div class="test-body-content">
                <div class="test-profile-container">
                    <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->test_title}}</span></p>
                    <p class="test-profile-label">Test description: <span class="test-profile-value">{{$test->test_instruction}}</span></p>
                    <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->test_total_points}}</span></p>
                </div>
                <div class="test-add-question-container">
                    <p class="text-input-label">Item Question <span class="red-asterisk">*</span></p>
                    <textarea class="text-input" name="item_question" readonly>{{$question->item_question}}</textarea>
                    @error('item_question')
                    <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                    @enderror
                    <p class="text-input-label">Attach an Image(Optional)</p>
                    <div>
                        <input type="text" class="text-input-attach-image" name="question_image" value="{{$question->question_image}}" readonly>
                        <button class="text-input-image-button">Browse</button>
                    </div>
                    <p class="text-supported-format">Supported formats: .jpg, .png, .gif</p>
                    <p class="text-input-label">Number of Choices/Options(Max. 10)</p>
                    @for($i = 1; $i <= $question->choices_number; $i++)
                        <div id="optionsContainer">
                            <p class="text-input-label">Option {{$i}}</p>
                            <textarea class="summernote" readonly disabled name="option_{{$i}}" id="option_{{$i}}">{{data_get($question, 'option_' . $i )}}</textarea>
                            @error('option_1')
                            <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                            @enderror
                        </div>
                        @endfor
                        <div class="item-answer-points-container">
                            <div class="correct-answer-container">
                                <p class="text-input-label">Answer <span class="red-asterisk">*</span></p>
                                <input class="select-option" type="text" readonly value="Option {{$question->question_answer}}">
                                <!-- <select class="select-option" id="option-select" name="question_answer" readonly>
                            @for($i = 1; $i <= $question->choices_number; $i++)
                                <option value="{{$i}}" @if($i == $question->question_answer) selected @endif>Option {{$i}}</option>
                            @endfor -->
                                </select>
                            </div>
                            <div class="item-point-container">
                                <p class="text-input-label">Item Point(s) <span class="red-asterisk">*</span></p>
                                <input type="text" class="point-input" value="{{$question->question_point}}" name="question_point" readonly>
                            </div>
                        </div>
                        <button class="save-test-button" id="edit-quiz-button" data-page="/tf/{{$test->id}}/{{$question->id}}/edit">Edit Quiz Item</button>
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
        document.getElementById('back-button').addEventListener('click', function() {
            window.history.back();
        });

        $('.summernote').summernote({
            placeholder: 'Enter Option...',
            tabsize: 2,
            height: 100,
            toolbar: [

            ]
        });
        for (var i = 1; i <= 10; i++) {
            $('#option_' + i).summernote('disable');
        }

        document.getElementById("edit-quiz-button").onclick = function() {
            const dataPage = this.getAttribute("data-page")
            window.location.href = dataPage;
        }
    </script>
</body>

</html>