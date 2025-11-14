<?php

namespace App\Http\Controllers;

use App\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportAuditController extends Controller
{
   protected ImportService $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    public function getLogs(Request $request, $inputType, $fileKey, $rowNumber)
    {
        $this->authorize('upload-data', $inputType, $request->user());
        $logs = $this->importService->getLogs($this->importService->getModelClass($inputType, $fileKey), $rowNumber);
        return response()->json($logs);
    }
}
