<div class="left-side sticky-left-side"> 
<!--logo and iconic logo start-->
<div class="logo">
    <h1><a href="./"><img src="{{ URL::asset('themes/'.$theme.'/images/KH_hnappur.png') }}" alt="" /></a></h1>
</div>
<div class="logo-icon text-center" title="Home"> <a href="{{URL('home')}}"><img src="{{ URL::asset('themes/'.$theme.'/images/KH_hnappur.png') }}" alt="" /></a> </div>
<!--logo and iconic logo end-->

<div class="left-side-inner">
    <!--sidebar nav start-->
    <ul class="nav nav-pills nav-stacked custom-nav">
        <li title="Goals" class="goals <?php echo ($activeMenu=='goals')?'active':'';?>"><a href="{{ URL('list') }}"><i></i></a></li>
     <!--   <li title="Mailbox" class="mailbox <?php echo ($activeMenu=='mailbox')?'active':'';?>"><a href="{{ URL('message') }}"><i></i></a></li>-->
        <li title="Trophy Room" class="trophy <?php echo ($activeMenu=='trophy')?'active':'';?>" ><a href="{{ URL('trophies') }}"><i></i></a></li>
        <li title="Assignment" class="edits <?php echo ($activeMenu=='assignment')?'active':'';?>"><a href="{{ URL('assignment/list') }}"><i></i></a></li>
        <li title="Shared Info" class="shared <?php echo ($activeMenu=='shared')?'active':'';?>"><a href="{{ URL('shared') }}"><i></i></a></li>
        <li title="Personal Info" class="per-info <?php echo ($activeMenu=='info')?'active':'';?>"><a href="{{ URL('profile') }}"><i></i></a></li>
         <?php 
        /*
        <li title="Help" class="help <?php echo ($activeMenu=='help')?'active':'';?>"><a href="http://www.keyhabits.com/goalsettingprocess/" target="_blank"><i></i></a></li>
        */
        ?>
        <li title="compass" class="compass <?php echo ($activeMenu=='statement-values')?'active':'';?>"><a href="{{ URL('statement-values') }}" ><i></i></a></li>
        <?php if(is_admin()){?>
        <li title="Clander" class="workshop <?php echo ($activeMenu=='workshop')?'active':'';?>"><a href="{{ URL('workshop/add') }}"><i></i></a></li>
        <?php } ?>
        <!-- <?php if (Auth::user()->guid == '-6589451487650500608') { ?>
            <li title="Webinar Manage" class="webinar <?php echo ($activeMenu=='webinar')?'active':'';?>"><a href="{{ URL('webinar/admin') }}" ><i></i></a></li>
        <?php } ?>
        <?php 
        use App\Helper\WebinarAcl;
        $webinarAcl= new WebinarAcl(Auth::user());
        if (Auth::user() && !empty(Auth::user()->guid) && $webinarAcl->canAccessWebinar()) {
            $recent_webinar_id=$webinarAcl->getWebinar();
            ?>
            <li title="Webinar" class="webinar <?php echo ($activeMenu=='webinar')?'active':'';?>"><a href="{{ URL('webinar/autoregister/'.$recent_webinar_id->id) }}" ><i></i></a></li>
        <?php }?> -->

        <li class="logout"><a href="{{ URL('logout') }}" class="logout"><i></i></a></li>
    </ul>
    <!--sidebar nav end--> 
</div>
</div>