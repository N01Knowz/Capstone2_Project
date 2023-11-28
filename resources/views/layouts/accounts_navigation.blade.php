<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/admin_navigator.css">
    @stack('styles')
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    @yield('modal-contents')
    <div class="main-container">
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
                        <form action="/logout" method="POST" class="header-setting-logout-form" onsubmit="return confirmLogout();">
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
                <img src="/images/logoWhite.png" id="logo">
                <p>Test Bank</p>
            </div>
            <div class="page-type @if(isset($pageType) && $pageType === 'accounts') chosen-type @endif" >
                <a class="test-link" href="/accounts">
                    <img @if(isset($pageType) && $pageType==='accounts' ) src="/images/users-icon-dark.png" @else src="/images/users-icon-light.png" @endif class="page-type-icon">
                    <p>Manage Accounts</p>
                </a>
            </div>
            <div class="page-type @if(isset($pageType) && $pageType === 'managetest') chosen-type @endif">
                <a class="test-link" href="/managetest">
                    <img @if(isset($pageType) && $pageType==='managetest' ) src="/images/manage-test-icon-dark.png" @else src="/images/manage-test-icon-light.png" @endif class="page-type-icon">
                    <p>Manage Tests</p>
                </a>
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                // User clicked OK, proceed with form submission
                return true;
            } else {
                // User clicked Cancel, prevent form submission
                return false;
            }
        }
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