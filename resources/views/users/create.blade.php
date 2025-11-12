@extends('adminlte::page')

@section('title', 'Create User')

@section('content_header')
    <h1>Create New User</h1>
@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">User Details</h3>
        </div>

        {{-- Point form to the store method --}}
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="card-body">
                @include('users.partials.form', ['user' => null] )
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
@stop
