@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <h1>Edit User: {{ $user->name }}</h1>
@stop

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">User Details</h3>
        </div>

        {{-- Point form to the update method --}}
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT') {{-- Required for the PUT/PATCH method --}}
            <div class="card-body">

                @include('users.partials.form', ['user' => $user])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-info">Update User</button>
            </div>
        </form>
    </div>
@stop
