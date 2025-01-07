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
use Illuminate\Support\Facades\Cache;



class LeadController extends Controller
{
    public function showLeadForm()
    {
        $vendors = Vendor::with('technology')
        ->orderBy('name')
        ->get();

        $interviewee_users = User::select('users.*', 'roles.role_name')
        ->leftJoin('roles', 'users.role', '=', 'roles.role_id')
        ->where('users.role', 3)
        ->orderBy('firstname')
        ->orderBy('lastname')
        ->get();
        $LeadStatuss = LeadStatus::where('leadstatusstatus', 1)->where('leadstatusstatus', 'active')->get();
        
        // $leads = Lead::with(['company', 'vendor', 'interviewer', 'createdUser', 'technology', 'leadStatus'])->get();
        
        $companies = Company::where('company_status', 1)->orderBy('company_name')->get();

        $technologies = Technology::where('technology_status', 1)->get();
             
        return view('lead.add-lead', compact('companies','technologies','vendors','interviewee_users', 'LeadStatuss'));
    }

    public function index()
    {
        $user = Auth::user();
        $loggedinUserId = $user->user_id;
        $loggedinUserRole = $user->role;

        // Fetch LeadStatuss directly without caching
        $LeadStatuss = LeadStatus::where('leadstatusstatus', 1)->get(['leadstatusid', 'leadstatusname']);

        // Use eager loading and select only necessary fields for Lead model
        // $leadsQuery = Lead::with(['companyLead', 'vendorLead', 'interviewerLead', 'createdUserLead', 'leadStatusLead']);
        $leadsQuery = Lead::select('id', 'company_id', 'technology_id', 'vendor_id', 'interviewee_id', 'interview_date', 'interview_status', 'lead_created_user_id', 'created_at', 'updated_at', 'is_read', 'lead_comment')
    ->with(['companyLead', 'vendorLead', 'interviewerLead', 'createdUserLead', 'leadStatusLead']);


        if ($loggedinUserRole == 3) {
            // If user role is 3, filter by interviewee_id
            $leadsQuery->where('interviewee_id', $loggedinUserId);
        }

        // Get paginated results
        $leads = $leadsQuery->get();

        
        return view('lead.view-lead', compact('leads', 'LeadStatuss'));
    }

    public function indexc2c()
    {
        $user = Auth::user();
        $loggedinUserId = $user->user_id;
        $loggedinUserRole = $user->role;

        $LeadStatuss = LeadStatus::where('leadstatusstatus', 1)->get(['leadstatusid', 'leadstatusname']);

        $leadsQuery = Lead::select('id', 'company_id', 'technology_id', 'vendor_id', 'interviewee_id', 'interview_date', 'interview_status', 'lead_created_user_id', 'created_at', 'updated_at', 'is_read', 'lead_comment')
        ->with(['companyLead', 'vendorLead', 'interviewerLead', 'createdUserLead', 'leadStatusLead']);
           
        // $leadsQuery->where('interviewee_id', $loggedinUserId);
        $leadsQuery->whereHas('vendorLead', function ($query) {
            $query->where('technology_id', 10);  // Filter leads where vendor's technology_id is 10
        });
        

        $leads = $leadsQuery->get();

        return view('lead.view-lead-c2c', compact('leads', 'LeadStatuss'));
    }

     public function searchLeads(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $interviewStatus = $request->input('interview_status');
    
        // Initialize leads query
        $leadsQuery = Lead::with(['company', 'vendor', 'interviewer', 'createdUser', 'technology', 'leadStatus']);
    
        // Validate date formats
        $dateFormat = 'd-m-Y';
        if (!empty($fromDate) && !Carbon::createFromFormat($dateFormat, $fromDate)) {
            return redirect()->back()->with('message', 'We accept this date format: ' . $dateFormat)->withInput();
        }
        if (!empty($toDate) && !Carbon::createFromFormat($dateFormat, $toDate)) {
            return redirect()->back()->with('message', 'We accept this date format: ' . $dateFormat)->withInput();
        }
    
        // If from_date is provided but to_date is not, show a message
        if (!empty($fromDate) && empty($toDate)) {
            return redirect()->back()->with('message', 'Please select the to date.')->withInput();
        }
        // If to_date is provided but from_date is not, show a message
        if (!empty($toDate) && empty($fromDate)) {
            return redirect()->back()->with('message', 'Please select the from date.')->withInput();
        }
    
        // Convert dates and apply date filter
        if (!empty($fromDate) && !empty($toDate)) {
            $fromDate = Carbon::createFromFormat($dateFormat, $fromDate)->startOfDay();
            $toDate = Carbon::createFromFormat($dateFormat, $toDate)->endOfDay();
            $leadsQuery->whereBetween('updated_at', [$fromDate, $toDate]);
        }
    
        // Filter by interview status if provided
        if ($interviewStatus) {
            $leadsQuery->where('interview_status', $interviewStatus);
        }
    
        // Get the filtered leads
        $leads = $leadsQuery->get();
    
        $LeadStatuss = LeadStatus::where('leadstatusstatus', 1)->get();
    
        // Prepare the selected values for the view
        $selectedValues = [
            'from_date' => $fromDate ? $fromDate->format('d-m-Y') : null,
            'to_date' => $toDate ? $toDate->format('d-m-Y') : null,
            'interview_status' => $interviewStatus,
        ];
    
        return view('lead.view-lead', compact('leads', 'LeadStatuss', 'selectedValues'));
    }

    public function updateReadStatus(Request $request, Lead $lead)
    {
        $lead->update(['is_read' => $request->is_read]);
        return response()->json(['success' => true]);
    }
    
  
}
