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
    <form method="POST" class="user-profile-sub-container">
        @csrf
        @method('PUT')
        <div class="edit-user-name-container">
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" required class="user-name-input">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required class="user-name-input">
            <label for="new_password_confirmation">New Password Confirmation:</label>
            <input type="password" name="new_password_confirmation" required class="user-name-input">
        </div>

        <button class="user-profile-button edit-profile-button">Save Edit</button>
        <a href="/profile">
            <button class="user-profile-button new-password-button" type="button">Cancel</button>
        </a>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li style="color: red;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </form>
</div>
@endsection