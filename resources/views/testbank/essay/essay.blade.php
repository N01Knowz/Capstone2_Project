<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Essay</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/front_page.css">
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/essay.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="test-container">
        <div class="header-navigator-container">
            <div class="menu-icon-container" onclick="toggleNavigator()">
                <img src="/images/menu-icon-light.png" alt="" class="menu-icon">
            </div>
            <div class="header-navigator-profile">
                <img class="header-navigator-profile-image" @if(is_null(auth()->user()->user_image)) src="/images/profile.png" @else src="/user_upload_images/{{auth()->user()->user_image}}" @endif>
                <p id="profile-name">{{auth()->user()->first_name;}} {{auth()->user()->last_name;}} ▼</p>
            </div>
        </div>
        <div class="modal-background" onclick="toggleNavigator()" id="modal-navigator"></div>
        <div class="navigator" id="navigator">
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
                <form method="get" action="/essay/create" class="add-test-button-anchor">
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
                            <th>Instruction</th>
                            <th>Status</th>
                            <th>Subject</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table content goes here -->
                        @foreach ($tests as $test)
                        <tr>
                            <td class="test-body-column test-body-title" data-id="{{$test->essID}}">
                                <p>{{$test->essTitle}}</p>
                            </td>
                            <td class="test-body-column test-body-instruction" data-id="{{$test->essID}}">
                                <p>{{$test->essInstruction}}</p>
                                <div class="question-labels">
                                    @isset(($test->tags['Realistic']))
                                    <div class="label r-label">Realistic</div>
                                    @endisset
                                    @isset(($test->tags['Investigative']))
                                    <div class="label i-label">Investigative</div>
                                    @endisset
                                    @isset(($test->tags['Artistic']))
                                    <div class="label a-label">Artistic</div>
                                    @endisset
                                    @isset(($test->tags['Social']))
                                    <div class="label s-label">Social</div>
                                    @endisset
                                    @isset(($test->tags['Enterprising']))
                                    <div class="label e-label">Enterprising</div>
                                    @endisset
                                    @isset(($test->tags['Conventional']))
                                    <div class="label c-label">Conventional</div>
                                    @endisset
                                    @isset(($test->tags['Unknown']))
                                    <div class="label u-label">Unknown</div>
                                    @endisset
                                </div>
                            </td>
                            <td class="test-body-column test-body-status" data-id="{{$test->essID}}">
                                <div>
                                    <p class="test-status-word" style="width: 3.5em;">@if($test->essIsPublic == 0) Private @else Public @endif</p>
                                    <img @if($test->essIsPublic == 0) src="/images/closed-eye-icon-light.png" style="background-color: #C61D1F; padding: 0.1em;" @else src="/images/eye-icon-light.png" style="background-color: #2d9c18; padding: 0.1em;" @endif class="test-status-icon">
                                </div>
                            </td>
                            <td class="test-body-column test-body-points" data-id="{{$test->essID}}">
                                <div>
                                    <p>{{$test->subjectName}}</p>
                                </div>
                            </td>
                            <td class="test-body-buttons-column">
                                <div class="test-body-buttons-column-div">
                                    <button class="test-body-buttons buttons-edit-button test-edit-button" data-id="{{$test->essID}}"><img src="/images/edit-icon.png" class="test-body-buttons-icons">
                                        <p>Edit</p>
                                    </button>
                                    <form method="POST" action="/essay/{{$test->essID}}" class="button-delete-form" onsubmit="return confirmDelete();">
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

    @if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
    @endif
    @if(session('store_success'))
    <script>
        alert("{{ session('store_success') }}");
    </script>
    @endif
    @if(session('update_success'))
    <script>
        alert("{{ session('update_success') }}");
    </script>
    @endif
    <script>
        function toggleNavigator() {
            var dropdown = document.getElementById("navigator");
            var modalNavigator = document.getElementById("modal-navigator");
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "flex";
            } else {
                dropdown.style.display = "none";
            }
            if (modalNavigator.style.display === "none" || modalNavigator.style.display === "") {
                modalNavigator.style.display = "block";
            } else {
                modalNavigator.style.display = "none";
            }
        }

        function toggleDropdown() {
            var dropdown = document.getElementById("dropdown-menu");
            var showButton = document.getElementById("profile-setting-icon");
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block";
                document.addEventListener('click', (event) => clickOutsideHandler(event, dropdown, showButton));
            } else {
                dropdown.style.display = "none";
                document.removeEventListener('click', clickOutsideHandler);
            }
        }

        function clickOutsideHandler(event, element, showButton) {
            if (!element.contains(event.target) && event.target !== showButton) {
                element.style.display = 'none';
                document.removeEventListener('click', clickOutsideHandler);
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

        document.addEventListener("DOMContentLoaded", function() {

            function handleRowClick(event) {
                const clickedColumn = event.currentTarget;
                const columnData = clickedColumn.getAttribute('data-id');
                window.location.href = "/essay/" + columnData;
            }

            const buttons = document.querySelectorAll(".buttons-edit-button");

            // Loop through each button and attach the event handler
            buttons.forEach(function(button) {
                button.onclick = function() {
                    const dataID = this.getAttribute("data-id");
                    window.location.href = "/essay/" + dataID + "/edit";
                }
            });

            const columns = document.querySelectorAll('.test-body-column');
            columns.forEach(column => {
                column.addEventListener('click', handleRowClick);
            });
        });
    </script>
</body>

</html>