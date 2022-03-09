<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
		//echo '<pre>';
		//print_r($post);
		//die;
		$this->name=$post['name'];
		$this->content=$post['content'];
		if($this->save()){
			return $this->id;
		}	
		else{
			return false;
		}
		
	}
	
	public function getAssignments(){
		$get =  Self::where(['is_delete'=>0])->get();
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
			return $get;
		}
		else{
			return false;
		}
	}
	
	public function edit_Assignment($post){
		$update=Self::find($post['id']);
		$update->name=$post['name'];
		$update->content=$post['content'];
		if($update->save()){
			return true;
		}else{
			return false;
		}
		
	}
 function delete_user($id){
		$update=Self::find($id);
		
		if($update->delete()){
			return true;
		}else{
			return false;
		}
		
	}
	
}
