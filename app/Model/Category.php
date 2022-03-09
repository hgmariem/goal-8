<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function videos()
    {
        return $this->hasMany(Video::class)
            ->published()
            ->orderBy('order', 'ASC');
    }

    public function parentId()
    {
        return $this->belongsTo(self::class);
    }
}
