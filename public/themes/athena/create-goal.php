<?php include ("header.php"); ?>
<!-- main content start-->
<div class="main-content">
 <div id="page-wrapper" class="no-pad">
   <div class="graphs">
   <div class="hide-on-mobile">
   <?php include ("create-goal-compact.php"); ?>
 </div>
 </div>
 
 <div class="hide-on-desktop">
 	<!--Mobile Header-->
    	<div class="mobile-header">
        	<a href="#" class=""><i class="fa fa-chevron-left"></i></a>
            <a href="#" class="create-goal"></a>
            <div class="text-center title">Create Goal</div>
        </div>
    <!--//Mobile Header Ends-->
    <!-- Content Mobile Starts-->
    	<div class="mobile-content home">
  			 <?php include ("mobile/create-goal-mobile.php"); ?>
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
