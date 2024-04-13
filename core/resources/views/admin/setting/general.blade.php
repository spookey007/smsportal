@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Site Title')</label>
                                    <input class="form-control" type="text" name="site_name" required value="{{$general->site_name}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6" id="selec2-wrapper">
                                <label> @lang('Timezone')</label>
                                <select class="select2-basic" name="timezone" required>
                                    @foreach($timezones as $timezone)
                                    <option value="'{{ @$timezone}}'">{{ __($timezone) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Email Batch Prefix')</label>
                                    <input class="form-control" type="text" name="email_batch_prefix" required value="{{$general->email_batch_prefix}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('SMS Batch Prefix')</label>
                                    <input class="form-control" type="text" name="sms_batch_prefix" required value="{{$general->sms_batch_prefix}}">
                                </div>
                            </div>
                            <div class="form-group   col-sm-6">
                                <label>@lang('Email Notification')</label>
                                <input type="checkbox"  data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disable')" name="en" @if($general->en) checked @endif>
                            </div>

                            <div class="form-group  col-sm-6">
                                <label>@lang('SMS Notification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disable')" name="sn" @if($general->sn) checked @endif>
                            </div>

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function (color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function () {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

            $('select[name=timezone]').val("'{{ config('app.timezone') }}'").select2();
            $('.select2-basic').select2({
                dropdownParent:$('.card-body')
            });
        })(jQuery);

    </script>
@endpush

@push('style')
    <style>
         .select2-container--default .select2-selection--single {
            border-color: #ddd;
            height: 45px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            line-height: 45px;
        }
    </style>
@endpush

