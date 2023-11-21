@extends('layouts.navigation')
@section('title', 'Profile')

@push('styles')
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/profile.css">
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
@endpush
@section('content')
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
<script>
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
@endsection