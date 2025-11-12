<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class DataImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function upload(UploadFileRequest $request)
    {

        $type = $request->input('import_type');
        $imports = config('import_data');



        return back()
            ->with('success', 'Files uploaded successfully!')
            ->with('uploaded_files');
    }


    public function create()
    {
        $config = $this->setupByPermission();
        return view('data_import.create', compact('config'));
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
