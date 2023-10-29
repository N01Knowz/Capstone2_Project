@extends('layouts.navigation')
@section('title', 'Matching')

@push('styles')
<!-- include libraries(jQuery, bootstrap) -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<link rel="stylesheet" href="/css/add_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/mcq_add_question.css">
<link rel="stylesheet" href="/css/mt_add_questions.css">
<link rel="stylesheet" href="/css/matching_test_description.css">
<link rel="stylesheet" href="/css/test_description.css">
@endpush
@section('content')
<div class="test-body-header">
    <a href="/matching" class="add-test-button-anchor">
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
        <p class="test-profile-label">Test name: <span class="test-profile-value">{{$test->mtTitle}}</span></p>
        <p class="test-profile-label">Total point(s): <span class="test-profile-value">{{$test->mtTotal}}</span></p>
        <p class="test-profile-label">Description: <span class="test-profile-value">{{$test->mtDescription}}</span></p>
    </div>
    <div class="test-add-question-container">
        <table>
            <thead>
                <tr>
                    <th>
                        <p class="test-profile-label">Item Text <span class="red-asterisk">*</span></p>
                    </th>
                    <th>
                        <p class="test-profile-label">Answer <span class="red-asterisk">*</span></p>
                    </th>
                    <th>
                        <p class="test-profile-label">Point(s) <span class="red-asterisk">*</span></p>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $question)
                <tr>
                    <td>
                        <input class="mt-inputs" readonly type="text" value="{{$question->itmQuestion}}">
                    </td>
                    <td><input class="mt-inputs" readonly type="text" value="{{$question->itmAnswer}}"></td>
                    <td><input class="mt-inputs" readonly type="text" placeholder="0.00" value="{{$question->itmPoints}}"></td>
                    @if(auth()->user()->id == $test->user_id)
                    <td class="buttons-column">
                        <div class="questions-table-buttons-column-div">
                            <form action="/matching/{{$test->mtID}}/{{$question->itmID}}/edit" method="GET" class="question-table-button-form">
                                <button class="questions-table-buttons buttons-edit-button"><img src="/images/edit-icon.png">
                                    <p>Edit</p>
                                </button>
                            </form>
                            <form action="/matching/{{$question->itmID}}/delete_question" method="POST" class="question-table-button-form" onsubmit="return confirmDelete();">
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
<script>
    document.getElementById('add_item_button').addEventListener('click', function() {
        window.location.href = window.location.href + "/create_question";
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


    $('.summernote').summernote({
        placeholder: 'Enter Option...',
        tabsize: 2,
        height: 100,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
</script>
</body>

</html>

@endsection