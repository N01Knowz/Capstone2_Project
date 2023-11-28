@extends('layouts.navigation')
@section('title', 'Multiple Choices')

@push('styles')
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/mcq.css">
<link rel="stylesheet" href="/css/filter.css">
@endpush
@section('content')

<div class="test-body-header">
    <form method="get" action="mcq/create" class="add-test-button-anchor">
        <button class="add-test-button"><img src="/images/add-test-icon.png" class="add-test-button-icon">
            <p>Add New Test</p>
        </button>
    </form>
    <form method="GET" action="" class="searchbar-container" id="filter-form">
        <input type="text" placeholder="Search tests here..." class="test-searchbar" name="search" @isset($searchInput) value="{{$searchInput}}" @endisset>
        <button class="search-button">Search</button>
    </form>
</div>
<div class="test-body-content">
    @include('layouts.filter')
    <div class="table-container">
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
                    <td class="test-body-column test-body-title" data-id="{{$test->qzID}}">
                        <p>{{$test->qzTitle}}</p>
                    </td>
                    <td class="test-body-column test-body-instruction" data-id="{{$test->qzID}}">
                        <p>{{$test->qzDescription}}</p>
                    </td>
                    <td class="test-body-column test-body-status" data-id="{{$test->qzID}}">
                        <div>
                            <p class="test-status-word" style="width: 3.5em;">@if($test->qzIsPublic == 0) Private @else Public @endif</p>
                            <img @if($test->qzIsPublic == 0) src="/images/closed-eye-icon-light.png" style="background-color: #C61D1F; padding: 0.1em;" @else src="/images/eye-icon-light.png" style="background-color: #2d9c18; padding: 0.1em;" @endif class="test-status-icon">
                        </div>
                    </td>
                    <td class="test-body-column test-body-points" data-id="{{$test->qzID}}">
                        <div>
                            <p>{{$test->subjectName}}</p>
                        </div>
                    </td>
                    <td class="test-body-buttons-column" id="test-bb">
                        <div class="test-body-buttons-column-div">
                            <form method="POST" action="/mcq/{{$test->qzID}}/publish" class="button-delete-form" @if($test->quiz_items_count == 0) onsubmit="return noItemsPublish();" @else onsubmit="return confirmPublish();" @endif>
                                @csrf
                                @method('PUT')
                                <button class="test-body-buttons @if($test->qzIsPublic) button-disabled @else buttons-publish-button @endif"><img src="/images/publish-icon-dark.png" class="test-body-buttons-icons">
                                    <p>Publish</p>
                                </button>
                            </form>
                            <button class="test-body-buttons @if($test->qzIsPublic) button-disabled @else buttons-edit-button @endif" id="test-edit-button" data-id="{{$test->qzID}}" @if($test->qzIsPublic) disabled @endif>
                                <img src="/images/edit-icon.png" class="test-body-buttons-icons">
                                <p>Edit</p>
                            </button>
                            <form method="GET" action="/print/mcq/{{$test->qzID}}" class="button-delete-form" target="_blank">
                                <button class="test-body-buttons buttons-print-button"><img src="/images/print-icon-dark.png" class="test-body-buttons-icons">
                                    <p>Print</p>
                                </button>
                            </form>
                            <form method="POST" action="/mcq/{{$test->qzID}}" class="button-delete-form" onsubmit="return confirmDelete();">
                                @csrf
                                @method('delete')
                                <button class="test-body-buttons @if($test->qzIsPublic) button-disabled @else buttons-delete-button @endif" @if($test->qzIsPublic) disabled @endif><img src="/images/delete-icon.png" class="test-body-buttons-icons">
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
        window.location.href = "/mcq/" + columnData;
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
            window.location.href = "/mcq/" + dataID + "/edit";
        }
    });
</script>

@if(session('publish'))
<script>
    alert("{{ session('publish') }}");
</script>
@endif
@endsection