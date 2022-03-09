<?php
  if(isset($choose_plan) && !empty($choose_plan)){
    
    $amount = $plan_price;
  }else{
    $amount = 0;
  }
  $time = time();
  $orderid = $order_id;
  $url = Config::get('constants.url');
   $paymentgatewayid = Config::get('constants.paymentgatewayid');
  $merchantid = Config::get('constants.merchantid');
  $secretKey = Config::get('constants.SecretKey');

  $success_url = url('/payment-success');
  $success_server = url('/payment-success');
  $failure_url = url('/payment-failure');
  $failed_server = url('/payment-failure');

  $complete = $merchantid.'|'.$success_url.'|'.$success_server.'|'.$orderid.'|'.$amount.'|USD';
  //dd($complete);
  $message = utf8_encode($complete);
  $checkhash = hash_hmac('sha256', $message, $secretKey);

?>

<!DOCTYPE HTML>
<html>
<head>
<title>{{$theme}} | Sign Up</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link media="(device-width: 320px)" rel="apple-touch-startup-image" href="{{ URL::asset('themes/'.$theme.'/images/apple-touch-startup-image-320x460.png') }}">
<link media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" href="{{ URL::asset('themes/'.$theme.'/images/apple-touch-startup-image-640x920.png') }}">
<link rel="apple-touch-icon" href="{{ URL::asset('themes/'.$theme.'/images/touch-icon-iphone.png') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('themes/'.$theme.'/images/touch-icon-ipad.png') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('themes/'.$theme.'/images/touch-icon-iphone-retina.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('themes/'.$theme.'/images/touch-icon-ipad-retina.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('themes/'.$theme.'/images/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('themes/'.$theme.'/images/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('themes/'.$theme.'/images/favicon-16x16.png') }}">
<!-- PACE JS -->
<script type="text/javascript" src="{{ URL::asset('/js/pacejs/js/pace.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/js/pacejs/css/themes/blue/pace-theme-minimal.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('themes/'.$theme.'/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('themes/'.$theme.'/css/style.css') }}"> 
<link href="{{ URL::asset('css/login/font-awesome.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('css/login/icon-font.min.css') }}" type='text/css' />
<link href="{{ URL::asset('css/login/animate.css') }}" rel="stylesheet" type="text/css" media="all">
<script type="text/javascript" src="{{ URL::asset('/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/themes/'.$theme.'/js/theme.ete.js') }}"></script>
</head>
<body class="sign-in-up">
<section>
  <div id="page-wrapper" class="sign-in-wrapper" style="height: 600px;">
    <div class="graphs">
    <div class="col-md-4 pull-right">
            @if (count($errors) > 0)
            <div class="alert alert-danger alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session()->get('error') }}
            </div>
            @endif
            
            @if(session()->has('success'))
            <div class="alert alert-success alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session()->get('success') }}
            </div>
            @endif
</div>
      <div class="sign-in-form">
        <h1 class="logo"></h1>

          <form id="form1" action="{{ $url }}" method="post">
             <input hidden type="text" name="merchantid" value="{{$merchantid}}" />
             <input hidden type="text" name="paymentgatewayid" value="{{$paymentgatewayid}}" />
             <input hidden type="text" size=100 name="checkhash" value="{{$checkhash}}" />
            <input hidden type="text" name="orderid" value="{{ $orderid }}" />
             <input hidden type="text" name="currency" value="USD" />
            <input hidden type="text" name="language" value="EN" />
             <input hidden type="text" name="buyername" value="{{ auth()->user()->fullname }}" />
             <input hidden type="text" name="buyeremail" value="{{ auth()->user()->email }}" />
             <input hidden type="text" size=100 name="returnurlsuccess" value="{{$success_url}}" />
             <input hidden type="text" size=100 name="returnurlsuccessserver" value="{{$success_url}}" />
             <input hidden type="text" size=100 name="returnurlcancel" value="{{$failure_url}}" />
            <input hidden type="text" size=100 name="returnurlerror" value="{{$failure_url}}?orderid=$orderid" />
             <input hidden type="text" name="itemdescription_0" value="{{ $choose_plan->title }}" />
             <input hidden type="text" name="itemcount_0" value="1" />
             <input hidden type="text" name="itemunitamount_0" value="{{ $amount }}" />
             <input hidden type="text" name="itemamount_0" value="{{ $amount }}" />
           <input hidden type="text" name="amount" value="{{ $amount }}" />
            <input hidden type="text" name=" pagetype " value="0" /><br>
             <input hidden type="text" name="skipreceiptpage " value="0" />
             <input hidden type="text" name="merchantemail" value="test@borgun.is" />
            <input type="submit" value="Process to payement" name="PostButton" />
          </form>
      
           
		 </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>