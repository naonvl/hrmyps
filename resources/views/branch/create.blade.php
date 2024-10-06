{{ Form::open(['url' => 'branch', 'method' => 'post']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Branch Name')]) }}
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('branch_start_time', __('Branch Start Time'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::time('branch_start_time', null, ['class' => 'form-control timepicker_format']) }}
                </div>
                @error('branch_start_time')
                    <span class="invalid-branch-start-time" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('branch_end_time', __('Branch End Time'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::time('branch_end_time', null, ['class' => 'form-control timepicker_format']) }}
                </div>
                @error('branch_end_time')
                    <span class="invalid-branch-end-time" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
