@php
$emailShow = $contactType == 'all' || $contactType == 'email' ? true : false;
$mobileShow = $contactType == 'all' || $contactType == 'mobile' ? true : false;
@endphp

@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">

                    {{-- Filter Form --}}
                    <form action="">
                        <div class="align-items-center justify-content-end d-flex flex-wrap gap-2">
                            <div class="flex-fill">
                                <label>@lang('Name')</label>
                                <input type="name" autocomplete="off" name="name" value="{{ request()->name ?? null }}" placeholder="@lang('Search with name')" value="" class="form-control">
                            </div>
                            @if ($emailShow)
                                <div class="flex-fill">
                                    <label>@lang('Email')</label>
                                    <input type="text" autocomplete="off" name="email" value="{{ request()->email ?? null }}" placeholder="@lang('Search with email')" value="" class="form-control">
                                </div>
                            @endif

                            @if ($mobileShow)
                                <div class="flex-fill">
                                    <label>@lang('Mobile')</label>
                                    <input type="text" autocomplete="off" name="mobile" value="{{ request()->mobile ?? null }}"
                                           placeholder="@lang('Search with mobile')" value="" class="form-control">
                                </div>
                            @endif

                            <div class="flex-fill">
                                <label>@lang('Status')</label>
                                <select class="form-control" name="status">
                                    <option value="" selected disabled>@lang('Select One')</option>
                                    <option value="1">@lang('Active')</option>
                                    <option value="0">@lang('Inactive')</option>
                                </select>
                            </div>

                            <div class="flex-fill">
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
                                    <th>@lang('Name')</th>

                                    @if ($emailShow)
                                        <th>@lang('Email')</th>
                                    @endif

                                    @if ($mobileShow)
                                        <th>@lang('Mobile')</th>
                                    @endif

                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ __($contacts->firstItem() + $loop->index) }}</td>
                                        <td data-label="@lang('Name')"> {{ __(@$contact->name) }}</td>

                                        @if ($emailShow)
                                            <td data-label="@lang('Email')">{{ __(@$contact->email) }}</td>
                                        @endif

                                        @if ($mobileShow)
                                            <td data-label="@lang('Mobile')">{{ __(@$contact->mobile) }}</td>
                                        @endif

                                        <td data-label="@lang('Status')">
                                            @if ($contact->status)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <button type="button" data-contact='@json($contact)'
                                                    class="btn btn-sm btn-outline--primary editBtn">
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
                @if ($contacts->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($contacts) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="contactModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add New Contact')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-times" aria-hidden="true"></i>
                    </button>
                </div>
                <form method="post" id="form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="fw-bold">@lang('Name')</label>
                            <input type="hidden" name="contact_type" value="{{ $contactType }}">
                            <input type="text" class="form-control form-control-lg" name="name" value="{{ old('name') }}"
                                   placeholder="@lang('Name')">
                        </div>

                        @if ($emailShow)
                            <div class="form-group">
                                <label class="fw-bold required">@lang('Email')</label>
                                <input type="email" class="form-control form-control-lg" name="email" value="{{ old('email') }}"
                                       placeholder="@lang('Email')">
                            </div>
                        @endif

                        @if ($mobileShow)
                            <div class="form-group">
                                <label class="fw-bold required">@lang('Mobile')</label>
                                <input type="tel" class="form-control form-control-lg" name="mobile" value="{{ old('mobile') }}"
                                       placeholder="@lang('Mobile')">
                            </div>
                        @endif

                        <div class="form-group d-none">
                            <label class="fw-bold required">@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-height="40px" data-onstyle="-success"
                                   data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')"
                                   data-off="@lang('Inactive')" name="status" id="bootstrap--toggle-switch">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45" id="btn-save">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- IMPORT MODAL --}}
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Import Contact')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-times" aria-hidden="true"></i>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.contact.import') }}" id="importForm"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="contact_type" value="{{ $contactType }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="alert alert-warning p-3" role="alert">
                                <p>
                                    @lang('The file you wish to upload has to be formatted as we provided template files.Any changes to these files will be considered as an invalid file format. Download links are provided below.')
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold required">@lang('Select File')</label>
                            <input type="file" class="form-control" name="file" accept=".txt,.csv,.xlsx">
                            <div class="mt-1">
                                <small class="d-block">
                                    @lang('Supported files:') <b class="fw-bold">@lang('csv'), @lang('excel'), @lang('txt')</b>
                                </small>
                                <small>
                                    @lang('Download all of the template files from here')
                                    <a href="{{ asset('assets/admin/file_template/' . $contactType . '/sample.csv') }}"
                                       title="@lang('Download csv file')" class="text--primary" download>
                                        <b>@lang('csv,')</b>
                                    </a>
                                    <a href="{{ asset('assets/admin/file_template/' . $contactType . '/sample.xlsx') }}"
                                       title="@lang('Download excel file')" class="text--primary" download>
                                        <b>@lang('excel,')</b>
                                    </a>
                                    <a href="{{ asset('assets/admin/file_template/' . $contactType . '/sample.txt') }}"
                                       title="@lang('Download txt file')" class="text--primary" download>
                                        <b>@lang('txt')</b>
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="Submit" class="btn btn--primary w-100 h-45">@lang('Upload')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Export MODAL --}}
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Export Filter')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-close" aria-hidden="true"></i>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.contact.export') }}" id="importForm"
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="contact_type" value="{{ $contactType }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="fw-bold">@lang('Export Column')</label>
                            <div class="row">
                                @php
                                    if ($contactType == 'email') {
                                        unset($columns[array_search('mobile', $columns)]);
                                    } elseif ($contactType == 'mobile') {
                                        unset($columns[array_search('email', $columns)]);
                                    }
                                @endphp
                                @foreach ($columns as $column)
                                    <div class="{{ $loop->last && $loop->odd ? 'col-lg-12' : 'col-lg-6' }} mb-3">
                                        <label>{{ __(keyToTitle($column)) }} @if ($contactType == $column)
                                                <small class="recommended text--primary">@lang('Recommended')</small>
                                            @endif
                                        </label>
                                        <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50"
                                               data-on="@lang('Yes')" data-off="@lang('No')" name="columns[]"
                                               value="{{ $column }}" {{ $column == 'created_at' || $column == 'updated_at' || $column == 'id' ? 'unchecked' : 'checked' }}>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="fw-bold">@lang('Order By')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                   data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('ASC')"
                                   data-off="@lang('DESC')" name="order_by" @if ($general->sn) checked @endif>
                        </div>

                        <div class="form-group">
                            <label class="fw-bold">@lang('Export Item')</label>
                            <select class="form-control form-control-lg" name="export_item">
                                <option value="10">@lang('10')</option>
                                <option value="50">@lang('50')</option>
                                <option value="100">@lang('100')</option>
                                @if ($contacts->total() > 100)
                                    <option value="{{ $contacts->total() }}">{{ __($contacts->total()) }} @lang('Contacts')
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="Submit" class="btn btn--primary w-100 h-45 contactExport">@lang('Export')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="row justify-content-end">
        <div class="col-lg-4 col-xl-3 col-xxl-2 ps-2 mb-1 p-0">
            <button type="button" class="btn btn-outline--primary w-100 addBtn h-100">
                <i class="las la-plus"></i> @lang('New')
            </button>
        </div>
        <div class="col-lg-4 col-xl-3 col-xxl-2 ps-2 mb-1 p-0">
            <button type="button" class="btn btn-outline--info importBtn w-100 h-100">
                <i class="las la-cloud-upload-alt"></i> @lang('Import')
            </button>
        </div>
        <div class="col-lg-4 col-xl-3 col-xxl-2 ps-2 mb-1 p-0">
            <button type="button" class="btn btn-outline--warning w-100 h-100 exportBtn">
                <i class="las la-cloud-download-alt"></i> @lang('Export')
            </button>
        </div>
    </div>
