@extends('layouts.accounts_navigation')
@section('title', 'Accounts')

@push('styles')
<link rel="stylesheet" href="/css/manage_test_index.css">
@endpush
@section('modal-contents')
@endsection
@section('content')
<div class="test-body-header">
    <div class="add-test-button-anchor">
    </div>
    <form method="GET" action="" class="searchbar-container">
        <input type="text" placeholder="Search user here..." class="test-searchbar" name="search" @isset($searchInput) value="{{$searchInput}}" @endisset>
        <button class="search-button">Search</button>
    </form>
</div>
<div class="body-content">
    <div class="table-container">
        <table class="test-body-table">
            <thead>
                <tr class="test-table-header">
                    <th>Title</th>
                    <th>Description</th>
                    <th>User</th>
                    <th>Test Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table content goes here -->
                @foreach ($tests as $test)
                <tr data-fullname="{{$test->first_name . ' ' . $test->last_name}}" data-id="{{$test->id}}" data-title="{{$test->title}}" data-description="{{$test->description}}" data-subject="{{$test->subjectName}}" data-type="{{$test->type}}" data-userimage="{{$test->user_image}}" data-item-count="{{$test->itemCount}}" onclick="showTestDescription(event)">
                    <td class="test-body-column test-body-title">
                        <p>{{$test->title}}</p>
                    </td>
                    <td class="test-body-column test-body-instruction">
                        <p>{{$test->description}}</p>
                    </td>
                    <td class="test-body-column test-body-points">
                        <p>{{$test->first_name . " " . $test->last_name}}</p>
                    </td>
                    <td class="test-body-buttons-column">
                        <p>{{$test->type}}</p>
                    </td>
                    <td class="show-hide-column">
                        <form method="POST" class="show-hide-form" action="managetest/{{strToLower($test->type)}}/{{$test->id}}/hide" @if($test->IsHidden)  onsubmit="return confirmShow();" @else  onsubmit="return confirmHide();" @endif>
                            @csrf
                            @method('PUT')
                            <button class="show-hide-button @if($test->IsHidden) show-button @else hide-button @endif">
                                @if($test->IsHidden) Show @else Hide @endif
                            </button>
                        </form>
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
@endsection

<script>
    function confirmShow() {
        if (confirm("Are you sure you want to show this record?")) {
            // User clicked OK, proceed with form submission
            return true;
        } else {
            // User clicked Cancel, prevent form submission
            return false;
        }
    }
    function confirmHide() {
        if (confirm("Are you sure you want to hide this record?")) {
            // User clicked OK, proceed with form submission
            return true;
        } else {
            // User clicked Cancel, prevent form submission
            return false;
        }
    }
</script>

@if(session('success'))
<script>
    var message = "{{ session('success') }}";
    alert(message);
</script>
@endif