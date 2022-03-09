<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goals;
use App\Helper\UserIdentity;
//use Auth;
use Validator;
class SharedController extends Controller {
    
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $repository;

    public function __construct(IsharedRepository $repository)
    {
        parent::__construct();
       $this->repository = $repository;
       // $this->middleware('guest');
    }  



    public function getReceivedInfo(Request $request)
    {
        $sharedVideos = $this->repository->GetallVideosOfWeek( $request['oauth_token']->user_id);
    	return $this->success($sharedVideos, 'Shared videos fetched successfully!');
    }
}
