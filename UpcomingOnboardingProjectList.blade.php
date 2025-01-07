@extends('layouts.app')
@section('content')
@section('title', 'Lead')

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="page-title">
                                 Upcoming Onboarding ProjectList
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <!-- <th>Id</th> -->
                                        <th>Company</th>
                                        <th style="display: none;">email</th>
                                        <th>Vendor</th>
                                        <th>Interviewee</th>
                                        <th>Lead Comment</th>
                                        <th>Status</th>
                                        <th>joining date</th>
                                        <th>Action</th>
                                        <th>Created BY</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leads as $lead)
                                    <tr>
                                        <!-- <td>{{ $lead->id }}</td> -->
                                        <td title="{{ $lead->company->company_name }}">
                                            <i class="fa fa-building"></i> 
                                            <span style="color: blue; font-weight: bold;">
                                                {{ mb_strimwidth($lead->company->company_name, 0, 10, '...') }}
                                            </span><br>
                                            <span> {{ $lead->company_phone }} </span><br>
                                            @if(isset($lead->company_rate))
                                                <div class="badge badge-success">
                                                    <span class="text">{{ $lead->company_rate }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td style="display: none;">
                                            {{ $lead->company_email }}
                                        </td>

                                        <td>
                                            <i class="fa fa-user"></i> {{ $lead->vendor->name }}  <h2><a href="{{ route('leads.show', $lead->id) }}">{{ $lead->vendor->technology->technology_name }}</a></h2>
                                        </td>
                                        <td>
                                            <i class="fa fa-id-card"></i> {{ $lead->interviewer->firstname }}  <h2><a href="{{ route('leads.show', $lead->id) }}" title="Interview Date"> <i class="fas fa-calendar-alt"></i>{{ \Carbon\Carbon::parse($lead->interview_date)->format('d-m-Y') }}</a></h2>
                                        </td>

                                        <td title="{{ $lead->lead_comment }}">
                                            <textarea name="lead_comment" class="form-control" rows="2" readonly data-toggle="popover"  data-content="{{ $lead->lead_comment }}">{{ $lead->lead_comment }}</textarea>
                                        </td>
                                        
                                        <td>
                                            <span class="badge badge-custom">
                                                <i></i> {{ $lead->leadStatus->leadstatusname }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <span class="badge badge-custom">
                                                <i></i> {{ $lead->joining_date }}
                                            </span>
                                        </td>

                                        <td class="text-right">
                                            <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-primary btn-sm mb-1">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="{{ route('leads.show', $lead->id) }}" class="btn btn-warning btn-sm mb-1">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            {{ $lead->createdUser->firstname }}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($lead->updated_at)->format('d M Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="disable-next-prev">
                        <ul class="pagination justify-content-right">
                            {{ $leads->links() }}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
       
        @include('section/notification') 
    </div>
</div>

@endsection
