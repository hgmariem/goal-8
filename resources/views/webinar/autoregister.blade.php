@extends('layouts.base') 
@section('content')

<div id="page-wrapper" class="no-pad">
    <div class="graphs">
        <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
            <?php 
                if($isMobile){?>
                    @include('mobile_header')
            <?php } ?>
            <style>
                .overlay {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    z-index: 11111;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                }
                iframe {
                    width: 100%;
                    height: 100%;
                    padding-top: 50px;
                }
                h1 {
                    max-width: 680px;
                    margin: 150px auto 0;
                    color: #000;
                    font-weight: normal;
                }
                body {
                    background:#eee;
                }
            </style>

        <h1>You will be redirected automatically in 3 sec... Please, wait... </h1>

        <div class="overlay"></div>
        <?php 
            use App\Helper\WebinarAcl;
            $webinarAcl= new WebinarAcl(Auth::user());
        ?>
        <iframe src="<?php echo $webinar->register_url; ?>&firstname=<?php echo $webinarAcl->getUser()->fullname;?>>&email=<?php echo $webinarAcl->getUser()->email ;?>&countrycode=XX&phonenumber=<?php echo $webinarAcl->getUser()->telephone;?>&schedule=1"
                frameborder="0" sandbox=""></iframe>
        </div>
    </div>
</div>
@endsection 

@section('footer_scripts')
<script>
    setTimeout(function(){
        document.location.href = "{{URL('webinar/view/'.$webinar->id)}}";
    }, 15000)
</script>
@endsection