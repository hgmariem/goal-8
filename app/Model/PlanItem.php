<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlanItem extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'plan_items';
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

}
