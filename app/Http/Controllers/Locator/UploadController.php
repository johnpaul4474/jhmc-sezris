<?php

namespace App\Http\Controllers\Locator;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locator\Upload;
use App\Models\Locator\ApplicationModel;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $app = ApplicationModel::with('uploads')->find(1);
        return dd($app);
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
    $request->validate([
        'file' => 'required_without:files|file|max:5120',
        'files.*' => 'sometimes|file|max:5120',
        'application_form_id' => 'required|exists:application_forms,id',
    ]);

    $savedFiles = [];

    $files = $request->file('files') ?? [$request->file('file')];

    foreach ($files as $file) {
        if (!$file) continue;

        $storedPath = $file->store('loctr', 'public');

        $upload = Upload::create([
            'file_name'            => $file->getClientOriginalName(),
            'file_path'            => $storedPath,
            'file_type'            => $file->getClientMimeType(),
            'file_size'            => $file->getSize(),
            'description'          => $request->input('description'),
            'user_id'              => Auth::id(),
            'application_form_id'  => $request->input('application_form_id'),
        ]);

        $savedFiles[] = $upload;
    }

    return response()->json([
        'message' => 'Files uploaded successfully!',
        'uploads' => $savedFiles
    ], 201);
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
