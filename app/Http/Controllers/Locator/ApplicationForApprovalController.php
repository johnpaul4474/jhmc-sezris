<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locator\ApplicationForApproval;
use App\Models\Locator\ApplicationModel;
use Inertia\Inertia;
use App\Models\Locator\ApproverGroupApprover;

class ApplicationForApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     * For Approver Group, Approver, ApplicationForApproval proof of concept
     */
    public function index()
    {
        //
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
        $app = ApplicationModel::findOrFail($request->input('application_id'));

        ApplicationForApproval::create([
            'application_id'    => $request->input('application_id'),
            'approver_group_id' => $request->input('approver_group_id'),
            'form_number'       => $app->form_number,
            'status'            => 'Pending',
        ]);

        return Inertia::render('Locator/Application/Create', []);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $approvers = ApplicationForApproval::with('approverGroup.approvers')->find($id);
    dd($approvers);
        // This is how to get the ApproverGroup
        echo "<pre>";
        print_r($approvers->approverGroup);

        // This is how to get the Approvers of the ApproverGroup
        print_r($approvers->approverGroup->approvers);
        echo "</pre>";
    }

    /**
     * Approve the application for the given approver.
     */
   public function approve(Request $request, $formNumber, $approverId)
{ 
    $appForm = ApplicationModel::where('form_number', $formNumber)->firstOrFail();

    $applicationForApproval = ApplicationForApproval::where('application_id', $appForm->id)
        ->with('approverGroup.approvers')
        ->firstOrFail();

    $group = $applicationForApproval->approverGroup;
    
    // ✅ Update the approver's pivot record
    $group->approvers()->updateExistingPivot($approverId, [
        'status'   => 'Approved',
        'acted_at' => now(),
    ]);
     
    // ✅ Check if all approvers have approved
    $remaining = $group->approvers()->wherePivot('status', '!=', 'Approved')->count();
    if ($remaining === 0) {
        $applicationForApproval->update([
            'status'   => 'Approved',
            'acted_at' => now(),
        ]);
        $appForm->update(['status' => 'Approved']);
    }

    // ✅ Reload all fresh data
    //$applicationForApproval->load('approverGroup.approvers');
    //$appForm->load('approvals'); // adjust this if ApplicationModel has a relation

    // ✅ Return updated data to frontend
    return back()->with([
        'success' => 'Approved successfully.',
        'application' => $appForm,
        'approvers' => $group->approvers,
    ]);
}


public function returnApproval(Request $request, $formNumber, $approverId)
{   
    $appForm= ApplicationModel::where('form_number', $formNumber)->first();

    $applicationForApproval = ApplicationForApproval::where('application_id', $appForm->id)
        ->with('approverGroup')
        ->firstOrFail();

    $group = $applicationForApproval->approverGroup;

    // Update pivot table with status and remark
    $group->approvers()->updateExistingPivot($approverId, [
        'status'   => 'Returned',
        'remark'   => $request->input('remark'),
        'acted_at' => now(),
    ]);
     $Approver = ApproverGroupApprover::where('approver_id',$approverId)->first();
     $prevApprover = ApproverGroupApprover::where('sequence',($Approver->sequence - 1))->first();
      $prevApprover->status = 'Pending';
      $prevApprover->save();
     // Optionally mark the whole application as returned
    $applicationForApproval->update([
        'status'   => 'Returned',
        'acted_at' => now(),
    ]);

    return back()->with('success', 'Application returned successfully.');
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
    $approval = ApplicationForApproval::findOrFail($id);

    $status = $request->input('status');

    if (in_array($status, ['approved', 'returned'])) {
        $approval->update([
            'status' => $status,
        ]);
    }

    return redirect()->back()->with('success', "Application has been {$status}.");
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
