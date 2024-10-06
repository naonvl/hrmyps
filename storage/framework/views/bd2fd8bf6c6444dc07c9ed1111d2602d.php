<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Document')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Document')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-style'); ?>
    <style>
        .dataTable-top {
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
                            <th>No. </th>
                            <th><?php echo e(__('Name')); ?></th>
                            <th><?php echo e(__('Document')); ?></th>
                            <th><?php echo e(__('Description')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                            <th><?php echo e(__('Notes')); ?></th>
                            <th width="200px"><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo e(__('Reject Document')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="rejectForm">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-12">
                                <textarea name="notes" id="notes" cols="30" rows="5" class="form-control"
                                    placeholder="Alasan Penolakan"></textarea>
                            </div>
                            <input type="hidden" name="id" id="rejectId">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                    <button type="button" class="btn btn-primary rejectButton"><?php echo e(__('Reject')); ?></button>
                </div>
            </div>
        </div>
    </div>
    <script></script>

    <script></script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/main.min.js')); ?>"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            const canDelete = $('#can-delete-document').val();
            const canEdit = $('#can-update-document').val();
            const userType = $('#user-type').val();
            let datatable = null;

            const documentPath = '<?php echo e(\App\Models\Utility::get_file('uploads/documentUpload')); ?>';

            const columns = [{
                    data: 'id',
                    render: (data, type, row, meta) =>
                        `<span>${meta.row + 1 + meta.settings._iDisplayStart}</span>`
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'document',
                    render: (data, type, row) => renderDocumentActions(row)
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'status',
                    render: (data, type, row) => renderStatusBadge(row.status),
                    name: 'description'
                },
                {
                    data: 'notes',
                    name: 'notes'
                },
                {
                    data: 'action',
                    render: (data, type, row) => renderActionButtons(row),
                    name: 'action'
                }
            ];

            function renderDocumentActions(row) {
                return `
                    <div class="action-btn bg-primary ms-2">
                        <a class="mx-3 btn btn-sm align-items-center" href="${documentPath}/${row.document}">
                            <i class="ti ti-download text-white"></i>
                        </a>
                    </div>
                    <div class="action-btn bg-secondary ms-2">
                        <a class="mx-3 btn btn-sm align-items-center" href="${documentPath}/${row.document}" target="_blank">
                            <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Preview')); ?>"></i>
                        </a>
                    </div>`;
            }

            function renderStatusBadge(status) {
                switch (status) {
                    case 'approved':
                        return `<span class="capitalize badge bg-success p-2 px-3 rounded status-badge7">${status}</span>`;
                    case 'pending':
                        return `<span class="capitalize badge bg-warning p-2 px-3 rounded status-badge7">${status}</span>`;
                    case 'rejected':
                        return `<span class="capitalize badge bg-danger p-2 px-3 rounded status-badge7">${status}</span>`;
                    default:
                        return '';
                }
            }

            function renderActionButtons(row) {
                if (userType === 'hr') {
                    return renderHrActions(row.id);
                } else {
                    let actionButtons = '';
                    if (row.status !== 'approved') {
                        if (canEdit) actionButtons += renderEditButton();
                        if (canDelete) actionButtons += renderDeleteButton();
                    }
                    return actionButtons;
                }
            }

            function renderHrActions(rowId) {
                return `
                    <div class="action-btn bg-info ms-2">
                        <button data-id="${rowId}"  class="approveDocument mx-3 btn btn-sm align-items-center">
                            <i class="ti ti-check text-white"></i>
                        </button>
                    </div>
                    <div class="action-btn bg-danger ms-2">
                        <button type="button" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="modal"
                            data-bs-target="#rejectModal" data-row-id="${rowId}" title="" data-title="<?php echo e(__('Reject Document')); ?>"
                            data-bs-original-title="<?php echo e(__('Reject')); ?>">
                            <i class="ti ti-x text-white"></i>
                        </button>
                    </div>`;
            }

            function renderEditButton() {
                return `
                    <div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="" data-ajax-popup="true"
                            data-size="md" data-bs-toggle="tooltip" title="" data-title="<?php echo e(__('Edit Document')); ?>"
                            data-bs-original-title="<?php echo e(__('Edit')); ?>">
                            <i class="ti ti-pencil text-white"></i>
                        </a>
                    </div>`;
            }

            function renderDeleteButton() {
                return `
                    <div class="action-btn bg-danger ms-2">
                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="" data-ajax-popup="true"
                            data-size="md" data-bs-toggle="tooltip" title="" data-title="<?php echo e(__('Delete Document')); ?>"
                            data-bs-original-title="<?php echo e(__('Delete')); ?>">
                            <i class="ti ti-trash text-white"></i>
                        </a>
                    </div>`;
            }

            $('#rejectModal').on('show.bs.modal', (event) => {
                const rowId = $(event.relatedTarget).data('row-id');
                $('#rejectId').val(rowId);
            });

            $(document).on('click', '.rejectButton', () => {
                rejectDocument();
            });
            $(document).on('click', '.approveDocument', (e) => {
                const id = $(e.currentTarget).data('id');
                approveDocument(id)
            });

            function approveDocument(id) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('document-upload.approve')); ?>",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            datatable.ajax.reload(null, false);
                        } else {}
                    },
                    error: function(error) {
                        // showToastr('error', error.responseJSON.message);
                    }
                });
            }

            function rejectDocument() {
                const formData = $('#rejectForm').serialize();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('document-upload.reject')); ?>",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#rejectModal').modal('hide');
                            datatable.ajax.reload(null, false);
                        } else {}
                    },
                    error: function(error) {}
                });
            }

            $('.dataTable-bottom').remove();
            datatable = initializeDataTable('pc-dt-simple', "<?php echo e(route('document-upload.list')); ?>", columns);

            function initializeDataTable(tableId, url, columns) {
                return $('#' + tableId).DataTable({
                    order: [
                        [0, 'desc']
                    ],
                    scrollX: true,
                    autoWidth: true,
                    columns: columns,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: url,
                        type: 'GET',
                        error: function(error) {
                            console.error('Error fetching data:', error);
                        },
                        data: params => ({
                            ...params,
                            dt_response: 1
                        }),
                        xhr: function() {
                            const xhr = new window.XMLHttpRequest();
                            xhr.addEventListener('load', () => JSON.parse(xhr.responseText));
                            return xhr;
                        }
                    }
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\hrm\resources\views/documentUpload/index.blade.php ENDPATH**/ ?>