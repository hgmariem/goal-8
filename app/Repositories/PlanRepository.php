<?php

namespace App\Repositories;

// import User Model
use App\Model\Plan;

class PlanRepository implements IPlanRepository
{
    // Return all publshed plans with their related plan_items ordered by Asc normal price
    public function getPublishedPlans(){
        return Plan::where('published',1)
                     ->orderBy('normal_price')
                     ->with('items')->get();
    }
    public function getPlanById(int $id){
        return Plan::where('id', '=', $id)
                    ->with('items')
                    ->get()
                    ->first();
    }
}