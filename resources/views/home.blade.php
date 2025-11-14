@extends('adminlte::page')

@section('title', 'AdminLTE Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

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

@section('css')
    {{-- <link rel="stylesheet" href="/css/custom.css"> --}}
@stop

@section('js')
    <script> console.log('Welcome!'); </script>
@stop
