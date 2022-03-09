<?php 
namespace App\Repositories;

interface IVideoRepository{
    
    public function getPublishedVideos();
    public function getVideoById(int $id);
}