<?php

namespace App\Models\Lead;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company\Company;
use App\Models\Company\Technology;
use App\Models\User\User;
use App\Models\Vendor\Vendor;


class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        // Other fillable fields here,
        'is_read',
    ];

    public function leadHistories()
    {
        return $this->hasMany(LeadHistory::class);
    }

    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class, 'interview_status', 'leadstatusid');
    }

    public function leadStatusLead()
    {
        return $this->belongsTo(LeadStatus::class, 'interview_status', 'leadstatusid')->select('leadstatusid', 'leadstatusname');
    }

    public static function countLeadsCreatedByUser($userId)
    {
        return self::where('lead_created_user_id', $userId)->count();
    }
    // ====================================================================
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function companyLead()
    {
        return $this->belongsTo(Company::class, 'company_id')->select('company_id', 'company_name');
    }

    // ====================================================================

    // Define the relationship with the User model for candidate
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function vendorLead()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id')->select('vendor_id', 'name', 'technology_id');
    }

    // ====================================================================

    // Define the relationship with the User model for interviewer
    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewee_id');
    }
    public function interviewerLead()
    {
        return $this->belongsTo(User::class, 'interviewee_id')->select('user_id', 'firstname');
    }
    // ====================================================================

    // Define the relationship with the User model for interviewer
    public function createdUser()
    {
        return $this->belongsTo(User::class, 'lead_created_user_id');
    }

    public function createdUserLead()
    {
        return $this->belongsTo(User::class, 'lead_created_user_id')->select('user_id', 'firstname');
    }


    // Define the relationship with the Technology model
    public function technology()
    {
        return $this->belongsTo(Technology::class, 'technology_id');
    }

}
