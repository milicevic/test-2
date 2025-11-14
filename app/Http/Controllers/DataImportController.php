<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Jobs\ProcessImportJob;
use App\Models\User;
use App\Exports\ExportData;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as Excel;

class DataImportController extends Controller
{

    public function index(Request $request, string $importType, string $fileKey, Excel $excel)
    {
        $fields = config("import_data.$importType.files.$fileKey.headers_to_db");
        $modelClass = Str::studly($importType) . Str::studly($fileKey);
        $query = "\\App\\Models\\$modelClass"::query();
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($fields, $search) {
                foreach ($fields as $field => $value) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        if ($request->has('export')) {
            return $excel->download(
                new ExportData($query, $fields),
                "{$importType}_{$fileKey}_export.xlsx"
            );
        }

        $importsData = $query->paginate(10)->appends(['search' => $search]);

        return view('import.index', compact('importType', 'fileKey', 'fields', 'importsData', 'modelClass'));
    }

    public function upload(UploadFileRequest $request)
    {
        $paths = [];
        foreach ($request->file('files') as $key => $file) {
            $originalName = $file->getClientOriginalName();
            $path = $file->storeAs('imports', $originalName);
            $paths[$key] = $path;
        }

        ProcessImportJob::dispatchSync($request->import_type, $paths, $request->user());

        return back()
            ->with('success', 'Files uploaded is started processing!. You will receive an email when it is done.');
    }

    public function delete(Request $request, $importType, $importId)
    {
        $this->authorize('upload-data', $importType, $request->user());
        $modelClass = "App\\Models\\".$request->get('model');
        if (!class_exists($modelClass)) {
            return redirect()->back()->with('error', 'Invalid model class.');
        }
        $record = $modelClass::find($importId);
        if (!$record) {
            return redirect()->back()->with('error', 'Record not found.');
        }
        $record->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }


    public function create()
    {
        $config = $this->setupByPermission();
        return view('import.create', compact('config'));
    }

    private function setupByPermission(): array
    {
        $setup = Config::get('import_data');
        $userPermissions = Auth::user()->permissions->pluck('name')->toArray();

        $setupConfig = [];
        $allowedPermissions = array_flip($userPermissions);

        foreach ($setup as $type => $typeConfig) {
            $requiredPermission = $typeConfig['permission_required'] ?? null;

            if ($requiredPermission === null || isset($allowedPermissions[$requiredPermission])) {
                $setupConfig[$type] = $typeConfig;
            }
        }

        return $setupConfig;
    }
}
