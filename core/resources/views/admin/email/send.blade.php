@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card b-radius--10">
                <div class="card-body" id="card-body">
                    <ul class="steps-wrapper d-flex justify-content-center mb-3 mt-3 flex-wrap">
                        <li class="step-item">
                            <span class="btn btn--primary custom--primary-btn" id="collect-email-btn">@lang('Collect Email')</span>
                        </li>
                        <li class="step-item">
                            <span class="btn btn--secondary" id="write-message-btn">@lang('Write Message')</span>
                        </li>
                    </ul>

                    <div id="wizard">
                        <section id="section-collect-email">
                            <form method="POST" id="emailMerge" action="{{ route('admin.email.merge') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="contact-list" class="fw-bold">@lang('Email From Contact')</label>
                                    <select id="contact-list" name="contact_id[]" class="form-control" multiple> </select>
                                </div>
                                <div class="form-group __select2-wrapper" id="group-list">
                                    <label class="fw-bold">@lang('Email From Group')</label>
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
                                            <a href="{{ asset('assets/admin/file_template/email/sample.csv') }}"
                                               title="@lang('Download csv file')" class="text--primary" download>
                                                <b>@lang('csv,')</b>
                                            </a>
                                            <a href="{{ asset('assets/admin/file_template/email/sample.xlsx') }}"
                                               title="@lang('Download excel file')" class="text--primary" download>
                                                <b>@lang('excel,')</b>
                                            </a>
                                            <a href="{{ asset('assets/admin/file_template/email/sample.txt') }}"
                                               title="@lang('Download txt file')" class="text--primary" download>
                                                <b>@lang('txt')</b>
                                            </a>
                                        </small>
                                    </div>
                                </div>
                            </form>
                        </section>

                        <section id="section-send-email" class="none">
                            <form method="POST" id="sendEmail" action="{{ route('admin.email.send') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="fw-bold required">@lang('Will Be Sent')</label>
                                    <select name="will_be_sent" class="form-control">
                                        <option value="1">@lang('Now')</option>
                                        <option value="2">@lang('Add Schedule')</option>
                                    </select>
                                </div>
                                <div id="schedule" class="none form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="fw-bold required" for="date">@lang('Schedule')</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control date" id="date" name="date" autocomplete="off">
                                                <span class="input-group-text">
                                                    <i class="las la-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="fw-bold required">@lang('Subject')</label>
                                    <input type="text" class="form-control form-control-lg" name="subject">
                                </div>
                                <div class="form-group" id="message">
                                    <label class="fw-bold required">@lang('Message')</label>
                                    <textarea name="" class="form-control form-control-lg nicEdit" name="message" cols="30" rows="10"></textarea>
                                </div>
                            </form>
                        </section>
                    </div>


                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <button type="submit" class="btn btn--primary nextBtn w-100 h-45" form="emailMerge">@lang('Next')</button>
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
                        <i class="la la-close" aria-hidden="true"></i>
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
                                <a href="{{ asset('assets/admin/file_template/email/sample.csv') }}"
                                   title="@lang('Download csv file')" class="text--primary" download>
                                    <b>@lang('Csv')</b>
                                </a>
                            </p>
                            <p>
                                @lang('Downloa template file')
                                <a href="{{ asset('assets/admin/file_template/email/sample.xlsx') }}"
                                   title="@lang('Download excel file')" class="text--primary" download>
                                    <b>@lang('Excel')</b>
                                </a>
                            </p>
                            <p>
                                @lang('Download template file')
                                <a href="{{ asset('assets/admin/file_template/email/sample.txt') }}"
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

    <div class="modal fade" data-bs-backdrop="static" id="progress-modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Send Email')</h5>
                </div>
                <div class="modal-body">
                    <h4 class="text--danger text-center">@lang('Don\'t close or refresh the window till finished')</h4>
                    <div class="mail-wrapper">
                        <img src="{{ getImage('assets/admin/images/sending.gif') }}" alt="">
                    </div>
                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar" style="width: 0%">0%</div>
                        </div>
                        <p>
                            <span class="email-sent text--success">0</span> @lang('email')
                            <span class="email-status"></span> <span>@lang('of total')</span>
                            <span class="total-email text--primary"></span> @lang('Emails')
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.email.history') }}" class="btn btn-outline--primary">
        <i class="las la-list"></i> @lang('Email History')
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
                    url: "{{ route('admin.contact.search', 'email') }}",
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
                dropdownParent: $("#section-collect-email")
            });

            $('.select2-multi-select').select2({
                dropdownParent: $(".__select2-wrapper")
            });

            $(".uploadFile").on('change', function(e) {
                let file = e.target.files[0];
                let fileExtention = file.name.split('.').pop();
                if (fileExtention != 'csv' && fileExtention != 'xlsx' && fileExtention != 'txt') {
                    notify('error', "@lang('File type not suported')");
                    document.querySelector('.uploadFile').value = '';
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

            let emails = [];
            let totalSendEmail = 0;

            $('#emailMerge').on('submit', function(event) {
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
                        $("#emailMerge").closest('.card-body').addClass('animate-border');
                    },
                    complete: function(e) {
                        $("#emailMerge").closest('.card-body').removeClass('animate-border');
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#section-send-email").removeClass('none');
                            $("#section-collect-email").addClass('none');
                            emails = response.emails;
                            $('#progress-modal').find(".total-email").text(emails.length);
                            $('.nextBtn').attr('form', "sendEmail");
                            $('.nextBtn').text("Send");
                            $("#write-message-btn").addClass('custom--primary-btn')
                            nicEditor();
                        } else {
                            $("#section-send-email").fadeOut();
                            $("#section-collect-email").removeClass('none');
                            notify('error', response.errors && response.errors.length > 0 ? response.errors : response.message);
                        }
                    },
                    error: function(e) {
                        notify("error", "@lang('')")
                    }
                });
            });



            const BATCHID = "{{ batchId() }}"

            $('#sendEmail').on('submit', function(event) {
                event.preventDefault();
                let formData = new FormData($(this)[0]);
                let message = $('#message').find('.nicEdit-main').html();
                formData.append('message', message);
                formData.append('batch_id', BATCHID);
                formData.append('email', emails[totalSendEmail])
                console.log(formData);
                sendEmail(formData);
            });

            function sendEmail(formData) {

                if ($(`select[name=will_be_sent]`).val() == 1) {
                    $('.email-status').text('sent')
                } else {
                    $('.email-status').text('schedule')
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    action: "{{ route('admin.email.send') }}",
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
                        totalSendEmail++;
                        if (totalSendEmail < emails.length) {
                            setTimeout(() => {
                                formData.append('email',emails[totalSendEmail])
                                sendEmail(formData)
                            }, 1000);
                        }
                        let percent = parseFloat((totalSendEmail / emails.length) * 100).toFixed(2);

                        $('#progress-modal').find(".progress-bar").css(`width`, `${percent}%`)
                        $('#progress-modal').find(".progress-bar").text(`${percent}%`)
                        $('#progress-modal').find(".email-sent").text(totalSendEmail);
                        $("#progress-modal").modal('show');
                        if (totalSendEmail == emails.length) {
                            notify('success', `Email ${$('.email-status').text()} successfully`);
                            setTimeout(() => {
                                "{{ session()->forget('EMAIL_FOR_SEND') }}";
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
            let date = new Date(new Date().toLocaleString("en-US", {
                timeZone: "{{ date_default_timezone_get() }}"
            }));

            function nicEditor() {
                let editor = $("#section-send-email").find(".nicEdit");
                let firstDiv = $("#message").find('div')[0];
                let nicEditorMain = $(editor).closest('#section-send-email').find('.nicEdit-main')[0];

                $(firstDiv).css({
                    width: "100%"
                });

                $(nicEditorMain).css({
                    width: "100%"
                });

                $(firstDiv).next().css({
                    width: "100%"
                });
            }

            $('#collect-email-btn').on('click', function(e) {
                emailCollectStep()
            });

            function emailCollectStep() {
                $("#section-send-email").addClass('none');
                $("#section-collect-email").removeClass('none');
                $('.nextBtn').attr('form', "emailMerge");
                $('.nextBtn').text("Next");
                $("#write-message-btn").removeClass('custom--primary-btn');
            };


            $('#write-message-btn').on('click', function(e) {
                if (emails.length > 0) {
                    $("#section-send-email").removeClass('none');
                    $("#section-collect-email").addClass('none');
                    $('.nextBtn').attr('form', "sendEmail");
                    $('.nextBtn').text("Submit");
                    $("#write-message-btn").addClass('custom--primary-btn')
                }
            });

            $(".uploadInFo").on('click', function(e) {
                $("#info-modal").modal('show')
            })

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
            min-height: calc(1.8rem + 1rem + 2px) !important;
            height: auto;
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

        .uploadInFo {
            cursor: pointer;
        }

    </style>
@endpush
