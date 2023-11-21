<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" href="/images/logo.png">
    @stack('styles')
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    @yield('modal-contents')
    <div class="test-container">
        <div class="header-navigator-container">
            <div class="menu-icon-container" onclick="toggleNavigator()">
                <img src="/images/menu-icon-light.png" alt="" class="menu-icon">
            </div>
            <div class="header-navigator-profile" id="header-navigator-profile" onclick="toggleDropdownHeader()">
                <img class="header-navigator-profile-image" @if(is_null(auth()->user()->user_image)) src="/images/profile.png" @else src="/user_upload_images/{{auth()->user()->user_image}}" @endif>
                {{auth()->user()->first_name;}} {{auth()->user()->last_name;}} ▼
                <div class="header-setting-container" id="header-setting-container">
                    <div class="header-setting-dropdown-menu" id="header-profile-dropdown-menu">
                        <form action="/profile" method="get">
                            <button class="header-setting-profile">Profile</button>
                        </form>
                        <form action="/logout" method="POST" class="header-setting-logout-form">
                            @csrf
                            <button class="header-setting-logout">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-background" onclick="toggleNavigator()" id="modal-navigator"></div>
        <div class="navigator" id="navigator">
            <div id="logo-container">
                <img src="/images/logo.png" id="logo">
                <p>Test Bank</p>
            </div>
            <!-- <div class="test-type @if(isset($testPage) && $testPage === 'essay') chosen-type @endif" id="essay-test" data-icon-id="essay-icon">
                <a class="test-link" href="/essay" onclick="chosenTestType('essay-test')">
                    <img @if(isset($testPage) && $testPage === 'essay') src="/images/essay-icon-dark.png" @else src="/images/essay-icon-light.png" @endif class="test-icon" data-icon-light="/images/essay-icon-light.png" data-icon-dark="/images/essay-icon-dark.png" id="essay-icon">
                    <p>Essay Tests</p>
                </a>
            </div> -->
            <div class="test-type @if(isset($testPage) && $testPage === 'mcq') chosen-type @endif" id="mcq-test" data-icon-id="mcq-icon">
                <a class="test-link" href="/mcq" onclick="chosenTestType('mcq-test')">
                    <img @if(isset($testPage) && $testPage === 'mcq') src="/images/mcq-icon-dark.png" @else src="/images/mcq-icon-light.png" @endif class="test-icon" data-icon-light="/images/mcq-icon-light.png" data-icon-dark="/images/mcq-icon-dark.png" id="mcq-icon">
                    <p>Multiple Choices Tests</p>
                </a>
            </div>
            <div class="test-type @if(isset($testPage) && $testPage === 'tf') chosen-type @endif" id="tf-test" data-icon-id="tf-icon">
                <a class="test-link" href="/tf" onclick="chosenTestType('tf-test')">
                    <img @if(isset($testPage) && $testPage === 'tf') src="/images/tf-icon-dark.png" @else src="/images/tf-icon-light.png" @endif class="test-icon" data-icon-light="/images/tf-icon-light.png" data-icon-dark="/images/tf-icon-dark.png" id="tf-icon">
                    <p>True or False Tests</p>
                </a>
            </div>
            <!-- <div class="test-type  @if(isset($testPage) && $testPage === 'mtf') chosen-type @endif" id="mtf-test" data-icon-id="mtf-icon">
                <a class="test-link" href="/mtf" onclick="chosenTestType('mtf-test')">
                    <img @if(isset($testPage) && $testPage === 'mtf') src="/images/tf-icon-dark.png" @else src="/images/tf-icon-light.png" @endif class="test-icon" data-icon-light="/images/tf-icon-light.png" data-icon-dark="/images/tf-icon-dark.png" id="mtf-icon">
                    <p>Modified True or False Tests</p>
                </a>
            </div> -->
            <div class="test-type  @if(isset($testPage) && $testPage === 'matching') chosen-type @endif" id="matching-test" data-icon-id="matching-icon">
                <a class="test-link" href="/matching" onclick="chosenTestType('matching-test')">
                    <img @if(isset($testPage) && $testPage === 'matching') src="/images/matching-icon-dark.png" @else src="/images/matching-icon-light.png" @endif class="test-icon" data-icon-light="/images/matching-icon-light.png" data-icon-dark="/images/matching-icon-dark.png" id="matching-icon">
                    <p>Matching Type</p>
                </a>
            </div>
            <div class="test-type @if(isset($testPage) && $testPage === 'enumeration') chosen-type @endif" id="enumeration-test" data-icon-id="enumeration-icon">
                <a class="test-link" href="/enumeration" onclick="chosenTestType('enumeration-test')">
                    <img @if(isset($testPage) && $testPage === 'enumeration') src="/images/enumeration-icon-dark.png" @else src="/images/enumeration-icon-light.png" @endif class="test-icon" data-icon-light="/images/enumeration-icon-light.png" data-icon-dark="/images/enumeration-icon-dark.png" id="enumeration-icon">
                    <p>Enumeration</p>
                </a>
            </div>
            <div class="test-type  @if(isset($testPage) && $testPage === 'test') chosen-type @endif" id="test-test" data-icon-id="test-icon">
                <a class="test-link" href="/test" onclick="chosenTestType('test-test')">
                    <img @if(isset($testPage) && $testPage === 'test') src="/images/test-icon-dark.png" @else src="/images/test-icon-light.png" @endif class="test-icon" data-icon-light="/images/test-icon-light.png" data-icon-dark="/images/test-icon-dark.png" id="test-icon">
                    <p>Test</p>
                </a>
            </div>
        </div>
        <div class="test-body">
            @yield('content')
        </div>
    </div>

    
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

        function toggleDropdownHeader() {
            var dropdown = document.getElementById("header-setting-container");
            var showButton = document.getElementById("header-navigator-profile");
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


        
    </script>
</body>

</html>