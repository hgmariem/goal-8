<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
	
	protected $table = 'tbl_types';
	protected $guarded = [
			'id',
    ];
}