<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Config;
use Carbon\Carbon;

class Logs extends Model
{
	
	protected $table = 'tbl_logs';

	protected $guarded = [
        'id',
    ];

}
