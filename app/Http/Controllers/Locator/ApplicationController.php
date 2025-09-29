<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locator\ApplicationModel;
use App\Models\Locator\ApplicationOption;

class ApplicationController extends Controller
{
  
    /**
     * Display a listing of the resource.
     */
    public function index()
     {   
        $application = ApplicationModel::with(['selections.option', 'selections.user'])
            ->latest()
            ->first();

        if (!$application) {
            return response()->json(['message' => 'No application found'], 404);
        }
        
        // Format the selections
        $selections = $application->selections->map(function($selection) {
            $amount = $selection->amount;
            return [
                'option_name' => $selection->option->name,
                'amount'      => $selection->amount,
                'validity'    => $selection->option->validity,
                'selected_at' => $selection->selected_at,
                'user_name'   => $selection->user->name, // or email/id
            ];
        });
        $totalAmount = $application->selections->sum('amount');
        
       
        return response()->json([
            'application' => [
                'id'           => $application->id,
                'form_title'   => $application->form_title,
                'control_no'   => $application->control_number,
                'form_number'  => $application->form_number,
                'selections'   => $selections,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $application = ApplicationModel::create([
        'form_title' => 'Gate Pass Permit',
        'user_id'    => auth()->id(),
    ]);

    return response()->json([
        'message' => 'Application created successfully!',
        'data'    => $application,
    ]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //    $validated = $request->validate([
    //         'form_title'      => 'required|string|max:255',
    //         'user_id'         => 'required|exists:users,id',
    //         'control_number'  => 'required|string|unique:applications,control_number',
    //         'form_number'     => 'nullable|string|max:100',
    //     ]);

    //     // ✅ create new application
    //     $application = Application::create($validated);

    //     // ✅ redirect or return JSON
    //     return redirect()
    //         ->route('applications.show', $application->id)
    //         ->with('success', 'Application created successfully!');
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
