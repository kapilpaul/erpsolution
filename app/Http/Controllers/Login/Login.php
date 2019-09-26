<?php

namespace App\Http\Controllers\Login;

use App\Models\User\User;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use GuzzleHttp\Client;
use Sentinel;
use App\Http\Requests\loginUserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class Login extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        $this->default();
        return view('auth.login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function postLogin(Request $request)
    {
        if ($request->is('api/*')) {
            if ($validation = $this->customRules($request)) {
                return $validation;
            }
        } else {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);
        }

        try {
            $this->default();

            $remember_me = false;
            if (isset($request->remember_me))
                $remember_me = true;

            if (Sentinel::authenticate($request->all(), $remember_me)) {
                if (Sentinel::inRole('admin') || Sentinel::inRole('user')) {
                    $http = new Client;

                    $response = $http->post(
                        url('oauth/token'), [
                            'form_params' => [
                                'grant_type' => 'password',
                                'client_id' => 2,
                                'client_secret' => '1FpF2emSaQKzAihsEPdOxjlmkSyYmDVXzbT9keph', //'lhRa8FMoyVkpTIeOZPgbdbJrMINZlz8nGXi31vPh',
                                'username' => $request->email,
                                'password' => $request->password,
                                'scope' => '',
                            ],
                        ]
                    );
                    $token = json_decode((string)$response->getBody());

                    if ($request->is('api/*')) {
                        return response()->json([
                            'success' => true,
                            'access_token' => $token->access_token,
                            'expiration' => $token->expires_in
                        ], 200);
                    }

                    return redirect()->route('dashboard')->with([
                        'access_token' => $token->access_token,
                        'expiration' => $token->expires_in
                    ]);
                } else {
                    return redirect()->route('login');
                }
            }

            if ($request->is('api/*')) {
                return response()->json(['error' => true, 'message' => 'Wrong Credentials'], 200);
            }

            return redirect()->back()->with(['error' => 'Wrong Credentials']);
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();

            if ($request->is('api/*')) {
                return response()->json(['error' => true, 'message' => 'You are banned for $delay seconds'], 200);
            }

            return redirect()->back()->with(['error' => "You are banned for $delay seconds"]);
        } catch (NotActivatedException $e) {
            if ($request->is('api/*')) {
                return response()->json(['error' => true, 'message' => 'Your account is not activated yet.'], 200);
            }

            return redirect()->back()->with(['error' => "Your account is not activated yet."]);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Sentinel::logout();

        return redirect()->route('login');
    }

    /**
     *
     */
    public function default()
    {
        $roles = Sentinel::getRoleRepository()->get();

        if (count($roles) == 0) {
            $input['slug'] = 'admin';
            $input['name'] = 'Admin';

            Sentinel::getRoleRepository()->createModel()->create($input);

            $input['slug'] = 'user';
            $input['name'] = 'User';
            Sentinel::getRoleRepository()->createModel()->create($input);
        }

        $kapil = User::where('email', "info@kapilpaul.me")->first();

        if (!$kapil) {
            $kapilData['first_name'] = 'Kapil';
            $kapilData['last_name'] = 'Paul';
            $kapilData['email'] = 'info@kapilpaul.me';
            $kapilData['password'] = 'nothing1234';

            $role = Sentinel::findRoleBySlug('admin');

            $user = Sentinel::registerAndActivate($kapilData);

            $role->users()->attach($user);
        }
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customRules($request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 500);
        }
    }
}
