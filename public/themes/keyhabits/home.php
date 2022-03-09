<?php include ("header.php"); ?>
<!-- main content start-->
<div class="main-content">
 <div id="page-wrapper" class="no-pad">
   <div class="graphs">
   <div class="hide-on-mobile">
   <?php include ("desktop/habits-home.php"); ?>
   <?php include ("desktop/day-shedule-home.php"); ?>
   <?php include ("desktop/tasks.php"); ?>
   <?php include ("desktop/reminders-character.php"); ?>
 </div>
 </div>
 
 <div class="hide-on-desktop">
 	<!--Mobile Header-->
    	<div class="mobile-header">
        	<a href="#" class="trigger"><i class="fa fa-bars"></i></a>
            <a href="#" class="mailbox"><i class="badge-danger">3</i></a>
            <div class="text-center"><a href="#" class="logo-mobile"><img src="images/logo.png" alt="" /></a></div>
        </div>
    <!--//Mobile Header Ends-->
    <!-- Content Mobile Starts-->
    	<div class="mobile-content home">
           <ul class="task-tabs nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#habits">Habits</a></li>
            <li><a data-toggle="tab" href="#tasks">Tasks</a></li>
            <li><a data-toggle="tab" href="#chararter">Chararter</a></li>
          </ul>
		  <div class="tab-content">
            <div id="habits" class="tab-pane fade in active">
              <?php include ("mobile/habits-home-mobile.php"); ?>
            </div>
            <div id="tasks" class="tab-pane fade">
              <?php include ("mobile/tasks-mobile.php"); ?>
            </div>
            <div id="chararter" class="tab-pane fade">
              <?php include ("mobile/reminders-character-mobile.php"); ?>
            </div>
            </div>
        </div>
    <!--//Content Mobile Ends-->
 </div>
<!--body wrapper start--> 
</div>
<!--body wrapper end--> 
</div>
<!-- main content end--> 
</section>
<?php include ("footer.php"); ?>
