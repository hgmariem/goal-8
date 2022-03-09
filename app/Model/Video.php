<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    public function category()
    {
        return $this->belongsTo(Voyager::modelClass('Category'));
    }
}
