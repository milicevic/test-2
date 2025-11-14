@extends('adminlte::page') @section('title', 'Data Imports')
@section('content_header')
<h1>{{ $importType }} - {{ $fileKey }}</h1>
@stop @section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $importType }}</h3>
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                @if(session('uploaded_files'))
                    <ul class="mt-2 mb-0">
                        @foreach(session('uploaded_files') as $name)
                            <li>{{ $name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach

        <div class="card-tools">

            <form
                action=" {{ route('import.index', ['importType' => $importType, 'fileKey' => $fileKey]) }}"
                method="GET"
                class="input-group input-group-sm"
                style="width: 250px"
            >
                <input
                    type="text"
                    name="search"
                    class="form-control float-right"
                    placeholder="Search..."
                    value="{{ request('search') }}"
                />
                <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                    </button>
                    <button
                        type="submit"
                        name="export"
                        value="1"
                        class="btn btn-success ml-1"
                    >
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    @foreach($fields as $field => $value)
                    <th>{{ $value["label"] }}</th>
                    @endforeach

                    <th style="width: 150px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($importsData as $import)
                <tr>
                    @foreach($fields as $field => $value)
                    <td>{{ $import->$field }}</td>
                    @endforeach
                    <td>
                        <a
                            href="javascript:void(0);"
                            class="btn btn-xs btn-primary mr-1"
                            data-import-type="{{ $importType }}"
                            data-file-key="{{ $fileKey }}"
                            data-row-number="{{ $import->id }}"
                            onclick="showLogsModal(this)"
                        >
                            Show logs
                        </a>
                        <form
                            action="{{ route('import.destroy', ['importType' => $importType, 'id' => $import->id]) }}"
                            method="POST"
                            class="d-inline"
                        >
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="model" value="{{ $modelClass }}">
                            <button
                                type="submit"
                                class="btn btn-xs btn-danger"
                                onclick="return confirm('Are you sure?')"
                            >
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td
                        colspan="{{ count($fields) + 1 }}"
                        class="text-center text-muted"
                    >
                        No results found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        {{ $importsData->links('pagination::bootstrap-4') }}
    </div>
</div>

<div class="modal fade" id="logsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Audit Logs</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="logsTable">
                    <thead>
                        <tr>
                            <th>Column</th>
                            <th>Inport Type</th>
                            <th>File</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center">
                                Select a row to see logs
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

<script>
    function showLogsModal(button) {
        const importType = button.dataset.importType;
        const rowNumber = button.dataset.rowNumber;
        const fileKey = button.dataset.fileKey;

        const modal = new bootstrap.Modal(document.getElementById("logsModal"));
        const tbody = document.querySelector("#logsTable tbody");

        // Show loading
        tbody.innerHTML =
            '<tr><td colspan="3" class="text-center">Loading...</td></tr>';

        fetch(`/imports/${importType}/${fileKey}/logs/${rowNumber}`)
            .then((response) => response.json())
            .then((logs) => {
                if (logs.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="3" class="text-center">No logs found</td></tr>';
                    return;
                }

                tbody.innerHTML = logs
                    .map(
                        (log) => `
                <tr>
                    <td>${log.column_name}</td>
                    <td>${log.import_type}</td>
                    <td>${log.file_name}</td>
                    <td>${log.old_value}</td>
                    <td>${log.new_value}</td>
                    <td>${log.updated_at}</td>
                </tr>
            `
                    )
                    .join("");
            })
            .catch((err) => {
                tbody.innerHTML =
                    '<tr><td colspan="3" class="text-center text-danger">Error loading logs</td></tr>';
                console.error(err);
            });

        modal.show();
    }
</script>
