<?php
namespace App\Model;

//use Illuminate\Database\Eloquent\Model;

class Goal2 extends Model
{
	
protected $table = 'tbl_types';


public function add_types(){
	$this->name=$post['name'];
	if($this->save()){
		return $this->id;
	}
}
 /*public function get_types(){
  $get =  Self::where(['name',$name])->get();
    if($get){
	   return $get;
     }
  }*/
}
