<?php 
namespace App\Repositories;

interface ISharedRepository{
	
	public function GetallVideosOfWeek($user);
    public function GetNumberofWeek($subscription);

	// more
}