<?php 
//use Auth;
use Carbon\Carbon;
use App\Model\GoalsMeta;
use App\Model\Device;
use App\Model\Logs;

if (! function_exists('sortSelfOrder')) {
	
	function sortSelfOrder($a, $b) {
	    return $a['self_order'] - $b['self_order'];
	}

}
if (! function_exists('generatePIN')) {
	function generatePIN($digits = 4){
	    $i = 0; //counter
	    $pin = ""; //our default pin is blank.
	    while($i < $digits){
	        //generate a random number between 0 and 9.
	        $pin .= mt_rand(0, 9);
	        $i++;
	    }
	    return $pin;
	}
}

function is_admin(){
	$user=(Auth::user())?Auth::user():false;
	if($user){
		$admin_groups=Config("constants.admins");
	        
		if(in_array($user->guid, $admin_groups)){
		    return true;  
		}
	}
	return false;
}

if (! function_exists('_substr')) {
	function _substr($_str, $start=0, $end=0){
		/*$start=($_end)?$_start:0;
		$end=($_start && $_start==0)?$end:$_start;*/
		
		$str=$_str;
		//var_dump($start, $end);

		if(strlen($str)>$end){
			$str=substr($_str, $start, $end)."...";
		}
		return $str;
	}
}

if (! function_exists('tab2nbsp')) {
	function tab2nbsp($str)
	{
	    return str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $str); 
	}
}

if (! function_exists('generateDateRange')) {
	function generateDateRange(Carbon $start_date, Carbon $end_date)
	{
	    $dates = [];

	    for($date = $start_date; $date->lte($end_date); $date->addDay()) {
	        $dates[] = $date->format('Y-m-d');
	    }

	    return $dates;
	}
}

if (! function_exists('process_goal_attr')) {

	function process_goal_attr($_attrs, $key){

		$default_attr=array(array('attr'=>$key, "sheet_id"=>"default", "sheet_number"=>1, "is_active"=>1, "sheet_name"=>date("d.m.Y")));

		$response=array("attrs"=>json_encode($default_attr), "default_html"=>"");

		//print_r($attrs);

		if($_attrs && !empty($_attrs)){
			
			$datas = isset($_attrs[$key])?$_attrs[$key]:array();
			
			$default_html=isset($datas[0])?$datas[0]['html']:"";
			
			$data=array();

			foreach ($datas as $key => $_data) {
				
				if($_data['is_active']==1){
					$default_html=$_data['html'];
				}

				unset($_data['html']);
				$data[]=$_data;
			}

			$response['attrs'] = json_encode($data);

			$response['default_html'] = $default_html;
		}

		return $response;
	}
}

if (! function_exists('clean_html')) {
	
	function clean_html($html){
		
		//$html = htmlentities(addslashes(trim(preg_replace('/\s+/', ' ',  $html))));

		$html = trim(preg_replace('/\s+/', ' ',  $html));

		$html = str_replace("&quot;", "'", $html);

		return $html;
	}
}



if(! function_exists('get_prefill_status_data')){
	function get_prefill_status_data($goal_id){
		return GoalsMeta::get_prefill_status_data($goal_id);
	}
}


if(! function_exists('update_device_id')){
	function update_device_id($post){
		return Device::addDeviceId($post);
	}
}



if(! function_exists('brtop')){

	function brtop($str){

		foreach ($str as $key => $va) 
		{
			//echo "<pre/>";
			//echo htmlentities(new_str_str_str);die;
			$new_str = preg_replace('#(?:<br\s*/?>\s*?)#', '<br></p><p>', $va['html']);
			$pattern = "/<p[^>]*><\\/p[^>]*>/"; 
			$new_str_str = preg_replace($pattern, '', $new_str);
			$new_pattern = "/<\\/p[^>]*><p[^>]*><\\/li[^>]*>/"; 
			$new_str_str_str = preg_replace($new_pattern, '</li>', $new_str_str);
			
			//echo "<pre/>";
			//echo htmlentities($new_str_str_str);die;
				$new_content = "<p>".$new_str_str_str."</p>";
				$data[$key]['is_active'] = $va['is_active'];
	            $data[$key]['sheet_id'] = $va['sheet_id'];
	            $data[$key]['sheet_number'] = $va['sheet_number'];            
	            $data[$key]['sheet_name'] = $va['sheet_name'];
	            $data[$key]['html'] = htmlentities($new_content);
	            $data[$key]['attr'] = $va['attr'];
	            $data[$key]['auto_save_id'] = $va['auto_save_id'];
			
		}
		/*echo "<pre/>";
		print_r($data);die;*/
    	return $data;
	}
}


if(! function_exists('brtopForAssignment')){

	function brtopForAssignment($str){

			$new_str = preg_replace('#(?:<br\s*/?>\s*?)#', '<br></p><p>', $str);
			$pattern = "/<p[^>]*><\\/p[^>]*>/"; 
			$new_str_str = preg_replace($pattern, '', $new_str);
			$new_pattern = "/<\\/p[^>]*><p[^>]*><\\/li[^>]*>/"; 
			$new_str_str_str = preg_replace($new_pattern, '</li>', $new_str_str);
			$new_content = "<p>".$new_str_str_str."</p>";
    		return $new_content;
	}
}


function setZeroValue($habit_id,$date,$value,$is_scale,$habit_type)
{
	//echo "Reached here....";die;
	$logs = Logs::where('goal_id',$habit_id)->where('date',$date)->first();

	if(empty($logs))
	{
		$logs = new Logs();
	}

	$logs->goal_id = $habit_id;
	$logs->goal_type = 1;
	$logs->value = $value;
	$logs->date = $date;
	$logs->is_scale = $is_scale;
	$logs->save();
	return $logs;
}


function AuthenticateToken($token){
	$isValidToken = '';

	$isValidToken  = DB::table("users as u")->join('devices as d','u.guid','=','d.user_id')->where(['d.user_token'=>$token])->first();

	
	if(isset($isValidToken) && !empty($isValidToken)){

		return $isValidToken;
	}else{
		return $isValidToken;
	}
}	

function getUserDetailByToken($token){

	$isValidToken = '';

	$isValidToken  = DB::table("users as u")->join('devices as d','u.guid','=','d.user_id')->where(['d.user_token'=>$token])->first();

	if(isset($isValidToken) && !empty($isValidToken)){
		return $isValidToken;
	}else{
		return $isValidToken;
	}
}



function unique_multidim_array($array, $key, $is_object=false) {
  $temp_array = array();
  $i = 0;
  $key_array = array();
  foreach($array as $val) {
      if($is_object){
          $val = (array) $val;
      }
      if (!in_array($val[$key], $key_array)) {
          $key_array[$i] = $val[$key];
          if($is_object){
              $temp_array[$i] = (object)$val;
          }else{
              $temp_array[$i] = $val;
          }
      }
      $i++;
  }
  return $temp_array;
}


if ( ! function_exists('objToArray')) {    
    function objToArray($obj){
  if(is_object($obj)) $obj = (array) $obj;
   if(is_array($obj)) {
       $new = array();
       foreach($obj as $key => $val) {
           $new[$key] = objToArray($val);
       }
   }
   else $new = $obj;
   return $new;
}    
}


function checkUniqueToken($token){


	$isValidToken  = DB::table("users as u")->join('devices as d','u.guid','=','d.user_id')->where(['d.user_token'=>$token])->get()->toArray();

	
	if(isset($isValidToken) && !empty($isValidToken)){
		return true;
	}else{
		return false;
	}
}	




?>
