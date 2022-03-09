<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Repositories\IUserRepository;
use Illuminate\Http\Request;
use Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IUserRepository $repository)
    {
        parent::__construct();
		    $this->repository = $repository;
        $this->middleware('guest')->except('logout');
    }

    public function showforgotpwd(){
        return view('auth.forgot');
    }
    public function showUpdatePassword(Request $request)
    {
        //Generate a random string.
        $token = request()->segment(2);
        $user = User::where('user_token', '=', $token)
            ->get()
            ->first();

        if ($user == null) {
            abort(419, 'Page Expirée');
        } else {
            return view('auth.passwords.reset');
        }

    }
    public function forgotpwd(Request $request){

        return $this->repository->forgotPwd($request->email);
    }
    public function updatePwd(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8' , 'confirmed'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $value) {

                return back()->with('error', $value[0]);

            }
        }

        return $this->repository->updatePwd($request);
    }

}
