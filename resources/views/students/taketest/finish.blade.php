@extends('layouts.student_navigation')
@section('title', 'Take Test')

@push('styles')
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/finishtest.css">
@endpush

@section('modal-contents')
@endsection
@section('content')
<div class="body-content">
    <div class="finish-content">
        <p>Your score is {{$points}}/{{$total}}</p>
        <a href="/taketest">
            <button class="confirm-button">Confirm</button>
        </a>
    </div>
</div>
@endsection