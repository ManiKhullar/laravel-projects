<?php

namespace App\Models\Lead;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadEndClientOrCompanyName extends Model
{
    use HasFactory;

    protected $table = 'lead_end_client_or_company_name';

    protected $fillable = [
        'lead_id',
        'end_client_or_company_name',
    ];
}
