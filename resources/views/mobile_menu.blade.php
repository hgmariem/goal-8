<div class="header-mobile hide-on-desktop animated fadeInLeft"> 
    <!--toggle button start--> 
    <ul class="mobile-menu">
        <li title="goals" class="goals <?php echo ($activeMenu=='goals')?'active':'';?>"><a href="{{ URL('list') }}"><i></i><span>Goals</span></a></li>
       <!-- <li class="mailbox <?php echo ($activeMenu=='mailbox')?'active':'';?>"><a href="{{ URL('message') }}"><i></i><span>Mailbox</span></a></li>-->
        <li class="trophy <?php echo ($activeMenu=='trophy')?'active':'';?>"><a href="{{ URL('trophies') }}"><i></i><span>Trophy Room</span></a></li>
        <li class="edits <?php echo ($activeMenu=='assignment')?'active':'';?>"><a href="{{ URL('assignment/list') }}"><i></i><span>Assignment</span></a></li>
        <li class="shared <?php echo ($activeMenu=='shared')?'active':'';?>"><a href="{{ URL('shared') }}"><i></i><span>Shared Info</span></a></li>
        <li class="per-info <?php echo ($activeMenu=='info')?'active':'';?>"><a href="{{ URL('profile') }}"><i></i><span>Personal Info</span></a></li>
        <!-- <li class="help <?php echo ($activeMenu=='help')?'active':'';?>"><a href="http://www.keyhabits.com/goalsettingprocess/" target="_blank"><i></i><span>Help</span></a></li> -->
        <li class="compass"><a href="{{ URL('statement-values') }}"><i></i><span>Compass</span></a></li>
       <?php 
        if (Auth::user()->guid == '-6589451487650500608') { ?>
            <li title="Webinar Manage" class="webinar <?php echo ($activeMenu=='webinar')?'active':'';?>"><a href="{{ URL('webinar/admin') }}" ><i></i><span>Webinar</span></a></li>
        <?php } ?>
        <?php 
        use App\Helper\WebinarAcl;
        $webinarAcl= new WebinarAcl(Auth::user());
        if (Auth::user() && !empty(Auth::user()->guid) && $webinarAcl->canAccessWebinar()) {
            $recent_webinar_id=$webinarAcl->getWebinar();
            ?>
            <li title="Webinar" class="webinar <?php echo ($activeMenu=='webinar')?'active':'';?>"><a href="{{ URL('webinar/autoregister/'.$recent_webinar_id->id) }}" ><i></i><span>Webinar</span></a></li>
        <?php }?>
        <li class="logout"><a href="{{ URL('logout') }}" class="logout"><i></i><span>Logout</span></a></li>
    </ul>
</div>