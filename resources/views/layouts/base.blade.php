<!DOCTYPE html>
<html lang="en">
    <head>
		{{-- CSRF Token --}}
		 <meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{$theme}}</title>
		
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
        <!--Datepicker css-->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('themes/'.$theme.'/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('themes/'.$theme.'/css/bootstrap-datepicker3.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('lib/themes/default.css') }}?{{ time() }}" id="theme_base">
        <link rel="stylesheet" href="{{ URL::asset('lib/themes/default.date.css') }}" id="theme_date">
        <link rel="stylesheet" href="{{ URL::asset('lib/themes/default.time.css') }}" id="theme_time">
        <link rel="stylesheet" href="{{ URL::asset('themes/'.$theme.'/css/style.css') }}?{{ time() }}"> 
        <script type="text/javascript" src="{{ URL::asset('assets/5367e0c7/jquery.js') }}"></script>
        <link rel="stylesheet" href="{{ URL::asset('/themes/'.$theme.'/css/icon-font.min.css') }}" type='text/css' />
        <link href="{{ URL::asset('/themes/'.$theme.'/css/dev.css') }}?{{ time() }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('themes/'.$theme.'/css/dist.css') }}?{{ time() }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/flat/green.css') }}?{{ time() }}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('themes/'.$theme.'/css/custom.css')}}?{{ time() }}">
        <?php /*<link rel="stylesheet" type="text/css" href="{{ URL::asset('js/Lean-Mean-Drag-and-Drop/dist/lmdd.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('js/dragula/dist/dragula.css')}}">*/?>
         @yield('head_css')
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        
		<script src="{{ URL::asset('js/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('/js/custom.ete.js') }}"></script>
        
		<script type="text/javascript">
		  var site_url = "{{App::make('url')->to('/')}}";
		  var currentUser = "{{Auth::user()!==null&&Auth::user()->id?Auth::user()->id:0}}";
		  var current_session_id="{{Auth::user()!==null&&Auth::user()->sessionid?Auth::user()->sessionid:0}}";
		  var current_guid="{{Auth::user()!==null&&Auth::user()->guid?Auth::user()->guid:0}}";
		  var site_current_url='<?php echo url()->current();?>';
		  // Get the current URL including the query string...
		  var site_current_url_query='<?php echo url()->full(); ?>';
		  // Get the full URL for the previous request...
		  var site_prev_url='<?php echo url()->previous();?>';
		  var site_action_method='<?php echo Route::getCurrentRoute()->getActionMethod();?>';
          var isMobile=<?php echo $isMobile?>;
          var isDesktop=<?php echo $isDesktop?>;

		</script>
        
    </head>

    <body class="sticky-header left-side-collapsed <?php echo ($isMobile)?"mobile-view":"desktop-view"; ?> <?php echo $activeMenu;?>-page" ng-app="xpsWeb"> 
    <section>
        <!-- mobile desktop start-->
		@include('desktop_menu')
        <!-- mobile desktop end--> 
        <!-- header-mobile-starts -->
        @include('mobile_menu')
		@yield('page_head_css_scripts')
        <!-- //header-mobile-ends --> 
        <!-- main content start-->
        <div class="main-content">
			 @yield('content')
        </div>
    </section>
        <script type="text/javascript">
            var deviceType = "Desktop";
        </script>
    <script src="{{ URL::asset('themes/'.$theme.'/js/jquery.nicescroll.js') }}"></script> 
	<!-- Bootstrap Core JavaScript --> 
	<script src="{{ URL::asset('themes/'.$theme.'/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-notify.min.js') }}"></script>
	<script src="{{ URL::asset('js/jquery.blockUI.js') }}"></script>
	<!--<script src="--><!--/js/bootstrap.js"></script>-->
	<script src="{{ URL::asset('lib/picker.js') }}"></script>
	<script src="{{ URL::asset('lib/picker.date.js') }}"></script>
	<script src="{{ URL::asset('lib/picker.time.js') }}"></script>
	<script src="{{ URL::asset('lib/legacy.js') }}"></script>
	<script src="{{ URL::asset('lib/main.js') }}"></script>
<!--    <script src="--><!--/js/jquery-2.1.4.min.js"></script>-->
	<script src="{{ URL::asset('js/icheck.min.js') }}"></script>
	<script src="{{ URL::asset('js/tooltip.js') }}"></script>
	<script src="{{ URL::asset('js/popover.js') }}"></script>
	<script src="{{ URL::asset('js/script.js') }}"></script>
	<script src="{{ URL::asset('js/mobile-menu.js') }}"></script>
	<script src="{{ URL::asset('themes/'.$theme.'/js/scripts.js') }}"></script> 
	<script src="{{ URL::asset('js/jquery.autosize.js') }}"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
    <script src="{{ URL::asset('js/nestedSortable/jquery.mjs.nestedSortable.js') }}"></script>
    <?php /*<script src="{{ URL::asset('js/Lean-Mean-Drag-and-Drop/dist/lmdd.js')}}"></script>
    <script src="{{ URL::asset('js/jquery.ui.touch-punch.min.js') }}"></script>*/?>
    <script src="{{ URL::asset('js/jquery.ui.touch-punch.min.js') }}"></script>

    <script type="text/javascript">
        // $(document).ready(function(){
        //  setInterval('checkBetalogin()', 1000);
        // });

        // function checkBetalogin(){
        //     var xhr;
        //     if(xhr && xhr.readyState != 4){
        //     xhr.abort();
        //     }

        //  xhr = $.ajax({
        //         url:"/chek_login",
        //         method:"get",
        //         success:function(data){
        //             var result = jQuery.parseJSON(data);
        //             console.log("status",result.status);
        //             if(!result.status){
        //             window.location = "/logout";

        //             }
        //         }
        //     })
        // }
    </script>
    <?php /*
    <script src="{{ URL::asset('js/dragula/dist/dragula.js') }}"></script>*/?>
	@yield('footer_scripts')  

	<div class="tloader">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="loading-text">Loading..</div>
    </div> 
</body>
</html>