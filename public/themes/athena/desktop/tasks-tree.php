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
<!--Row-->
      <div class="task-row bg-white"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span><span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">46%</span> <span class="progress"> <span class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width: 46%"></span> </span> </div>
      <!--Row Ends--> 
      
<div class="panel panel-primary has-child"> 
  <!--Row Parent-->
  <div class="task-row has-child bg-grey"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-filled"></span> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">99%</span> <span class="progress"> <span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="99" aria-valuemin="0" aria-valuemax="100" style="width: 99%"></span> </span> </div>
  <!--Row Parent Ends-->
  <div class="panel-wrapper collapse">
    <div class="task-row-child"> 
      <!--Row Child-->
      <div class="task-row bg-pink"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">46%</span> <span class="progress"> <span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width: 46%"></span> </span> </div>
      <!--Row Child Ends--> 
      <!--Row Child-->
      <div class="task-row  bg-pink"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">46%</span> <span class="progress"> <span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width: 46%"></span> </span> </div>
      <!--Row Child Ends--> 
    </div>
  </div>
</div>

<div class="panel panel-primary has-child"> 
  <!--Row Parent-->
  <div class="task-row has-child"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-filled"></span> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-minus"></i></a> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">68%</span> <span class="progress"> <span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%"></span> </span> </div>
  <!--Row Parent Ends-->
  <div class="panel-wrapper collapse in">
    <div class="task-row-child"> 
      <!--Row Child-->
      <div class="task-row bg-pink"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">35%</span> <span class="progress"> <span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 35%"></span> </span> </div>
      <!--Row Child Ends--> 
      <!--Row Child-->
      <div class="task-row bg-pink"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">7%</span> <span class="progress"> <span class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100" style="width: 7%"></span> </span> </div>
      <!--Row Child Ends--> 
    </div>
  </div>
</div>

<div class="panel panel-primary has-child"> 
  <!--Row Parent-->
  <div class="task-row has-child"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-filled"></span> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">68%</span> <span class="progress"> <span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%"></span> </span> </div>
  <!--Row Parent Ends-->
  <div class="panel-wrapper collapse">
    <div class="task-row-child"> 
      <!--Row Child-->
      <div class="task-row bg-pink"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">35%</span> <span class="progress"> <span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 35%"></span> </span> </div>
      <!--Row Child Ends--> 
      <!--Row Child-->
      <div class="task-row bg-pink"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">7%</span> <span class="progress"> <span class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100" style="width: 7%"></span> </span> </div>
      <!--Row Child Ends--> 
    </div>
  </div>
</div>

<!--Row-->
      <div class="task-row bg-white"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span><span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">46%</span> <span class="progress"> <span class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width: 46%"></span> </span> </div>
      <!--Row Ends-->

<div class="panel panel-primary has-child"> 
  <!--Row Parent-->
  <div class="task-row has-child"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-filled"></span> <a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">68%</span> <span class="progress"> <span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%"></span> </span> </div>
  <!--Row Parent Ends-->
  <div class="panel-wrapper collapse">
    <div class="task-row-child"> 
      <!--Row Child-->
      <div class="task-row bg-pink"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">35%</span> <span class="progress"> <span class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 35%"></span> </span> </div>
      <!--Row Child Ends--> 
      <!--Row Child-->
      <div class="task-row bg-pink"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span> <span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">7%</span> <span class="progress"> <span class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100" style="width: 7%"></span> </span> </div>
      <!--Row Child Ends--> 
    </div>
  </div>
</div>

<!--Row-->
      <div class="task-row bg-white"> <span class="arrows-holder"> <a href="#" class="arrow-up"><i class="lnr lnr-chevron-up"></i></a><a href="#" class="arrow-down"><i class="lnr lnr-chevron-down"></i></a> </span> <span class="thumbup-unfilled"></span> <span class="goal-data"> <span class="gd-heading">Lose Weight</span><span class="gd-date">by April 12, 2015</span> </span> <span class="persentage">46%</span> <span class="progress"> <span class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100" style="width: 46%"></span> </span> </div>
      <!--Row Ends-->