<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Settings')); ?>

<?php $__env->stopSection(); ?>
<?php
    // $logo = asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $meta_image = \App\Models\Utility::get_file('uploads/meta/');

    $company_logo = Utility::getValByName('company_logo');
    $company_logo_light = Utility::getValByName('company_logo_light');
    $company_favicon = Utility::getValByName('company_favicon');
    // $SITE_RTL = env('SITE_RTL');

    $SITE_RTL = $settings['SITE_RTL'];
    if ($SITE_RTL == '') {
        $SITE_RTL == 'off';
    }

    $lang = \App\Models\Utility::getValByName('default_language');
    $color = isset($settings['theme_color']) ? $settings['theme_color'] : 'theme-3';
    $is_sidebar_transperent = isset($settings['is_sidebar_transperent']) ? $settings['is_sidebar_transperent'] : 'on';
    $dark_mode = isset($settings['dark_mode']) ? $settings['dark_mode'] : '';
    $currantLang = Utility::languages();

    $file_type = config('files_types');
    $setting = App\Models\Utility::settings();

    $local_storage_validation = $setting['local_storage_validation'];
    $local_storage_validations = explode(',', $local_storage_validation);

    $s3_storage_validation = $setting['s3_storage_validation'];
    $s3_storage_validations = explode(',', $s3_storage_validation);

    $wasabi_storage_validation = $setting['wasabi_storage_validation'];
    $wasabi_storage_validations = explode(',', $wasabi_storage_validation);

    $chatgpt = Utility::getValByName('enable_chatgpt');

