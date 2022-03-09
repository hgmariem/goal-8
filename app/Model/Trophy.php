<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;

class Trophy extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_trophy';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

	public function getAllTrophies($filter=array()){

		$trophs= DB::table('tbl_trophy')->select('*')->where("user_id",$filter['user_id'])->where("deleted",0)->orderBy('trophy_date','DESC')->get();
		
		// echo "<pre/>";
		// print_r($trophs);die;
		if(isset($trophs) && !empty($trophs)){
			$data_lists=array();
			foreach($trophs as $troph){
				$year=Carbon::parse($troph->trophy_date)->format("Y");
				$month=Carbon::parse($troph->trophy_date)->format("m");
				$data_lists[$year][$month][]=$troph;
			}

			return $data_lists;
		}
	}

	public function getAllTro($filter=array()){

		$trophs= DB::table('tbl_trophy')->select('*')->where("user_id",$filter['user_id'])->where("deleted",0)->orderBy('trophy_date','DESC')->get();
		
		if(isset($trophs) && !empty($trophs)){
			$data_lists=array();
			foreach($trophs as $troph){
				$year=Carbon::parse($troph->trophy_date)->format("Y");
				$month=Carbon::parse($troph->trophy_date)->format("m");
				$data_lists[$year][$month][]=$troph;
			}
			
			return $data_lists;
		}
	}
	
	public function add($data){

		if(!$trophy=Self::where("item_id", $data['item_id'])->first()){
			$trophy= new Trophy();
		}

		$trophy->name=$data['name'];
		$trophy->item_id=$data['item_id'];
		$trophy->trophy_date=Carbon::now()->format("Y-m-d");
		$trophy->user_id=$data['user_id'];
		$trophy->save();
		return $trophy;
	}

	public function get_trophy_data_by_year($trophy_date){
		$trophy_dates=substr($trophy_date,0,4);
		$trophs=  DB::table('tbl_trophy as trf')->where("deleted",0)->where('trf.trophy_date','LIKE','%'.$trophy_date.'%')->select('*')->get();
		return $trophs;
		
	}
	public function get_trophy_data($id){
		$get =  Self::where('id',$id)->where("deleted",0)->first();
		if($get){
			return $get;
		}
		else{
			return false;
		}
	}
	public function edit_trophy(){
		$update=Self::find($post['id']);
		$update->name=$post['name'];
		$update->date_change=$post['trophy_date'];
		if($update->save()){
			return true;
		}else{
			return false;
		}
	}
	
	
}