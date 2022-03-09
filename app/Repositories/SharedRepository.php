<?php

namespace App\Repositories;

// import User Model
use App\Model\Plan;
use App\Model\PlanVideo;
use App\Model\User_Subscription;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SharedRepository implements ISharedRepository
{

    // Return video list for the current week
    public function GetallVideosOfWeek($user)
    {
        //   return new collection();

        $subscription = User_Subscription::where('id', $user->lastsubscription_id)->get()->first();
        if ($subscription) {
           
            $plan = Plan::where('id', $subscription->plan_id)->with('items')->get()->first();
           
            if ($plan) {
                $currentWeek = $this->GetNumberofWeek($subscription);
                // dd($currentWeek);
                //id_categ == item_id
                
                // Get Video List By item
                /*
                $allSharedVideos = PlanVideo::where('plan_id',$plan->id)
                ->orderBy('displayorder')
                ->get();
                $NbrOfVideos = count($plan->items);

                $allSharedVideos = $allSharedVideos->where('displayorder','>=',$currentWeek)->take($NbrOfVideos); */

                return $this->GetPlanVideoByItem($plan->items, $currentWeek);
                //->take($diff);
            }
        }
        return $sharedVideos = PlanVideo::where('plan_id', 0)
            ->orderBy('displayorder')
            ->get();

    }

    public function GetNumberofWeek($subscription)
    {
        /*  $subscriptionFromDate = Carbon::parse($subscription->subscription_from_date);
        $subscriptionTillDate = Carbon::parse($subscription->subscription_till_date);
        $NbrWeeksOfSubscription = (int)($subscriptionTillDate->diffInDays($subscriptionFromDate))/7;
         */
        // Specify the start date
        $date1 = Carbon::parse($subscription->subscription_from_date);
        // Specify the end date
        $now = Carbon::parse(Carbon::now()->format('Y-m-d'));
        // Get number of days from subscription from_date to now
        // here we will now the number of the current week
        // exemple if $nbrPassedDays <= 7 , $currentWeek ==1  else /7
        $nbrPassedDays = ($now->diffInDays($date1));
        // dd("passed",$nbrPassedDays);
        if ($nbrPassedDays <= 7) {
            // week 1
            $currentWeek = 1;
        } else {

            $currentWeek = (int) ($nbrPassedDays / 7);
        }

        return $currentWeek;
    }

    public function GetPlanVideoByItem(Collection $items, int $currentWeek)
    {
        //dd($items);
      
        $planVideos = new Collection(new PlanVideo);

        //$collection = new Collection([]);
        foreach ($items as $item) {

            //select from video
            $planVideo = PlanVideo::where('item_id', '=', $item->id)
                                    ->where('displayorder','=',$currentWeek)
                                    ->get()->first();
            
                $planVideos->add($planVideo);

            //$collection->push($videoElement->title, $videoElement->video_url );

        }
     //   dd($planVideos);
        return ($planVideos);
    }
}
