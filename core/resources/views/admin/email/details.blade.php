@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body">
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span class="fw-bold">@lang('Email To: ')</span>
                        <span>{{ __($email->email)}}</span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span class="fw-bold">@lang('Subject: ')</span>
                        <span>{{ __($email->subject)}}</span>
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span class="fw-bold">@lang('Iniated: ')</span>
                        <span class="text-end">
                            {{ @$email->created_at}} <br> <small>{{ diffForHumans($email->created_at) }}</small>
                        </span>
                    </li>
                </ul>
                @php
                    echo $email->message;
                @endphp
            </div>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.email.history') }}" class="btn btn-outline--primary">
    <i class="las la-list"></i> @lang('Email History')
</a>
@endpush
