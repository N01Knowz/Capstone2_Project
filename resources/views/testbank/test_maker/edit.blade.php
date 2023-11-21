@extends('layouts.navigation')
@section('title', 'Test Maker')

@push('styles')
<link rel="stylesheet" href="/css/add_page.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/navigator.css">
@endpush
@section('content')
<div class="test-body-header">
    <a href="/mcq" class="add-test-button-anchor">
        <button class="add-test-button"><img src="/images/back-icon.png" class="add-test-button-icon">
            <p>Back</p>
        </button>
    </a>
    <div class="searchbar-container">
    </div>
</div>
<form method="POST" action="/test/{{$test->tmID}}" class="test-body-content">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{auth()->user()->id;}}">
    <p class="text-input-label">Title<span class="red-asterisk"> *</span></p>
    <input type="text" class="textinput-base textarea-title text-input-background" name="title" value="{{$test->tmTitle}}">
    @error('title')
    <div class="alert alert-dange red-asterisk">{{ $message }}</div>
    @enderror
    <p class="text-input-label label-margin-top">Description<span class="red-asterisk"> *</span></p>
    <textarea class="textinput-base textarea-question text-input-background" name="description">{{$test->tmDescription}}</textarea>
    @error('description')
    <div class="alert alert-danger red-asterisk">{{ $message }}</div>
    @enderror
    <div class="share-container">
        <input type="checkbox" @if($test->tmIsPublic == '1') checked="true" @endif class="share-checkbox" name="share" >
        <p class="text-input-label">Share with other users</p>
    </div>
    <div class="add-test-button-anchor">
        <button class="save-test-button">Save Changes</button>
    </div>
</form>
@endsection