@extends('layouts.auth')
@section('page-title')
    {{ __('Login') }}
@endsection
@php
    $logos = \App\Models\Utility::get_file('uploads/logo/');

    $logo = Utility::get_superadmin_logo();

@endphp
@section('content')
    <div class="card-body">
        <img src="{{ $logos . '/' . (isset($logo) && !empty($logo) ? $logo . '?' . time() : 'logo-dark.png' . '?' . time()) }}"
            class="logo mx-auto" alt="{{ config('app.name', 'HRM-YPS') }}" alt="logo" loading="lazy"
            style="max-height: 50px;" />
            <h2 class="my-3 f-w-600 text-center">{{ __('Login') }}</h2>
        <div class="custom-login-form">
            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label">NIK</label>
                    <input id="employee_id" type="text" class="form-control  @error('employee_id') is-invalid @enderror"
                        name="employee_id" placeholder="NIK" required autofocus>
                    @error('employee_id')
                        <span class="error invalid-email text-danger" role="alert">
                            <small>{{ $message }}</small>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3 pss-field">
                    <label class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror"
                        name="password" placeholder="{{ __('Password') }}" required>
                    @error('password')
                        <span class="error invalid-password text-danger" role="alert">
                            <small>{{ $message }}</small>
                        </span>
                    @enderror
                </div>
                {{-- <div class="form-group mb-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    @if (Route::has('password.request'))
                        <span>
                            <a href="{{ route('password.request', $lang) }}" tabindex="0">{{ __('Forgot Your Password?') }}</a>
                        </span>
                    @endif
                </div>
            </div> --}}
                <div class="d-grid">
                    <button class="btn btn-primary mt-2" type="submit">
                        {{ __('Login') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ asset('custom/libs/jquery/dist/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".form_data").submit(function(e) {
                $(".login_button").attr("disabled", true);
                return true;
            });
        });
    </script>
    @if (isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endpush
