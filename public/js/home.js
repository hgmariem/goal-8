jQuery(window).bind("load resize", function() {
	var emSize = parseFloat($("body").css("font-size"));

	$("ul.week-title li").width(parseInt($("ul.week-title li").width()));
	$("ul.week-title li").css("padding-left",parseInt($("ul.week-title li").css("padding-left"))+"px");
	$("ul.week-title li").css("padding-right",parseInt($("ul.week-title li").css("padding-right"))+"px");

	$("div.habit-checkbox").width(parseInt($("ul.week-title li").width()));
	$("div.habit-checkbox").css("padding-left",parseInt($("ul.week-title li").css("padding-left"))+"px");
	$("div.habit-checkbox").css("padding-right",parseInt($("ul.week-title li").css("padding-right"))+"px");

	$("ul.habit-list li").each(function(){
		var a = $(this).parent();
		var b = a.find('.sort-div');
		var c = a.find('.habit-checkbox-container');
		var c1 = c.find('.habit-checkbox');
		var d = a.find('.habit-link');
		$(this).width(a.width());
		var e = a.width()-b.width()-c.width();
		d.width(e-4*emSize);
		if (d.height()>c.height()){
			c.height(d.height()+20);
		}
	});
	$('#task-id').height($('#home').height()*0.60-$('#header-id').height()-$('#divider').height()-8);
	$('#character-id').height($('#home').height()*0.60-$('#header-id').height()-$('#divider').height()-8);
	$('#character-id').width($('#home').width()-$('#task-id').width()-5);
});
function getTotalWidthOfObject(object) {
    if(object == null || object.length == 0) {
        return 0;
    }
    var value       = parseInt(object.width());
    value           += parseInt(object.css("padding-left"), 10) + parseInt(object.css("padding-right"), 10); //Total Padding Width
    value           += parseInt(object.css("margin-left"), 10) + parseInt(object.css("margin-right"), 10); //Total Margin Width
    value           += parseInt(object.css("borderLeftWidth"), 10) + parseInt(object.css("borderRightWidth"), 10); //Total Border Width
    return value;
}
$(document).ready(function() {
	var a = $("#habit-id");
	/*$('.calendar').on('touchstart click',function(event){
		var b = a.scrollTop();
		a.scrollTop(b+100);
	});*/
	$("ul.habit-list li .habit-checkbox .regular-checkbox").each(function(){
		var a = $(this);
		set_checkbox(a);
		a.on('touchstart click',function(event) {
			var d = (parseInt(a.data('value'))+1)%3;
			a.data('value',d);
			// console.log(a.data('value'));
			set_checkbox(a);
		});
	});
});
function set_checkbox(a){
	var d = parseInt(a.data('value'));
	if (d==2){
		a.addClass('na-checkbox');
		a.removeClass('check-checkbox');
	}
	if (d==1){
		a.addClass('check-checkbox');
		a.removeClass('na-checkbox');
	}
	if (d==0){
		a.removeClass('check-checkbox');
		a.removeClass('na-checkbox');
	}
}