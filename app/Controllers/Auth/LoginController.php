<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override the login method to add reCAPTCHA validation
     */
    public function login(Request $request)
    {
        // Validate login + reCAPTCHA
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => 'Captcha verification failed, please try again.',
        ]);

        return $this->attemptLoginAndRespond($request);
    }

    /**
     * Handles login attempt and redirects
     */
    protected function attemptLoginAndRespond(Request $request)
    {
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If login failed
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Custom sendLoginResponse to handle cookies and redirect based on roles
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if (getLoggedInUser()->email_verified_at == null) {
            $userEmail = getLoggedInUser()->email;
            auth()->logout();
            Flash::error(__('messages.verification.verify_your_email_address'));

            return redirect('login');
        }

        if ($request->user()->hasRole('Admin')) {
            $this->redirectTo = 'dashboard';
        } else {
            if ($request->user()->hasRole(['Receptionist'])) {
                $this->redirectTo = 'reports';
            } elseif ($request->user()->hasRole(['Doctor', 'Case Manager', 'Lab Technician', 'Pharmacist'])) {
                $this->redirectTo = 'employee/doctor';
            } elseif ($request->user()->hasRole(['Patient'])) {
                $this->redirectTo = 'patient/my-cases';
            } elseif ($request->user()->hasRole(['Nurse'])) {
                $this->redirectTo = 'bed-types';
            } elseif ($request->user()->hasRole(['Accountant'])) {
                $this->redirectTo = 'item-categories';
            } else {
                $this->redirectTo = 'employee/notice-board';
            }
        }

        if (! isset($request->remember)) {
            return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath())
                    ->withCookie(Cookie::forget('email'))
                    ->withCookie(Cookie::forget('password'))
                    ->withCookie(Cookie::forget('remember'));
        }

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath())
                ->withCookie(Cookie::make('email', $request->email, 3600))
                ->withCookie(Cookie::make('password', $request->password, 3600))
                ->withCookie(Cookie::make('remember', 1, 3600));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/login');
    }
}
