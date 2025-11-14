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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(ImporLog $imporLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImporLog $imporLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImporLog $imporLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImporLog $imporLog)
    {
        //
    }
}
