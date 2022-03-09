<?php include ("header.php"); ?>
<!-- main content start-->
<div class="main-content">
 <div id="page-wrapper" class="no-pad">
   <div class="graphs">
   <div class="hide-on-mobile">
   <?php include ("desktop/goals-assingment-list-desktop.php"); ?>
 </div>
 </div>
 
 <div class="hide-on-desktop">
 	<!--Mobile Header-->
    	<div class="mobile-header">
        	<a href="#" class="trigger"><i class="fa fa-bars"></i></a>
            <a href="#" class="mailbox"><i class="badge-danger">3</i></a>
            <div class="text-center">
            	<div class="dropdown gls">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Goals
                  <span class="caret"></span></button>
                  <ul class="dropdown-menu">
                    <li><a href="#">Create Goal</a></li>
                  </ul>
                </div>
            </div>
        </div>
    <!--//Mobile Header Ends-->
    <!-- Content Mobile Starts-->
    	<div class="mobile-content home">
  			 <?php include ("mobile/goals-assingment-list-mobile.php"); ?>
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
