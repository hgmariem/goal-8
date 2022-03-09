<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Model\Webinar;
use Auth;
use Illuminate\Pagination\Paginator;

class WebinarController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    public function index(){

		return view('webinar.index', ['var' => $get,'def'=>$get_def]);
	}

	public function admin(){
		$webinar=new Webinar();
		$webinars = Webinar::paginate(10);
		return view('webinar.admin', ['webinars' => $webinars]);
	}

	public function view($id){
		$webinar=Webinar::find($id);
		/*echo "<pre/>";
		print_r($webinar);die;*/
		return view('webinar.view', ['webinar' => $webinar]);
	}

	public function autoregister($id){
		
		$webinar=Webinar::find($id);
		return view('webinar.autoregister', ['webinar' => $webinar]);
	}
	
	public function add(Request $request){
		if($post=$request->all() && $request->isMethod('post')){
			$webinar=new Webinar();
			$webinar->name=$post['name'];
			$webinar->date=$post['date'];
			$webinar->url=$post['url'];
			$webinar->register_url=$post['register_url'];
			$webinar->groups_allowed=json_encode($post['groups_allowed']);
			if($webinar->save()){
				redirect('admin');
			}
		}
		
		$webinar=(object)array();

		return view('webinar.create', ['webinar' => $webinar]);
	}

	public function update(Request $request){
		
		$post=$request->all();
		
		if($request->isMethod('post')){
			$webinar=Webinar::where($post['id'])->first();
			$webinar->name=$post['name'];
			$webinar->date=$post['date'];
			$webinar->url=$post['url'];
			$webinar->register_url=$post['register_url'];
			$webinar->groups_allowed=json_encode($post['groups_allowed']);
			if($webinar->save()){
				redirect('admin');
			}
		}
		
		$webinar=Webinar::where($post['id'])->first();

		return view('webinar.create', ['webinar' => $webinar]);

	}

	public function delete(Request $request){
		$id=$request->input("id");
		$webinar=Webinar::find($id);
		$webinar->is_deleted=1;
		$webinar->save();

		redirect('admin');
	}
	
}