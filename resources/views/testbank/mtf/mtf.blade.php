@extends('layouts.navigation')
@section('title', 'Modified True or False')

@push('styles')
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/tf.css">
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
@endpush
@section('content')
<div class="test-body-header">
    <form method="get" action="mtf/create" class="add-test-button-anchor">
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
                <th>Subject</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <!-- Table content goes here -->
            @foreach ($tests as $test)
            <tr id="test-question-description">
                <td class="test-body-column test-body-title" data-id="{{$test->mtfID}}">
                    <p>{{$test->mtfTitle}}</p>
                </td>
                <td class="test-body-column test-body-instruction" data-id="{{$test->mtfID}}">
                    <p>{{$test->mtfDescription}}</p>
                </td>
                <td class="test-body-column test-body-status" data-id="{{$test->mtfID}}">
                    <div>
                        <p class="test-status-word" style="width: 3.5em;">@if($test->mtfIsPublic == 0) Private @else Public @endif</p>
                        <img @if($test->mtfIsPublic == 0) src="/images/closed-eye-icon-light.png" style="background-color: #C61D1F; padding: 0.1em;" @else src="/images/eye-icon-light.png" style="background-color: #2d9c18; padding: 0.1em;" @endif class="test-status-icon">
                    </div>
                </td>
                <td class="test-body-column test-body-points" data-id="{{$test->mtfID}}">
                    <div>
                        <p>{{$test->subjectName}}</p>
                    </div>
                </td>
                <td class="test-body-buttons-column" id="test-bb">
                    <div class="test-body-buttons-column-div">
                        <button class="test-body-buttons buttons-edit-button" id="test-edit-button" data-id="{{$test->mtfID}}"><img src="/images/edit-icon.png" class="test-body-buttons-icons">
                            <p>Edit</p>
                        </button>
                        <form method="GET" action="/print/mtf/{{$test->mtfID}}" class="button-delete-form" target="_blank">
                            <button class="test-body-buttons buttons-print-button"><img src="/images/print-icon-dark.png" class="test-body-buttons-icons">
                                <p>Print</p>
                            </button>
                        </form>
                        <form method="POST" action="/mtf/{{$test->mtfID}}" class="button-delete-form" onsubmit="return confirmDelete();">
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
<script>
    function confirmDelete() {
        if (confirm("Are you sure you want to delete this record?")) {
            // User clicked OK, proceed with form submission
            return true;
        } else {
            // User clicked Cancel, prevent form submission
            return false;
        }
    }

    function handleRowClick(event) {
        const clickedColumn = event.currentTarget;
        const columnData = clickedColumn.getAttribute('data-id');
        window.location.href = "/mtf/" + columnData;
    }

    const columns = document.querySelectorAll('.test-body-column');
    columns.forEach(column => {
        column.addEventListener('click', handleRowClick);
    });

    // Select all elements with the class "buttons-edit-button"
    const buttons = document.querySelectorAll(".buttons-edit-button");

    // Loop through each button and attach the event handler
    buttons.forEach(function(button) {
        button.onclick = function() {
            const dataID = this.getAttribute("data-id");
            window.location.href = "/mtf/" + dataID + "/edit";
        }
    });
</script>
@endsection