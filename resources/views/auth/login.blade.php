<!DOCTYPE HTML>
<html>
<head>
<title>{{$theme}} | Log In</title>
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
  <div id="page-wrapper" class="sign-in-wrapper">
    <div class="graphs">
      <div class="sign-in-form">
        <h1 class="logo"></h1>
        <div class="signin">
        <div class="sign-in-form-top">
          <p>Please <span>Sign In</span></p>
        </div>
		<div class="page-wrapper-alerts">
		   @include('partials.form-status')
		 </div>
	 
          <form action="{{URL('postlogin')}}" method="POST">
			{{ csrf_field() }}
            <div class="form-group">
              <label style="color: white">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email..." value="<?php echo isset($credentials->username)?$credentials->username:""?>"/>
            </div>
            <div class="form-group">
              <label style="color: white">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password..." value="<?php echo isset($credentials->password)?$credentials->password:""?>"/>
            </div>
            <div class="signin-rit"> 
				<span class="checkbox1">
					<label class="checkbox">
					  <input id="remember_token" type="checkbox" name="remember_token" value="1">
					  <label for="remember_token"><span></span></label> Remember Me
					</label>
				</span>
				<p><a href="{{URL('forgotpwd')}}">Forgot Password?</a></p>
			</div>
            <input type="submit" value="Login">
            <span style="color: white;">Not a member?</span><a href="{{URL('register')}}"> Sign Up</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>
<script type="text/javascript">
  $(window).on('popstate', function(e) {
  var lastEntry = customHistory.pop();
  history.pushState(lastEntry.data, lastEntry.title, lastEntry.location);
  // load the last entry
});
</script>