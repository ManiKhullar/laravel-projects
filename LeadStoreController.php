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


class LeadStoreController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'technology_id' => 'nullable',
            'vendor_id' => 'required',
            'interviewee_id' => 'required',
            'interview_date' => 'required|date_format:d-m-Y',
            'lead_comment' => 'nullable',
            'meeting_link' => 'nullable',
            'source' => 'nullable',
            'other_company_name' => 'nullable|required_if:company_id,other|string|max:255',
            'interview_time' => 'nullable',

        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Redirect back to the form view with validation errors and old input data
            return back()->withErrors($validator)->withInput();
        }

        // Check if the candidate is already associated with the company
        $existingLead = Lead::where('company_id', $request->input('company_id'))
        ->where('vendor_id', $request->input('vendor_id'))
        ->whereNotIn('interview_status', [13])
        ->exists();

        if ($existingLead) {
            return back()->with('error', 'This candidate is already associated with this company.')->withInput();
        }

        // Check if the selected company is "other"
        if ($request->input('company_id') === 'other') {
            // Check if the company name already exists
            $existingCompany = Company::where('company_name', $request->input('other_company_name'))->first(); // Use first() instead 

            if ($existingCompany) {
               
                $companyId = $existingCompany->company_id;
        
                // Return back with error message and selected company ID
                return back()->with('error', 'The company name already exists. And has been selected')->with('selected_company_id', $companyId);
            }

            // Create a new company instance and save it to the database
            $company = new Company();
            $company->company_name = $request->input('other_company_name');
            $company->company_status = 1;
            $company->save();

            // Set the company ID to the newly created company's ID
            $companyId = $company->company_id;
        } else {
            $companyId = $request->input('company_id');
        }

        // Format the interview date using Carbon
        $interviewDate = Carbon::createFromFormat('d-m-Y', $request->input('interview_date'));

        // Get the logged-in user's name and role
        $user = Auth::user();
        $userId = $user->user_id;
        $userName = $user->firstname . ' ' . $user->lastname;
        $roleName = Role::find($user->role)->role_name;
        // Create a new lead instance and save it to the database
        $lead = new Lead();
        $lead->company_id = $companyId;
        $lead->technology_id = $request->input('technology_id');
        $lead->vendor_id = $request->input('vendor_id');
        $lead->interviewee_id = $request->input('interviewee_id');
        $lead->interview_date = $interviewDate;
        $lead->interview_status = $request->input('interview_status');
        $lead->lead_comment = $request->input('lead_comment');
        $lead->meeting_link = $request->input('meeting_link');
        $lead->source = $request->input('source');
        $lead->company_email = $request->input('company_email');
        $lead->company_phone = $request->input('company_phone');
        $lead->company_rate = $request->input('company_rate');
        $lead->lead_created_user_id = $userId;
        $lead->lead_created_user_role = $user->role;
        $lead->interview_status = $request->input('interview_time');
        $lead->save();

        $leadId = $lead->id;
 

        // Insert data into LeadHistory table
        $leadHistory = new LeadHistory();
        $leadHistory->lead_id = $leadId;
        $leadHistory->comment = $request->input('lead_comment'); 
        $leadHistory->interview_status = $request->input('interview_status');
        $leadHistory->leadCreate_user_Id = $userId;
        $leadHistory->leadCreate_user_name = $userName;
        $leadHistory->leadCreate_user_role = $roleName;
        $leadHistory->save();

        return redirect()->route('add.lead.submit')->with('success', 'Lead created successfully!');
    }
  
}
