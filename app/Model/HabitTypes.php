<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Logs;
use DB;

class HabitTypes extends Model
{
	
	protected $table = 'tbl_habit_types';

	protected $guarded = [
			'id',
		];
	
	
	
	public function add_habit_types($data){
		/*echo "<pre/>";
		print($data);die;*/
		$type=Self::where('goal_id',$data['goal_id'])->first();
		if(!$type){
			$type = new static($data);
		}
		
		$type->fill($data);
		
		$type->save();
		return $type;
	}

	public function updateHabit($id)
	{
		$habitDetails = $this->highest_lowest($id);
		
		$habitType = HabitTypes::where('goal_id',$id)->first();

		 $habitType->minimum = $habitDetails->lowest;
		 $habitType->maximum = $habitDetails->highest;
		 $habitType->save();
		 /*echo "<pre/>";
		 print_r($habitType);die;*/
	}

	public function highest_lowest($goal_id)
	{
		$result = DB::table('tbl_logs')->select([DB::raw('MAX(value) AS highest'),DB::raw('MIN(value) AS lowest')])->where('goal_id',$goal_id)->where('value','!=',-1)->first();
		/*echo "<pre/>";
		print_r($result);die;*/
		return $result;
	}

	public function getHabitLoop($id)
	{
		$result = Self::where('goal_id',$id)->first();
		return $result;
	}

	
}