?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Settings')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/summernote/summernote-bs4.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('css/summernote/summernote-bs4.js')); ?>"></script>
    <script>
        $('.colorPicker').on('click', function(e) {
            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass('custom-color');
            $('.themes-color-change').removeClass('active_color');
            $(this).addClass('active_color');
            const input = document.getElementById("color-picker");
            setColor();
            input.addEventListener("input", setColor);

            function setColor() {
                $(':root').css('--color-customColor', input.value);
            }

            $(`input[name='color_flag`).val('true');
        });

        $('.themes-color-change').on('click', function() {

            $(`input[name='color_flag`).val('false');

            var color_val = $(this).data('value');
            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass(color_val);
            $('.theme-color').prop('checked', false);
            $('.themes-color-change').removeClass('active_color');
            $('.colorPicker').removeClass('active_color');
            $(this).addClass('active_color');
            $(`input[value=${color_val}]`).prop('checked', true);
        });

        $.fn.removeClassRegex = function(regex) {
            return $(this).removeClass(function(index, classes) {
                return classes.split(/\s+/).filter(function(c) {
                    return regex.test(c);
                }).join(' ');
            });
        };
    </script>
    <script>
        $(document).on('change', '.email-template-checkbox', function() {
            var url = $(this).data('url');
            var chbox = $(this);

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: chbox.val()
                },
                success: function(data) {

                },
            });
        });

        function enablecookie() {
            const element = $('#enable_cookie').is(':checked');
            $('.cookieDiv').addClass('disabledCookie');
            if (element == true) {
                $('.cookieDiv').removeClass('disabledCookie');
                $("#cookie_logging").attr('checked', true);
            } else {
                $('.cookieDiv').addClass('disabledCookie');
                $("#cookie_logging").attr('checked', false);
            }
        }


        // $('.themes-color-change').on('click', function() {
        //     var color_val = $(this).data('value');
        //     $('.theme-color').prop('checked', false);
        //     $('.themes-color-change').removeClass('active_color');
        //     $(this).addClass('active_color');
        //     $(`input[value=${color_val}]`).prop('checked', true);

        // });
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
    <script>
        document.getElementById('company_logo').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
    </script>
    <script>
        document.getElementById('company_logo_light').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image1').src = src
        }
    </script>
    <script>
        document.getElementById('company_favicon').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image2').src = src
        }
    </script>
    <script>
        $(document).on("click", '.send_email', function(e) {
            e.preventDefault();
            var title = $(this).attr('data-title');

            var size = 'md';
            var url = $(this).attr('data-url');
            if (typeof url != 'undefined') {
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);
                $("#commonModal").modal('show');
                $.post(url, {
                    _token: '<?php echo e(csrf_token()); ?>',
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                }, function(data) {
                    $('#commonModal .body').html(data);
                });
            }
        });


        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            $("#email_sending").show();
            var post = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_email .btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if (data.is_success) {
                        show_toastr('Success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                    $("#email_sending").hide();
                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        });
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })
        $(".list-group-item").click(function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });

        function check_theme(color_val) {
            $('#theme_color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }

        $(document).on('change', '[name=storage_setting]', function() {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'wasabi') {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top">
                    <div class="list-group list-group-flush" id="useradd-sidenav">

                        


                        <a href="#company-settings" id="company-setting-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Company Settings')); ?> <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#system-settings" id="system-setting-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('System Settings')); ?> <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#email-settings" id="email-setting-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Email Settings')); ?> <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>



                        


                        <a id="email-notification-tab" data-toggle="tab" href="#email-notification-settings" role="tab"
                            aria-controls="" aria-selected="false"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Email Notification Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>

                        <a href="#ip-restriction-settings" id="ip-restrict-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('IP Restriction Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>

                        <?php if(Auth::user()->type == 'company'): ?>
                            <a href="#zoom-meeting-settings" id="zoom-meeting-tab"
                                class="list-group-item list-group-item-action border-0"><?php echo e(__('Zoom Meeting Settings')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            

                            

                            
                        <?php endif; ?>
                        <a href="#recaptcha-print-settings" id="recaptcha-print-setting-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Recaptcha Settings')); ?> <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#offer-letter-settings" id="offer-letter-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Offer Letter Settings')); ?> <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#joining-letter-settings" id="joining-letter-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Joining Letter Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>

                        <a href="#experience-certificate-settings" id="experience-certificate-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Certificate of Experience Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>

                        <a href="#noc-settings" id="noc-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('No Objection Certificate Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>

                        
                        <a href="#google-calender" id="google-calendar-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Google Calendar Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#SEO-settings" id="google-calendar-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('SEO Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#cache-settings" id="cache-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Cache Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#webhook-settings" id="webhook-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Webhook Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#cookie-settings" id="cache-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Cookie Consent Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#pills-chatgpt-settings" id="chatgpt-tab"
                            class="list-group-item list-group-item-action border-0"><?php echo e(__('Chat GPT Key Settings')); ?>

                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                    </div>

                </div>
            </div>

            <div class="col-xl-9">
                


                <div class="" id="company-settings">
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('Company Settings')); ?></h5>
                        </div>
                        <?php echo e(Form::model($settings, ['route' => 'company.settings', 'method' => 'post'])); ?>

                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <?php echo e(Form::label('company_name *', __('Company Name *'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('company_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Company Name'])); ?>


                                    <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-company_name" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                </div>
                                <div class="form-group col-md-4">
                                    <?php echo e(Form::label('company_address', __('Address'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('company_address', null, ['class' => 'form-control ', 'placeholder' => 'Enter Address'])); ?>

                                    <?php $__errorArgs = ['company_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-company_address" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php echo e(Form::label('company_city', __('City'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('company_city', null, ['class' => 'form-control ', 'placeholder' => 'Enter City'])); ?>

                                    <?php $__errorArgs = ['company_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-company_city" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php echo e(Form::label('company_state', __('State'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('company_state', null, ['class' => 'form-control ', 'placeholder' => 'Enter State'])); ?>

                                    <?php $__errorArgs = ['company_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-company_state" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php echo e(Form::label('company_zipcode', __('Zip/Post Code'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('company_zipcode', null, ['class' => 'form-control', 'placeholder' => 'Enter Zip/Post Code'])); ?>

                                    <?php $__errorArgs = ['company_zipcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-company_zipcode" role="alert">
                                            <strong class="text-danger"><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php echo e(Form::label('company_country', __('Country'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('company_country', null, ['class' => 'form-control ', 'placeholder' => 'Enter Country'])); ?>

                                    <?php $__errorArgs = ['company_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-company_country" role="alert"><strong
                                                class="text-danger"><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php echo e(Form::label('company_telephone', __('Telephone'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('company_telephone', null, ['class' => 'form-control', 'placeholder' => 'Enter Telephone'])); ?>

                                    <?php $__errorArgs = ['company_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-company_telephone" role="alert"><strong
                                                class="text-danger"><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                


                                

                                <div class="form-group col-md-6">
                                    <?php echo e(Form::label('timezone', __('Timezone'), ['class' => 'col-form-label text-dark'])); ?>

                                    <select type="text" name="timezone" class="form-control select2" id="timezone">
                                        <option value=""><?php echo e(__('Select Timezone')); ?></option>
                                        <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($k); ?>"
                                                <?php echo e($settings['timezone'] == $k ? 'selected' : ''); ?>>
                                                <?php echo e($timezone); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-timezone" role="alert">
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="" for="ip_restrict"><?php echo e(__('Ip Restrict')); ?></label>
                                    <div class="custom-control custom-switch mt-3">
                                        <input type="checkbox" class=" form-check-input" data-toggle="switchbutton"
                                            data-onstyle="primary" name="ip_restrict" id="ip_restrict"
                                            <?php echo e($settings['ip_restrict'] == 'on' ? 'checked="checked"' : ''); ?>>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn-submit btn btn-primary" type="submit">
                                <?php echo e(__('Save Changes')); ?>

                            </button>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>



                <div class="" id="system-settings">
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo e(__('System Settings')); ?></h5>
                        </div>
                        <?php echo e(Form::model($settings, ['route' => 'system.settings', 'method' => 'post'])); ?>

                        <div class="card-body">
                            <div class="row company-setting">
                                <div class="form-group col-md-4">
                                    <?php echo e(Form::label('site_currency', __('Currency *'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('site_currency', null, ['class' => 'form-control '])); ?>

                                    <small class="text-xs">
                                        <?php echo e(__('Note: Add currency code as per three-letter ISO code')); ?>.
                                        <a href="https://stripe.com/docs/currencies"
                                            target="_blank"><?php echo e(__('You can find out how to do that here.')); ?></a>
                                    </small>
                                    <?php $__errorArgs = ['site_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <br>
                                        <span class="text-xs text-danger invalid-site_currency"
                                            role="alert"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php echo e(Form::label('site_currency_symbol', __('Currency Symbol *'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('site_currency_symbol', null, ['class' => 'form-control'])); ?>

                                    <?php $__errorArgs = ['site_currency_symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-xs text-danger invalid-site_currency_symbol"
                                            role="alert"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="col-form-label"><?php echo e(__('Currency Symbol Position')); ?></label>
                                    <div class="form-check form-check">
                                        <input class="form-check-input" type="radio" id="pre" value="pre"
                                            name="site_currency_symbol_position"
                                            <?php if($settings['site_currency_symbol_position'] == 'pre'): ?> checked <?php endif; ?>>
                                        <label class="form-check-label" for="pre">
                                            <?php echo e(__('Pre')); ?>

                                        </label>
                                    </div>
                                    <div class="form-check form-check">
                                        <input class="form-check-input" type="radio" id="post" value="post"
                                            name="site_currency_symbol_position"
                                            <?php if($settings['site_currency_symbol_position'] == 'post'): ?> checked <?php endif; ?>>
                                        <label class="form-check-label" for="post">
                                            <?php echo e(__('Post')); ?>

                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="site_date_format" class="col-form-label"><?php echo e(__('Date Format')); ?></label>
                                    <select type="text" name="site_date_format" class="form-control"
                                        id="site_date_format">
                                        <option value="M j, Y"
                                            <?php if(@$settings['site_date_format'] == 'M j, Y'): ?> selected="selected" <?php endif; ?>>
                                            Jan 1,2015</option>
                                        <option value="d-m-Y"
                                            <?php if(@$settings['site_date_format'] == 'd-m-Y'): ?> selected="selected" <?php endif; ?>>
                                            dd-mm-yyyy</option>
                                        <option value="m-d-Y"
                                            <?php if(@$settings['site_date_format'] == 'm-d-Y'): ?> selected="selected" <?php endif; ?>>
                                            mm-dd-yyyy</option>
                                        <option value="Y-m-d"
                                            <?php if(@$settings['site_date_format'] == 'Y-m-d'): ?> selected="selected" <?php endif; ?>>
                                            yyyy-mm-dd</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="site_time_format" class="col-form-label"><?php echo e(__('Time Format')); ?></label>
                                    <select type="text" name="site_time_format" class="form-control"
                                        id="site_time_format">
                                        <option value="g:i A"
                                            <?php if(@$settings['site_time_format'] == 'g:i A'): ?> selected="selected" <?php endif; ?>>
                                            10:30 PM</option>
                                        <option value="g:i a"
                                            <?php if(@$settings['site_time_format'] == 'g:i a'): ?> selected="selected" <?php endif; ?>>
                                            10:30 pm</option>
                                        <option value="H:i"
                                            <?php if(@$settings['site_time_format'] == 'H:i'): ?> selected="selected" <?php endif; ?>>
                                            22:30 am</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    


                                    <?php echo e(Form::label('employee_prefix', __('Employee Prefix'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('employee_prefix', null, ['class' => 'form-control'])); ?>

                                    <?php $__errorArgs = ['employee_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-xs text-danger invalid-employee_prefix" role="alert">
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                </div>







                            </div>
                        </div>

                        <div class="card-footer ">
                            <div class="col-sm-12 px-2">
                                <div class="text-end">
                                    <?php echo e(Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary'])); ?>

                                </div>
                            </div>
                        </div>
                        <?php echo Form::close(); ?>

                    </div>
                </div>


                <div class="" id="email-settings">
                    <?php echo e(Form::open(['route' => 'email.settings', 'method' => 'post'])); ?>

                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><?php echo e(__('Email Settings')); ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <?php echo e(Form::label('mail_driver', __('Mail Driver'), ['class' => 'col-form-label'])); ?>

                                            <?php echo e(Form::text('mail_driver', $setting['mail_driver'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')])); ?>

                                            <?php $__errorArgs = ['mail_driver'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-xs text-danger invalid-mail_driver"
                                                    role="alert"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <?php echo e(Form::label('mail_host', __('Mail Host'), ['class' => 'col-form-label'])); ?>

                                            <?php echo e(Form::text('mail_host', $setting['mail_host'], ['class' => 'form-control ', 'placeholder' => __('Enter Mail Host')])); ?>

                                            <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-xs text-danger invalid-mail_driver"
                                                    role="alert"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <?php echo e(Form::label('mail_port', __('Mail Port'), ['class' => 'col-form-label'])); ?>

                                            <?php echo e(Form::text('mail_port', $setting['mail_port'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')])); ?>

                                            <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-xs text-danger invalid-mail_port"
                                                    role="alert"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <?php echo e(Form::label('mail_username', __('Mail Username'), ['class' => 'col-form-label'])); ?>

                                            <?php echo e(Form::text('mail_username', $setting['mail_username'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')])); ?>

                                            <?php $__errorArgs = ['mail_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-xs text-danger invalid-mail_username"
                                                    role="alert"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <?php echo e(Form::label('mail_password', __('Mail Password'), ['class' => 'col-form-label'])); ?>

                                            <?php echo e(Form::text('mail_password', $setting['mail_password'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')])); ?>

                                            <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-xs text-danger invalid-mail_password"
                                                    role="alert"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <?php echo e(Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'col-form-label'])); ?>

                                            <?php echo e(Form::text('mail_encryption', $setting['mail_encryption'], ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')])); ?>

                                            <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-xs text-danger invalid-mail_encryption"
                                                    role="alert"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <?php echo e(Form::label('mail_from_address', __('Mail From Address'), ['class' => 'col-form-label'])); ?>

                                            <?php echo e(Form::text('mail_from_address', $setting['mail_from_address'], ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')])); ?>

                                            <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-xs text-danger invalid-mail_from_address"
                                                    role="alert"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            <?php echo e(Form::label('mail_from_name', __('Mail From Name'), ['class' => 'col-form-label'])); ?>

                                            <?php echo e(Form::text('mail_from_name', $setting['mail_from_name'], ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')])); ?>

                                            <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-xs text-danger invalid-mail_from_name"
                                                    role="alert"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            
                                            <a href="#" class="btn btn-print-invoice  btn-primary m-r-10 send_email"
                                                data-ajax-popup="true" data-title="<?php echo e(__('Send Test Mail')); ?>"
                                                data-url="<?php echo e(route('test.mail')); ?>">
                                                <?php echo e(__('Send Test Mail')); ?>

                                            </a>

                                        </div>
                                        <div class="text-end col-md-6">
                                            <?php echo e(Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary'])); ?>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>



                

                

                <!--Email Notification Setting-->
                <div id="email-notification-settings" class="card">

                    <?php echo e(Form::model($settings, ['route' => ['company.email.setting'], 'method' => 'post'])); ?>

                    <?php echo csrf_field(); ?>
                    <div class="col-md-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <h5><?php echo e(__('Email Notification Settings')); ?></h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- <div class=""> -->
                                <?php $__currentLoopData = $EmailTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $EmailTemplate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                        <div class="list-group">
                                            <div class="list-group-item form-switch form-switch-right">
                                                <label class="form-label"
                                                    style="margin-left:5%;"><?php echo e($EmailTemplate->name); ?></label>

                                                <input class="form-check-input" name='<?php echo e($EmailTemplate->id); ?>'
                                                    id="email_tempalte_<?php echo e($EmailTemplate->template->id); ?>"
                                                    type="checkbox"
                                                    <?php if($EmailTemplate->template->is_active == 1): ?> checked="checked" <?php endif; ?>
                                                    type="checkbox" value="1"
                                                    data-url="<?php echo e(route('company.email.setting', [$EmailTemplate->template->id])); ?>" />
                                                <label class="form-check-label"
                                                    for="email_tempalte_<?php echo e($EmailTemplate->template->id); ?>"></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <!-- </div> -->
                            </div>
                            <div class="card-footer p-0">
                                <div class="col-sm-12 mt-3 px-2">
                                    <div class="text-end">
                                        <input class="btn btn-print-invoice  btn-primary " type="submit"
                                            value="<?php echo e(__('Save Changes')); ?>">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>

                <div class="" id="ip-restriction-settings">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">

                            <h5><?php echo e(__('IP Restriction Settings')); ?></h5>

                            <a data-url="<?php echo e(route('create.ip')); ?>" class="btn btn-sm btn-primary"
                                data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Create New IP')); ?>"
                                data-bs-placement="top" data-size="md" data-ajax-popup="true"
                                data-title="<?php echo e(__('Create New IP')); ?>">
                                <i class="ti ti-plus"></i>
                            </a>

                        </div>
                        <div class="card-body table-border-style ">
                            <div class="table-responsive">
                                <table class="table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th class="w-75"> <?php echo e(__('IP')); ?></th>
                                            <th width="200px"> <?php echo e('Action'); ?></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $__currentLoopData = $ips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="Action">
                                                <td class="sorting_1"><?php echo e($ip->ip); ?></td>
                                                <td class="">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Company Settings')): ?>
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="<?php echo e(route('edit.ip', $ip->id)); ?>" data-size="md"
                                                                data-ajax-popup="true" data-title="<?php echo e(__('Edit IP')); ?>"
                                                                data-bs-toggle="tooltip" class="edit-icon"
                                                                data-bs-placement="top" title="<?php echo e(__('Edit')); ?>"><i
                                                                    class="ti ti-pencil text-white"></i></a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Company Settings')): ?>
                                                        <div class="action-btn bg-danger ms-2">
                                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['destroy.ip', $ip->id], 'id' => 'delete-form-' . $ip->id]); ?>

                                                            <a href="#!"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="<?php echo e(__('Delete')); ?>">
                                                                <i class="ti ti-trash text-white"></i></a>
                                                            <?php echo Form::close(); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if(Auth::user()->type == 'company'): ?>
                    <div class="" id="zoom-meeting-settings">
                        <div class="card">
                            <div class="card-header">
                                <h5><?php echo e(__('Zoom Meeting Settings')); ?></h5>
                            </div>
                            <?php echo e(Form::open(['route' => 'zoom.settings', 'method' => 'post'])); ?>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        <?php echo e(Form::label('zoom_account_id', __('Zoom Account ID'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('zoom_account_id', isset($settings['zoom_account_id']) ? $settings['zoom_account_id'] : '', ['class' => 'form-control ', 'placeholder' => 'Enter Zoom Account ID'])); ?>

                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        <?php echo e(Form::label('zoom_client_id', __('Zoom Client ID'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('zoom_client_id', isset($settings['zoom_client_id']) ? $settings['zoom_client_id'] : '', ['class' => 'form-control ', 'placeholder' => 'Enter Zoom Client ID'])); ?>

                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        <?php echo e(Form::label('zoom_client_secret', __('Zoom Client Secret Key'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('zoom_client_secret', isset($settings['zoom_client_secret']) ? $settings['zoom_client_secret'] : '', ['class' => 'form-control ', 'placeholder' => 'Enter Zoom Client Secret Key'])); ?>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    <?php echo e(__('Save Changes')); ?>

                                </button>
                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                    



                    

                    
                <?php endif; ?>

                <div id="recaptcha-print-settings" class="card">
                    <div class="col-md-12">
                        <form method="POST" action="<?php echo e(route('recaptcha.settings.store')); ?>" accept-charset="UTF-8">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                            target="_blank" class="text-blue">
                                            <h5 class=""><?php echo e(__('ReCaptcha settings')); ?></h5><small
                                                class="text-secondary font-weight-bold">(<?php echo e(__('How to Get Google reCaptcha Site and Secret key')); ?>)</small>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        <div class="col switch-width">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                                    class="" name="recaptcha_module" id="recaptcha_module"
                                                    value="yes"
                                                    <?php echo e($settings['recaptcha_module'] == 'yes' ? 'checked="checked"' : ''); ?>>
                                                <label class="custom-control-label form-control-label px-2"
                                                    for="recaptcha_module "></label><br>
                                                <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                    target="_blank" class="text-blue">

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        <label for="google_recaptcha_key"
                                            class="form-label"><?php echo e(__('Google Recaptcha Key')); ?></label>
                                        <input class="form-control" placeholder="<?php echo e(__('Enter Google Recaptcha Key')); ?>"
                                            name="google_recaptcha_key" type="text"
                                            value="<?php echo e($settings['google_recaptcha_key']); ?>" id="google_recaptcha_key">

                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        <label for="google_recaptcha_secret"
                                            class="form-label"><?php echo e(__('Google Recaptcha Secret')); ?></label>
                                        <input class="form-control "
                                            placeholder="<?php echo e(__('Enter Google Recaptcha Secret')); ?>"
                                            name="google_recaptcha_secret" type="text"
                                            value="<?php echo e($settings['google_recaptcha_secret']); ?>"
                                            id="google_recaptcha_secret">

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">

                                <?php echo e(Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary'])); ?>


                            </div>
                        </form>
                    </div>
                </div>

                <div class="" id="offer-letter-settings">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5><?php echo e(__('Offer Letter Settings')); ?></h5>
                            <div class="d-flex justify-content-end drp-languages">
                                <ul class="list-unstyled mb-0 m-2">
                                    <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                        <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                            data-bs-toggle="dropdown" href="#" role="button"
                                            aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                                            <span class="drp-text hide-mob text-primary">
                                                <?php echo e(Str::ucfirst($offerlangName->fullName)); ?>

                                            </span>
                                            <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                        </a>
                                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                            aria-labelledby="dropdownLanguage">
                                            
                                            <?php $__currentLoopData = App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $offerlangs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('get.offerlatter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $code, 'joininglangs' => $joininglang])); ?>"
                                                    class="dropdown-item ms-1 <?php echo e($offerlang == $code ? 'text-primary' : ''); ?>">
                                                    <span><?php echo e(ucFirst($offerlangs)); ?></span>
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </li>
                                </ul>

                            </div>
                        </div>
                        <div class="card-body ">
                            <h5 class="font-weight-bold pb-3"><?php echo e(__('Placeholders')); ?></h5>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header card-body">
                                        <div class="row text-xs">
                                            <div class="row">
                                                <p class="col-4"><?php echo e(__('Applicant Name')); ?> : <span
                                                        class="pull-end text-primary">{applicant_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                        class="pull-right text-primary">{app_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Job title')); ?> : <span
                                                        class="pull-right text-primary">{job_title}</span></p>
                                                <p class="col-4"><?php echo e(__('Job type')); ?> : <span
                                                        class="pull-right text-primary">{job_type}</span></p>
                                                <p class="col-4"><?php echo e(__('Proposed Start Date')); ?> : <span
                                                        class="pull-right text-primary">{start_date}</span></p>
                                                <p class="col-4"><?php echo e(__('Working Location')); ?> : <span
                                                        class="pull-right text-primary">{workplace_location}</span></p>
                                                <p class="col-4"><?php echo e(__('Days Of Week')); ?> : <span
                                                        class="pull-right text-primary">{days_of_week}</span></p>
                                                <p class="col-4"><?php echo e(__('Salary')); ?> : <span
                                                        class="pull-right text-primary">{salary}</span></p>
                                                <p class="col-4"><?php echo e(__('Salary Type')); ?> : <span
                                                        class="pull-right text-primary">{salary_type}</span></p>
                                                <p class="col-4"><?php echo e(__('Salary Duration')); ?> : <span
                                                        class="pull-end text-primary">{salary_duration}</span></p>
                                                <p class="col-4"><?php echo e(__('Offer Expiration Date')); ?> : <span
                                                        class="pull-right text-primary">{offer_expiration_date}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style ">

                            <?php echo e(Form::open(['route' => ['offerlatter.update', $offerlang], 'method' => 'post'])); ?>


                            <div class="form-group col-12">
                                <?php echo e(Form::label('content', __(' Format'), ['class' => 'form-label text-dark'])); ?>

                                <textarea name="content" class="summernote-simple"><?php echo isset($currOfferletterLang->content) ? $currOfferletterLang->content : ''; ?></textarea>
                            </div>
                            <div class="card-footer text-end">

                                <?php echo e(Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary'])); ?>

                            </div>

                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>

                <div class="" id="joining-letter-settings">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5><?php echo e(__('Joining Letter Settings')); ?></h5>
                            <div class="d-flex justify-content-end drp-languages">
                                <ul class="list-unstyled mb-0 m-2">
                                    <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                        <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                            data-bs-toggle="dropdown" href="#" role="button"
                                            aria-haspopup="false" aria-expanded="false" id="dropdownLanguage1">
                                            <span class="drp-text hide-mob text-primary">

                                                <?php echo e(Str::ucfirst($joininglangName->fullName)); ?>

                                            </span>
                                            <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                        </a>
                                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                            aria-labelledby="dropdownLanguage1">
                                            
                                            <?php $__currentLoopData = App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $joininglangs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('get.joiningletter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $offerlang, 'joininglangs' => $code])); ?>"
                                                    class="dropdown-item ms-1 <?php echo e($joininglang == $code ? 'text-primary' : ''); ?>">
                                                    <span><?php echo e(ucFirst($joininglangs)); ?></span>
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </li>

                                </ul>
                            </div>

                        </div>
                        <div class="card-body ">
                            <h5 class="font-weight-bold pb-3"><?php echo e(__('Placeholders')); ?></h5>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header card-body">
                                        <div class="row text-xs">
                                            <div class="row">
                                                <p class="col-4"><?php echo e(__('Applicant Name')); ?> : <span
                                                        class="pull-end text-primary">{date}</span></p>
                                                <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                        class="pull-right text-primary">{app_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Employee Name')); ?> : <span
                                                        class="pull-right text-primary">{employee_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Address')); ?> : <span
                                                        class="pull-right text-primary">{address}</span></p>
                                                <p class="col-4"><?php echo e(__('Designation')); ?> : <span
                                                        class="pull-right text-primary">{designation}</span></p>
                                                <p class="col-4"><?php echo e(__('Start Date')); ?> : <span
                                                        class="pull-right text-primary">{start_date}</span></p>
                                                <p class="col-4"><?php echo e(__('Branch')); ?> : <span
                                                        class="pull-right text-primary">{branch}</span></p>
                                                <p class="col-4"><?php echo e(__('Start Time')); ?> : <span
                                                        class="pull-end text-primary">{start_time}</span></p>
                                                <p class="col-4"><?php echo e(__('End Time')); ?> : <span
                                                        class="pull-right text-primary">{end_time}</span></p>
                                                <p class="col-4"><?php echo e(__('Number of Hours')); ?> : <span
                                                        class="pull-right text-primary">{total_hours}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style ">

                            <?php echo e(Form::open(['route' => ['joiningletter.update', $joininglang], 'method' => 'post'])); ?>

                            <div class="form-group col-12">
                                <?php echo e(Form::label('content', __(' Format'), ['class' => 'form-label text-dark'])); ?>

                                <textarea name="content" class="summernote-simple"><?php echo isset($currjoiningletterLang->content) ? $currjoiningletterLang->content : ''; ?></textarea>
                            </div>
                            <div class="card-footer text-end">
                                <?php echo e(Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary'])); ?>

                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>

                <div class="" id="experience-certificate-settings">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5><?php echo e(__('Certificate of Experience Settings')); ?></h5>
                            <div class="d-flex justify-content-end drp-languages">
                                <ul class="list-unstyled mb-0 m-2">
                                    <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                        <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                            data-bs-toggle="dropdown" href="#" role="button"
                                            aria-haspopup="false" aria-expanded="false" id="dropdownLanguage1">
                                            <span class="drp-text hide-mob text-primary">

                                                <?php echo e(Str::ucfirst($explangName->fullName)); ?>

                                            </span>
                                            <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                        </a>
                                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                            aria-labelledby="dropdownLanguage1">
                                            
                                            <?php $__currentLoopData = App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $explangs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('get.experiencecertificate.language', ['noclangs' => $noclang, 'explangs' => $code, 'offerlangs' => $offerlang, 'joininglangs' => $joininglang])); ?>"
                                                    class="dropdown-item ms-1 <?php echo e($explang == $code ? 'text-primary' : ''); ?>">
                                                    <span><?php echo e(ucFirst($explangs)); ?></span>
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </li>

                                </ul>
                            </div>

                        </div>
                        <div class="card-body ">
                            <h5 class="font-weight-bold pb-3"><?php echo e(__('Placeholders')); ?></h5>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header card-body">
                                        <div class="row text-xs">
                                            <div class="row">
                                                <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                        class="pull-right text-primary">{app_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Employee Name')); ?> : <span
                                                        class="pull-right text-primary">{employee_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Date of Issuance')); ?> : <span
                                                        class="pull-right text-primary">{date}</span></p>
                                                <p class="col-4"><?php echo e(__('Designation')); ?> : <span
                                                        class="pull-right text-primary">{designation}</span></p>
                                                <p class="col-4"><?php echo e(__('Start Date')); ?> : <span
                                                        class="pull-right text-primary">{start_date}</span></p>
                                                <p class="col-4"><?php echo e(__('Branch')); ?> : <span
                                                        class="pull-right text-primary">{branch}</span></p>
                                                <p class="col-4"><?php echo e(__('Start Time')); ?> : <span
                                                        class="pull-end text-primary">{start_time}</span></p>
                                                <p class="col-4"><?php echo e(__('End Time')); ?> : <span
                                                        class="pull-right text-primary">{end_time}</span></p>
                                                <p class="col-4"><?php echo e(__('Number of Hours')); ?> : <span
                                                        class="pull-right text-primary">{total_hours}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style ">

                            <?php echo e(Form::open(['route' => ['experiencecertificate.update', $explang], 'method' => 'post'])); ?>

                            <div class="form-group col-12">
                                <?php echo e(Form::label('content', __(' Format'), ['class' => 'form-label text-dark'])); ?>

                                <textarea name="content" class="summernote-simple"><?php echo isset($curr_exp_cetificate_Lang->content) ? $curr_exp_cetificate_Lang->content : ''; ?></textarea>



                            </div>

                            <div class="card-footer text-end">

                                <?php echo e(Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary'])); ?>

                            </div>

                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>

                <div class="" id="noc-settings">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5><?php echo e(__('No Objection Certificate Settings')); ?></h5>
                            <div class="d-flex justify-content-end drp-languages">
                                <ul class="list-unstyled mb-0 m-2">
                                    <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                        <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                            data-bs-toggle="dropdown" href="#" role="button"
                                            aria-haspopup="false" aria-expanded="false" id="dropdownLanguage1">
                                            <span class="drp-text hide-mob text-primary">

                                                <?php echo e(Str::ucfirst($noclangName->fullName)); ?>

                                            </span>
                                            <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                        </a>
                                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                            aria-labelledby="dropdownLanguage1">
                                            
                                            <?php $__currentLoopData = App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $noclangs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('get.noc.language', ['noclangs' => $code, 'explangs' => $explang, 'offerlangs' => $offerlang, 'joininglangs' => $joininglang])); ?>"
                                                    class="dropdown-item ms-1 <?php echo e($noclang == $code ? 'text-primary' : ''); ?>">
                                                    <span><?php echo e(ucFirst($noclangs)); ?></span>
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </li>

                                </ul>
                            </div>

                        </div>
                        <div class="card-body ">
                            <h5 class="font-weight-bold pb-3"><?php echo e(__('Placeholders')); ?></h5>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header card-body">
                                        <div class="row text-xs">
                                            <div class="row">
                                                <p class="col-4"><?php echo e(__('Date')); ?> : <span
                                                        class="pull-end text-primary">{date}</span></p>
                                                <p class="col-4"><?php echo e(__('Company Name')); ?> : <span
                                                        class="pull-right text-primary">{app_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Employee Name')); ?> : <span
                                                        class="pull-right text-primary">{employee_name}</span></p>
                                                <p class="col-4"><?php echo e(__('Designation')); ?> : <span
                                                        class="pull-right text-primary">{designation}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style ">
                            <?php echo e(Form::open(['route' => ['noc.update', $noclang], 'method' => 'post'])); ?>

                            <div class="form-group col-12">
                                <?php echo e(Form::label('content', __(' Format'), ['class' => 'form-label text-dark'])); ?>

                                <textarea name="content" class="summernote-simple"><?php echo isset($currnocLang->content) ? $currnocLang->content : ''; ?></textarea>

                            </div>

                            <div class="card-footer text-end">

                                <?php echo e(Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary'])); ?>

                            </div>

                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>

                
                <div class="card" id="google-calender">
                    <div class="col-md-12">
                        <?php echo e(Form::open(['url' => route('google.calender.settings'), 'enctype' => 'multipart/form-data'])); ?>

                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <h5 class="">
                                        <?php echo e(__('Google Calendar')); ?>

                                    </h5>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                    <div class="col switch-width">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="form-check-input" name="is_enabled"
                                                data-toggle="switchbutton" data-onstyle="primary" id="is_enabled"
                                                <?php echo e(isset($settings['is_enabled']) && $settings['is_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                            <label class="custom-control-label form-label" for="is_enabled"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                    <?php echo e(Form::label('Google calendar id', __('Google Calendar Id'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('google_clender_id', !empty($settings['google_clender_id']) ? $settings['google_clender_id'] : '', ['class' => 'form-control ', 'placeholder' => 'Google Calendar Id'])); ?>

                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                    <?php echo e(Form::label('Google calendar json file', __('Google Calendar json File'), ['class' => 'col-form-label'])); ?>

                                    <input type="file" class="form-control" name="google_calender_json_file"
                                        id="file">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn-submit btn btn-primary" type="submit">
                                <?php echo e(__('Save Changes')); ?>

                            </button>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>

                
                <div id="SEO-settings" class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <h5><?php echo e(__('SEO Settings')); ?></h5>
                            </div>

                            <?php if($chatgpt == 'on'): ?>
                                <div class="col-md-2">
                                    <a href="#" class="btn btn-sm btn-primary" data-size="medium"
                                        data-ajax-popup-over="true" data-url="<?php echo e(route('generate', ['seo'])); ?>"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="<?php echo e(__('Generate')); ?>"
                                        data-title="<?php echo e(__('Generate Content With AI')); ?>">
                                        <i class="fas fa-robot"></i><?php echo e(__(' Generate With AI')); ?>

                                    </a>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <div class="card-body">
                        <?php echo e(Form::open(['url' => route('seo.settings'), 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::label('Meta Keywords', __('Meta Keywords'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('meta_title', !empty($setting['meta_title']) ? $setting['meta_title'] : '', ['class' => 'form-control ', 'placeholder' => 'Meta Keywords'])); ?>

                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('Meta Description', __('Meta Description'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::textarea('meta_description', !empty($setting['meta_description']) ? $setting['meta_description'] : '', ['class' => 'form-control ', 'placeholder' => 'Meta Description', 'rows' => 3])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <?php echo e(Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label'])); ?>

                                </div>
                                <div class="setting-card">
                                    <div class="logo-content">
                                        <img id="image2"
                                            src="<?php echo e($meta_image . '/' . (isset($setting['meta_image']) && !empty($setting['meta_image']) ? $setting['meta_image'] : 'hrm-yps.png')); ?>"
                                            class="img_setting seo_image">
                                    </div>
                                    <div class="choose-files mt-4">
                                        <label for="meta_image">
                                            <div class="bg-primary company_favicon_update"> <i
                                                    class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                            </div>
                                            <input type="file" class="form-control file" id="meta_image"
                                                name="meta_image" data-filename="meta_image">
                                        </label>
                                    </div>
                                    <?php $__errorArgs = ['meta_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="row">
                                            <span class="invalid-logo" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>


                        <div class="card-footer text-end">
                            <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                value="<?php echo e(__('Save Changes')); ?>">
                        </div>

                        <?php echo e(Form::close()); ?>

                    </div>
                </div>

                
                <div id="cache-settings">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><?php echo e(__('Cache Settings')); ?></h5>
                                </div>
                            </div>
                        </div>
                        <?php echo e(Form::open(['url' => route('clear.cache')])); ?>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-11 col-md-11 col-sm-11 form-group">
                                    <?php echo e(Form::label('Current cache size', __('Current cache size'), ['class' => 'col-form-label'])); ?>

                                    <div class="input-group mb-5">
                                        <input type="text" class="form-control" value="<?php echo e($file_size); ?>"
                                            readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text"
                                                id="basic-addon6"><?php echo e(__('MB')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn-submit btn btn-primary" type="submit">
                                <?php echo e(__('Clear Cache')); ?>

                            </button>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>

                
                <div class="" id="webhook-settings">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">

                            <h5><?php echo e(__('Webhook Settings')); ?></h5>
                            <a data-url="<?php echo e(route('create.webhook')); ?>" class="btn btn-sm btn-primary"
                                data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Create New Webhook')); ?>"
                                data-bs-placement="top" data-size="md" data-ajax-popup="true"
                                data-title="<?php echo e(__('Create New Webhook')); ?>">
                                <i class="ti ti-plus"></i>
                            </a>

                        </div>
                        <div class="card-body table-border-style ">
                            <div class="table-responsive">
                                <table class="table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th class="w-25">
                                                <?php echo e(__('Module')); ?></th>
                                            <th class="w-20">
                                                <?php echo e(__('URL')); ?></th>
                                            <th class="w-30">
                                                <?php echo e(__('Method')); ?></th>
                                            <th width="150px">
                                                <?php echo e('Action'); ?></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $__currentLoopData = $webhooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webhook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="Action">
                                                <td class="sorting_1">
                                                    <?php echo e($webhook->module); ?></td>
                                                <td class="sorting_3">
                                                    <?php echo e($webhook->url); ?></td>
                                                <td class="sorting_2">
                                                    <?php echo e($webhook->method); ?></td>
                                                <td class="">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Webhook')): ?>
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="<?php echo e(route('edit.webhook', $webhook->id)); ?>"
                                                                data-size="md" data-ajax-popup="true"
                                                                data-title="<?php echo e(__('Edit Webhook Settings')); ?>"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="<?php echo e(__('Edit')); ?>"
                                                                data-bs-placement="top" class="edit-icon"
                                                                data-original-title="<?php echo e(__('Edit')); ?>"><i
                                                                    class="ti ti-pencil text-white"></i></a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Webhook')): ?>
                                                        <div class="action-btn bg-danger ms-2">
                                                            <?php echo Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['destroy.webhook', $webhook->id],
                                                                'id' => 'delete-form-' . $webhook->id,
                                                            ]); ?>

                                                            <a href="#!" data-bs-toggle="tooltip"
                                                                data-bs-original-title="<?php echo e(__('Delete')); ?>"
                                                                data-bs-placement="top"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                title="<?php echo e(__('Delete')); ?>">
                                                                <i class="ti ti-trash text-white"></i></a>
                                                            <?php echo Form::close(); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" id="cookie-settings">
                    <?php echo e(Form::model($settings, ['route' => 'cookie.setting', 'method' => 'post'])); ?>

                    <div
                        class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                        <h5><?php echo e(__('Cookie Settings')); ?></h5>
                        <div class="d-flex align-items-center">
                            <?php echo e(Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold me-3'])); ?>

                            <div class="custom-control custom-switch" onclick="enablecookie()">
                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                    name="enable_cookie" class="form-check-input input-primary " id="enable_cookie"
                                    <?php echo e($settings['enable_cookie'] == 'on' ? ' checked ' : ''); ?>>
                                <label class="custom-control-label mb-1" for="enable_cookie"></label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body cookieDiv <?php echo e($settings['enable_cookie'] == 'off' ? 'disabledCookie ' : ''); ?>">
                        <div class="row ">

                            <?php if($chatgpt == 'on'): ?>
                                <div class="text-end">
                                    <div class="mt-0">
                                        <a data-size="md" class="btn btn-primary text-white btn-sm"
                                            data-ajax-popup-over="true" data-url="<?php echo e(route('generate', ['cookie'])); ?>"
                                            data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
                                            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-6">
                                <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                    <input type="checkbox" name="cookie_logging"
                                        class="form-check-input input-primary cookie_setting" id="cookie_logging"
                                        onclick="enableButton()"
                                        <?php echo e($settings['cookie_logging'] == 'on' ? ' checked ' : ''); ?>>
                                    <label class="form-check-label"
                                        for="cookie_logging"><?php echo e(__('Enable logging')); ?></label>
                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('cookie_title', null, ['class' => 'form-control cookie_setting', 'placeholder' => 'Cookie Title'])); ?>

                                </div>
                                <div class="form-group ">
                                    <?php echo e(Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label'])); ?>

                                    <?php echo Form::textarea('cookie_description', null, [
                                        'class' => 'form-control cookie_setting',
                                        'rows' => '3',
                                        'placeholder' => 'Cookie Description',
                                    ]); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch custom-switch-v1 ">
                                    <input type="checkbox" name="necessary_cookies"
                                        class="form-check-input input-primary" id="necessary_cookies" checked
                                        onclick="return false">
                                    <label class="form-check-label"
                                        for="necessary_cookies"><?php echo e(__('Strictly necessary cookies')); ?></label>
                                </div>
                                <div class="form-group ">
                                    <?php echo e(Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('strictly_cookie_title', null, ['class' => 'form-control cookie_setting', 'placeholder' => 'Strictly Cookie Title'])); ?>

                                </div>
                                <div class="form-group ">
                                    <?php echo e(Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label'])); ?>

                                    <?php echo Form::textarea('strictly_cookie_description', null, [
                                        'class' => 'form-control cookie_setting ',
                                        'rows' => '3',
                                        'placeholder' => 'Strictly Cookie Description',
                                    ]); ?>

                                </div>
                            </div>
                            <div class="col-12">
                                <h5><?php echo e(__('More Information')); ?></h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <?php echo e(Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('more_information_description', null, ['class' => 'form-control cookie_setting', 'placeholder' => 'Contact Us Description'])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <?php echo e(Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('contactus_url', null, ['class' => 'form-control cookie_setting', 'placeholder' => 'Contact Us URL'])); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div id="csv_file" class="col-md-6">
                                <?php if(isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on'): ?>
                                    <label for="file"
                                        class="form-label"><?php echo e(__('Download cookie accepted data')); ?></label>
                                    <a href="<?php echo e(asset(Storage::url('uploads/sample')) . '/data.csv'); ?>"
                                        class="btn btn-primary mr-2 ">
                                        <i class="ti ti-download"></i>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="text-end col-md-6">
                                <input type="submit" class="btn btn-xs btn-primary"
                                    value="<?php echo e(__('Save Changes')); ?>">
                            </div>
                        </div>
                    </div>

                    <?php echo e(Form::close()); ?>

                </div>

                

            </div>
        </div>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hrm\resources\views/setting/company_settings.blade.php ENDPATH**/ ?>