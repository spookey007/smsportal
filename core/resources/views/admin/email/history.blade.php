@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="">
                        <div class="row align-items-center">
                            <div class="col-lg-3">
                                <label>@lang('Email')</label>
                                <input type="email" autocomplete="off" name="email" value="{{ request()->email ?? null }}" placeholder="@lang('Search with email')" value="" class="form-control">
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected>@lang('All')</option>
                                    <option value="1">@lang('Completed')</option>
                                    <option value="2">@lang('Schedule')</option>
                                    <option value="9">@lang('Fail')</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('Date')</label>
                                <input name="date" value="{{ request()->date ?? null }}" class="form-control search-date" autocomplete="off" placeholder="@lang('Start Date-End Date')" type="text">
                            </div>
                            <div class="col-lg-2">
                                <label>@lang('Sendder')</label>
                                <select name="sender" class="form-control">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    <option value="php">@lang('PHP Mail')</option>
                                    <option value="smtp">@lang('SMTP')</option>
                                    <option value="sendgrid">@lang('SendGrid API')</option>
                                    <option value="mailjet">@lang('Mailjet API')</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn--primary w-100 h-45 mt-4" type="submit">
                                    <i class="fas fa-filter"></i>@lang('Filter')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Sender')</th>
                                    <th>@lang('To')</th>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Sent')</th>
                                    <th>@lang('Initiated')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ __($logs->firstItem() + $loop->index) }} </td>
                                        <td data-label="@lang('Sender')">{{ __($log->sender) }}</td>
                                        <td data-label="@lang('To')"> {{ __($log->email) }}</td>
                                        <td data-label="@lang('Subject')"> {{ __(strLimit($log->subject, 25)) }}</td>
                                        <td data-label="@lang('Sent')">
                                            @if (@$log->sent_time)
                                                {{ __(@$log->sent_time) }} <br>
                                                <small>{{ __(diffForHumans($log->sent_time)) }}</small>
                                            @endif
                                        </td>
                                        <td data-label="@lang('initiated')">
                                            {{ @$log->created_at }} <br> <small>{{ diffForHumans($log->created_at) }}</small>
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if ($log->status == 1)
                                                <span class="badge badge--success">@lang('Completed')</span>
                                            @elseif($log->status == 9)
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="badge badge--warning">@lang('Fail')</span>
                                                    <span class="text--primary ms-1 fail-reason"
                                                          fail-reason="{{ $log->fail_reason }}">
                                                        <i class="las la-question-circle"></i>
                                                    </span>
                                                </div>
                                            @else
                                                <span class="badge badge--primary" data-bs-toggle="tooltip" title="{{ 'This email  be sent at ' . showDateTime($log->schedule) }}">
                                                    @lang('Scheduled')
                                                </span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('admin.email.view', $log->id) }}" class="btn btn-sm btn-outline--primary editBtn">
                                                <i class="la la-eye"></i> @lang('View')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($logs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($logs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="messageModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add SMTP')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-times" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.email.send') }}" class="btn btn-outline--primary">
        <i class="las la-paper-plane"></i> @lang('Send Email')
    </a>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush


@push('script')
    <script>
        (function($) {

            $('.fail-reason').on('click', function(e) {
                let reason = $(this).attr('fail-reason');
                $("#messageModal").find('.modal-title').text("Fails Reason");
                $("#messageModal").find('.message').html(reason);
                $("#messageModal").modal('show');

            });

            $('.search-date').datepicker({
                language: 'en',
                dateFormat: 'yyyy-mm-dd',
                range: true
            });

            $('#status').val("{{ @request()->status }}");

            @if (request()->sender)
                $('select[name=sender]').val("{{ request()->sender }}")
            @endif


        })(jQuery);
    </script>
@endpush





@push('style')
    <style>
        span.text--primary.ms-1.fail-reason {
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
        }

    </style>
@endpush
