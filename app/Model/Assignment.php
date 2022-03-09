<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
class Assignment extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_assignments';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];
	
	public function add_Assignment($post){
		
		$this->name=$post['name'];
		$this->user_id=$post['user_id'];
		$this->content=trim(preg_replace('/\s+/', ' ', $post['content']));
		$this->list_order=$this->get_max_list_order($post['user_id'])+1;

		if($this->save()){
			return $this->id;
		}	
		else{
			return false;
		}
	}

	public function addAssignment($post){

		if(empty($post['name']))
		{
			return false;
		}

		if(isset($post['id']) && !empty($post['id']))
		{
			$id = $post['id'];
		}
		else
		{
			$id = "";
			$data['list_order']  = $this->get_max_list_order($post['user_id'])+1;
		}
		$data['name']        = $post['name'];
		$data['user_id']     = $post['user_id'];
		$data['content']     = trim(preg_replace('/\s+/', ' ', $post['content']));
		/*echo "<pre/>";
		print_r($data);die;*/
		$assignmentDetails   = Self::updateOrCreate(["id"=>$id],$data);

		return $assignmentDetails;
		
	}
	
	public function getAssignments($user_id){
		$get=Self::where(['is_delete'=>0])->where("user_id",$user_id)->orderBy("list_order","DESC")->get();
		if($get){
			return $get;
		}
		else{
			return false;
		}
	}
	
	
	public function get_assignment_data($id){
		$get =  Self::where('id',$id)->first();
		if($get){
			/*echo "<pre/>";
			print_r($get);die;*/
			return $get;
		}
		else{
			return false;
		}
	}
	
	public function edit_Assignment($post){
		$update=Self::find($post['id']);
		$update->name=$post['name'];
		$update->content=trim(preg_replace('/\s+/', ' ', $post['content']));
		if($update->save()){
			return $update;
		}else{
			return false;
		}
		
	}
	
	public function _delete($id){
		$update=Self::find($id);
		
		if($update->delete()){
			return true;
		}else{
			return false;
		}
		
	}

	public function get_max_list_order($user_id){
		return DB::table($this->table)->where("user_id",$user_id)->max('list_order');
	}
	
}
