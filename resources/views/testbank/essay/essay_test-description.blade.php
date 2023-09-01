<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/essay_add_page.css">
    <link rel="stylesheet" href="/css/add_page.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/navigator.css">
</head>

<body>
    <div class="test-container">
        <div class="navigator">
            <div id="logo-container">
                <img src="/images/logo.png" id="logo">
                <p>Test Bank</p>
            </div>
            <div class="test-type chosen-type" id="essay-test" data-icon-id="essay-icon">
                <a class="test-link" href="/essay" onclick="chosenTestType('essay-test')">
                    <img src="/images/essay-icon-dark.png" class="test-icon" data-icon-light="/images/essay-icon-light.png" data-icon-dark="/images/essay-icon-dark.png" id="essay-icon">
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
                <a href="/essay" class="add-test-button-anchor">
                    <button class="add-test-button"><img src="/images/back-icon.png" class="add-test-button-icon">
                        <p>Back</p>
                    </button>
                </a>
                <input type="text" placeholder="Search tests here..." class="test-searchbar">
            </div>
            <form method="GET" action="/essay/{{$test->id}}/edit" class="test-body-content">
                <input type="hidden" name="id" value="{{auth()->user()->id;}}">
                <p class="text-input-label">Title<span class="red-asterisk"> *</span></p>
                <input type="text" class="textinput-base textarea-title text-input-background" name="title" required value="{{$test->test_title}}" readonly>
                @error('title')
                <div class="alert alert-dange red-asterisk">{{ $message }}</div>
                @enderror
                <p class="text-input-label label-margin-top">Question<span class="red-asterisk"> *</span></p>
                <textarea class="textinput-base textarea-question text-input-background" name="question" required readonly>{{$test->test_question}}</textarea>
                @error('question')
                <div class="alert alert-danger red-asterisk">{{ $message }}</div>
                @enderror
                <p class="text-input-label label-margin-top">Instructions</p>
                <textarea class="textinput-base textarea-instruction text-input-background" name="instruction" value="{{$test->test_instruction}}" readonly></textarea>
                <p class="text-input-label label-margin-top">Attach an Image(Optional)</p>
                <div>
                    <input type="text" class="text-input-background text-input-attach-image" name="image" value="{{$test->test_image}}" readonly>
                    <button class="text-input-image-button">Browse</button>
                </div>
                <p class="text-supported-format">Supported formats: .jpg, .png, .gif</p>
                <div class="share-container">
                    <input type="checkbox" class="share-checkbox" name="share" disabled>
                    <p class="text-input-label">Share with other faculties</p>
                </div>
                <table class="criteria-points-table">
                    <thead>
                        <tr>
                            <th class="criteria-column">
                                <p class="text-input-label">Criteria<span class="red-asterisk"> *</span></p>
                            </th>
                            <th class="points-column">
                                <p class="text-input-label">Point(s)</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input readonly type="text" class="criteria-point-input criteria-input" required placeholder="E.g Content" name="criteria_1" value="{{$question->item_question}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input readonly type="number" class="criteria-point-input point-input" required min="0" name="criteria_point_1" value="{{$question->question_point}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input readonly type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" name="criteria_2" value="{{$question->option_1}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input readonly type="number" class="criteria-point-input point-input" min="0" name="criteria_point_2" value="{{$question->option_2}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input readonly type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" name="criteria_3" value="{{$question->option_3}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input readonly type="number" class="criteria-point-input point-input" min="0" name="criteria_point_3" value="{{$question->option_4}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input readonly type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" name="criteria_4" value="{{$question->option_5}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input readonly type="number" class="criteria-point-input point-input" min="0" name="criteria_point_4" value="{{$question->option_6}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="criteria-point-sub-container">
                                    <input readonly type="text" class="criteria-point-input criteria-input" placeholder="(Optional)" name="criteria_5" value="{{$question->option_7}}">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input readonly type="number" class="criteria-point-input point-input" min="0" name="criteria_point_5" value="{{$question->option_8}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="text-input-label">Total:</p>
                            </td>
                            <td>
                                <div>
                                    <input readonly type="number" class="criteria-point-input point-input" min="0" name="criteria_point_5" value="{{$test->test_total_points}}" readonly>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="add-test-button-anchor">
                    <button class="save-test-button">Edit Test</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const pointInputs = document.querySelectorAll(".point-input");
        pointInputs.forEach(pointInput => {
            pointInput.addEventListener('input', handleTotalPoints);
        });

        function handleTotalPoints() {
            var total_points = document.getElementById("total_points");
            const pointInputs = document.querySelectorAll(".point-input");
            total_points.value = 0;
            let sum = 0;
            pointInputs.forEach(pointInput => {
                sum += parseInt(pointInput.value) || 0;
            });
            total_points.value = sum;
        }

        
    </script>
</body>

</html>