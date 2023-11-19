@extends('layouts.navigation')
@section('title', 'True or False')

@push('styles')
<link rel="stylesheet" href="/css/add_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/test_description.css">
<link rel="stylesheet" href="/css/tf_test_description.css">
@endpush
@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
    alert("{{ $error }}");
</script>
@endforeach
@endif
@if(session('wrong_template'))
<script>
    var message = "{{ session('wrong_template') }}";
    alert(message);
</script>
@endif
@if(session('success'))
<script>
    var message = "{{ session('success') }}";
    alert(message);
</script>
@endif
@section('modal-contents')
<div class="add-item-container" id="add_item_container">
    <div class="add-item-sub-container" id="add_item_sub_container">
        <div class="add-item-modal-header">
            <p class="add-item-enter-answer">Download template <span><a href="{{ route('tf-excel') }}" class="btn btn-primary">here</a></span></p>
            <button class="add-item-modal-header-close" id="add_item_modal_header_close">x</button>
        </div>
        <div class="add-item-modal-body">
            <div class="add-item-modal-body-content">
                <strong>Guide: Write below the header and make sure to use the template.</strong>
                <ul>
                    <li><strong>Question:</strong> Question of the item <strong>(Required)</strong></li>
                    <li><strong>Item Points:</strong> Points for the item <strong>(1 point if blank)</strong></li>
                    <li><strong>Answer Number:</strong> Number representing the correct answer choice. <strong>0 is False, 1 is True. (Required)</strong></li>
                    <li><strong>If there are questions that fails to follow the template. It will be skipped and not be uploaded</strong></li>
                </ul>
                <form action="/tf/{{$test->tfID}}/create_multiple_questions" method="POST" id="add_item_form" class="upload-form" enctype="multipart/form-data">
                    @csrf
                    <strong>Upload items here.</strong>
                    <input type="file" name="tf_items" accept=".xlsx, .xls">
                </form>
            </div>
        </div>
        <div class="add-item-modal-footer">
            <div class="add-item-buttons-container">
                <button form="add_item_form" class="add-item-save-button add-item-modal-button" id="save-quiz-button">Upload</button>
                <button id="add_item_close_button" class="add-item-close-button add-item-modal-button">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="test-body-header">
    <a class="add-test-button-anchor" href="/tf">
        <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
            <p>Back</p>
        </button>
    </a>
    <div class="searchbar-container">
        @if(!$test->tfIsPublic)
        @if(auth()->user()->id == $test->user_id)
        <button class="add-test-question-button" id="add_item_button"><img src="/images/add-test-icon.png">
            <p>Add Item</p>
        </button>
        @endif
        @endif
        @if(!$test->tfIsPublic)
        @if(auth()->user()->id == $test->user_id)
        <button class="add-test-question-button" id="add-test-button"><img src="/images/add-test-icon.png">
            <p>Add Multiple Item</p>
        </button>
        @endif
        @endif
    </div>
</div>
<div class="test-body-content">
    <div class="test-profile-container">
        <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->tfTitle}}</span></p>
        <p class="test-profile-label">Test description: <span class="test-profile-value">{{$test->tfDescription}}</span></p>
        <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->tfTotal}}</span></p>
    </div>
    <div class="test-questions-container">
        <div class="test-questions-table-container">
            <p class="test-question-label">Test Items</p>
            <table class="questions-table">
                <thead>
                    <tr>
                        <th class="questions-table-no-column">No.</th>
                        <th class="questions-table-question-column">Question</th>
                        <th class="questions-table-point-column">Point(s)</th>
                        @if(auth()->user()->id == $test->user_id)
                        @if(!$test->tfIsPublic)
                        <th class="questions-table-buttons-column"></th>
                        @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($questions as $question)
                    <tr id="test-question-item-description">
                        <td class="question-description" data-test-id="{{$test->tfID}}" data-question-id="{{$question->itmID}}">
                            <p>{{ $loop->index + 1}}</p>
                        </td>
                        <td class="question-description" data-test-id="{{$test->tfID}}" data-question-id="{{$question->itmID}}">
                            <p>{{$question->itmQuestion}}</p>
                            <div class="question-labels">
                                @isset(($question->tags['Realistic']))
                                <div class="label r-label">Realistic</div>
                                @endisset
                                @isset(($question->tags['Investigative']))
                                <div class="label i-label">Investigative</div>
                                @endisset
                                @isset(($question->tags['Artistic']))
                                <div class="label a-label">Artistic</div>
                                @endisset
                                @isset(($question->tags['Social']))
                                <div class="label s-label">Social</div>
                                @endisset
                                @isset(($question->tags['Enterprising']))
                                <div class="label e-label">Enterprising</div>
                                @endisset
                                @isset(($question->tags['Conventional']))
                                <div class="label c-label">Conventional</div>
                                @endisset
                                @isset(($question->tags['Unknown']))
                                <div class="label u-label">Unknown</div>
                                @endisset
                            </div>
                        </td>
                        <td class="question-description" data-test-id="{{$test->tfID}}" data-question-id="{{$question->itmID}}">
                            <p>{{$question->itmPoints}}</p>
                        </td>
                        @if(auth()->user()->id == $test->user_id)
                        @if(!$test->tfIsPublic)
                        <td>
                            <div class="questions-table-buttons-column-div">
                                <form action="/tf/{{$test->tfID}}/{{$question->itmID}}/edit" method="GET" class="question-table-button-form">
                                    <button class="questions-table-buttons buttons-edit-button"><img src="/images/edit-icon.png">
                                        <p>Edit</p>
                                    </button>
                                </form>
                                <form action="/tf/{{$question->itmID}}/delete_question" method="POST" class="question-table-button-form" onsubmit="return confirmDelete();">
                                    @csrf
                                    @method('delete')
                                    <button class="questions-table-buttons buttons-delete-button"><img src="/images/delete-icon.png">
                                        <p>Delete</p>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
    document.getElementById('add_item_button').addEventListener('click', function() {
        window.location.href = window.location.href + "/create_question";
    });

    function handleRowClick(event) {
        const clickedColumn = event.currentTarget;
        const questionID = clickedColumn.getAttribute('data-question-id');
        const testID = clickedColumn.getAttribute('data-test-id');
        window.location.href = "/tf/" + testID + "/" + questionID;
    }

    const columns = document.querySelectorAll('.question-description');
    columns.forEach(column => {
        column.addEventListener('click', handleRowClick);
    });

    function confirmDelete() {
        if (confirm("Are you sure you want to delete this record?")) {
            // User clicked OK, proceed with form submission
            return true;
        } else {
            // User clicked Cancel, prevent form submission
            return false;
        }
    }
    const add_item_container = document.getElementById('add_item_container');
    const add_item_sub_container = document.getElementById('add_item_sub_container');
    const add_item_modal_header_close = document.getElementById('add_item_modal_header_close');
    const add_item_close_button = document.getElementById('add_item_close_button');
    document.getElementById('add-test-button').addEventListener('click', function() {
        add_item_container.style.display = "flex";
        setTimeout(() => {
            add_item_sub_container.classList.add("show");
        }, 10);
    });

    add_item_container.addEventListener("click", function(event) {
        if (event.target === add_item_container || event.target === add_item_modal_header_close || event.target === add_item_close_button) {
            add_item_container.style.display = "none";
            add_item_sub_container.classList.remove("show");
        }
    });
</script>
@endsection