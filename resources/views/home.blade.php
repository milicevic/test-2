@extends('adminlte::page')

{{-- Set the title displayed in the browser tab --}}
@section('title', 'AdminLTE Dashboard')

{{-- Set the title displayed in the page header --}}
@section('content_header')
    <h1>Dashboard</h1>
@stop

{{-- This is the main content area for your page --}}
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Welcome</h3>
        </div>
        <div class="card-body">
            You are logged in and viewing the AdminLTE styled dashboard!
        </div>
    </div>
@stop

{{-- Optional: Include custom CSS assets --}}
@section('css')
    {{-- <link rel="stylesheet" href="/css/custom.css"> --}}
@stop

{{-- Optional: Include custom JS assets --}}
@section('js')
    <script> console.log('Welcome!'); </script>
@stop
