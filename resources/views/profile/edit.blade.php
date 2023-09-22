<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="icon" href="/images/logo.png">
    <link rel="stylesheet" href="/css/navigator.css">
    <link rel="stylesheet" href="/css/body.css">
    <link rel="stylesheet" href="/css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <!-- <h1>Your id is: {{auth()->user()->id;}}</h1> -->
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
            <div class="profile-container">
                <img @if(is_null(auth()->user()->user_image)) src="/images/profile.png" @else src="/user_upload_images/{{auth()->user()->user_image}}" @endif id="profile-pic">
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
            <div class="user-profile-container">
                <form method="POST" class="user-profile-sub-container" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <img id="selectedImage" class="user-profile-picture" @if(is_null(auth()->user()->user_image)) src="/images/profile.png" @else src="/user_upload_images/{{auth()->user()->user_image}}" @endif>
                    <input type="file" id="imageInput" style="display:none;" name="imageInput" accept="image/*">
                    <button class="user-profile-button" type="button" id="browseButton">Select Image</button>
                    <div class="edit-user-name-container">
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" value="{{auth()->user()->first_name;}}" required class="user-name-input">
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" value="{{auth()->user()->last_name;}}" required class="user-name-input">
                    </div>

                    <button class="user-profile-button edit-profile-button">Save Edit</button>
                    <a href="/profile">
                        <button class="user-profile-button new-password-button" type="button">Cancel</button>
                    </a>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </form>
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
        // JavaScript Code
        document.addEventListener("DOMContentLoaded", function() {

            // Get references to the text input, button, and file input
            const choosePhotoButton = document.getElementById('browseButton');
            const imageInput = document.getElementById('imageInput');
            const selectedImage = document.getElementById('selectedImage');


            // Add a click event listener to the button
            choosePhotoButton.addEventListener('click', () => {
                // Trigger a click event on the file input
                imageInput.click();
            });

            // Listen for changes in the file input
            imageInput.addEventListener('change', () => {
                const selectedFile = imageInput.files[0];

                // Check if a file was selected
                if (selectedFile) {
                    // Check the file extension
                    const fileExtension = selectedFile.name.split('.').pop().toLowerCase();

                    if (['gif', 'png', 'jpeg', 'jpg'].includes(fileExtension)) {

                        // Display the selected image
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            selectedImage.src = e.target.result;
                        };
                        reader.readAsDataURL(selectedFile);

                    } else {
                        alert('Please select a GIF, PNG, or JPEG image.');
                    }
                }
            });
        });
    </script>
</body>

</html>