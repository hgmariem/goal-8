<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Model\User;
use App\Repositories\IUserRepository;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Mail;
use Session;
use Carbon\Carbon;

class UserController extends Controller
{
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

  protected function validator(array $data)
  {
      return Validator::make($data, [
          'fullname' => ['required', 'string', 'max:125'],
          'city' => ['required', 'string', 'max:255'],
          'post_code' => ['required', 'string', 'max:8'],
          'email' => ['required', 'string', 'email', 'max:255','unique:users'],
          ]);
          //echo "Great Man.......";die;

  }

    public function update_profile(Request $request)
    {
      //$this->validator($request->all())->validate();
      $User = $this->repository->update_profile($request, $request->id);
        if(!$User) {
          return response('User not found', 404);
        }
        Session::flash('message', 'Profile has been updated successfully! ');
        Session::flash('alert-class', 'alert-success alert-dismissible'); 
        return redirect()->back();
    }
    
    public function Change_password(Request $request)
    {
      $hashedPassword = Auth::user()->password;
      
      $User = $this->repository->Change_password($request, $request->id, $hashedPassword);
      
      if (!$User) {
          Session::flash('message', 'Password has been updated successfully!');
          Session::flash('alert-class', 'alert-success');
          return redirect()->back();
      } else {
          Session::flash('message', 'old password doesnt matched!');
          Session::flash('alert-class', 'alert-danger');
          return redirect()->back();
        }
    }

    public function unsubscribe_user(Request $request)
    {
        $User = $this->repository->unsubscribe_user($request, $request->id);
        if(!$User) {
          return response('User not found', 404);
        } 
        Session::flash('message', 'Profile has been updated successfully. ');
        Session::flash('alert-class', 'alert-success alert-dismissible'); 
        
        return redirect()->back();
    }

}
