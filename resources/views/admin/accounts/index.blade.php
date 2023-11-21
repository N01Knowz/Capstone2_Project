@extends('layouts.accounts_navigation')
@section('title', 'Accounts')

@push('styles')
<link rel="stylesheet" href="/css/manage_accounts_index.css">
@endpush
@section('modal-contents')
<div class="manage-accounts-modal-background" id="manage-accounts-modal-background" onclick="toggleModal()">
    <div class="manage-accounts-modal-content" onclick="stopPropagation(event)">
        <p>Are you sure you want to delete the account <span id="delete-user-email"></span>?</p>
        <form method="POST" id="delete-form">
            @csrf
            @method('delete')
            <input type="password" name="user-password">
            <div>
                <button>Confirm</button>
                <button type="button" onclick="toggleModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('content')
<div class="test-body-header">
    <form method="get" action="/register/admin" class="add-test-button-anchor">
        @if($user_role == 'super admin')
        <button class="add-test-button"><img src="/images/add-test-icon.png" class="add-test-button-icon">
            <p>Add New Admin</p>
        </button>
        @endif
    </form>
    <form method="GET" action="" class="searchbar-container">
        <input type="text" placeholder="Search user here..." class="test-searchbar" name="search" @isset($searchInput) value="{{$searchInput}}" @endisset>
        <button class="search-button">Search</button>
    </form>
</div>
<div class="manage-account-content">
    <div class="manage-account-table-container">
        <table id="sortable-table" class="manage-account-table">
            <thead>
                <tr>
                    <th data-column="id">ID</th>
                    <th data-column="first_name">First Name</th>
                    <th data-column="last_name">Last Name</th>
                    <th data-column="email">Email</th>
                    <th data-column="role">Role</th>
                    <th data-column="active">Active</th>
                    <th class="action-button-column">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr data-id="{{$user->id}}" data-first_name="{{$user->first_name}}" data-last_name="{{$user->last_name}}" data-email="{{$user->email}}" data-role="{{ucwords($user->role)}}" data-active="{{$user->active ? 'Active' : 'Inactive'}}">
                    <td>{{$user->id}}</td>
                    <td>{{$user->first_name}}</td>
                    <td>{{$user->last_name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{ucwords($user->role)}}</td>
                    <td>{{$user->active ? 'Active' : 'Inactive'}}</td>
                    <td>
                        <div class="action-button-cell">
                            @if($user->active)
                            <form class="button-container" method="POST" action="/accounts/deactivate/{{$user->id}}" onsubmit="return confirmDeactivate();">
                                @csrf
                                @method('PUT')
                                <button class="deactivate-button">Deactivate</button>
                            </form>
                            @else
                            <form class="button-container" method="POST" action="/accounts/activate/{{$user->id}}" onsubmit="return confirmActivate();">
                                @csrf
                                @method('PUT')
                                <button class="activate-button">Activate</button>
                            </form>
                            @endif
                            <div class="button-container">
                                <button class="delete-button" data-email="{{$user->email}}" data-id="{{$user->id}}" onclick="confirmDelete(event)">Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@if(session('message'))
<script>
    var message = "{{ session('message') }}";
    alert(message);
</script>
@endif
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function confirmDelete(event) {
        const button = event.target;

        const email = button.getAttribute('data-email');
        const id = button.getAttribute('data-id');

        const emailSpan = document.getElementById('delete-user-email');
        emailSpan.innerHTML = email;
        const form = document.getElementById('delete-form');
        form.action = `/accounts/${id}/delete`;
        toggleModal();
    }

    function stopPropagation(event) {
        event.stopPropagation();
    }

    function toggleModal() {
        const modal = document.getElementById('manage-accounts-modal-background');
        if (modal.style.display === "none" || modal.style.display === "") {
            modal.style.display = "flex";

        } else {
            modal.style.display = "none";
        }

    }

    function confirmActivate() {
        if (confirm("Are you sure you want to activate this user?")) {
            // User clicked OK, proceed with form submission
            return true;
        } else {
            // User clicked Cancel, prevent form submission
            return false;
        }
    }

    function confirmDeactivate() {
        if (confirm("Are you sure you want to deactivate this user?")) {
            // User clicked OK, proceed with form submission
            return true;
        } else {
            // User clicked Cancel, prevent form submission
            return false;
        }
    }

    $(document).ready(function() {
        $('#sortable-table th[data-column]').on('click', function() {
            const column = $(this).data('column');
            const order = $(this).data('order') || 'asc';

            // Toggle the sort order
            $(this).data('order', order === 'asc' ? 'desc' : 'asc');

            // Sort the table rows
            const $table = $('#sortable-table');
            const $rows = $table.find('tbody tr').get();

            $rows.sort(function(a, b) {
                const keyA = $(a).data(column);
                const keyB = $(b).data(column);

                // Handle sorting for the 'ID' column as numbers
                if (column === 'id') {
                    return order === 'asc' ? keyA - keyB : keyB - keyA;
                }

                if (order === 'asc') {
                    return keyA.localeCompare(keyB);
                } else {
                    return keyB.localeCompare(keyA);
                }
            });

            // Clear the table and append the sorted rows
            $table.find('tbody').empty();
            $.each($rows, function(index, row) {
                $table.find('tbody').append(row);
            });
        });
    });
</script>
@endsection

@if(session('success'))
<script>
    var message = "{{ session('success') }}";
    alert(message);
</script>
@endif