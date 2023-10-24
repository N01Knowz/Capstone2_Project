<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/body.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
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
            <div class="test-type chosen-type" id="mcq-test" data-icon-id="mcq-icon">
                <a class="test-link" href="/mcq" onclick="chosenTestType('mcq-test')">
                    <img src="/images/mcq-icon-dark.png" class="test-icon" data-icon-light="/images/mcq-icon-light.png" data-icon-dark="/images/mcq-icon-dark.png" id="mcq-icon">
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
                    <p id="profile-name">Some Guy's Name</p>
                    <p id="profile-email">someguyemail@gmail.com</p>
                </div>
                <img src="/images/icons8-gear-50.png" id="profile-setting-icon">
            </div>
        </div>
        <div class="test-body">
            @yield('content')
        </div>
    </div>
    <script>
        function handleRowClick(event) {
            window.location.href = "mcq/1/description";
        }

        const columns = document.querySelectorAll('.test-body-column');
        columns.forEach(column => {
            column.addEventListener('click', handleRowClick);
        });

        document.getElementById("test-add-question").onclick = function() {
            window.location.href = "mcq/question/add";
        }
    </script>
    <!-- <script>
        function chosenTestType(newTestTypeId) {
            const oldDivElement = document.querySelector('.chosen-type');
            if (oldDivElement) {
                oldDivElement.classList.remove('chosen-type');
                flipIconColor(oldDivElement);
            }

            console.log(newTestTypeId);
            const newDivElement = document.getElementById(newTestTypeId);
            newDivElement.classList.add('chosen-type');
            flipIconColor(newDivElement);
        }

        function flipIconColor(divElement) {
            if (divElement) {
                const testIconId = divElement.getAttribute('data-icon-id');
                const iconElement = document.getElementById(testIconId);

                const getIconLight = iconElement.getAttribute('data-icon-light');
                const getIconDark = iconElement.getAttribute('data-icon-dark');

                if (divElement.classList.contains('chosen-type')) {
                    iconElement.src = getIconDark;
                }
                else {
                    iconElement.src = getIconLight;
                }
            }
        }
    </script> -->
    <!-- <script src="/javascript/index.js"></script> -->
</body>
</html>