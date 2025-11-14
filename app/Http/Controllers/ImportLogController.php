<?php

namespace App\Http\Controllers;

use App\Models\ImporLog;
use App\Models\ImportLog;
use Illuminate\Http\Request;

class ImportLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $importLogs = ImportLog::with('user', 'validationLogs')->paginate(10);

        return view('import.logs.index', compact('importLogs'));
    }

    
}
