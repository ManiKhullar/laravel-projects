<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead\LeadStatus;
use App\Models\Lead\Lead;


class LeadStatusController extends Controller
{
   /**
     * Show the form for creating a new lead status.
     *
     * @return \Illuminate\View\View
     */

    public function create()
    {
        // Fetch all lead statuses
        $leadStatuses = LeadStatus::all();

        // Fetch count of leads for each lead status
        $leadStatusesCount = [];
        foreach ($leadStatuses as $leadStatus) {
            $leadStatusesCount[$leadStatus->leadstatusid] = Lead::where('interview_status', $leadStatus->leadstatusid)->count();
        }

        return view('lead.leadStatus.add-leadstatus', compact('leadStatuses', 'leadStatusesCount'));
    }

    /**
     * Store a newly created lead status in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'leadstatusname' => 'required|string|max:255',
        ]);

        // Create a new lead status record
        LeadStatus::create([
            'leadstatusname' => $request->leadstatusname,
            'leadstatusstatus' => 1, 
        ]);

        return redirect()->back()->with('success', 'Lead status added successfully.');
    }
    public function edit($id)
    {
        $leadStatuses = LeadStatus::all();
        $editleadStatus = LeadStatus::findOrFail($id);
        return view('lead.leadStatus.add-leadstatus', compact('editleadStatus', 'leadStatuses'));
    }

     /**
     * Update the specified lead status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeadStatus  $leadstatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeadStatus $leadstatus)
    {
        // Validate the request data
        $request->validate([
            'leadstatusname' => 'required|string|max:255',
            'leadstatusstatus' => 'required|in:active,inactive',
        ]);

        // Update the lead status
        $leadstatus->update([
            'leadstatusname' => $request->input('leadstatusname'),
            'leadstatusstatus' => $request->input('leadstatusstatus'),
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Lead status updated successfully');
    }
    public function destroy($id)
    {
        $leadStatus = LeadStatus::find($id);

        if ($leadStatus) {
            // Delete the lead status
            $leadStatus->delete();
            return redirect()->back()->with('success', 'Lead status deleted successfully');
        }
        return redirect()->back()->with('error', 'Lead status not found');
    }
}
