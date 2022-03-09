<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Def_goals extends Model
{
	
protected $table = 'tbl_default_goals';

protected $guarded = [
        'id',
    ];
	
	 public function add_Def_goals($post){
	  
	   $this->name=$post['name'];
	   $this->due_date=date("y/m/d",strtotime($post['due_date']));
	   $this->habit_start_date=date("y/m/d",strtotime($post['habit_start_date']));
	   $this->status=$post['status'];
	   $this->improvement=$post['improvement'];
	   $this->risk=$post['risk'];
	   $this->benefits=$post['benefits'];
	   $this->vision=$post['vision'];
	   $this->vision_decades=$post['vision_decades'];
	   $this->barriers=$post['barriers'];
	   $this->priority=$post['priority'];
	   $this->initiative=$post['initiative'];
	   $this->help=$post['help'];
	   $this->support=$post['support'];
	   $this->environment=$post['environment'];
	   $this->imagery=$post['imagery'];
	   
	  
	   if($this->save()){
		   return $this->id;
		   
	   }else{
		   return false;
	   } 
    }
	   
	   
	   
	 
	public function get_Def_goals(){
		$get =  Self::where(['is_delete'=>0])->get();
		
		if($get){
			return $get;
		}
		else{
			return false;
		}
	} 
	// Get Gaals by Id
	public function get_by_id($id){
		$get =  Self::where('id',$id)->first();
		
		if($get){
			return $get;
		}
		else{
			return false;
		}
	}

	public function edit_Def_goals($post){
	   
	   $update=Self::find($post['id']);
	   $update->name=$post['name'];
	   $update->due_date=date("y/m/d",strtotime($post['due_date']));
	   $update->habit_start_date=date("y/m/d",strtotime($post['habit_start_date']));
	   $update->status=$post['status'];
	   $update->improvement=$post['improvement'];
	   $update->risk=$post['risk'];
	   $update->benefits=$post['benefits'];
	   $update->vision=$post['vision'];
	   $update->vision_decades=$post['vision_decades'];
	   $update->barriers=$post['barriers'];
	   $update->priority=$post['priority'];
	   $update->initiative=$post['initiative'];
	   $update->help=$post['help'];
	   $update->support=$post['support'];
	   $update->environment=$post['environment'];
	   $update->imagery=$post['imagery'];
	  
	   
		if($update->save()){
			return true;
		}else{
			return false;
		}
	}
	
	public function delete_def_goals($id){
		$update=Self::find($id);
		
		if($update->delete()){
			return true;
		}else{
			return false;
		}
	}	
	    
	   
}	