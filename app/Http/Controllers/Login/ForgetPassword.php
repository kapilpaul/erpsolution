<?php

namespace App\Http\Controllers\Login;

use App\Models\User\User;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Illuminate\Support\Facades\Mail;
use Sentinel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ForgetPasswordController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forgotPasword()
    {
        return view('auth.forgotpassword');
    }

    /**
     * Post Forgot Password
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postForgotPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (count($user) == 0) {
            if ($request->is('api/*')) {
                return response()->json(['success' => true, 'message' => 'Reset Code has been sent'], 200);
            }
            return redirect()->back()->with(['success' => 'Reset Code has been sent.']);
        }

        $reminder = Reminder::exists($user) ?: Reminder::create($user);

        $this->sendEmail($user, $reminder->code);

        if ($request->is('api/*')) {
            return response()->json(['success' => true, 'message' => 'Reset Code has been sent'], 200);
        }

        return redirect()->back()->with(['success' => 'Reset Code has been sent.']);

    }

    /**
     * Reset Password
     * @param $email
     * @param $reset_code
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function resetPassword($email, $reset_code)
    {

        $user = User::whereEmail($email)->first();

        if (count($user) == 0) {
            abort(404);
        }

        if ($reminder = Reminder::exists($user)) {
            if ($reset_code == $reminder->code) {
                return view('auth.resetPassword', compact('user', 'reminder'));
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * Post Reset Password
     * @param Request $request
     * @param $email
     * @param $reset_code
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postResetPassword(Request $request, $email, $reset_code)
    {

        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);

        $user = User::whereEmail($email)->first();

        if (count($user) == 0) {
            abort(404);
        }

        if ($reminder = Reminder::exists($user)) {
            if ($reset_code == $reminder->code) {
                Reminder::complete($user, $reset_code, $request->password);

                return redirect('/login')->with(['success' => 'Login with your New Password']);
            } else {
                return redirect('/');
            }
        } else {
            return redirect('/');
        }
    }

    /**
     * Sending Email to User
     * @param $user
     * @param $code
     */
    public function sendEmail($user, $code)
    {
        $mail_data = [
            'user' => $user,
            'code' => $code,
        ];

        Mail::send('email.forgotPassword', $mail_data, function ($message) use ($user) {
            $message->to($user->email)->subject("$user->name reset your passowrd");
        });
    }
}
