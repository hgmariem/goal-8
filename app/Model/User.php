<?php

namespace App\Model;
use DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];
	
	//protected $table = 'tbl_users';
    public $timestamps = true;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function checkSession($id){
	}
	
	/*public function create(){
		
	}*/

    public function get_user($id){
		$get =  Self::where('id',$id)->first();
		if($get){
			return $get;
		}
		else{
			return false;
		}
	}
    public static function getAllUsers(){
      $result =  DB::table('users as u')
            ->join('devices as d', 'd.user_id', '=', 'u.guid')
            ->select('u.*', 'd.firebase_token','d.device_id','d.device_type', 'd.user_token')
            ->whereNotNull('d.firebase_token')
            ->get();
            return $result;

    }
   
    public static function getAll(){
        $result =  DB::table('users as u')
              ->get();
              return $result;
  
      }
   
}
