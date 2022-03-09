<?php 
namespace App\Repositories;

interface IPlanRepository{
    public function getPublishedPlans();
    public function getPlanById(int $id);
}