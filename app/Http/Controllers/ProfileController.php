<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\User;
use App\Repositories\IUserRepository;
use App\Model\Goals;
use Auth;
use Config;
use Carbon\Carbon;

class ProfileController extends Controller{
	
	public function __construct(IUserRepository $repository)
    {
         
        $this->middleware('auth');
        parent::__construct();
		$this->repository = $repository;
    }


    public function index(Request $request){
		$user = $this->repository->getUserByParam('id',Auth::user()->id);
		//dd($user);
		// lsat user subscription
		$reminder = $request->instance()->query('reminder');
		return view('profile.index',[
			'user'=> $user,
			"reminder" => $reminder
		]);
	}

}