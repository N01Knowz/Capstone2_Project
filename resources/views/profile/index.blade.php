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
    <div class="user-profile-sub-container">
        <img @if(is_null(auth()->user()->user_image)) src="/images/profile.png" @else src="/user_upload_images/{{auth()->user()->user_image}}" @endif class="user-profile-picture">
        <div class="user-name-email">
            <p class="user-profile-name">{{auth()->user()->first_name;}} {{auth()->user()->last_name;}}</p>
            <p class="user-profile-email">{{auth()->user()->email;}}</p>
        </div>
        <form action="/profile/edit" method="get">
            <button class="user-profile-button edit-profile-button">Edit Profile</button>
        </form>
        <form action="/profile/new_password" method="get">
            <button class="user-profile-button new-password-button">New Password</button>
        </form>
    </div>
</div>
@endsection