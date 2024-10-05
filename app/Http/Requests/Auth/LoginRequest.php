<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function authenticate()
    // {
    //     $this->ensureIsNotRateLimited();

    //     if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
    //         RateLimiter::hit($this->throttleKey());

    //         throw ValidationException::withMessages([
    //             'email' => trans('auth.failed'),
    //         ]);
    //     }

    //     RateLimiter::clear($this->throttleKey());
    // }

    public function authenticate()
    {
        $user = $this->getUserByEmployeeId();

        if (!$user || !$this->checkUserStatus($user) || !$this->attemptLogin($user)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'employee_id' => __('These credentials do not match our records.'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    private function getUserByEmployeeId()
    {
        if (strpos($this->employee_id, '@') !== false) {
            $user = User::where('email', $this->employee_id)->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'employee_id' => __("Email doesn't exist."),
                ]);
            }
        } else {
            $employee = Employee::where('employee_id', $this->employee_id)->first();

            if (!$employee) {
                throw ValidationException::withMessages([
                    'employee_id' => __("Employee ID doesn't exist."),
                ]);
            }

            $user = User::find($employee->user_id);

            if (!$user) {
                throw ValidationException::withMessages([
                    'employee_id' => __("This employee ID doesn't match."),
                ]);
            }

            $this->employee_id .= '@yps.co.id';
        }

        return $user;
    }

    private function checkUserStatus($user)
    {
        if ($user->is_active != 1 || $user->is_login_enable != 1) {
            throw ValidationException::withMessages([
                'employee_id' => __('Your account is disabled from company.'),
            ]);
        }

        return true;
    }

    private function attemptLogin($user)
    {
        if (!password_verify($this->password, $user->password)) {
            throw ValidationException::withMessages([
                'employee_id' => __('Invalid password.'),
            ]);
        }

        return Auth::attempt(['email' => $this->employee_id, 'password' => $this->password, 'id' => $user->id], $this->boolean('remember'));
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')) . '|' . $this->ip();
    }
}
