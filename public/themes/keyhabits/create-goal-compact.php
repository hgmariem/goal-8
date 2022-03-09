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
    <div class="col-md-5 no-pad">
      <div class="compact-view-goals">
        <div class="panel panel-primary has-child"> 
          <!--Row Parent-->
          <div class="no-mar-top">
            <div class="header">
              <span class="float-right"><a href="#"><i class="fa fa-plus"></i></a> <a href="#"><i class="fa fa-close"></i></a></span>
              <h4>Main Goal</h4>
              <a href="#" class="settings"><i class="fa fa-cog"></i></a>
              <a href="create-goal-extended.php" class="view-swicther">Extended View</a>
            </div>
            <div class="content">
              <input type="text" class="text" placeholder="Main Goal Name..." />
              <!--Row Parent-->
              <div class="goal-row goal-row-parent"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">H</span> <span class="title-goal">Go to sleep before 10pm</span>
                <div class="panel-wrapper collapse in">
                  <div class="goal-row-child"> 
                    <!--Row Child-->
                    <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span>
                      <div class="goal-row-child"> 
                        <!--Row Child-->
                        <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span> </div>
                        <!--Row Child Ends--> 
                        <!--Row Child-->
                        <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <span class="first-alf">H</span> <span class="title-goal">Cook for the family</span> </div>
                        <!--Row Child Ends--> 
                      </div>
                    </div>
                    <!--Row Child Ends--> 
                  </div>
                </div>
              </div>
              <!--Row Parent Ends--> 
              <!--Row Parent-->
              <div class="goal-row goal-row-parent"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">H</span> <span class="title-goal">Go to sleep before 10pm</span> 
                <!--Row Parent Ends-->
                <div class="panel-wrapper collapse in">
                  <div class="goal-row-child"> 
                    <!--Row Child-->
                    <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span>
                      <div class="goal-row-child"> 
                        <!--Row Child-->
                        <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span> </div>
                        <!--Row Child Ends--> 
                        <!--Row Child-->
                        <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <span class="first-alf">H</span> <span class="title-goal">Cook for the family</span> </div>
                        <!--Row Child Ends--> 
                      </div>
                    </div>
                    <!--Row Child Ends--> 
                  </div>
                </div>
              </div>
              <!--Row Parent Ends-->
              <!--Row Parent-->
              <div class="goal-row goal-row-parent"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">H</span> <span class="title-goal">Go to sleep before 10pm</span> 
                <!--Row Parent Ends-->
                <div class="panel-wrapper collapse in">
                  <div class="goal-row-child"> 
                    <!--Row Child-->
                    <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span>
                      <div class="goal-row-child"> 
                        <!--Row Child-->
                        <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span> </div>
                        <!--Row Child Ends--> 
                      </div>
                    </div>
                    <!--Row Child Ends--> 
                  </div>
                </div>
              </div>
              <!--Row Parent Ends-->
              <!--Row Parent-->
              <div class="goal-row goal-row-parent"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="first-alf">H</span> <span class="title-goal">Go to sleep before 10pm</span> 
                <!--Row Parent Ends-->
                <div class="panel-wrapper collapse in">
                  <div class="goal-row-child"> 
                    <!--Row Child-->
                    <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a><span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span>
                    <div class="goal-row-child"> 
                    <!--Row Child-->
                      <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a><span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span> </div>
                    <!--Row Child Ends--> 
                    <!--Row Child-->
                     <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <span class="first-alf">H</span> <span class="title-goal">Cook for the family</span> </div>
                    <!--Row Child Ends-->
                    <!--Row Child-->
                      <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a><span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span> </div>
                    <!--Row Child Ends--> 
                    <!--Row Child-->
                     <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <span class="first-alf">H</span> <span class="title-goal">Cook for the family</span> </div>
                    <!--Row Child Ends-->
                    <!--Row Child-->
                      <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a><span class="first-alf">T</span> <span class="title-goal">Look at birds in morning</span> </div>
                    <!--Row Child Ends--> 
                    <!--Row Child-->
                     <div class="goal-row"> <a href="#" class="float-right settings"><i class="fa fa-cog"></i></a> <span class="first-alf">H</span> <span class="title-goal">Cook for the family</span> </div>
                    <!--Row Child Ends--> 
                      </div>
                    </div>
                    <!--Row Child Ends--> 
                  </div>
                </div>
              </div>
              <!--Row Parent Ends--> 
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-7 no-pad">
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
          <input type="submit" value="Submit this Goal"  class="submit"/>
        </div>
        <!--//Section Ends--> 
      </div>
    </div>

