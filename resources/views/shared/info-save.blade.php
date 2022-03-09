@extends('layouts.base')
@section('page_head_css_scripts')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/xps.css') }}">
 
<style>

body {
    font-size: 0.85em;
    font-family:Helvetica, Arial, sans-serif;
}
.avatar {
    padding: 0;
}
#sharedInfoApp {
    /*//padding-top: 16px;*/
}
.shared-section{
  padding-top: 20px;
}

[dlmenu] .right-button, .root-button .right-button {
    height: 32px;
}

#sharedInfoApp .search input {
    height: 40px;
}

#sharedInfoApp .viewbar ul li.list-view,
#sharedInfoApp .viewbar ul li.thumbs-view {
    width: 48px;
}
#sharedInfoApp .viewbar ul li.list-view.selected,
#sharedInfoApp .viewbar ul li.thumbs-view.selected {
    height: 30px;
}

#sharedInfoApp ._4shzo2 {
    z-index: 2 !important;
}
#sharedInfoApp #DocumentList > div:nth-child(1){
 z-index: 2 !important;
}
</style>
@endsection
@section('content')
<div id="page-wrapper" class="no-pad">
    <div class="graphs">
        <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>" style="<?php  echo (!$isMobile)?"display: none":""; ?>">
            <section class="lobby-goals-rows col-lg-12 no-pad">
                <?php 
                    if($isMobile){?>
                        @include('mobile_header')
                <?php } ?>
            </section>
        </div>
        <div id="sharedInfoApp">
        </div>  
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bowser/1.9.2/bowser.min.js"></script>
<script src="{{ URL::asset('js/xps-web-collections.59bfaf.js') }}"></script>
<script src="{{ URL::asset('js/public.575f1eeb.js') }}"></script>
@endsection