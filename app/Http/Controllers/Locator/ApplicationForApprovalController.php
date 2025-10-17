<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locator\ApplicationForApproval;
use App\Models\Locator\ApplicationModel;
use Inertia\Inertia;

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
            'status'            => 'pending',
        ]);

        return Inertia::render('Locator/Application/Create', []);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $approvers = ApplicationForApproval::with('approverGroup.approvers')->find($id);

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
    // Find the approval record by form_number
    $applicationForApproval = ApplicationForApproval::where('form_number', $formNumber)
        ->with('approverGroup.approvers')
        ->firstOrFail();

    $group = $applicationForApproval->approverGroup;

    // Update the approver's pivot status
    $group->approvers()->updateExistingPivot($approverId, [
        'status'   => 'approved',
        'acted_at' => now(),
    ]);

    // Check if all approvers approved
    $remaining = $group->approvers()->wherePivot('status', '!=', 'approved')->count();

    if ($remaining === 0) {
        $applicationForApproval->update([
            'status'   => 'approved',
            'acted_at' => now(),
        ]);
    }

    return back()->with('success', 'Application approved successfully.');
}

public function returnApproval(Request $request, $formNumber, $approverId)
{
    $applicationForApproval = ApplicationForApproval::where('form_number', $formNumber)
        ->with('approverGroup')
        ->firstOrFail();

    $group = $applicationForApproval->approverGroup;

    // Update pivot table with status and remark
    $group->approvers()->updateExistingPivot($approverId, [
        'status'   => 'returned',
        'remark'   => $request->input('remark'),
        'acted_at' => now(),
    ]);

    // Optionally mark the whole application as returned
    $applicationForApproval->update([
        'status'   => 'returned',
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
