<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>

<?php
    $setting = App\Models\Utility::settings();

?>



<?php $__env->startSection('content'); ?>
    <?php if(session('status')): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>


    <?php if(\Auth::user()->type == 'employee'): ?>
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5><?php echo e(__('Calendar')); ?></h5>
                            <input type="hidden" id="path_admin" value="<?php echo e(url('/')); ?>">
                        </div>
                        <div class="col-lg-6">
                            
                            <label for=""></label>
                            <?php if(isset($setting['is_enabled']) && $setting['is_enabled'] == 'on'): ?>
                                <select class="form-control" name="calender_type" id="calender_type"
                                    style="float: right;width: 155px;" onchange="get_data()">
                                    <option value="google_calender"><?php echo e(__('Google Calendar')); ?></option>
                                    <option value="local_calender" selected="true">
                                        <?php echo e(__('Local Calendar')); ?></option>
                                </select>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id='event_calendar' class='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card"style="height: 230px;">
                <div class="card-header">
                    <h5><?php echo e(__('Mark Attandance')); ?></h5>
                </div>
                <div class="card-body">
                    <p class="text-muted pb-0-5">
                        <?php echo e(__('My Office Time: ' . $officeTime['startTime'] . ' to ' . $officeTime['endTime'])); ?></p>
                    <?php if($hasPending): ?>
                        <?php
                            $pendingDoc = $documentUploads->where('status', 'pending')->first();
                            $rejectedDoc = $documentUploads->where('status', 'rejected')->first();
                        ?>
                        <?php if($pendingDoc): ?>
                            <div class="alert alert-warning text-center" role="alert">
                                Beberapa dokumen masih menunggu untuk disetujui!
                            </div>
                        <?php elseif($rejectedDoc): ?>
                            <div class="alert alert-danger text-center" role="alert">
                                Ada dokumen yang di tolak, silahkan upload ulang dokumen!
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning text-center" role="alert">
                                Silahkan upload dokumen-dokumen wajib sebelum melakukan absen!
                            </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-center">
                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $document = $documentUploads->where('type', $doc->id)->first();
                                ?>
                                <?php if($document): ?>
                                    <?php if($document->status == 'approved'): ?>
                                        ✅
                                    <?php elseif($document->status == 'rejected'): ?>
                                        ❌
                                    <?php else: ?>
                                        ⌛
                                    <?php endif; ?>
                                <?php else: ?>
                                ❌
                                <?php endif; ?>
                                <?php echo e($doc->name); ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-6 float-right border-right">
                                <?php if(empty($employeeAttendance) || $employeeAttendance->clock_out != '00:00:00'): ?>
                                    
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#clockInModal"><?php echo e(__('CLOCK IN')); ?></button>
                                    <div class="modal fade" id="clockInModal" tabindex="-1"
                                        aria-labelledby="clockInModalLabel" aria-hidden="true" data-bs-backdrop="static"
                                        data-bs-keyboard="false">
                                        <div class="modal-dialog modal-fullscreen-sm-down">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="clockInModalLabel"><?php echo e(__('Clock In')); ?>

                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php echo e(Form::open(['url' => 'attendanceemployee/attendance', 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>

                                                    <video id="video" width="100%" height="420" autoplay></video>
                                                    <canvas id="canvas" width="100%" height="420"
                                                        style="display: none;"></canvas>
                                                    <button type="button" class="btn btn-primary w-100" id="snap"
                                                        style="margin-top: 10px;">Take Picture</button>
                                                    <button type="button" class="d-none btn btn-secondary w-100"
                                                        id="re-snap" style="margin-top: 10px;">Retake</button>
                                                    <input type="hidden" name="clockin_photo" id="clockin_photo">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                                                    <button type="submit" class="btn btn-primary" id="clock-in"><?php echo e(__('Clock In')); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <button type="submit" value="0" name="in" id="clock_in"
                                        class="btn btn-primary disabled" disabled><?php echo e(__('CLOCK IN')); ?></button>
                                <?php endif; ?>
                                <?php echo e(Form::close()); ?>

                            </div>
                            <div class="col-md-6 float-left">
                                <?php if(!empty($employeeAttendance) && $employeeAttendance->clock_out == '00:00:00'): ?>
                                    <?php echo e(Form::model($employeeAttendance, ['route' => ['attendanceemployee.update', $employeeAttendance->id], 'method' => 'PUT'])); ?>

                                    <button type="submit" value="1" name="out" id="clock_out"
                                        class="btn btn-danger"><?php echo e(__('CLOCK OUT')); ?></button>
                                <?php else: ?>
                                    <button type="submit" value="1" name="out" id="clock_out"
                                        class="btn btn-danger disabled" disabled><?php echo e(__('CLOCK OUT')); ?></button>
                                <?php endif; ?>
                                <?php echo e(Form::close()); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card" style="height: 462px;">
                <div class="card-header card-body table-border-style">
                    <h5><?php echo e(__('Meeting schedule')); ?></h5>
                </div>
                <div class="card-body" style="height: 320px">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Meeting title')); ?></th>
                                    <th><?php echo e(__('Meeting Date')); ?></th>
                                    <th><?php echo e(__('Meeting Time')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($meeting->title); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($meeting->date)); ?></td>
                                        <td><?php echo e(\Auth::user()->timeFormat($meeting->time)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5><?php echo e(__('Announcement List')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Title')); ?></th>
                                    <th><?php echo e(__('Start Date')); ?></th>
                                    <th><?php echo e(__('End Date')); ?></th>
                                    <th><?php echo e(__('Description')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($announcement->title); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($announcement->start_date)); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($announcement->end_date)); ?></td>
                                        <td><?php echo e($announcement->description); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="col-xxl-12">

            
            <div class="row">

                <div class="col-lg-4 col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-3 mb-sm-0">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-users"></i>
                                        </div>
                                        <div class="ms-3">
                                            <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                            <h6 class="m-0"><?php echo e(__('Staff')); ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto text-end">
                                    <h4 class="m-0 text-primary"><?php echo e($countUser + $countEmployee); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-3 mb-sm-0">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-info">
                                            <i class="ti ti-ticket"></i>
                                        </div>
                                        <div class="ms-3">
                                            <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                            <h6 class="m-0"><?php echo e(__('Ticket')); ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto text-end">
                                    <h4 class="m-0 text-info"> <?php echo e($countTicket); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-auto mb-3 mb-sm-0">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-warning">
                                            <i class="ti ti-wallet"></i>
                                        </div>
                                        <div class="ms-3">
                                            <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                            <h6 class="m-0"><?php echo e(__('Account Balance')); ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto text-end">
                                    <h4 class="m-0 text-warning"><?php echo e(\Auth::user()->priceFormat($accountBalance)); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-primary">
                                    <i class="ti ti-cast"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                    <h6 class="m-0"><?php echo e(__('Jobs')); ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-primary"><?php echo e($activeJob + $inActiveJOb); ?></h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-4 col-md-6">

            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-info">
                                    <i class="ti ti-cast"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                    <h6 class="m-0"><?php echo e(__('Active Jobs')); ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-info"> <?php echo e($activeJob); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">

            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-cast"></i>
                                </div>
                                <div class="ms-3">
                                    <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                    <h6 class="m-0"><?php echo e(__('Inactive Jobs')); ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-warning"><?php echo e($inActiveJOb); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        

        <div class="col-xxl-12">
            <div class="row">
                <div class="col-xl-5">

                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <h5><?php echo e(__('Meeting schedule')); ?></h5>
                        </div>
                        <div class="card-body" style="height: 324px; overflow:auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('Title')); ?></th>
                                            <th><?php echo e(__('Date')); ?></th>
                                            <th><?php echo e(__('Time')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php $__currentLoopData = $meetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meeting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($meeting->title); ?></td>
                                                <td><?php echo e(\Auth::user()->dateFormat($meeting->date)); ?></td>
                                                <td><?php echo e(\Auth::user()->timeFormat($meeting->time)); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <h5><?php echo e(__("Today's Not Clock In")); ?></h5>
                        </div>
                        <div class="card-body" style="height: 324px; overflow:auto">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('Name')); ?></th>
                                            <th><?php echo e(__('Status')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php $__currentLoopData = $notClockIns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notClockIn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($notClockIn->name); ?></td>
                                                <td><span class="absent-btn"><?php echo e(__('Absent')); ?></span></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-7">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5><?php echo e(__('Calendar')); ?></h5>
                                    <input type="hidden" id="path_admin" value="<?php echo e(url('/')); ?>">
                                </div>
                                <div class="col-lg-6">
                                    
                                    <label for=""></label>
                                    <?php if(isset($setting['is_enabled']) && $setting['is_enabled'] == 'on'): ?>
                                        <select class="form-control" name="calender_type" id="calender_type"
                                            style="float: right;width: 155px;" onchange="get_data()">
                                            <option value="google_calender"><?php echo e(__('Google Calendar')); ?></option>
                                            <option value="local_calender" selected="true">
                                                <?php echo e(__('Local Calendar')); ?></option>
                                        </select>
                                    <?php endif; ?>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="card-body card-635">
                            <div id='calendar' class='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5><?php echo e(__('Announcement List')); ?></h5>
                </div>
                <div class="card-body" style="height: 270px; overflow:auto">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Title')); ?></th>
                                    <th><?php echo e(__('Start Date')); ?></th>
                                    <th><?php echo e(__('End Date')); ?></th>
                                    <th><?php echo e(__('Description')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($announcement->title); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($announcement->start_date)); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($announcement->end_date)); ?></td>
                                        <td><?php echo e($announcement->description); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/main.min.js')); ?>"></script>
    <script>
        (function() {
            var video = document.getElementById('video');
            video.style.transform = 'scaleX(-1)';
            var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');
            var localMediaStream = null;

            document.getElementById('snap').addEventListener('click', function() {
                video.pause();
                ctx.save();
                ctx.scale(-1, 1);
                ctx.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
                ctx.restore();

                var dataURL = canvas.toDataURL('image/png');
                document.getElementById('clockin_photo').value = dataURL;
                document.getElementById('snap').classList.add('d-none');
                document.getElementById('re-snap').classList.remove('d-none');
            });
            document.getElementById('re-snap').addEventListener('click', function() {
                document.getElementById('re-snap').classList.add('d-none');
                document.getElementById('snap').classList.remove('d-none');
                video.play();
            });

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        aspectRatio: 9 / 16
                    }
                })
                .then(function(stream) {
                    video.srcObject = stream;
                    localMediaStream = stream;
                })
                .catch(function(err) {
                    console.log('getUserMedia() error: ', err);
                });

            document.getElementById('clockInModal').addEventListener('hidden.bs.modal', function() {
                // if (localMediaStream != null) {
                //     localMediaStream.getTracks().forEach(track => track.stop());
                // }
            });
        })();
    </script>
    <?php if(Auth::user()->type == 'company' || Auth::user()->type == 'hr'): ?>
        <script type="text/javascript">
            $(document).ready(function() {
                get_data();
            });

            function get_data() {
                var calender_type = $('#calender_type :selected').val();
                console.log(calender_type);
                $('#calendar').removeClass('local_calender');
                $('#calendar').removeClass('google_calender');
                if (calender_type == undefined) {
                    calender_type = 'local_calender';
                }
                $('#calendar').addClass(calender_type);

                $.ajax({
                    url: $("#path_admin").val() + "/event/get_event_data",
                    method: "POST",
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
                        'calender_type': calender_type
                    },
                    success: function(data) {
                        (function() {
                            var etitle;
                            var etype;
                            var etypeclass;
                            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                                headerToolbar: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                                },
                                buttonText: {
                                    timeGridDay: "<?php echo e(__('Day')); ?>",
                                    timeGridWeek: "<?php echo e(__('Week')); ?>",
                                    dayGridMonth: "<?php echo e(__('Month')); ?>"
                                },
                                // slotLabelFormat: {
                                //     hour: '2-digit',
                                //     minute: '2-digit',
                                //     hour12: false,
                                // },
                                themeSystem: 'bootstrap',
                                slotDuration: '00:10:00',
                                allDaySlot: true,
                                navLinks: true,
                                droppable: true,
                                selectable: true,
                                selectMirror: true,
                                editable: true,
                                dayMaxEvents: true,
                                handleWindowResize: true,
                                events: data,
                                // height: 'auto',
                                // timeFormat: 'H(:mm)',
                            });
                            calendar.render();
                        })();
                    }
                });

            }
        </script>
    <?php else: ?>
        <script>
            $(document).ready(function() {
                get_data();
            });

            function get_data() {
                var calender_type = $('#calender_type :selected').val();
                console.log(calender_type);
                $('#event_calendar').removeClass('local_calender');
                $('#event_calendar').removeClass('google_calender');
                if (calender_type == undefined) {
                    calender_type = 'local_calender';
                }
                $('#event_calendar').addClass(calender_type);

                $.ajax({
                    url: $("#path_admin").val() + "/event/get_event_data",
                    method: "POST",
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
                        'calender_type': calender_type
                    },
                    success: function(data) {
                        (function() {
                            var etitle;
                            var etype;
                            var etypeclass;
                            var calendar = new FullCalendar.Calendar(document.getElementById(
                                'event_calendar'), {
                                headerToolbar: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                                },
                                buttonText: {
                                    timeGridDay: "<?php echo e(__('Day')); ?>",
                                    timeGridWeek: "<?php echo e(__('Week')); ?>",
                                    dayGridMonth: "<?php echo e(__('Month')); ?>"
                                },
                                // slotLabelFormat: {
                                //     hour: '2-digit',
                                //     minute: '2-digit',
                                //     hour12: false,
                                // },
                                themeSystem: 'bootstrap',
                                slotDuration: '00:10:00',
                                allDaySlot: true,
                                navLinks: true,
                                droppable: true,
                                selectable: true,
                                selectMirror: true,
                                editable: true,
                                dayMaxEvents: true,
                                handleWindowResize: true,
                                events: data,
                                // height: 'auto',
                                // timeFormat: 'H(:mm)',
                            });
                            calendar.render();
                        })();
                    }
                });

            }
        </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hrm\resources\views/dashboard/dashboard.blade.php ENDPATH**/ ?>