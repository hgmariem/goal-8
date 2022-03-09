<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
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
	public function userdata(){
		$trophs= DB::table('tbl_trophy')->select('*')->orderBy('trophy_date','DESC')->get();
		if(isset($trophs) && !empty($trophs)){
			$data_lists=array();
			foreach($trophs as $troph){
				$year=substr($troph->trophy_date,0,4);
				$month=substr($troph->trophy_date,5,2);
				$data_lists[$year][$month][]=$this->get_trophy_data_by_year($troph->trophy_date);
			}
			return $data_lists;
		}
	}
	
	public function get_trophy_data_by_year($trophy_date){
		$trophy_dates=substr($trophy_date,0,4);
		$trophs=  DB::table('tbl_trophy as trf')->where('trf.trophy_date','LIKE','%'.$trophy_date.'%')->select('*')->get();
		return $trophs;
		
	}
	public function get_trophy_data($id){
		$get =  Self::where('id',$id)->first();
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