<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use App\Repositories\IUserRepository;
use App\Traits\ApiResponser;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;

class AuthController
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $repository;

    public function __construct(IUserRepository $repository)
    {

        $this->repository = $repository;
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = $this->validateLogin($request);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        // Attempt to log the user in
        $remember_me = isset($request->remember_me) ? true : false;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {

            $user = $this->repository->getUserByEmail($request->email);
            return $this->token($this->getPersonalAccessToken(),null, $user);

        } else {
            $user = $this->repository->getUserByEmail($request->email);
            if ($user) {

                if (!Hash::check($request->password, $request->password)) {
                    // Wrong Role
                    return $this->error('Wrong password!', 404);
                }
            } else {

                return $this->error('Email Not Found!', 404);
            }

        }

    }
    // pass Toke in Authorization Header Type Bearer
    /**
     * Logout api
     *
      * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        //Auth::user()->token()->revoke();
        return $this->success($request, 200);
    }

    public function getPersonalAccessToken()
    {
        if (request()->remember_me === 'true') {
            Passport::personalAccessTokensExpireIn(now()->addDays(15));
        }

        return Auth::user()->createToken('Personal Access Token');
    }

    // pass Toke in Authorization Header Type Bearer
    public function user()
    {
        
        return $this->success(Auth::user());
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validateSignup($request);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        $user = $this->repository->register($request);
        Auth::attempt(['email' => $user->email, $user->password]);
        return $this->token($this->getPersonalAccessToken(), 'User Created', 201);

    }
    /**
     * Forgot Password api
     *
     * @return \Illuminate\Http\Response
     */
    public function forgotpwd(Request $request)
    {
        $email = $request->email;

        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //get citoyen by email
            $user = $this->repository->getUserByEmail($email);
            if ($user != null) {
                do {
                    //Generate a random code.
                    $token = random_int(100000, 999999);

                    $user = User::where('user_token', '=', $token)
                        ->where('email', '=', $email)
                        ->get();
                }
                //pas de citoyen avec le meme token et mail
                while ($user == null);
                {
                    $user = $this->repository->getUserByEmail($email);
                    //dd("fdhgs");
                    User::where('email', $email)
                        ->update(['user_token' => $token]);
                    // Send Email Register which contain the link to reset password

                    Mail::send('email.resetpwdMobile', ['data' => $user, 'token' => $token], function ($message) use ($user) {
                        $message->to($user->email, 'Welcome To KeyHabits')
                            ->subject('Reset your KeyHabits Password');
                    });
                    return $this->success($user, "Check your email to reset your password!");
                }
            } else {
                return $this->error("User Not Found!", 404);
            }
        } else {
            return $this->error("Email is empty!.", 404);
        }

    }
    // Reset Password & revoke verification code
    /**
     * Reset Password api
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePwd(Request $request)
    {
        //get token from url.
        $token = request()->token;
        // dd($token);
        $user = $this->repository->getUserByToken($token);
        if ($user == null) {
            return $this->error('Veriy your code!', 419);
        }
        $this->repository->updateUserPwdByToken($token, $request->password);
        return $this->success(null, "Password reset successfully. You can login now!");
    }

    public function validateLogin($request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        return $validator;
    }
    public function validateSignup($request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:125'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return $validator;
    }
    public function validateForgotPwd($request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);
        return $validator;
    }

}
