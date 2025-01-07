<?php
namespace App\Http\Controllers\Lead;
use Illuminate\Pagination\Paginator;


use App\Http\Controllers\Controller;
use App\Models\Lead\Lead;
use Illuminate\Http\Request;
use App\Models\User\User;
use Carbon\Carbon;

class LeadStatusBaseRecordController extends Controller
{

    public function StatusBaseRecordShow($id, Request $request)
    {   
        $user_id = $request->query('user_id'); // Retrieve user_id from query parameters

        // Fetch leads based on the provided lead status ID and user_id with pagination
        $leadsQuery = Lead::with(['company', 'vendor', 'interviewer', 'createdUser', 'technology', 'leadStatus'])
                        ->where('interview_status', $id);

        // Apply additional condition if user_id is provided in the URL
        if ($user_id) {
            //apply filter for Interview and sales base on the role id
            $user_detail = User::findOrFail($user_id);
            $user_detail_role = $user_detail->role;

            if($user_detail_role == 3){
                $leadsQuery->where('interviewee_id', $user_id);
            }else{
                $leadsQuery->where('lead_created_user_id', $user_id);
            }

        }

        $leads = $leadsQuery->paginate(10); // Specify the number of records per page

        return view('lead.leadstatus_filterRecord', compact('leads'));
    }
    public function UpcomingOnboardingProjectList(Request $request)
    {
        $leads = Lead::with(['company', 'vendor', 'interviewer', 'createdUser', 'technology', 'leadStatus'])
        ->whereRaw("STR_TO_DATE(joining_date, '%d-%m-%Y') >= ?", [Carbon::today()->format('Y-m-d')]) // Compare the date
        ->paginate(10);

    return view('lead.UpcomingOnboardingProjectList', compact('leads'));
    }

}
