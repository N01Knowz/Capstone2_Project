@extends('layouts.navigation')
@section('title', 'True or False')

@push('styles')
<link rel="stylesheet" href="/css/add_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/test_description.css">
@endpush
@section('content')
<div class="test-body-header">
    <a class="add-test-button-anchor" href="/tf">
        <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
            <p>Back</p>
        </button>
    </a>
    <div class="searchbar-container">
        @if(auth()->user()->id == $test->user_id)
        <button class="add-test-question-button" id="add_item_button"><img src="/images/add-test-icon.png">
            <p>Add Test Item</p>
        </button>
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
                        <th class="questions-table-buttons-column"></th>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- <div class="criteria-point-container">
                    <div class="criteria-point-sub-container">
                        <p class="text-input-label">Criteria<span class="red-asterisk"> *</span></p>
                        <input type="text" class="text-input-background critera-point-input">
                    </div>
                    <div class="criteria-point-sub-container">
                        <div>
                            <p class="text-input-label">Point(s)</p>
                            <input type="text" class="text-input-background critera-point-input">
                        </div>
                    </div>
                </div> -->
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
</script>
@endsection