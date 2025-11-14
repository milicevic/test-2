<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Exports\ExportData;
use App\Services\ImportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class DataImportController extends Controller
{
    protected ImportService $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }
    public function index(Request $request, string $importType, string $fileKey)
    {
        $fields = $this->importService->getHeaders($importType, $fileKey);

         if ($request->has('export')) {
            $query = $this->importService->getSearchQuery($importType, $fileKey, $request->get('search'));

           return Excel::download(
                new ExportData($query, $fields),
                "{$importType}_{$fileKey}_export.xlsx"
            );
        }

        $modelClass = $this->importService->getModelClass($importType, $fileKey);
        $importsData = $this->importService->getSearchAndPaginated($importType, $fileKey, $request->get('search'));
        return view('import.index', compact('importType', 'fileKey', 'fields', 'importsData', 'modelClass'));
    }

    public function upload(UploadFileRequest $request)
    {
        $this->importService->uploadAndDispatch($request->import_type, $request->file('files'), $request->user());

        return back()
            ->with('success', 'Files uploaded is started processing!. You will receive an email when it is done.');
    }


    public function delete(Request $request, $importType, $importId)
    {
        $this->authorize('upload-data', $importType, $request->user());
        $modelClass = $request->get('model');

        if (!class_exists($modelClass)) {
            return back()->with('error', 'Invalid model class.');
        }

        $this->importService->delete($modelClass, $importId, $request->user());

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }


    public function create(Request $request)
    {
        $config = $this->importService->getSetupByPermission($request->user());

        return view('import.create', compact('config'));
    }
}
