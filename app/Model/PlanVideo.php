<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlanVideo extends Model
{

  protected $table = 'plan_videos';
  protected $fillable = [
    'item_id','video_id','display_order'
   ];


}