@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body">
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span class="fw-bold">@lang('SMS To: ')</span>
                        <span>{{ __($sms->mobile)}}</span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span class="fw-bold">@lang('Iniated: ')</span>
                        <span class="text-end">
                            {{ @$sms->created_at}} <br> <small>{{ diffForHumans($sms->created_at) }}</small>
                        </span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span class="fw-bold">@lang('Message:')</span>
                        <span class="text-end">
                            {{ __(@$sms->message)}}
                        </span>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.sms.history') }}" class="btn btn-outline--primary">
    <i class="las la-list"></i> @lang('SMS History')
</a>
@endpush
