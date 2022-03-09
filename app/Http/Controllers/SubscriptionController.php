<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\User_Subscription;
use App\Model\Plan;
use App\Repositories\IPlanRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Mail;
use Session;
use Config;
use Cookie;
use DB;
use Auth;
use Carbon\Carbon;
class SubscriptionController extends Controller
{

    public function __construct(IPlanRepository $repository)
    {
        session_start();
        parent::__construct();
        $this->repository = $repository;
       // $this->middleware('guest');
    }


    public function subscription(){

        return view('subscription.subscription',["plans" => $this->repository->getPublishedPlans()]);
    }

    public function payment(Request $request){
 

        if(Auth::check()){
            $choose_plan = array();
            $post = $request->all();
           
            if(isset($post['plan_id']) && !empty($post['plan_id'])){
            $_SESSION['plan_id'] = $post['plan_id'];
            
            $choose_plan = $this->repository->getPlanById($post['plan_id']);
            //dd($choose_plan);
            if(isset($choose_plan) && !empty($choose_plan)){
                
                $amount_plan = "price_plan".$post['plan_id'];
                if($post[$amount_plan] == "normal_price"){
                    $plan_price = $choose_plan->normal_price;
                    $validity = $choose_plan->normal_period;
                } else{
                    $plan_price = $choose_plan->discount_price;
                    $validity = $choose_plan->discount_period;
                }
                $time = time();
    
            $user_id = Auth::user()->id;
            $order_id = "ORDERID-".$time.$user_id;
                $start_date = Carbon::now()->format('Y-m-d');
                $end_date = Carbon::now()->addMonths($validity)->format('Y-m-d');
                $subscriptionData['guid'] = $user_id;
                $subscriptionData['plan_id'] = $_SESSION['plan_id'];
                $subscriptionData['transaction_id'] = $order_id;
                $subscriptionData['subscription_from_date'] = $start_date;
                $subscriptionData['subscription_till_date'] = $end_date;
                $subscriptionData['meta_data'] = json_encode($post);
                $subscriptionData['amount'] = $plan_price;
                $NewUserSubscription = User_Subscription::addSubscriptionPlan($subscriptionData);
             
                $userData = User::where('id',$user_id)->first();
                $userData->activated=1;
                $userData->lastsubscription_id = $NewUserSubscription->id;
                $userData->save();

                return view('subscription.payment',compact('userData','choose_plan','plan_price','order_id'));
            }else{
                return redirect('/subscription')->with('error','Your session expired.please try again.');
            }
        }
        
        }else{
            return redirect('/subscription')->with('error','Your session expired.please try again.');
        }
    }

    public function payment_success(Request $request){
        $post = $request->all();
        $order_id = $post['orderid'];
        $subscriptionData['meta_data'] = json_encode($post);
        $subscriptionData['payment_status'] = $post['status'];
        User_Subscription::updateSubscriptionPlanData($order_id,$subscriptionData);

        return redirect('/payment-view')->with('success','Your Payment has been successfully done.');
    }

    public function paymentView(){

        return view('subscription.success');
    }


    public function payment_failure(Request $request){
        //dd($request->all());
        $post = $request->all();
        $order_id = $post['orderid'];
        User_Subscription::where('transaction_id',$order_id)->delete();
        return redirect('/failure-view')->with('error',"Your Payment has been failed.please try again.");
    }

    public function failureView(){
        return view('subscription.failure');
    }


}
