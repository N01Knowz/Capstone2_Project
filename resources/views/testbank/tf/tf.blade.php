@extends('layouts.navigation')
@section('title', 'True or False')

@push('styles')
<link rel="stylesheet" href="/css/filter.css">
<link rel="stylesheet" href="/css/teacher_front-page.css">
<link rel="stylesheet" href="/css/tf.css">
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
@endpush
@section('content')
<div class="test-body-content">
    @include('layouts.filter')
    <div class="table-container">
        <div class="test-body-header">
            <form method="get" action="tf/create" class="add-test-button-anchor">
                <button class="add-test-button"><img src="/images/add-test-icon.png" class="add-test-button-icon">
                    <p>Add New Test</p>
                </button>
            </form>
            <form method="GET" action="" class="searchbar-container" id="filter-form">
                <input type="text" placeholder="Search tests here..." class="test-searchbar" name="search" @isset($searchInput) value="{{$searchInput}}" @endisset>
                <button class="search-button">Search</button>
            </form>
        </div>
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
                    <td class="test-body-column test-body-title" data-id="{{$test->tfID}}">
                        <p>{{$test->tfTitle}}</p>
                    </td>
                    <td class="test-body-column test-body-instruction" data-id="{{$test->tfID}}">
                        <p>{{$test->tfDescription}}</p>
                    </td>
                    <td class="test-body-column test-body-status" data-id="{{$test->tfID}}">
                        <div>
                            @if($test->qzIsPublic == 0)
                            <p class="test-status-word" style="width: 3.5em; font-weight: bold;">Unpublished </p>
                            @else
                            <p class="test-status-word" style="width: 3.5em; color: green; font-weight: bold;"> Published</p>@endif
                        </div>
                    </td>
                    <td class="test-body-column test-body-points" data-id="{{$test->tfID}}">
                        <div>
                            <p>{{$test->subjectName}}</p>
                        </div>
                    </td>
                    <td class="test-body-buttons-column" id="test-bb">
                        <div class="test-body-buttons-column-div">
                            <form method="POST" action="/tf/{{$test->tfID}}/publish" class="button-delete-form" @if($test->tf_items_count == 0) onsubmit="return noItemsPublish();" @else onsubmit="return confirmPublish();" @endif>
                                @csrf
                                @method('PUT')
                                <button class="test-body-buttons @if($test->tfIsPublic) button-disabled @endif">
                                    <img src="/images/upload-icon-dark.png" class="test-body-buttons-icons">
                                </button>
                            </form>
                            <button class="test-body-buttons @if($test->tfIsPublic) button-disabled else buttons-edit-button @endif" @if($test->tfIsPublic) disabled @endif id="test-edit-button" data-id="{{$test->tfID}}">
                                <img src="/images/edit-text-icon-dark.png" class="test-body-buttons-icons">
                            </button>
                            <form method="GET" action="/print/tf/{{$test->tfID}}" class="button-delete-form" target="_blank">
                                <button class="test-body-buttons"><img src="/images/printing-icon-dark.png" class="test-body-buttons-icons">
                                </button>
                            </form>
                            <form method="POST" action="/tf/{{$test->tfID}}" class="button-delete-form" onsubmit="return confirmDelete();">
                                @csrf
                                @method('delete')
                                <button class="test-body-buttons @if($test->tfIsPublic) button-disabled @endif" @if($test->tfIsPublic) disabled @endif>
                                    <img src="/images/delete-icon-dark.png" class="test-body-buttons-icons">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{$tests->onEachSide(1)->links('pagination::default') }}
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

    function noItemsPublish() {
        alert("There's no item for this record.");
        return false;
    }

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


    function confirmPublish() {
        if (confirm("Are you sure you want to publish this record? You will not be able to edit and delete this record and also won't be able to add, edit, and delete the items.")) {
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
        window.location.href = "/tf/" + columnData;
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
            window.location.href = "/tf/" + dataID + "/edit";
        }
    });
</script>
@if(session('publish'))
<script>
    alert("{{ session('publish') }}");
</script>
@endif
@endsection