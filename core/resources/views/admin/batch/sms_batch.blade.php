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
                                    <th>@lang('Batch Number')</th>
                                    <th>@lang('Total SMS')</th>
                                    <th>@lang('Total Successs SMS')</th>
                                    <th>@lang('Total Faild SMS')</th>
                                    <th>@lang('SMS Sender')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batches as $batch)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ __($batches->firstItem() + $loop->index) }}</td>
                                        <td data-label="@lang('Batch Number')">
                                            <strong>{{ __($batch->batch_id) }}</strong>
                                        </td>
                                        <td data-label="@lang('Total SMS')">{{ __(@$batch->total) }}</td>
                                        <td data-label="@lang('Total Successs SMS')">
                                            <span class="badge badge--{{ $batch->total_success <= 0 ? 'danger' : 'success' }}">{{ __(@$batch->total_success) }}</span>
                                        </td>
                                        <td data-label="@lang('Total Faild SMS')">
                                            <span class="badge badge--{{ $batch->total_fail <= 0 ? 'success' : 'danger' }}">{{ __(@$batch->total_fail) }}</span>
                                        </td>
                                        <td data-label="@lang('SMS Sender')">{{ __(ucfirst($batch->sender)) }}</td>
                                        <td data-label="@lang('Status')">
                                            @if ($batch->status)
                                                <span class="badge badge--success">@lang('Completed')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('admin.sms.history') }}?batch_id={{ $batch->id }}" data-contact='@json($batch)'
                                               class="btn btn-sm btn-outline--primary editBtn">
                                                <i class="la la-list"></i> @lang('View All SMS')
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
                @if ($batches->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($batches) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
