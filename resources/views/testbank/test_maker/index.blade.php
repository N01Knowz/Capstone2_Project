@extends('layouts.navigation')
@section('title', 'Test Maker')

@push('styles')
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/tf.css">
<link rel="stylesheet" href="/css/filter.css">
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
@endpush
@section('content')
<div class="test-body-header">
    <form method="get" action="test/create" class="add-test-button-anchor">
        <button class="add-test-button"><img src="/images/add-test-icon.png" class="add-test-button-icon">
            <p>Add New Test</p>
        </button>
    </form>
    <form method="GET" action="" class="searchbar-container">
        <input type="text" placeholder="Search tests here..." class="test-searchbar" name="search" @isset($searchInput) value="{{$searchInput}}" @endisset>
        <button class="search-button">Search</button>
    </form>
</div>
<div class="test-body-content">
    @include('layouts.filter_testmaker')
    <table class="test-body-table">
        <thead>
            <tr class="test-table-header">
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Total point(s)</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <!-- Table content goes here -->
            @foreach ($tests as $test)
            <tr id="test-question-description">
                <td class="test-body-column test-body-title" data-id="{{$test->tmID}}">
                    <p>{{$test->tmTitle}}</p>
                </td>
                <td class="test-body-column test-body-instruction" data-id="{{$test->tmID}}">
                    <p>{{$test->tmDescription}}</p>
                </td>
                <td class="test-body-column test-body-status" data-id="{{$test->tmID}}">
                    <div>
                        <p class="test-status-word" style="width: 3.5em;">@if($test->tmIsPublic == 0) Private @else Public @endif</p>
                        <img @if($test->tmIsPublic == 0) src="/images/closed-eye-icon-light.png" style="background-color: #C61D1F; padding: 0.1em;" @else src="/images/eye-icon-light.png" style="background-color: #2d9c18; padding: 0.1em;" @endif class="test-status-icon">
                    </div>
                </td>
                <td class="test-body-column test-body-points" data-id="{{$test->tmID}}">
                    <div>
                        <p>{{$test->tmTotal}}</p>
                    </div>
                </td>
                <td class="test-body-buttons-column" id="test-bb">
                    <div class="test-body-buttons-column-div">
                        <form method="POST" action="/test/{{$test->tmID}}/publish" class="button-delete-form" onsubmit="return confirmPublish();">
                            @csrf
                            @method('PUT')
                            <button class="test-body-buttons @if($test->tmIsPublic) button-disabled @else buttons-publish-button @endif" @if($test->tmIsPublic) disabled @endif>
                                <img src="/images/upload-icon-dark.png" class="test-body-buttons-icons">
                            </button>
                        </form>
                        <button class="test-body-buttons @if($test->tmIsPublic) button-disabled @else buttons-edit-button @endif" @if($test->tmIsPublic) disabled @endif id="test-edit-button" data-id="{{$test->tmID}}">
                            <img src="/images/edit-icon.png" class="test-body-buttons-icons">
                            <p>Edit</p>
                        </button>
                        <form method="GET" action="/print/tm/{{$test->tmID}}" class="button-delete-form" target="_blank">
                            <button class="test-body-buttons buttons-print-button"><img src="/images/print-icon-dark.png" class="test-body-buttons-icons">
                                <p>Print</p>
                            </button>
                        </form>
                        <form method="POST" action="/test/{{$test->tmID}}" class="button-delete-form" onsubmit="return confirmDelete();">
                            @csrf
                            @method('delete')
                            <button class="test-body-buttons @if($test->tmIsPublic) button-disabled @else buttons-delete-button @endif" @if($test->tmIsPublic) disabled @endif>
                                <img src="/images/delete-icon.png" class="test-body-buttons-icons">
                                <p>Delete</p>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $tests->onEachSide(1)->appends(request()->query())->links('pagination::default') }}
    </div>
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

    function confirmPublish() {
        if (confirm("Are you sure you want to publish this record? You will not be able to edit and delete this record and also won't be able to add, edit, and delete the items.")) {
            // User clicked OK, proceed with form submission
            return true;
        } else {
            // User clicked Cancel, prevent form submission
            return false;
        }
    }

    function noItemsPublish() {
        alert("There's no item for this record.");
        return false;
    }

    function handleRowClick(event) {
        const clickedColumn = event.currentTarget;
        const columnData = clickedColumn.getAttribute('data-id');
        window.location.href = "/test/" + columnData;
    }

    const columns = document.querySelectorAll('.test-body-column');
    columns.forEach(column => {
        column.addEventListener('click', handleRowClick);
    });

    const buttons = document.querySelectorAll(".buttons-edit-button");

    // Loop through each button and attach the event handler
    buttons.forEach(function(button) {
        button.onclick = function() {
            const dataID = this.getAttribute("data-id");
            window.location.href = "/test/" + dataID + "/edit";
        }
    });
</script>
@endsection