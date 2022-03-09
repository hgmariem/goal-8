<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{

    public function items()
    {
        return $this->hasMany(PlanItem::class);
    }

}
