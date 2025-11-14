<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportAuditController extends Controller
{
    public function index(Request $request, string $importType, string $fileKey)
    {
        $fields = config("import_data.$importType.files.$fileKey.headers_to_db");
        $modelClass = Str::studly($importType) . Str::studly($fileKey);
        $query = "\\App\\Models\\$modelClass"::query();

        // Apply search if keyword exists
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($fields, $search) {
                foreach ($fields as $field => $value) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        $importsData = $query->paginate(10)->appends(['search' => $search]);

        return view('import.index', compact('importType','fileKey','fields','importsData'));
    }
    
    public function getLogs(Request $request, $modelClass, $rowNumber)
    {
        $logs = AuditLog::where('model', $modelClass)
            ->where('row_number', $rowNumber   )
            ->get();

        return response()->json($logs);
    }
}
