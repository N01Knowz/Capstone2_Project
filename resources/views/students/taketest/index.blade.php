@extends('layouts.student_navigation')
@section('title', 'Take Test')

@push('styles')
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/filter.css">
<link rel="stylesheet" href="/css/testtake.css">
<link rel="stylesheet" href="/css/front_page.css">
@endpush

@section('modal-contents')
<div class="add-item-container" id="add_item_container">
    <div class="add-item-sub-container" id="add_item_sub_container">
        <div class="add-item-modal-header">
            <p class="add-item-enter-answer">Take Test</p>
            <button class="add-item-modal-header-close " id="add_item_modal_header_close">x</button>
        </div>
        <div class="add-item-modal-body">
            <div class="add-item-modal-body-content">
                <p>
                    <strong>Title: <span style="font-weight: normal;" id="item-title"></span></strong>
                </p>
                <p>
                    <strong>Description: <span style="font-weight: normal;" id="item-description"></span></strong>
                </p>
                <p>
                    <strong>Subject: <span style="font-weight: normal;" id="item-subject"></span></strong>
                </p>
                <p>
                    <strong>Test Type: <span style="font-weight: normal;" id="item-type"></span></strong>
                </p>
                <p>
                    <strong>Questions: <span style="font-weight: normal;" id="item-count"></span></strong>
                </p>
                <p>
                    <strong>By: <span style="font-weight: normal;" id="item-fullname"></span></strong>
                </p>
            </div>
        </div>
        <form class="add-item-modal-footer" id="take_test_form">
            <div class="add-item-buttons-container">
                <button form="take_test_form" class="add-item-save-button add-item-modal-button" id="take-test-button">Yes</button>
                <button type="button" id="add_item_close_button" class="add-item-close-button add-item-modal-button">No</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('content')
<div class="test-body-header">
    <div class="add-test-button-anchor">
        <!-- <button class="add-test-button"><img src="/images/add-test-icon.png" class="add-test-button-icon">
            <p>Add New Test</p>
        </button> -->
    </div>
    <form method="GET" action="" class="searchbar-container" id="filter-form">
        <input type="text" placeholder="Search tests here..." class="test-searchbar" name="search" @isset($searchInput) value="{{$searchInput}}" @endisset>
        <button class="search-button">Search</button>
    </form>
</div>
<div class="body-content">
    @include('layouts.filter_student')
    <div class="table-container">
        <table class="test-body-table">
            <thead>
                <tr class="test-table-header">
                    <th>Title</th>
                    <th>Description</th>
                    <th>Subject</th>
                    <th>Test Type</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table content goes here -->
                @foreach ($tests as $test)
                <tr data-creator-id='{{$test->creatorID}}' data-fullname="{{$test->first_name . ' ' . $test->last_name}}" data-id="{{$test->id}}" data-title="{{$test->title}}" data-description="{{$test->description}}" data-subject="{{$test->subjectName}}" data-type="{{$test->type}}" data-userimage="{{$test->user_image}}" data-item-count="{{$test->itemCount}}" onclick="showTestDescription(event)">
                    <td class="test-body-column test-body-title">
                        <p>{{$test->title}}</p>
                    </td>
                    <td class="test-body-column test-body-instruction">
                        <p>{{$test->description}}</p>
                    </td>
                    <td class="test-body-column test-body-points">
                        <p>{{$test->subjectName}}</p>
                    </td>
                    <td class="test-body-buttons-column">
                        <p>{{$test->type}}</p>
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
    function showTestDescription(event) {
        const clickedRow = event.currentTarget;
        const fullname = clickedRow.getAttribute('data-fullname');
        const image = clickedRow.getAttribute('data-userimage');
        const title = clickedRow.getAttribute('data-title');
        const description = clickedRow.getAttribute('data-description');
        const subject = clickedRow.getAttribute('data-subject');
        const type = clickedRow.getAttribute('data-type');
        const count = clickedRow.getAttribute('data-item-count');
        const id = clickedRow.getAttribute('data-id');
        const creatorid = clickedRow.getAttribute('data-creator-id');
        

        const modal_form = document.getElementById('take_test_form');
        modal_form.action = "/taketest/" + type.toLowerCase() + "/" + id + "/" + creatorid + "/test"; 

        const item_title = document.getElementById('item-title');
        item_title.innerHTML = "";
        item_title.innerHTML = title;
        const item_description = document.getElementById('item-description');
        item_description.innerHTML = "";
        item_description.innerHTML = description;
        const item_subject = document.getElementById('item-subject');
        item_subject.innerHTML = "";
        item_subject.innerHTML = subject;
        const item_type = document.getElementById('item-type');
        item_type.innerHTML = "";
        item_type.innerHTML = type;
        const item_fullname = document.getElementById('item-fullname');
        item_fullname.innerHTML = "";
        item_fullname.innerHTML = fullname;
        const item_count = document.getElementById('item-count');
        item_count.innerHTML = "";
        item_count.innerHTML = count;
        show_add_item_modal();
    }

    function show_add_item_modal() {
        add_item_container.style.display = "flex";
        setTimeout(() => {
            add_item_sub_container.classList.add("show");
        }, 10);
    }
    const add_item_container = document.getElementById('add_item_container');
    const add_item_sub_container = document.getElementById('add_item_sub_container');
    const add_item_modal_header_close = document.getElementById('add_item_modal_header_close');
    const add_item_close_button = document.getElementById('add_item_close_button');
    add_item_container.addEventListener("click", function(event) {
        if (event.target === add_item_container || event.target === add_item_modal_header_close || event.target === add_item_close_button) {
            add_item_container.style.display = "none";
            add_item_sub_container.classList.remove("show");
        }
    });
    

    const take_test = document.getElementById('take-test-button');

</script>
@endsection
@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif