@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form action="{{ route('admin.setting.notification.email.update') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label class="fw-bold">@lang('Email Sent From') </label>
                        <input type="text" class="form-control " placeholder="@lang('Email address')" name="email_from"
                            value="{{ $general->email_from }}" required />
                    </div>
                    <div class="form-group">
                        <label class="fw-bold">@lang('Email Send Method')</label>
                        <select name="email_method" class="form-control">
                            <option value="php">@lang('PHP Mail')</option>
                            <option value="smtp">@lang('SMTP')</option>
                            <option value="sendgrid">@lang('SendGrid API')</option>
                            <option value="mailjet">@lang('Mailjet API')</option>
                        </select>
                    </div>
                    <div class="row mt-4 d-none configForm" id="smtp">
                        <div class="col-md-12">
                            <h6 class="mb-2 fw-bold">@lang('SMTP Configuration')</h6>
                        </div>
                        <div class="col-12 select2--wrapper">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Smtp')</label>
                                <select class="form-control select2-basic" name="smtp">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    @foreach ($allSmtp as $smtp)
                                    <option value="{{$smtp->id}}" data-smtp='@json($smtp)'>
                                        {{__($smtp->host).'-'.__($smtp->username)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Host') </label>
                                <input type="text" class="form-control" placeholder="e.g. @lang('smtp.googlemail.com')"
                                    readonly name="host" value="{{ $general->mail_config->host ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Port') </label>
                                <input type="text" class="form-control" placeholder="@lang('Available port')" readonly
                                    name="port" value="{{ $general->mail_config->port ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Encryption')</label>
                                <select class="form-control" readonly name="enc">
                                    <option value="ssl">@lang('SSL')</option>
                                    <option value="tls">@lang('TLS')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Username') </label>
                                <input type="text" class="form-control"
                                    placeholder="@lang('Normally your email') address" readonly name="username"
                                    value="{{ $general->mail_config->username ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Password') </label>
                                <div class="input-group">
                                    <input type="password" class="form-control smtp-password" readonly
                                    placeholder="@lang('Normally your email password')" name="password"
                                    value="{{ $general->mail_config->password ?? '' }}" />
                                    <span class="input-group-text  text-white bg--primary tooglePassword">
                                        <i class="las la-eye"></i>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 d-none configForm" id="sendgrid">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('SendGrid API Configuration')</h6>
                        </div>
                        <div class="form-group col-md-12">
                            <label>@lang('App Key') </label>
                            <input type="text" class="form-control" placeholder="@lang('SendGrid App key')"
                                name="appkey" value="{{ $general->mail_config->appkey ?? '' }}" />
                        </div>
                    </div>
                    <div class="row mt-4 d-none configForm" id="mailjet">
                        <div class="col-md-12">
                            <h6 class="mb-2">@lang('Mailjet API Configuration')</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Api Public Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('Mailjet Api Public Key')"
                                    name="public_key" value="{{ $general->mail_config->public_key ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">@lang('Api Secret Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('Mailjet Api Secret Key')"
                                    name="secret_key" value="{{ $general->mail_config->secret_key ?? '' }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- TEST MAIL MODAL --}}
<div id="testMailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Test Mail Setup')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.setting.notification.email.test') }}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Sent to') </label>
                                <input type="text" name="email" class="form-control"
                                    placeholder="@lang('Email Address')">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
<button type="button" data-bs-target="#testMailModal" data-bs-toggle="modal" class="btn btn-sm btn-outline--primary"><i
        class="las la-paper-plane"></i> @lang('Send Test Mail')</button>
@endpush
@push('script')
<script>
    (function ($) {
        "use strict";

        @if (session()->has('notify'))
            let url = window.location;
        if (url.search) {
            var method = '{{ $general->mail_config->name }}';
            location.search = `sender=${method}`;
            if (method == 'smtp') {
                $("select[name=smtp]").val("{{ $general->smtp_id }}");
                setSmtpValue()
            }
        }
        @endif

        var method = '{{ $general->mail_config->name }}';
        emailMethod(method);
        function emailMethod(method) {
            $("select[name=email_method]").val(method);
            $('.configForm').addClass('d-none');
            if (method != 'php') {
                $(`#${method}`).removeClass('d-none');
            }
            if (method == 'smtp') {
                $("select[name=smtp]").val("{{ $general->smtp_id }}");
                setSmtpValue()
            }
        }


        $('select[name=email_method]').on('change', function () {
            var method = $(this).val();
            emailMethod(method);
        });

        @if (@$_GET['sender'])
        var method = "{{ @$_GET['sender'] }}";
        if (method == 'phpmail') {
            method = 'php';
        } else if (method == 'smtp') {
            $("select[name=smtp]").val("{{ $general->smtp_id }}");
            setSmtpValue()
        }
        emailMethod(method);
        @endif





        $('.select2-basic').select2({
            dropdownParent: $('.select2--wrapper')
        });

        $('select[name=smtp]').on('change', function (e) {
            setSmtpValue()
        });

        function setSmtpValue() {
            let smtp = $("select[name=smtp").find('option:selected').data('smtp');
            $("input[name='host']").val(smtp.host);
            $("input[name='port']").val(smtp.port);
            $("select[name='enc']").val(smtp.encryption);
            $("input[name='username']").val(smtp.username);
            $("input[name='password']").val(smtp.password);
        }

        let number=1;
        $(".tooglePassword").on('click',function (e) {
            if(number % 2 ==0){
                $('.smtp-password').attr('type','password');
                $(this).html(`
                    <i class="las la-eye"></i>
                `);
            }
            if(number % 2 !=0){
                $('.smtp-password').attr('type','text');
                $(this).html(`
                    <i class="las la-low-vision"></i>
                `);
            }
            number++;
         })
    })(jQuery);

</script>
@endpush

@push('style')
    <style>
        .tooglePassword{
            cursor: pointer;
        }
    </style>
@endpush
