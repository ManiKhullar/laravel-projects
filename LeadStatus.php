<?php

namespace App\Models\Lead;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    protected $table = 'lead_statuses';
    protected $primaryKey = 'leadstatusid';
    protected $fillable = ['leadstatusname', 'leadstatusstatus'];
}
