@php
$type = $groupType == 1 ? 'email' : 'mobile';
@endphp
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
                                    @if ($groupType == 1)
                                        <th>@lang('Email')</th>
                                    @else
                                        <th>@lang('Mobile')</th>
                                    @endif
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ __($contacts->firstItem() + $loop->index) }}</td>
                                        @if ($groupType == 1)
                                            <td data-label="@lang('Email')"> {{ __(@$contact->contact->email) }}</td>
                                        @else
                                            <td data-label="@lang('Mobile')"> {{ __(@$contact->contact->mobile) }}</td>
                                        @endif
                                        <td data-label="@lang('Action')">
                                            <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to remove this contact from group?')" data-action="{{ route('admin.group.delete.contact', $contact->id) }}">
                                                <i class="la la-trash"></i> @lang('Delete')
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

    <div class="modal fade" id="groupModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add') {{ __(ucfirst($type)) }} @lang('To Group')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-times" aria-hidden="true"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.group.to.contact.save', ['groupId' => $group->id, 'groupType' => $groupType]) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group" id="section-contact">
                            <label for="" class="fw-bold">@lang('Add ') {{ __(ucfirst($type)) }}</label>
                            <select class="form-control form-control-lg" id="contact-list" name="contacts[]" multiple></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"> @lang('Submit') </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Import Contact')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="la la-times" aria-hidden="true"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.group.import.contact', ['groupId' => $group->id, 'groupType' => $groupType]) }}" id="importForm" enctype="multipart/form-data">
                    @csrf
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
                                    @lang('Download the all template file from here')
                                    <a href="{{ asset('assets/admin/file_template/' . $type . '/sample.csv') }}" data-bs-toggle="tooltip" title="@lang('Download csv file')" class="text--primary" download>
                                        <b>@lang('csv,')</b>
                                    </a>
                                    <a href="{{ asset('assets/admin/file_template/' . $type . '/sample.xlsx') }}" data-bs-toggle="tooltip" title="@lang('Download excel file')" class="text--primary" download>
                                        <b>@lang('excel,')</b>
                                    </a>
                                    <a href="{{ asset('assets/admin/file_template/' . $type . '/sample.txt') }}" ata-bs-toggle="tooltip" title="@lang('Download txt file')" class="text--primary" download>
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


    <x-confirmation-modal></x-confirmation-modal>
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-outline--primary addBtn">
        <i class="las la-plus"></i> @lang('Add Contact')
    </button>
    <button type="button" class="btn btn-outline--primary importData">
        <i class="las la-cloud-upload-alt"></i> @lang('Import Contact')
    </button>
@endpush

@push('script')
    <script>
        (function($) {

            let groupModal = $("#groupModal");

            $('.addBtn').on('click', function(e) {
                groupModal.modal('show');
            });

            $(".importData").on('click', function(e) {
                $('#importModal').modal('show')
            });

            $("#importForm").on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData($(this)[0])
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    url: $(this).attr('action'),
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(e) {
                        $("#importModal").find(".modal-header").addClass('animate-border');
                    },
                    complete: function(e) {
                        $("#importModal").find(".modal-header").removeClass('animate-border');
                        $("#importModal").modal('hide');
                        setInterval(() => {
                            location.reload();
                        }, 2000);
                    },
                    success: function(response) {
                        if (response.success) {
                            notify('success', response.message);
                        } else {
                            notify('error', response.errors && response.errors.length > 0 ? response.errors : response.message || "@lang('Somehting went to wrong')")
                        }
                    },
                    error: function(e) {
                        notify('error', "@lang('Somehting went to wrong')")
                    }
                });
            });

            let action = "{{ route('admin.contact.search', ':type') }}";


            $('#contact-list').select2({
                ajax: {
                    url: action.replace(":type", "{{ $type }}"),
                    type: "get",
                    dataType: 'json',
                    delay: 1000,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page,
                            rows: 5,
                            forSelect2: true,
                            group_id: "{{ $group->id }}"
                        };
                    },
                    processResults: function(response, params) {
                        console.log(response);
                        params.page = params.page || 1;
                        return {
                            results: response,
                            pagination: {
                                more: params.page < response.length
                            }
                        };
                    },
                    cache: false
                },

            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .select2-container--default .select2-selection--multiple {
            border-color: #ddd;
            min-height: calc(1.8rem + 1rem + 2px) !important;
            height: auto;
        }

        .select2-container {
            z-index: 9999;
        }

    </style>
@endpush
