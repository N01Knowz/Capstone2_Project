<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Maker</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/front_page.css">
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/tf.css">
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
                <form method="get" action="test/create" class="add-test-button-anchor">
                    <button class="add-test-button"><img src="/images/add-test-icon.png" class="add-test-button-icon">
                        <p>Add New Test</p>
                    </button>
                </form>
                <form method="GET" action="" class="searchbar-container">
                    <input type="text" placeholder="Search tests here..." class="test-searchbar" name="search">
                    <button class="search-button">Search</button>
                </form>
            </div>
            <div class="test-body-content">
                <table class="test-body-table">
                    <thead>
                        <tr class="test-table-header">
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Total point(s)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table content goes here -->
                        @foreach ($tests as $test)
                        <tr id="test-question-description">
                            <td class="test-body-column test-body-title" data-id="{{$test->tmID}}">
                                <p>{{$test->tmTitle}}</p>
                            </td>
                            <td class="test-body-column test-body-instruction" data-id="{{$test->tmID}}">
                                <p>{{$test->tmDescription}}</p>
                            </td>
                            <td class="test-body-column test-body-status" data-id="{{$test->tmID}}">
                                <div>
                                    <p class="test-status-word">@if($test->tmIsPublic == 0) Private @else Public @endif</p><img src="/images/eye-icon-light.png" class="test-status-icon">
                                </div>
                            </td>
                            <td class="test-body-column test-body-points" data-id="{{$test->tmID}}">
                                <div>
                                    <p>{{$test->tmTotal}}</p>
                                </div>
                            </td>
                            <td class="test-body-buttons-column" id="test-bb">
                                <div class="test-body-buttons-column-div">
                                    <button class="test-body-buttons buttons-add-question-button" id="test-add-question" data-id="{{$test->tmID}}"><img src="/images/add-test-icon.png" class="test-body-buttons-icons">
                                        <p>Add Question</p>
                                    </button>
                                    <button class="test-body-buttons buttons-edit-button" id="test-edit-button" data-id="{{$test->tmID}}"><img src="/images/edit-icon.png" class="test-body-buttons-icons">
                                        <p>Edit</p>
                                    </button>
                                    <form method="GET" action="/print/tm/{{$test->tmID}}" class="button-delete-form" target="_blank">
                                        <button class="test-body-buttons buttons-print-button"><img src="/images/print-icon-dark.png" class="test-body-buttons-icons">
                                            <p>Print</p>
                                        </button>
                                    </form>
                                    <form method="POST" action="/test/{{$test->tmID}}" class="button-delete-form" onsubmit="return confirmDelete();">
                                        @csrf
                                        @method('delete')
                                        <button class="test-body-buttons buttons-delete-button"><img src="/images/delete-icon.png" class="test-body-buttons-icons">
                                            <p>Delete</p>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

        document.getElementById("test-add-question").onclick = function() {
            const dataID = this.getAttribute("data-id")
            window.location.href = "/test/" + dataID + "/create_question";
        }

        function handleRowClick(event) {
            const clickedColumn = event.currentTarget;
            const columnData = clickedColumn.getAttribute('data-id');
            window.location.href = "/test/" + columnData;
        }

        const columns = document.querySelectorAll('.test-body-column');
        columns.forEach(column => {
            column.addEventListener('click', handleRowClick);
        });

        const buttons = document.querySelectorAll(".buttons-edit-button");

        // Loop through each button and attach the event handler
        buttons.forEach(function(button) {
            button.onclick = function() {
                const dataID = this.getAttribute("data-id");
                window.location.href = "/test/" + dataID + "/edit";
            }
        });
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