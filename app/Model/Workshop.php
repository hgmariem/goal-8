<?php
namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
	
	protected $table = 'tbl_workshop';
	protected $guarded = [
			'id',
    ];


    public function getAllWorkshop($user_id){
      $till_date = date('Y-m-d', strtotime('-60 days'));
      $current_date = date("Y-m-d");
    	$result = self::where('user_id',$user_id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),">=",$till_date)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),"<=",$current_date)->where('is_deleted',0)->offset(0)->limit(10)->get();
    	return $result;
    }


    public function getAllOlderWorkshop($user_id){
      $till_date = date('Y-m-d', strtotime('-60 days'));
      $current_date = date("Y-m-d");
      $result = self::where('user_id',$user_id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),"<=",$till_date)->where('is_deleted',0)->offset(0)->limit(10)->get();
      return $result;

    }


    public static function getAfterCurrentDateWorkshop(){
      $current_date = date("Y-m-d");
      $result = self::where(DB::raw("(DATE_FORMAT(date,'%Y-%m-%d'))"),">=",$current_date)->where('is_deleted',0)->get();
      return $result;
    }


    public function addWorkshop($post){
      $result = Workshop::updateOrCreate(['id' => $post['id'],'user_id'=>$post['user_id']],$post);
      return $result;
    }

    public function deleteWorkshop($id){
      $data = array("is_deleted"=>1);
      $result = Workshop::where("id",$id)->update($data);
      return $result;
    }
}