<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/add_page.css">
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/test_description.css">
    <link rel="stylesheet" href="/css/enumeration-test_description.css">
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
            <div class="test-type chosen-type" id="enumeration-test" data-icon-id="enumeration-icon">
                <a class="test-link" href="/enumeration" onclick="chosenTestType('enumeration-test')">
                    <img src="/images/enumeration-icon-dark.png" class="test-icon" data-icon-light="/images/enumeration-icon-light.png" data-icon-dark="/images/enumeration-icon-dark.png" id="enumeration-icon">
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
                <a href="/mcq" class="add-test-button-anchor">
                    <button class="add-test-button"><img src="/images/back-icon.png" class="add-test-button-icon"><p>Back</p></button>
                </a>
                <input type="text" placeholder="Search tests here..." class="test-searchbar">
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
                                <tr>
                                    <td><p>1</p></td>
                                    <td><p>Touch</p></td>
                                    <td><p>No</p></td>
                                    <td>
                                        <div class="questions-table-buttons-column-div">
                                            <button class="questions-table-buttons buttons-delete-button"><img src="/images/delete-icon.png"><p>Delete</p></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><p>1</p></td>
                                    <td><p>Taste</p></td>
                                    <td><p>No</p></td>
                                    <td>
                                        <div class="questions-table-buttons-column-div">
                                            <button class="questions-table-buttons buttons-delete-button"><img src="/images/delete-icon.png"><p>Delete</p></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><p>1</p></td>
                                    <td><p>Vision</p></td>
                                    <td><p>No</p></td>
                                    <td>
                                        <div class="questions-table-buttons-column-div">
                                            <button class="questions-table-buttons buttons-delete-button"><img src="/images/delete-icon.png"><p>Delete</p></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><p>1</p></td>
                                    <td><p>Hearing</p></td>
                                    <td><p>No</p></td>
                                    <td>
                                        <div class="questions-table-buttons-column-div">
                                            <button class="questions-table-buttons buttons-delete-button"><img src="/images/delete-icon.png"><p>Delete</p></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><p>1</p></td>
                                    <td><p>Smell</p></td>
                                    <td><p>No</p></td>
                                    <td>
                                        <div class="questions-table-buttons-column-div">
                                            <button class="questions-table-buttons buttons-delete-button"><img src="/images/delete-icon.png"><p>Delete</p></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="add-test-question-button"><img src="/images/add-test-icon.png"><p>Add Answer</p></button>
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
</body>
</html>