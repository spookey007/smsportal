@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Host')</th>
                                    <th>@lang('Port')</th>
                                    <th>@lang('Encryption')</th>
                                    <th>@lang('Username|Paswword')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allSmtp as $smtp)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ __($allSmtp->firstItem() + $loop->index) }}</td>
                                        <td data-label="@lang('Host')"> {{ __(@$smtp->host) }} <br> {{ __(@$smtp->driver) }}</td>
                                        <td data-label="@lang('Port')"> {{ __(@$smtp->port) }}</td>
                                        <td data-label="@lang('Encryption')"> {{ __(@$smtp->encryption) }}</td>
                                        <td data-label="@lang('Username|Paswword')">
                                            {{ __(@$smtp->username) }}
                                            <br>
                                            <span class="badge badge--success password" mode="hide" password="{{ $smtp->password }}">
                                                {{ __(str_replace(@$smtp->password, str_repeat('X', strlen(@$smtp->password)), @$smtp->password)) }}
                                            </span>

                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if ($smtp->status)
                                                <span class="badge badge--primary">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <button type="button" data-host='@json($smtp)' class="btn btn-sm btn-outline--primary editBtn">
                                                <i class="la la-pen"></i> @lang('Edit')
                                            </button>
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
                @if ($allSmtp->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($allSmtp) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add SMTP')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="la la-times" aria-hidden="true"></i></button>
                </div>
                <form method="post" id="form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="fw-bold required">@lang('Host')</label>
                            <input type="text" class="form-control form-control-lg" name="host" value="{{ old('host') }}" placeholder="@lang('Email Host')">
                        </div>
                        <div class="form-group">
                            <label class="fw-bold required">@lang('Port')</label>
                            <input type="text" class="form-control form-control-lg" name="port" value="{{ old('port') }}" placeholder="@lang('Port')">
                        </div>
                        <div class="form-group">
                            <label class="fw-bold required">@lang('Encryption')</label>
                            <select class="form-control" name="encryption">
                                <option value="ssl" @selected(old('encryption') == 'ssl')>@lang('SSL')</option>
                                <option value="tls" @selected(old('encryption') == 'tls')>@lang('TLS')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold required">@lang('Username')</label>
                            <input type="text" class="form-control form-control-lg" name="username" value="{{ old('username') }}" placeholder="@lang('Username')">
                        </div>
                        <div class="form-group">
                            <label class="fw-bold required">@lang('Password')</label>
                            <input type="text" class="form-control form-control-lg" name="password" value="{{ old('password') }}" placeholder="@lang('Password')">
                        </div>
                        <div class="form-group d-none">
                            <label class="fw-bold required">@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-height="40px" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" id="bootstrap--toggle-switch">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45" id="btn-save">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-outline--primary addBtn">
        <i class="las la-plus"></i> @lang('New SMTP')
    </button>
@endpush

@push('script')
    <script>
        (function($) {

            let modal = $("#modal");

            $('.addBtn').on('click', function(e) {
                let action = "{{ route('admin.smtp.store') }}";
                modal.find(".modal-title").text("@lang('Add SMTP')");
                modal.find('form').trigger('reset');
                $('#bootstrap--toggle-switch').closest('.form-group').addClass('d-none');
                modal.find('form').attr('action', action);
                modal.modal('show');
            });

            $('.editBtn').on('click', function(e) {
                let action = "{{ route('admin.smtp.update', ':id') }}";
                let host = $(this).data('host');
                setFormValue(host, 'form')
                $('#bootstrap--toggle-switch').closest('.form-group').removeClass('d-none');
                if (host.status) {
                    $('#bootstrap--toggle-switch').bootstrapToggle('on');
                } else {
                    $('#bootstrap--toggle-switch').bootstrapToggle('off');
                }
                modal.find(".modal-title").text("@lang('Edit SMTP')");
                modal.find('form').attr('action', action.replace(':id', host.id));
                modal.modal('show');
            });

            $(".password").on('click', function(e) {
                let mode = $(this).attr('mode');
                mode = mode == 'hide' ? 'show' : 'hide';
                let password = $(this).attr('password');
                if (mode == 'show') {
                    $(this).text(password);
                } else {
                    $(this).text("X".repeat(password.length))
                }
                $(this).attr('mode', mode)
            });



        })(jQuery);
    </script>
@endpush
@push('style')
    <style>
        .password {
            cursor: pointer;
        }

    </style>
@endpush
