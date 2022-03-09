<?php

namespace App\Http\Middleware;

use App\Model\User_Subscription;
use App\Setting;
use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        switch ($user->activated) {
            case 1:
                $subscription = User_Subscription::where('id', $user->lastsubscription_id)
                    ->where('payment_status', 'ok')
                    ->get()->first();
                if ($subscription) {

                    if ($subscription->subscription_till_date >= Carbon::now()->format('Y-m-d')) {
                        $date = $subscription->subscription_till_date;
                        $now = Carbon::parse(Carbon::now()->format('Y-m-d'));

                        $diff = $now->diffInDays($date);

                        $SettingDurationAfterExpire = Setting::where('key', 'site.TrialAfterExpiredSub')->get()->first();
                        $SettingRemidnerBeforeExpire = Setting::where('key', 'site.RemindPeriodInDays')->get()->first();

                        $durationAfterExpire = (int) $SettingDurationAfterExpire->value;
                        if ($durationAfterExpire == 0 || $durationAfterExpire == null) {$durationAfterExpire == 1;}
                        $SettingRemidnerBeforeExpire = (int) $SettingRemidnerBeforeExpire->value;
                        if ($SettingRemidnerBeforeExpire == 0 || $SettingRemidnerBeforeExpire == null) {$SettingRemidnerBeforeExpire == 7;}
 
                        if (($diff + $durationAfterExpire) <= $SettingRemidnerBeforeExpire) {
                            $request->merge(array("reminder" => 'Remdinder! Your subscription will expire in ' . ($diff + $durationAfterExpire) . ' days'));
                        }

                        return $next($request);
                    } else {
                        return redirect('/subscription');
                    }
                }
                return $next($request);

            case 0:
                $subscription = User_Subscription::where('id', $user->lastsubscription_id)
                ->where('payment_status', 'ok')
                ->get()->first();
              
            if ($subscription) {

                if ($subscription->subscription_till_date >= Carbon::now()->format('Y-m-d')) {
                    $date = Carbon::parse($subscription->subscription_till_date);
                    $now = Carbon::parse(Carbon::now()->format('Y-m-d'));

                    $diff = $now->diffInDays($date);

                    $SettingDurationAfterExpire = Setting::where('key', 'site.TrialAfterExpiredSub')->get()->first();

                    $durationAfterExpire = (int) $SettingDurationAfterExpire->value;
                    if ($durationAfterExpire == 0 || $durationAfterExpire == null) {$durationAfterExpire == 1;}
                                    
                    if (($diff + $durationAfterExpire) >= $durationAfterExpire) {
                        $request->merge(array("reminder" => 'Remdinder! Your subscription will expire in ' . ($diff + $durationAfterExpire) . ' days'));
                    }

                    return $next($request);
                } else {
                    return redirect('/subscription');
                }
            }
                return redirect('/subscription');
        }
    }
}