@endpush
@push('script')
    <script>
        (function($) {

            let contactModal = $("#contactModal");

            $('.addBtn').on('click', function(e) {
                let action = "{{ route('admin.contact.store') }}";
                contactModal.find(".modal-title").text("@lang('Add Contact')");
                contactModal.find('form').trigger('reset');
                $('#status').closest('.form-group').addClass('d-none');
                contactModal.find('form').attr('action', action);
                contactModal.modal('show');
            });

            $('.editBtn').on('click', function(e) {
                let action = "{{ route('admin.contact.update', ':id') }}";
                let contact = $(this).data('contact');
                setFormValue(contact, 'form')
                $('#bootstrap--toggle-switch').closest('.form-group').removeClass('d-none');
                if (contact.status) {
                    $('#bootstrap--toggle-switch').bootstrapToggle('on');
                } else {
                    $('#bootstrap--toggle-switch').bootstrapToggle('off');
                }
                contactModal.find(".modal-title").text("@lang('Edit Contact')");
                contactModal.find('form').attr('action', action.replace(':id', contact.id));
                contactModal.modal('show');
            });

            $(".importBtn").on('click', function(e) {
                let importModal = $("#importModal");
                importModal.modal('show');
            });

            $('#importForm').on('submit', function(event) {
                event.preventDefault();
                let formData = new FormData($(this)[0]);
                let time = 0;
                $.ajax({
                    url: $(this).attr('action'),
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#importModal').find('.modal-header').addClass('animate-border');
                    },
                    complete: function(e) {
                        $('#importModal').find('.modal-header').removeClass('animate-border');
                    },
                    success: function(response) {
                        if (response.success) {
                            notify('success', response.message);
                            $("#importModal").modal('hide');
                            setTimeout(() => {
                                location.reload()
                            }, 2000);
                        } else {
                            notify('error', response.errors && response.errors.length > 0 ? response.errors : response.message || "@lang('Something went the wrong')");
                        }
                    },

                });
            });

            $(".exportBtn").on('click', function(e) {
                let modal = $("#exportModal");
                modal.modal('show')
            });

            $("#exportModal form").on('submit', function(e) {
                $("#exportModal").modal('hide')
            });
            $("select[name=status]").val("{{ request()->status ?? '' }}");

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .recommended {
            font-size: 10px;
        }

    </style>
@endpush
