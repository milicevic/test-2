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
                        <td>{{ $importLog->file_name }}</td>
                        <td>{{ $importLog->original_file }}</td>
                        <td>{{ $importLog->status }}</td>

                        <td>
                            <button class="btn btn-xs btn-primary"
                                    data-toggle="modal"
                                    data-target="#logModal_{{ $importLog->id }}">
                                Show logs
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="logModal_{{ $importLog->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Validation Logs for Import #{{ $importLog->id }}</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">

                                    @if($importLog->validationLogs && count($importLog->validationLogs) > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Table name</th>
                                                <th>Row Name</th>
                                                <th>Row Number</th>
                                                <th>Column Name</th>
                                                <th>Column Value</th>
                                                <th>Message</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($importLog->validationLogs as $log)
                                                <tr>
                                                    <td>{{ $log->table_name ?? '-' }}</td>
                                                    <td>{{ $log->row_name ?? '-' }}</td>
                                                    <td>{{ $log->row_number ?? '-' }}</td><
                                                    <td>{{ $log->column_name ?? '-' }}</td>
                                                    <td>{{ $log->column_value ?? '-' }}</td>
                                                    <td>{{ $log->message ?? '-' }}</td>

                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>No validation errors logged.</p>
                                    @endif

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">
                                        Close
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            {{ $importLogs->links('pagination::bootstrap-4') }}
        </div>
    </div>
@stop
