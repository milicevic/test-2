@extends('adminlte::page')

@section('title', 'Edit permission')

@section('content_header')
    <h1>Edit Permission: {{ $permission->name }}</h1>
@stop

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Permission Details</h3>
        </div>

        {{-- Point form to the update method --}}
        <form action="{{ route('permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT') {{-- Required for the PUT/PATCH method --}}
            <div class="card-body">
                @include('permissions.partials.form', ['permission' => $permission])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-info">Update Permission</button>
            </div>
        </form>
    </div>
@stop
