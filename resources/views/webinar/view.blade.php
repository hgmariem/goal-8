@extends('layouts.base') 
@section('content')

<div id="page-wrapper" class="no-pad">
    <div class="graphs">
    <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
    <?php 
        if($isMobile){?>
            @include('mobile_header')
    <?php } ?>
        <link rel="stylesheet" href="{{ URL::asset('/css/custom.css')}}">
        <style>
        	body, html {
        		height: 100%;
        	}
                .webinar-update-link{
                        position: absolute;
            background: #8bc34a;
            padding: 14px;
            color: #fff;
            z-index: 11111;
                }
        </style>
        
        <?php if (Auth::user()->guid == '-6589451487650500608')
            {?>
            <a class="webinar-update-link" href="{{URL('/webinar/update/1')}}">Webinar Update</a>
        <?php } ?>
            <iframe src="<?php echo $webinar->url; ?>" frameborder="0" style="width: 100%; height: 600px; padding-top:50px"></iframe>
    </div>
  </div>
</div>
@endsection 

@section('footer_scripts')

@endsection