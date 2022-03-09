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
       
        <div class="signin" style="background:#f5f5f5;">
        <div>Your Payment has been failed. please try again.</div>
		 </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>