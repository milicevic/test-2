@extends('adminlte::page')

@section('title', 'Dynamic Import Form')

@section('content_header')
    <h1>Dynamic Import Form</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        {{-- Flash messages --}}
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
        {{-- The Form --}}
        <form id="importForm" method="POST" action="{{ route('data.import.upload') }}" enctype="multipart/form-data">
            @csrf

            {{-- Select Import Type --}}
            <div class="form-group">
                <label for="importType">Select Import Type</label>
                <select id="importType" name="import_type" class="form-control" required>
                    <option value="">-- Select Type --</option>
                    @foreach($config as $key => $import)
                        <option value="{{ $key }}">{{ $import['label'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Container where dynamic file inputs will appear --}}
            <div id="filesContainer"></div>

            <button type="submit" class="btn btn-primary mt-3">Upload</button>
        </form>

    </div>
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const importTypeSelect = document.getElementById('importType');
    const filesContainer = document.getElementById('filesContainer');

    const imports = {!! json_encode($config) !!};

    importTypeSelect.addEventListener('change', function() {
        const type = this.value;
        filesContainer.innerHTML = ''; // clear previous inputs

        if (!type || !imports[type]) return;

        const files = imports[type].files;

        for (const [fileKey, fileConfig] of Object.entries(files)) {
            const block = document.createElement('div');
            block.classList.add('mt-4','border', 'p-3', 'rounded');

            let html = `
                <div class="form-group">
                    <label>${fileConfig.label}</label>
                    <input type="file" name="files[${fileKey}]" class="form-control mb-6"  />
                </div>
            `;

            const headerLabels = Object.values(fileConfig.headers_to_db).map(h => `• ${h.label}`);
            html += `
                <div class="mt-2 text-muted">
                    <strong>Expected headers:</strong><br>
                    ${headerLabels.join('<br>')}
                </div>
            `;

            block.innerHTML = html;
            filesContainer.appendChild(block);
        }
    });
});
</script>
@stop

