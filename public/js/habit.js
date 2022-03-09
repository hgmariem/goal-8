$(document).ready(function() {
	 $(".habit_list tr td input.regular-checkbox").each(function(){
		var a = $(this);
		set_checkbox(a);
		a.on('touchstart click',function(event) {
			var d = (parseInt(a.data('value'))+1)%3;
			a.data('value',d);
			console.log(a.data('value'));
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