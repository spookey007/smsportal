@extends('admin.layouts.app')
@section('panel')
    @if (@json_decode($general->system_info)->version > systemDetails()['version'])
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-warning mb-3 text-white">
                    <div class="card-header">
                        <h3 class="card-title"> @lang('New Version Available') <button class="btn btn--dark float-end">@lang('Version') {{ json_decode($general->system_info)->version }}</button> </h3>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-dark">@lang('What is the Update ?')</h5>
                        <p>
                            <pre class="f-size--24">{{ json_decode($general->system_info)->details }}</pre>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (@json_decode($general->system_info)->message)
        <div class="row">
            @foreach (json_decode($general->system_info)->message as $msg)
                <div class="col-md-12">
                    <div class="alert border--primary border" role="alert">
                        <div class="alert__icon bg--primary"><i class="far fa-bell"></i></div>
                        <p class="alert__message">@php echo $msg; @endphp</p>
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="row gy-4">
        <div class="col-xxl-3 col-sm-6">
            <div class="card bg--primary has-link box--shadow2 overflow-hidden">
                <a href="{{ route('admin.email.history') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-envelope-open f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Total Email Sent')</span>
                            <h2 class="text-white">{{ __($widget['total_email_sent']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card bg--success has-link box--shadow2">
                <a href="{{ route('admin.email.history') }}?status=1" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-inbox f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Total Success Email')</span>
                            <h2 class="text-white">{{ __($widget['total_success_email_sent']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card bg--warning has-link box--shadow2">
                <a href="{{ route('admin.email.history') }}?status=2" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-envelope-open-text f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Total Scheduled Email')</span>
                            <h2 class="text-white">{{ __($widget['total_schedule_email']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card bg--red has-link box--shadow2">
                <a href="{{ route('admin.email.history') }}?status=9" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-comment-slash f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Total Faild Email')</span>
                            <h2 class="text-white">{{ __($widget['total_failed_email']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <div class="card bg--info has-link box--shadow2 overflow-hidden">
                <a href="{{ route('admin.sms.history') }}" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-sms f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Total SMS Sent')</span>
                            <h2 class="text-white">{{ __($widget['total_sms_sent']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card bg--green has-link box--shadow2">
                <a href="{{ route('admin.sms.history') }}?status=1" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-comment f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Total Success SMS')</span>
                            <h2 class="text-white">{{ __($widget['total_success_sms_sent']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card bg--17 has-link box--shadow2">
                <a href="{{ route('admin.sms.history') }}?status=2" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="lar la-comment-dots f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Total Scheduled SMS')</span>
                            <h2 class="text-white">{{ __($widget['total_schedule_sms']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="card bg--10 has-link box--shadow2">
                <a href="{{ route('admin.sms.history') }}?status=9" class="item-link"></a>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <i class="las la-phone-slash f-size--56"></i>
                        </div>
                        <div class="col-8 text-end">
                            <span class="text--small text-white">@lang('Total Faild SMS')</span>
                            <h2 class="text-white">{{ __($widget['total_sms_fail']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-users overlay-icon text--success"></i>
                <div class="widget-two__icon b-radius--5 bg--success">
                    <i class="fas fa-users"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ __($widget['total_contacts']) }}</h3>
                    <p>@lang('Total Contact')</p>
                </div>
                <a href="{{ route('admin.contact.all') }}" class="widget-two__btn border--success btn-outline--success border">@lang('View All')</a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-spinner overlay-icon text--warning"></i>
                <div class="widget-two__icon b-radius--5 bg--warning">
                    <i class="las la-mail-bulk"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ __($widget['total_email_contacts']) }}</h3>
                    <p>@lang('Total Email Contact')</p>
                </div>
                <a href="{{ route('admin.contact.email') }}" class="widget-two__btn border--warning btn-outline--warning border">@lang('View All')</a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-ban overlay-icon text--success"></i>
                <div class="widget-two__icon b-radius--5 bg--success">
                    <i class="las la-phone-volume"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ __($widget['total_mobile_contacts']) }}</h3>
                    <p>@lang('Total SMS Contact')</p>
                </div>
                <a href="{{ route('admin.contact.sms') }}" class="widget-two__btn border--success btn-outline--success border">@lang('View All')</a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-ban overlay-icon text--danger"></i>
                <div class="widget-two__icon b-radius--5 bg--danger">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ __($widget['total_banned_contacts']) }}</h3>
                    <p>@lang('Total Banned Contact')</p>
                </div>
                <a href="{{ route('admin.contact.all') }}?status=0" class="widget-two__btn border--danger btn-outline--danger border">@lang('View All')</a>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-ban overlay-icon text--info"></i>
                <div class="widget-two__icon b-radius--5 bg--info">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ __($widget['total_groups']) }}</h3>
                    <p>@lang('Total Group')</p>
                </div>
                <a href="{{ route('admin.contact.all') }}" class="widget-two__btn border--info btn-outline--info border">@lang('View All')</a>
            </div>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="las la-envelope-square overlay-icon text--primary"></i>
                <div class="widget-two__icon b-radius--5 bg--primary">
                    <i class="las la-envelope-square"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ __($widget['total_email_groups']) }}</h3>
                    <p>@lang('Total Email Group')</p>
                </div>
                <a href="{{ route('admin.email.group.index') }}" class="widget-two__btn border--primary btn-outline--primary border">@lang('View All')</a>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="las la-tty overlay-icon text--cyan"></i>
                <div class="widget-two__icon b-radius--5 bg--cyan">
                    <i class="las la-tty"></i>
                </div>
                <div class="widget-two__content">
                    <h3 class="text-white">{{ __($widget['total_mobile_groups']) }}</h3>
                    <p class="text-white">@lang('Total SMS Group')</p>
                </div>
                <a href="{{ route('admin.sms.group.index') }}" class="widget-two__btn border--cyan btn-outline--cyan text--cyan border">@lang('View All')</a>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5 bg--white">
                <i class="fas fa-ban overlay-icon text-white"></i>
                <div class="widget-two__icon b-radius--5 bg--danger">
                    <i class="la la-ban"></i>
                </div>
                <div class="widget-two__content">
                    <h3>{{ __($widget['total_banned_groups']) }}</h3>
                    <p>@lang('Total Banned Group')</p>
                </div>
                <a href="{{ route('admin.group.banned') }}" class="widget-two__btn border--danger btn-outline--danger border">@lang('View All')</a>
            </div>
        </div>
    </div>


    <div class="row gy-4 mt-2">
        <div class="col-lg-12">
            <h5>@lang('Availabe Email Sender')</h5>
        </div>
        @foreach ($emailSender as $sender)
            <div class="col-xl-3 col-sm-6">
                <div class="widget-six bg--white rounded-2 box--shadow2 h-100 {{ $sender }} p-3">
                    <div class="shape-icon text--warning shape-icon-2">
                        <i class="las la-envelope"></i>
                    </div>
                    <div class="widget-six__top justify-content-center border-bottom border--{{ $sender }} p-2">
                        <img src=" {{ getImage('assets/admin/images/email_sender/' . $sender . '.png') }}" alt="{{ $sender }}" class="__img gateway-thumb" alt="">
                    </div>
                    <div class="widget-six__bottom mt-3">
                        <h5>{{ __(ucfirst($sender)) }}</h5>
                        <a href="{{ route('admin.setting.notification.email') }}?sender={{ $sender }}" class="btn btn-outline--primary btn-sm">@lang('Configure')</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <div class="row gy-4 mt-2">
        <div class="col-lg-12">
            <h5>@lang('Availabe SMS Gateway')</h5>
        </div>
        @foreach ($smsGateway as $gatWay)
            <div class="col-xl-3 col-sm-6">
                <div class="widget-six bg--white rounded-2 box--shadow2 h-100 p-3">
                    <div class="shape-icon text--warning">
                        <i class="las la-wifi"></i>
                    </div>
                    <div class="widget-six__top justify-content-center border-bottom border--{{ $gatWay }} p-2">
                        <img src="{{ getImage('assets/admin/images/sms_gatway/' . strtolower($gatWay) . '.png') }}" class="gateway-thumb" alt="{{ $gatWay }}">
                    </div>
                    <div class="widget-six__bottom mt-3">
                        <h5>{{ __(ucfirst($gatWay)) }}</h5>
                        <a href="{{ route('admin.setting.notification.sms') }}?sms_method={{ $gatWay }}" class="btn btn-outline--primary btn-sm">@lang('Configure')</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal fade bd-example-modal-lg" id="cronModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Cron Job Setting Instruction')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <p class="cron-p-style cron-p-style alert-info text--dark p-3">
                                @lang('To automatic send scheduled Emails, and SMS, you need to set the cron job and make sure the job is running properly. Set the cron time as minimum as possible. Once per minute is ideal.')
                            </p>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>@lang('Email Sent Command')</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg copyText" value="curl -s {{ route('cron.email') }}" readonly>
                                <button class="input-group-text btn--primary copyBtn border-0"> @lang('COPY')</button>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>@lang('SMS Send Command')</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg copyText" value="curl -s {{ route('cron.sms') }}" readonly>
                                <button class="input-group-text btn--primary copyBtn border-0"> @lang('COPY')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        'use strict';
        (function($) {

            $('.copyBtn').on('click', function() {
                var copyText = $(this).parents('.input-group').find('.copyText')[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999)
                document.execCommand("copy");
                notify('success', 'Url copied successfully ' + copyText.value);
            });

            @if (Carbon\Carbon::parse($general->last_email_cron)->diffInSeconds() >= 6000 || Carbon\Carbon::parse($general->last_sms_cron)->diffInSeconds() >= 6000)
                $(window).on('load', function(e) {
                    $("#cronModal").modal('show');
                });
            @endif

        })(jQuery);
    </script>
@endpush



@push('style')
    <style>
        .gateway-thumb {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin: 0 auto;
        }

        .widget-six__bottom {
            gap: 5px 15px;
        }

        .widget-six {
            position: relative;
        }

        .widget-six .shape-icon {
            position: absolute;
            right: -30px;
            top: -32px;
            font-size: 100px;
            opacity: .09;
            transform: rotate(-135deg);
            line-height: 1;
        }

        .shape-icon.shape-icon-2 {
            transform: unset;
            right: -3px;
            top: -20px;
        }

        .click-mail {
            border-color: #d9f4fc !important;
        }

        .border--phpmail {
            border-bottom: 1px solid #6281b8 !important;
        }

        .border--smtp {
            border-bottom: 1px solid #13bf9b !important;
        }

        .border--mailjet {
            border-bottom: 1px solid #6281b8 !important;
        }

        .border--sendgrid {
            border-bottom: 1px solid #6fd4ef !important;
        }

        .border--infobip {
            border-bottom: 1px solid #ff5c15 !important;
        }

        .border--clickatell {
            border-bottom: 1px solid #8dc63f !important;
        }

        .border--messageBird {
            border-bottom: 1px solid #2581d8 !important;
        }

        .border--infobip {
            border-bottom: 1px solid #ff5c15 !important;
        }

        .border--nexmo {
            border-bottom: 1px solid #000 !important;
        }

        .border--smsBroadcast {
            border-bottom: 1px solid #e86c1f !important;
        }

        .border--twilio {
            border-bottom: 1px solid #f12e44 !important;
        }

        .border--textMagic {
            border-bottom: 1px solid #30668f !important;
        }

        .border--custom {
            border-bottom: 1px solid #000 !important;
        }

    </style>
@endpush
