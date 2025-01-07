<?php

namespace App\Http\Controllers\Lead;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\User;
use App\Models\User\Role;
use App\Models\Company\Company;
use App\Models\Company\Technology;
use Illuminate\Support\Str;
use App\Models\Lead\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Lead\LeadStatus;
use App\Models\Lead\LeadHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor\Vendor;


class LeadEditController extends Controller
{

    public function edit(Lead $lead)
    {
        // $leads = Lead::with(['company', 'vendor', 'interviewer', 'createdUser', 'technology', 'leadStatus'])->get();

        $editLead = Lead::find($lead->id);

        $vendors = Vendor::with('technology')->get();

    
        $interviewee_users = User::select('users.*', 'roles.role_name')
            ->leftJoin('roles', 'users.role', '=', 'roles.role_id')
            ->where('users.role', '=', 3)
            ->get();
    
        $companies = Company::where('company_status', 1)->get();

        $technologies = Technology::where('technology_status', 1)->get();

        $LeadStatuss = LeadStatus::where('leadstatusstatus', 1)->get();

        return view('lead.edit-lead', compact('editLead', 'vendors', 'interviewee_users', 'companies', 'technologies', 'LeadStatuss'));
    }
    public function update(Request $request, Lead $lead)
    {
    
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'technology_id' => 'nullable',
            'vendor_id' => 'required',
            'interviewee_id' => 'required',
            'interview_date' => 'required|date_format:d-m-Y',
            'lead_comment' => 'nullable',
            'interview_time' => 'nullable',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Redirect back to the form view with validation errors and old input data
            return back()->withErrors($validator)->withInput();
        }

        // Update lead data
        $lead->company_id = $request->input('company_id');
        $lead->technology_id = $request->input('technology_id');
        $lead->vendor_id = $request->input('vendor_id');
        $lead->interviewee_id = $request->input('interviewee_id');
        $lead->interview_date = Carbon::createFromFormat('d-m-Y', $request->input('interview_date'));
        $lead->lead_comment = $request->input('lead_comment');
        $lead->interview_status = $request->input('interview_status');
        $lead->meeting_link = $request->input('meeting_link');
        $lead->source = $request->input('source');
        $lead->company_email = $request->input('company_email');
        $lead->company_phone = $request->input('company_phone');
        $lead->company_rate = $request->input('company_rate');
        $lead->joining_date = $request->input('joining_date');
        $lead->interview_time = $request->input('interview_time');
        $lead->save();

        $leadId = $lead->id;
        // Get the logged-in user's name and role
        $user = Auth::user();
        $userName = $user->firstname . ' ' . $user->lastname;
        $userId = $user->user_id;
        $roleName = Role::find($user->role)->role_name;
        // Insert data into LeadHistory table
        $leadHistory = new LeadHistory();
        $leadHistory->lead_id = $leadId;
        $leadHistory->comment = $request->input('lead_comment'); 
        $leadHistory->interview_status = $request->input('interview_status');

        // Set the logged-in user's name and role
        $leadHistory->leadCreate_user_Id = $userId;
        $leadHistory->leadCreate_user_name = $userName;
        $leadHistory->leadCreate_user_role = $roleName;

        $leadHistory->save();

        $edit_user_id = $request->input('edit_user_id');
        if(isset($edit_user_id)){
            return redirect()->route('user.leads.show', ['userId' => $edit_user_id])->with('success', 'Lead Updated successfully!');
        }else{
            return redirect()->route('view.lead')->with('success', 'Lead Updated successfully!');
        }

    }
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('view.lead')->with('success', 'Lead deleted successfully.');
    }
    
}
