@extends('layouts.navigation')
@section('title', 'Enumeration')

@push('styles')
<link rel="stylesheet" href="/css/add_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/test_description.css">
<link rel="stylesheet" href="/css/enumeration-test_description.css">
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
<div class="add-item-container-2" id="add_item_container_2">
    <div class="add-item-sub-container-2" id="add_item_sub_container_2">
        <div class="add-item-modal-header-2">
            <p class="add-item-enter-answer-2">Download template <span><a href="{{ route('enumeration-excel') }}" class="btn btn-primary">here</a></span></p>
            <button class="add-item-modal-header-close-2" id="add_item_modal_header_close_2">x</button>
        </div>
        <div class="add-item-modal-body-2">
            <div class="add-item-modal-body-content-2">
                <strong>Guide: Write below the header and make sure to use the template.</strong>
                <ul>
                    <li><strong>Answer:</strong> Answer of the test <strong>(Required)</strong></li>
                    <li><strong>Case Sensitive:</strong> 0 for not case sensitive. 1 for case sensitive <strong>(Default: Not case sensitive)</strong></li>
                    <li><strong>If there are questions that fails to follow the template. It will be skipped and not be uploaded</strong></li>
                </ul>
                <form action="/enumeration/{{$test->etID}}/create_multiple_questions" method="POST" id="add_item_form_2" class="upload-form" enctype="multipart/form-data">
                    @csrf
                    <strong>Upload items here.</strong>
                    <input type="file" name="enumeration_items" accept=".xlsx, .xls">
                </form>
            </div>
        </div>
        <div class="add-item-modal-footer-2">
            <div class="add-item-buttons-container-2">
                <button form="add_item_form_2" class="add-item-save-button-2 add-item-modal-button-2" id="save-quiz-button_2">Upload</button>
                <button id="add_item_close_button_2" class="add-item-close-button-2 add-item-modal-button-2">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="add-item-container" id="add_item_container">
    <div class="add-item-sub-container" id="add_item_sub_container">
        <div class="add-item-modal-header">
            <p class="add-item-enter-answer">Enter Answer</p>
            <button class="add-item-modal-header-close" id="add_item_modal_header_close">x</button>
        </div>
        <div class="add-item-modal-body">
            <form action="/enumeration/{{$test->etID}}/create_question" method="POST" id="add_item_form">
                @csrf
                <p class="add-item-form-answer-label">Answer</p>
                <input type="text" name="answer_text" class="add-item-text-input" required>
                <br>
                <div class="case-sensitive-container">
                    <input type="checkbox" name="case_sensitive_text">
                    <p class="add-item-form-answer-label">This answer is case sensitive</p>
                </div>
            </form>
        </div>
        <div class="add-item-modal-footer">
            <div class="add-item-buttons-container">
                <button form="add_item_form" class="add-item-save-button add-item-modal-button" id="save-quiz-button">Save</button>
                <button id="add_item_close_button" class="add-item-close-button add-item-modal-button">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="test-body-header">
    <a href="/enumeration" class="add-test-button-anchor">
        <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
            <p>Back</p>
        </button>
    </a>
    <div class="searchbar-container">
        @if(auth()->user()->id == $test->user_id)
        @if(!$test->etIsPublic)
        <button class="add-test-question-button" id="add-test-button"><img src="/images/add-test-icon.png">
            <p>Add Answer</p>
        </button>
        @endif
        @endif
        @if(auth()->user()->id == $test->user_id)
        @if(!$test->etIsPublic)
        <button class="add-test-question-button" id="add-test-button_2"><img src="/images/add-test-icon.png">
            <p>Add Multiple Item</p>
        </button>
        @endif
        @endif
    </div>
</div>
<div class="test-body-content">
    <div class="test-profile-container">
        <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->etTitle}}</span></p>
        <p class="test-profile-label">Test description: <span class="test-profile-value">{{$test->etDescription}}</span></p>
        <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->etTotal}}</span></p>
    </div>
    <div class="test-questions-container">
        <div class="test-questions-table-container">
            <p class="test-question-label">Enumeration Answers</p>
            <table class="questions-table">
                <thead>
                    <tr>
                        <th class="enumeration-questions-table-no-column">No.</th>
                        <th class="enumeration-questions-table-answer-column">Answer(s)</th>
                        <th class="enumeration-questions-table-sensitive-column">Case Sensitive</th>
                        @if(auth()->user()->id == $test->user_id)
                        @if(!$test->etIsPublic)
                        <th class="enumeration-questions-table-buttons-column"></th>
                        @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($questions as $question)
                    <tr>
                        <td>
                            <p>{{$loop->iteration}}</p>
                        </td>
                        <td>
                            <p>{{$question->itmAnswer}}</p>
                        </td>
                        <td>
                            <p>@if($question->itmIsCaseSensitive == "0")
                                No
                                @else
                                Yes
                                @endif
                            </p>
                        </td>
                        @if(auth()->user()->id == $test->user_id)
                        @if(!$test->etIsPublic)
                        <td>
                            <form action="/enumeration/{{$question->itmID}}/delete_question" method="POST" class="questions-table-buttons-column-div" onsubmit="return confirmDelete();">
                                @csrf
                                @method('delete')
                                <button class="questions-table-buttons buttons-delete-button"><img src="/images/delete-icon.png">
                                    <p>Delete</p>
                                </button>
                            </form>
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
    var save_button = document.getElementById("save-quiz-button");

    // Add a click event listener to the button
    save_button.addEventListener("click", function() {
        // Disable the button
        save_button.disabled = true;
        document.getElementById("add_item_form").submit();
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

    const add_item_container_2 = document.getElementById('add_item_container_2');
    const add_item_sub_container_2 = document.getElementById('add_item_sub_container_2');
    const add_item_modal_header_close_2 = document.getElementById('add_item_modal_header_close_2');
    const add_item_close_button_2 = document.getElementById('add_item_close_button_2');
    document.getElementById('add-test-button_2').addEventListener('click', function() {
        add_item_container_2.style.display = "flex";
        setTimeout(() => {
            add_item_sub_container_2.classList.add("show");
        }, 10);
    });

    add_item_container_2.addEventListener("click", function(event) {
        if (event.target === add_item_container_2 || event.target === add_item_modal_header_close_2 || event.target === add_item_close_button_2) {
            add_item_container_2.style.display = "none";
            add_item_sub_container_2.classList.remove("show");
        }
    });
</script>
@endsection