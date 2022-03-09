<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Model\User;
//use App\Helper\UserIdentity;
use App\Repositories\IUserRepository;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Mail;
use Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

   // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
   protected $redirectTo = "/subscription";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $repository;

    public function __construct(IUserRepository $repository)
    {
        parent::__construct();
       $this->repository = $repository;
       session_start();
       // $this->middleware('guest');
    }    

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
      
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:125'],
            'lastname' => ['required', 'string', 'max:125'],
            'city' => ['required', 'string', 'max:255'],
            'post_code' => ['required', 'string', 'max:8'],
            'email' => ['required', 'string', 'email', 'max:255','unique:users'],
            'password' => ['required', 'string', 'min:8' , 'confirmed'],
            ]);
            //echo "Great Man.......";die;

    }
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // public function newRegistation(){
    //     return view('auth.register');
    // }

    /**
     * Create a new user instance after a valid registration.
     * Call Function register from UserRepository
     * @param  array  $data
     * @return \App\Models\User
     */

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

       $user = $this->repository->register($request);
       
        return redirect('/')
        ->with('success','Successfully register');
//dd($user);
 
    }
    
    

}
