<script>
	$(document).ready(function() {

	/** ******************************
	 * Collapse Panels
	 * [data-perform="panel-collapse"]
	 ****************************** **/
	(function($, window, document){
		var panelSelector = '[data-perform="panel-collapse"]';

		$(panelSelector).each(function() {
			var $this = $(this),
			parent = $this.closest('.panel'),
			wrapper = parent.find('.panel-wrapper'),
			collapseOpts = {toggle: false};

			if( ! wrapper.length) {
				wrapper =
				parent.children('.panel-heading').nextAll()
				.wrapAll('<div/>')
				.parent()
				.addClass('panel-wrapper');
				collapseOpts = {};
			}
			wrapper
			.collapse(collapseOpts)
			.on('hide.bs.collapse', function() {
				$this.children('i').removeClass('fa-minus').addClass('fa-plus');
			})
			.on('show.bs.collapse', function() {
				$this.children('i').removeClass('fa-plus').addClass('fa-minus');
			});
		});
		$(document).on('click', panelSelector, function (e) {
			e.preventDefault();
			var parent = $(this).closest('.panel');
			var wrapper = parent.find('.panel-wrapper');
			wrapper.collapse('toggle');
		});
	}(jQuery, window, document));
  
  /** ******************************
	 * Remove Panels
	 * [data-perform="panel-dismiss"]
	 ****************************** **/
	(function($, window, document){
		var panelSelector = '[data-perform="panel-dismiss"]';
		$(document).on('click', panelSelector, function (e) {
			e.preventDefault();
			var parent = $(this).closest('.panel');
			removeElement();

			function removeElement() {
				var col = parent.parent();
				parent.remove();
				col.filter(function() {
					var el = $(this);
					return (el.is('[class*="col-"]') && el.children('*').length === 0);
				}).remove();
			}
		});
	}(jQuery, window, document));
  
});
</script> 
<div class="main-content">
    <div class="col-md-12 no-pad">
      <div class="compact-view-goals mobile">
        <div class="panel panel-primary has-child"> 
          <!--Row Parent-->
          <div class="no-mar-top">
            <div class="header">
              <span class="float-right"><a href="#"><i class="fa fa-cog"></i></a> </span>
              <h4>Main Goal</h4>
              <a href="#" class="settings"><i class="fa fa-eye"></i></a>
              <span class="date">22 June 2015</span>
            </div>
            <div class="content">
              <span class="text">Health - Energy Managment</span>
              <!--Row Parent-->
              <div class="goal-row"><span class="float-right"> <a href="#" class="settings"><i class="fa fa-cog"></i></a> </span> <span class="goal-title">Habit</span><a href="#" class="settings"><i class="fa fa-eye-slash"></i></a> <span class="date">31 Oct 2015</span><span class="sub-title-goal">Run everyday after work</span>
              <div class="goal-row-parent pad-left-20"> 
                    <span class="float-right"><a href="#" class="settings"><i class="fa fa-cog"></i></a> </span> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-minus"></i></a> <span class="goal-title">Task</span> <a href="#" class="settings"><i class="fa fa-eye"></i></a> <span class="date">31 Oct 2015</span> <span class="sub-title-goal">Sing a song before going to work</span>
                  </div> 
                <!--Row Parent Ends-->
                <div class="panel-wrapper collapse in">
                <!--Row Child--> 
                  <div class="goal-row-child pad-left-20"> 
                    <span class="float-right"><a href="#" class="settings"><i class="fa fa-cog"></i></a> </span><span class="goal-title left">Undefined</span> <a href="#" class="settings"><i class="fa fa-eye-slash"></i></a> <span class="date">31 Oct 2015</span><span class="sub-title-goal mar-left-20">Run everyday after work</span>
                  </div>
                <!--Row Child Ends-->
                </div>
                  </div>
              <!--Row Parent Ends--> 
              <!--Row Parent-->
              <div class="goal-row"><span class="float-right"> <a href="#" class="settings"><i class="fa fa-cog"></i></a> </span> <span class="goal-title">Habit</span><a href="#" class="settings"><i class="fa fa-eye-slash"></i></a> <span class="date">31 Oct 2015</span><span class="sub-title-goal">Run everyday after work</span>
              <div class="goal-row-parent pad-left-20"> 
                    <span class="float-right"><a href="#" class="settings"><i class="fa fa-cog"></i></a> </span> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-minus"></i></a> <span class="goal-title">Task</span> <a href="#" class="settings"><i class="fa fa-eye"></i></a> <span class="date">31 Oct 2015</span> <span class="sub-title-goal">Sing a song before going to work</span>
                  </div> 
                <!--Row Parent Ends-->
                <div class="panel-wrapper collapse in">
                <!--Row Child--> 
                  <div class="goal-row-child pad-left-20"> 
                    <span class="float-right"><a href="#" class="settings"><i class="fa fa-cog"></i></a> </span><span class="goal-title left">Undefined</span> <a href="#" class="settings"><i class="fa fa-eye-slash"></i></a> <span class="date">31 Oct 2015</span><span class="sub-title-goal mar-left-20">Run everyday after work</span>
                  </div>
                <!--Row Child Ends-->
                </div>
                  </div>
              <!--Row Parent Ends-->
                </div>
              </div>
            </div>
          </div>
        </div>
    <div class="col-md-12 no-pad">
      <div class="goal-create-details"> 
        <!--Section-->
        <div class="section">
          <header>
            <h4>Status<small> - What is your current situation?</small> <span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></h4>
            <textarea placeholder="Status Description..."></textarea>
          </header>
        </div>
        <!--//Section Ends--> 
        <!--Section-->
        <div class="section">
          <header>
            <h4>Improvement<small> - Do you want to improve the situation?</small> <span data-toggle="tooltip" data-placement="bottom" title="Do you want to improve the situation" class="info-tt fa fa-info-circle"></span></h4>
            <textarea placeholder="Improvement Description..."></textarea>
          </header>
        </div>
        <!--//Section Ends--> 
        <!--Section-->
        <div class="section">
          <header>
            <h4>Risk<small> - What effect will it have if you don't improve/change the situation?</small> <span data-toggle="tooltip" data-placement="bottom" title="What effect will it have if you don't improve/change the situation?" class="info-tt fa fa-info-circle"></span></h4>
            <textarea placeholder="Risk Description..."></textarea>
          </header>
        </div>
        <!--//Section Ends--> 
        <!--Section-->
        <div class="section">
          <header>
            <h4>Benefit<small> - What effect will it have if you improve the situation?</small> <span data-toggle="tooltip" data-placement="bottom" title="What effect will it have if you improve the situation?" class="info-tt fa fa-info-circle"></span></h4>
            <textarea placeholder="Benefit Description..."></textarea>
          </header>
        </div>
        <!--//Section Ends--> 
        <!--Section-->
        <div class="section">
          <header>
            <h4>Vision-Years<small> - How do you want your situation to be in 1,2,3 years from now?</small> <span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></h4>
            <textarea placeholder="Vision-Years Description..."></textarea>
          </header>
        </div>
        <!--//Section Ends--> 
        <input type="submit" value="Submit this Goal"  class="submit"/>
      </div>
    </div>
</div>
      </div>
    </div>

<?php include ("footer.php"); ?>
