<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locator\ApplicationForApproval;
use Inertia\Inertia;

class ApplicationForApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *for Approver Group, Approver, ApplicationForApproval proof of concept
     */
    public function index()
    {
       $approvals = ApplicationForApproval::with('approverGroup.approvers', 'application')->get();

if ($approvals->isEmpty()) {
    echo "No approvals found.<br>";
} else {
    foreach ($approvals as $approval) {
        echo "Approval ID: {$approval->id}<br>";

        echo $approval->approver?->name 
            ? "Approver: {$approval->approver->name}<br>" 
            : "No approver assigned<br>";

        echo $approval->approverGroup?->name 
            ? "Approver Group: {$approval->approverGroup->name}<br>" 
            : "No group assigned<br>";

        if ($approval->approverGroup) {
            echo "<br>Approvers of {$approval->approverGroup->name} with sequence:<br>";
            foreach ($approval->approverGroup->approvers as $user) {
                echo "- {$user->name} (sequence: {$user->pivot->sequence})<br>";
            }
        } else {
            echo "No approver group available.<br>";
        }

        echo "<hr>";
    }
}

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $application = ApplicationForApproval::create([
        'application_id'      => $request->input('application_id'),
        'approver_group_id'   => $request->input('approver_group_id'),
        'status'              => 'pending',
    ]);

       return Inertia::render('Locator/Application/Create',[]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {   
        $approvers = ApplicationForApproval::with('approverGroup.approvers')->find($id);
        //this is how to get the ApproverGroup
        echo "<pre>";
        print_r($approvers->approverGroup);
        //this is how to get the Approvers of the ApproverGroup
        print_r($approvers->approverGroup->approvers);
        echo "</pre>";
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
