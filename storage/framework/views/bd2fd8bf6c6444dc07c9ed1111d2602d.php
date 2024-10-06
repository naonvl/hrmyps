<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Document')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Document')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-style'); ?>
<style>
    .dataTable-top{
        display: none !important;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Document')): ?>
        <a href="#" data-url="<?php echo e(route('document-upload.create')); ?>" data-ajax-popup="true" data-title="Upload Dokumen"
            data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="<?php echo e(__('Create')); ?>">
            <i class="ti ti-plus"></i>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-xl-12">
        <div class="card">
            <input type="hidden" id="user-type" value="<?php echo e(\Auth::user()->type); ?>">
            <input type="hidden" id="can-delete-document" value="<?php echo e(Gate::check('Delete Document') ? 'true' : 'false'); ?>">
            <input type="hidden" id="can-update-document" value="<?php echo e(Gate::check('Edit Document') ? 'true' : 'false'); ?>">
            <div class="card-body p-4">
                <table class="datatable" id="pc-dt-simple">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Document')); ?></th>
                            <th><?php echo e(__('Description')); ?></th>
                            <th width="200px"><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/main.min.js')); ?>"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            const canDelete = $('#can-delete-document').val();
            const canEdit = $('#can-update-document').val();
            const userType = $('#user-type').val();
            $('.dataTable-bottom').remove();
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '<?php echo e(route('document-upload.list')); ?>', // Assuming you're using a route to fetch data
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'document',
                        render: function(data, type, row) {
                            let documentPath =
                                '<?php echo e(\App\Models\Utility::get_file('uploads/documentUpload')); ?>';
                            return `<div class="action-btn bg-primary ms-2">
                                    <a class="mx-3 btn btn-sm align-items-center"
                                        href="${documentPath}/${row.document}">
                                        <i class="ti ti-download text-white"></i>
                                    </a>
                                </div>
                                <div class="action-btn bg-secondary ms-2">
                                    <a class="mx-3 btn btn-sm align-items-center"
                                        href="${documentPath}/${row.document}" target="_blank">
                                        <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip"
                                            data-bs-original-title="<?php echo e(__('Preview')); ?>"></i>
                                    </a>
                                </div>`;
                        },
                        name: 'document'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        render: function(data, type, row) {
                            if (userType == 'hr') {
                                return `<div class="action-btn bg-info ms-2">
                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                    data-url=""
                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                    title="" data-title="<?php echo e(__('Approve Document')); ?>"
                                    data-bs-original-title="<?php echo e(__('Approve')); ?>">
                                    <i class="ti ti-check text-white"></i>
                                </a>
                            </div>
                            <div class="action-btn bg-danger ms-2">
                                <button type="button" class="mx-3 btn btn-sm  align-items-center"
                                    data-bs-toggle="modal" data-bs-target="#rejectModal" title=""
                                    data-title="<?php echo e(__('Reject Document')); ?>"
                                    data-bs-original-title="<?php echo e(__('Reject')); ?>">
                                    <i class="ti ti-x text-white"></i>
                                </button>
                            </div>`;
                            } else {
                                let actionBtn = '';
                                if (canEdit) {
                                    actionBtn += `<div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-url=""
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="<?php echo e(__('Edit Document')); ?>"
                                                            data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>`;
                                }

                                if (canDelete) {
                                    actionBtn += `<div class="action-btn bg-danger ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-url=""
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="<?php echo e(__('Delete Document')); ?>"
                                                            data-bs-original-title="<?php echo e(__('Delete')); ?>">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                    </div>`;
                                }

                                return actionBtn;
                            }

                        },
                        name: 'action',
                    }
                ]
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hrm\resources\views/documentUpload/index.blade.php ENDPATH**/ ?>