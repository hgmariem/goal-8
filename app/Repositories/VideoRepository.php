<?php

namespace App\Repositories;

// import User Model
use App\Model\Category;

class VideoRepository implements IVideoRepository
{
    // Return all video categories  with their related videos ordered by Asc video_order
    public function getPublishedVideos(){
        return Category::where('published',1)
                     ->orderBy('order')
                     ->with('videos')->get();
    }
    public function getVideoById(int $id){

    }
}