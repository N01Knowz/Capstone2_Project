@extends('layouts.navigation')
@section('title', 'Matching')

@push('styles')
<link rel="stylesheet" href="/css/add_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/mcq_add_question.css">
<link rel="stylesheet" href="/css/mt_add_questions.css">
<link rel="stylesheet" href="/css/matching_test_description.css">
<link rel="stylesheet" href="/css/test_description.css">
<link rel="stylesheet" href="/css/mcq_test_description.css">
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
            <p class="add-item-enter-answer">Download template <span><a href="{{ route('matching-excel') }}" class="btn btn-primary">here</a></span></p>
            <button class="add-item-modal-header-close" id="add_item_modal_header_close">x</button>
        </div>
        <div class="add-item-modal-body">
            <div class="add-item-modal-body-content">
                <strong>Guide: Write below the header and make sure to use the template.</strong>
                <ul>
                    <li><strong>Item Text:</strong> Question of the item <strong>(Can be blank as long as there is a corresponding Item Answer for distractors)</strong></li>
                    <li><strong>Item Answer:</strong> Answers for the Questions <strong>(Required)</strong></li>
                    <li><strong>Item Points:</strong> Points for the item <strong>(1 point if blank)</strong></li>
                    <li><strong>If there are questions that fails to follow the template. It will be skipped and not be uploaded</strong></li>
                </ul>
                <form action="/matching/{{$test->mtID}}/create_multiple_questions" method="POST" id="add_item_form" class="upload-form" enctype="multipart/form-data">
                    @csrf
                    <strong>Upload items here.</strong>
                    <input type="file" name="matching_items" accept=".xlsx, .xls">
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
    <a href="/matching" class="add-test-button-anchor">
        <button class="add-test-button" id="back-button"><img src="/images/back-icon.png" class="add-test-button-icon">
            <p>Back</p>
        </button>
    </a>
    <div class="searchbar-container">
        @if(auth()->user()->id == $test->user_id)
        @if(!$test->mtIsPublic)
        <button class="add-test-question-button" id="add_item_button"><img src="/images/add-test-icon.png">
            <p>Add Test Item</p>
        </button>
        @endif
        @endif
        @if(!$test->mtIsPublic)
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
                        <div>
                            <input class="mt-inputs" readonly type="text" value="{{$question->itmQuestion}}">
                        </div>
                    </td>
                    <td>
                        <div class="mt-cell">
                            <input class="mt-inputs mtcell-top" readonly type="text" value="{{$question->itmAnswer}}">
                        </div>
                    </td>
                    <td>
                        <div class="mt-cell">
                            <input class="mt-inputs mtcell-top" readonly type="text" placeholder="0.00" value="{{$question->itmPoints}}">
                        </div>
                    </td>
                    @if(auth()->user()->id == $test->user_id)
                    @if(!$test->mtIsPublic)
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
                    @endif
                </tr>
                <tr>
                    <td>
                        <div class="question-labels" style="margin-bottom: 1em;">
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
</body>

</html>

@endsection