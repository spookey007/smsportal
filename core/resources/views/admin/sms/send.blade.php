@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card b-radius--10">
                <div class="card-body" id="card-body">
                    <ul class="steps-wrapper d-flex justify-content-center mb-3 mt-3 flex-wrap">
                        <li class="step-item">
                            <span class="btn btn--primary custom--primary-btn" id="collect-mobile-btn">@lang('Collect Mobile Number')</span>
                        </li>
                        <li class="step-item">
                            <span class="btn btn--secondary" id="write-message-btn">@lang('Write Message')</span>
                        </li>
                    </ul>
                    <div id="wizard">
                        <section id="section-collect-mobile">
                            <form method="POST" id="mobileNumberMerge" action="{{ route('admin.mobile.number.merge') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="contact-list" class="fw-bold">@lang('Mobile Number From Contact')</label>
                                    <select id="contact-list" name="contact_id[]" class="form-control" multiple> </select>
                                </div>
                                <div class="form-group __select2-wrapper" id="group-list">
                                    <label class="fw-bold">@lang('Mobile Number From Group')</label>
                                    <select class="form-control select2-multi-select" name="group_id[]" id="group-list" multiple>
                                        <option value="" disabled>@lang('Select One')</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="" class="fw-bold">@lang('Upload File')</label> <strong class="uploadInFo"><i class="fas fa-info-circle text--primary"></i></strong>
                                    <input type="file" class="form-control form-control-lg uploadFile" id="uploadFile" accept=".txt,.csv,.xlsx" name="file">
                                    <small class="file-size float-end"></small>
                                    <div class="mt-2">
                                        <small class="d-block">
                                            @lang('Supported files:') <b class="fw-bold">@lang('csv'), @lang('excel'),
                                                @lang('txt')</b>
                                        </small>
                                        <small>
                                            @lang('Download all of the template files from here')
                                            <a href="{{ asset('assets/admin/file_template/mobile/sample.csv') }}"
                                               title="@lang('Download csv file')" class="text--primary" download>
                                                <b>@lang('csv,')</b>
                                            </a>
                                            <a href="{{ asset('assets/admin/file_template/mobile/sample.xlsx') }}"
                                               title="@lang('Download excel file')" class="text--primary" download>
                                                <b>@lang('excel,')</b>
                                            </a>
                                            <a href="{{ asset('assets/admin/file_template/mobile/sample.txt') }}"
                                               title="@lang('Download txt file')" class="text--primary" download>
                                                <b>@lang('txt')</b>
                                            </a>
                                        </small>
                                    </div>
                                </div>
                            </form>
                        </section>
                        <section id="section-send-message" class="none">
                            <form method="POST" id="sendMessage" action="{{ route('admin.email.send') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="fw-bold required">@lang('Will Be Sent')</label>
                                    <select name="will_be_sent" class="form-control"">
                                                                        <option value=" 1">@lang('Now')</option>
                                        <option value="2">@lang('Add Schedule')</option>
                                    </select>
                                </div>
                                <div id="schedule" class="none form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="fw-bold required" for="date">@lang('Shedule')</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control date" id="date" name="date" autocomplete="off">
                                                <span class="input-group-text">
                                                    <i class="las la-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" id="message">
                                    <label class="fw-bold required">@lang('Message')</label>
                                    <textarea class="form-control form-control-lg" required name="message" cols="30" rows="8"></textarea>
                                    <p class="message-count"></p>
                                </div>
                            </form>
                        </section>
                    </div>
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <button type="submit" class="btn btn--primary nextBtn w-100 h-45" form="mobileNumberMerge">@lang('Next')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-bs-backdrop="static" id="progress-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Send SMS')</h5>
                </div>
                <div class="modal-body">
                    <h4 class="text--danger text-center">@lang('Don\'t close or refresh the window till finish')</h4>
                    <div class="mail-wrapper">
                        <img src="{{ getImage('assets/admin/images/sending2.gif') }}" alt="">
                    </div>
                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar" style="width: 0%">0%</div>
                        </div>
                        <p>
                            <span class="message-sent text--success">0</span> @lang('SMS')
                            <span class="email-status"></span> <span>@lang('of total')</span>
                            <span class="total-email text--primary"></span> @lang('SMS')
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="info-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('File Upload Information')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-times" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="alert alert-warning p-3" role="alert">
                            <p>
                                @lang('The file you wish to upload has to be formatted as we provided template files. Any changes to these files will be considered as an invalid file format. Download links are provided below.')
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <small>
                            <p>
                                @lang('Downloa template file')
                                <a href="{{ asset('assets/admin/file_template/mobile/sample.csv') }}"
                                   title="@lang('Download csv file')" class="text--primary" download>
                                    <b>@lang('Csv')</b>
                                </a>
                            </p>
                            <p>
                                @lang('Downloa template file')
                                <a href="{{ asset('assets/admin/file_template/mobile/sample.xlsx') }}"
                                   title="@lang('Download excel file')" class="text--primary" download>
                                    <b>@lang('Excel')</b>
                                </a>
                            </p>
                            <p>
                                @lang('Download template file')
                                <a href="{{ asset('assets/admin/file_template/mobile/sample.txt') }}"
                                   title="@lang('Download txt file')" class="text--primary" download>
                                    <b>@lang('Text')</b>
                                </a>
                            </p>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.sms.history') }}" class="btn btn-outline--primary">
        <i class="las la-list"></i> @lang('SMS History')
    </a>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {

            $('#contact-list').select2({
                ajax: {
                    url: "{{ route('admin.contact.search', 'mobile') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 1000,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page,
                            rows: 5,
                            forSelect2: true
                        };
                    },
                    processResults: function(response, params) {
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
                dropdownParent: $("#section-collect-mobile")
            });

            $('.select2-multi-select').select2({
                dropdownParent: $(".__select2-wrapper")
            });

            $(".csvFile").on('change', function(e) {
                let file = e.target.files[0];
                let fileExtention = file.name.split('.').pop();
                if (fileExtention != 'csv' && fileExtention != 'xlsx' || fileExtention != 'txt') {
                    notify('error', "@lang('File type must be csv')");
                    document.querySelector('.csvFile').value = '';
                    return false;
                }
                let size = fileSize(file.size);
                $(".file-size").text(size);
            });

            ///get the file size on js file upload
            function fileSize(bytes) {

                let marker = 1024; // Change to 1000 if required
                let kiloBytes = marker; // One Kilobyte is 1024 bytes
                let megaBytes = marker * marker; // One MB is 1024 KB
                let gigaBytes = marker * marker * marker; // One GB is 1024 MB
                let teraBytes = marker * marker * marker * marker; // One TB is 1024 GB

                if (bytes < kiloBytes) return bytes + " Bytes";
                else if (bytes < megaBytes) return (bytes / kiloBytes).toFixed(2) + " KB";
                else if (bytes < gigaBytes) return (bytes / megaBytes).toFixed(2) + " MB";
                else return (bytes / gigaBytes).toFixed(2) + " GB";
            }

            let mobileNumbers = [];
            let totalMessageSend = 0;

            $('#mobileNumberMerge').on('submit', function(event) {
                event.preventDefault();
                const CSRF_TOKEN = "csrf_token()";
                let formData = new FormData($(this)[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    url: $(this).attr('action'),
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#mobileNumberMerge").closest('.card-body').addClass('animate-border');
                    },
                    complete: function(e) {
                        $("#mobileNumberMerge").closest('.card-body').removeClass('animate-border');
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#section-send-message").removeClass('none');
                            $("#section-collect-mobile").addClass('none');
                            mobileNumbers = response.mobileNumbers;
                            $('#progress-modal').find(".total-email").text(mobileNumbers.length);
                            $('.nextBtn').attr('form', "sendMessage");
                            $('.nextBtn').text("Send");
                            $("#write-message-btn").addClass('custom--primary-btn')


                        } else {
                            $("#section-send-message").fadeOut();
                            $("#section-collect-mobile").removeClass('none');
                            notify('error', response.errors && response.errors.length > 0 ? response.errors : response.message);
                        }
                    },
                    error: function(e) {
                        notify('error', "@lang('Something went to wrong')")
                    }
                });
            });


            const BATCHID = "{{ batchId('2') }}";

            $('#sendMessage').on('submit', function(event) {
                event.preventDefault();
                let formData = new FormData($(this)[0]);
                let message = $('#message').find('.nicEdit-main').html();
                formData.append('message', message);
                formData.append('batch_id', BATCHID);
                formData.append('mobile', mobileNumbers[totalMessageSend])
                sendMessage(formData);

            });

            function sendMessage(formData) {
                if ($(`select[name=will_be_sent]`).val() == 1) {
                    $('.email-status').text('sent')
                } else {
                    $('.email-status').text('schedule')
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    action: "{{ route('admin.sms.send') }}",
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#card-body").addClass('animate-border');
                    },
                    complete: function(e) {
                        $("#card-body").removeClass('animate-border');
                    },
                    success: function(response) {
                        if (!response.success) {
                            notify('error', response.errors && response.errors.length > 0 ? response.errors : response.message)
                            return false;
                        }
                        totalMessageSend++;
                        if (totalMessageSend < mobileNumbers.length) {
                            setTimeout(() => {
                                formData.append('mobile', mobileNumbers[totalMessageSend])
                                sendMessage(formData)
                            }, 1000);
                        }
                        let percent = parseFloat((totalMessageSend / mobileNumbers.length) * 100).toFixed(2);

                        $('#progress-modal').find(".progress-bar").css(`width`, `${percent}%`)
                        $('#progress-modal').find(".progress-bar").text(`${percent}%`)
                        $('#progress-modal').find(".message-sent").text(totalMessageSend);
                        $("#progress-modal").modal('show')
                        if (totalMessageSend == mobileNumbers.length) {
                            notify('success', `Mobile ${$('.email-status').text()} successfully`);
                            setTimeout(() => {
                                "{{ session()->forget('MOBILE_NUMBER_FOR_SEND') }}";
                                $("#progress-modal").modal('hide');
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(e) {
                        notify('error', "@lang('Something went to wrong')")
                    }
                });

            };

            $("select[name=will_be_sent]").on('change', function(e) {
                let value = $(this).val();
                if (value == 2) {
                    $("#schedule").fadeIn();
                } else {
                    $("#schedule").fadeOut();
                }
            });

            $('#collect-mobile-btn').on('click', function(e) {
                mobileNumberCollect()
            });

            function mobileNumberCollect() {
                $("#section-send-message").addClass('none');
                $("#section-collect-mobile").removeClass('none');
                $('.nextBtn').attr('form', "mobileNumberMerge");
                $('.nextBtn').text("Next");
                $("#write-message-btn").removeClass('custom--primary-btn')
            };
            $('#write-message-btn').on('click', function(e) {
                if (emails.length > 0) {
                    $("#section-send-message").removeClass('none');
                    $("#section-collect-mobile").addClass('none');
                    $('.nextBtn').attr('form', "sendMessage");
                    $('.nextBtn').text("Submit");
                    $("#write-message-btn").addClass('custom--primary-btn')
                }
            });

            $(`textarea[name=message]`).on('keyup', function(e) {
                let string = $(this).val();
                let word = string.split(" ");
                if (string.length > 0) {
                    $(".message-count").html(`
                <span class="text--success">${string.length}</span> Character  <span class="text--success">${word.length}</span> Words
                `)
                } else {
                    $(".message-count").empty()
                }
            });

            $(".uploadInFo").on('click', function(e) {
                $("#info-modal").modal('show')
            })
            let date = new Date(new Date().toLocaleString("en-US", {
                timeZone: "{{ date_default_timezone_get() }}"
            }));

            $('#date').datepicker({
                language: 'en',
                dateFormat: 'yyyy-mm-dd',
                minDate: date,
                timepicker: true,
            });

        })(jQuery);
    </script>
@endpush
@push('style')
    <style>
        .none {
            display: none;
        }

        .select2-container--default .select2-selection--multiple {
            border-color: #ddd;
            height: calc(1.8rem + 1rem + 2px) !important;
        }

        .steps-wrapper {
            gap: 15px 55px;
            position: relative;
            z-index: 1;

        }

        .steps-wrapper::before {
            position: absolute;
            content: '';
            width: 100px;
            left: 50%;
            transform: translateX(-50%);
            top: 49%;
            border: 1px dashed #4634ff;
            z-index: -01;
            opacity: .7;
        }

        .custom--primary-btn {
            background: #6759fc !important;
            cursor: auto;
        }

        .nicEdit-main {
            min-height: 200px !important;
        }

    </style>
@endpush
