<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locator\ApplicationModel;
use App\Models\Locator\ApplicationOption;
use Inertia\Inertia;
use App\Models\ApplicationCategory;
use App\Models\Locator\UserApplicationSelection;


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
    // $application = ApplicationModel::create([
    //     'form_title' => 'Gate Pass Permit',
    //     'user_id'    => auth()->id(),
    // ]);

    // return response()->json([
    //     'message' => 'Application created successfully!',
    //     'data'    => $application,
    // ]);
   $user = auth()->user(); // or Auth::user()
    //   $application = \App\Models\ApplicationModel::create([
    //     'form_title' => 'Draft Application',
    //     'user_id'    => $user->id,
    // ]);
   
    return Inertia::render('Locator/Application/Create', [
        'user' => $user,
        'application_form_id' => null,
        
    ]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->input('type'));
       $user = auth()->user();
    $categories = ApplicationCategory::with('options.validity')->get();
    
    // Create an empty application record just to get the ID
    $application = ApplicationModel::create([
        'form_title' => $request->input('type') ,
        'user_id'    => $user->id,
    ]);
    // Collection of all options
    

    return Inertia::render('Locator/Application/Create', [
        'user' => $user,
        'application_form_id' => $application->id, // Pass the new ID
        'categories' => $categories,
    'options' => ApplicationOption::select('id', 'name', 'value', 'validity')->get(),

    ]);

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
    public function pendingList(){
        return Inertia::render('Locator/Application/Pending', []);
    }
    public function approvedList(){
        return Inertia::render('Locator/Application/Approved',[]);
    }
    /**
     * Request $request are application_id and option_id selected by the locator
     * this function saves selected option to user_application_selected Table
     * 
     * **/
    public function saveOptionSelection(Request $request)
    {    
    $user = auth()->user();
    $validated = $request->validate([
        'application_id' => 'required|exists:application_forms,id',
        'option_id' => 'required|exists:application_options,id',
    ]);

    $selection = UserApplicationSelection::updateOrCreate(
        [
            'application_id' => $validated['application_id'],
            'user_id'        => auth()->id(),
        ],
        [
            'option_id'   => $validated['option_id'],
            'selected_at' => now(),
            'amount'      => ApplicationOption::find($validated['option_id'])->value ?? 0,
        ]
    );
    
    return Inertia::render('Locator/Application/Create',[
       'user' => $user,
        'application_form_id' => $selection->application_id,
        'options' => ApplicationOption::select('id', 'name', 'value', 'validity')->get(),
    ]);
    
    }
}
