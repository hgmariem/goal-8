<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Repositories\IUserRepository;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;
use Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;
    

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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $key => $value) {

                return redirect('/')->with('error', $value[0]);

            }
        }
	  return	$this->repository->login($request);

    }

    public function login(Request $request)
    {
        if ($post = $request->all()) {
            //dd($post);
            if (isset($post['email']) && !empty($post['email']) && isset($post['password']) && !empty($post['password'])) {

                $this->postLogin($request);

            } else {
                return redirect('/')->with('error', 'Email OR Password is Empty.');
            }
        }

        $credentials = isset($_COOKIE['credentials']) ? json_decode($_COOKIE['credentials']) : array();
        //dd($credentials);
        return view('auth.login', ['credentials' => $credentials]);
    }
    public function logout()
    {

       return $this->repository->logout();
    }

}
