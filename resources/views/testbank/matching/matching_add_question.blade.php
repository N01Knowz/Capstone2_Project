<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            <div class="profile-container">
                <img src="/images/profile.png" id="profile-pic">
                <div class="info">
                    <p id="profile-name">{{auth()->user()->first_name;}} {{auth()->user()->last_name;}}</p>
                    <p id="profile-email">{{auth()->user()->email;}}</p>
                </div>
                <img src="/images/icons8-gear-50.png" id="profile-setting-icon">
            </div>
        </div>
        <div class="test-body">
            <div class="test-body-header">
                <div class="add-test-button-anchor">
                    <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">Back</button>
                </div>
                <input type="text" placeholder="Search tests here..." class="test-searchbar">
            </div>
            <div class="test-body-content">
                <div class="test-profile-container">
                    <p class="test-profile-label">Test name: <span class="test-profile-value">Science Matching Type</span></p>
                    <p class="test-profile-label">Test description: <span class="test-profile-value">Science matching type test</span></p>
                    <p class="test-profile-label">Total point(s): <span class="test-profile-value">1.00</span></p>
                </div>
                <div class="test-add-question-container">
                    <p class="text-input-label">Item Question <span class="red-asterisk">*</span></p>
                    <textarea class="text-input">Matching Type test 1</textarea>
                    <p class="text-input-label">Number of Choices/Options(Max. 10)</p>
                    <input type="text" class="text-input-choices" value="2">
                    <p class="mt-note">Note: you may add extra choices (distractors) by adding an answer with a blank item text. Blank item test will not be added to the list of answerable items (including points).</p>
                    <div class="mt-choices-container">
                        <div class="mt-choices-inputs-container">
                            <p class="test-profile-label">Item Text <span class="red-asterisk">*</span></p>
                            <input class="mt-inputs" type="text" placeholder="Item 1">
                            <input class="mt-inputs" type="text" placeholder="Item 2">
                        </div>
                        <div class="mt-choices-inputs-container">
                            <p class="test-profile-label">Answer <span class="red-asterisk">*</span></p>
                            <input class="mt-inputs" type="text" placeholder="Answer 1">
                            <input class="mt-inputs" type="text" placeholder="Answer 2">
                        </div>
                        <div class="mt-choices-inputs-container mt-point-container">
                            <p class="test-profile-label">Point(s) <span class="red-asterisk">*</span></p>
                            <input class="mt-inputs" type="text" placeholder="0.00">
                            <input class="mt-inputs" type="text" placeholder="0.00">
                        </div>
                    </div>
                    <button class="save-test-button">Save Quiz Item</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('back-button').addEventListener('click', function() {
            window.history.back();
        });

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