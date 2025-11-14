@extends('adminlte::page')

@section('title', 'Import Logs')

@section('content_header')
    <h1>Import Logs</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Import logs</h3>

        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Import type</th>
                        <th>File</th>
                        <th>Original file name</th>
                        <th>Status</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($importLogs as $importLog)
                    <tr>
                        <td>{{ $importLog->user->name }}</td>
                        <td>{{ $importLog->import_type }}</td>
                        <td>{{ $importLog->file }}</td>
                        <td>{{ $importLog->original_file }}</td>
                        <td>{{ $importLog->status }}</td>

                        <td>
                            <a href="#" class="btn btn-xs btn-primary mr-1">Show logs</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            {{ $importLogs->links('pagination::bootstrap-4') }}
        </div>
    </div>
@stop
