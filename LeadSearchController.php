<?php

namespace App\Http\Controllers\Lead;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Lead\Lead;

class LeadSearchController extends Controller
{
    /**
     * Display the search form for leads.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showSearchForm()
    {
        // echo "DSFS";
        // die;
        return view('lead.search.searchform');
    }

    public function LeadSearch(Request $request)
    {
        $query = $request->input('query');
    
        $leads = Lead::select(
            'leads.*',
            'leads.created_at as lead_created_at',
            'companies.*',
            'vendors.*',
            'technologies.technology_name',
            'interviewers.firstname as interviewer_firstname',
            'interviewers.lastname as interviewer_lastname',
            'lead_statuses.leadstatusname as lead_status'
        )
        ->leftJoin('companies', 'leads.company_id', '=', 'companies.company_id')
        ->leftJoin('vendors', 'leads.vendor_id', '=', 'vendors.vendor_id')
        ->leftJoin('users as interviewers', 'leads.interviewee_id', '=', 'interviewers.user_id')
        ->leftJoin('lead_statuses', 'leads.interview_status', '=', 'lead_statuses.leadstatusid')
        ->leftJoin('technologies', 'vendors.technology_id', '=', 'technologies.technology_id')
        ->where(function($queryBuilder) use ($query) {
            $queryBuilder->where('companies.company_name', 'like', "%{$query}%")
                         ->orWhere(DB::raw("CONCAT(vendors.name, ')')"), 'like', "%{$query}%")
                         ->orWhere(DB::raw("CONCAT(interviewers.firstname, ' ', interviewers.lastname)"), 'like', "%{$query}%");
        })
        ->get();
    
        $oldLeads = DB::table('old_records')
            ->where('client_name', 'like', "%{$query}%")
            ->orWhere('candidate_name', 'like', "%{$query}%")
            ->orWhere('interview_taken_by', 'like', "%{$query}%")
            ->orWhere('technology', 'like', "%{$query}%")
            ->get();
    
        return response()->json([
            'current' => $leads,
            'old' => $oldLeads
        ]);
    }
    
}
