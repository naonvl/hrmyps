<?php echo e(Form::open(['url' => 'document', 'method' => 'post'])); ?>

<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Document Name')])); ?>

                </div>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger"><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('is_mandatory', 'Dokumen Wajib', ['class' => 'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::select('is_mandatory', ['0'=>'Tidak' ,'1'=>'Ya'], null, ['class' => 'form-control select2 ','placeholder' => 'Pilih Data'])); ?>

                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('need_approval', 'Diperlukan Approval', ['class' => 'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::select('need_approval', ['0'=>'Tidak' ,'1'=>'Ya'], null, ['class' => 'form-control select2 ','placeholder' => 'Pilih Data'])); ?>

                </div>
            </div>
        </div>



    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH C:\xampp\htdocs\hrm\resources\views/document/create.blade.php ENDPATH**/ ?>