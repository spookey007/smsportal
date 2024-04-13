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
                                    <th>@lang('Name')</th>
                                    <th>@lang('Contacts')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $group)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ __($groups->firstItem() + $loop->index) }}</td>
                                        <td data-label="@lang('Name')"> {{ __(@$group->name) }}</td>
                                        <td data-label="@lang('Contacts')"> {{ __(@$group->contact_count) }}</td>

                                        <td data-label="@lang('Status')">
                                            @if ($group->status)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <button type="button" data-group='@json($group)' class="btn btn-sm btn-outline--primary editBtn">
                                                <i class="la la-pen"></i> @lang('Edit')
                                            </button>

                                            <a class="btn btn-sm btn-outline--success" href="{{ route('admin.group.contact.view', ['id' => $group->id, 'groupType' => $groupType]) }}">
                                                <i class="las la-eye"></i> @lang('View Contact')
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
                @if ($groups->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($groups) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="groupModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add New Contact')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="la la-times" aria-hidden="true"></i></button>
                </div>
                <form method="post">
                    @csrf
                    <input type="hidden" name="type" value="{{ $groupType }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="fw-bold required">@lang('Name')</label>
                            <input required type="text" class="form-control form-control-lg" name="name" value="{{ old('name') }}" placeholder="@lang('Name')">
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
    @if ($groupType)
        <button type="button" class="btn btn-outline--primary addBtn">
            <i class="las la-plus"></i> @lang('New Group')
        </button>
    @endif

@endpush

@push('script')
    <script>
        (function($) {

            let groupModal = $("#groupModal");

            $('.addBtn').on('click', function(e) {
                let action = "{{ route('admin.group.store') }}";
                groupModal.find(".modal-title").text("@lang('Add Group')");
                groupModal.find('form').trigger('reset');
                $('#bootstrap--toggle-switch').closest('.form-group').addClass('d-none');
                groupModal.find('form').attr('action', action);
                groupModal.modal('show');
            });

            $('.editBtn').on('click', function(e) {
                let action = "{{ route('admin.group.update', ':id') }}";
                let group = $(this).data('group');
                groupModal.find('input[name=name]').val(group.name);
                $('#bootstrap--toggle-switch').closest('.form-group').removeClass('d-none');
                bootstrapToggleSwitch(group.status);
                groupModal.find(".modal-title").text("@lang('Edit Group')");
                groupModal.find('form').attr('action', action.replace(':id', group.id));
                groupModal.modal('show');

            });

        })(jQuery);
    </script>
@endpush
