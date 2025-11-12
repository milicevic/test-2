@extends('adminlte::page')

@section('title', 'Create Permission')

@section('content_header')
    <h1>Create New Permission</h1>
@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Permission Details</h3>
        </div>

        {{-- Point form to the store method --}}
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="card-body">
                @include('permissions.partials.form', ['user' => null] )
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Create Permission</button>
            </div>
        </form>
    </div>
@stop
