{{ Form::open(['url' => 'document', 'method' => 'post']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Document Name')]) }}
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('is_mandatory', 'Dokumen Wajib', ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::select('is_mandatory', ['0'=>'Tidak' ,'1'=>'Ya'], null, ['class' => 'form-control select2 ','placeholder' => 'Pilih Data']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('need_approval', 'Diperlukan Approval', ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::select('need_approval', ['0'=>'Tidak' ,'1'=>'Ya'], null, ['class' => 'form-control select2 ','placeholder' => 'Pilih Data']) }}
                </div>
            </div>
        </div>



    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
