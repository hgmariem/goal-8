$(document).ready(function() {

	$(".show-task-link").each(function() {
        var a = $(this);
		a.bind('touchstart click',function(event) {
			var b = $(this).parent().parent().parent().parent();
			var c = b.find('> ul');
			if (c.css("display") == "none"){
				c.show();
 			    $(this).removeClass('bg2');
 			    $(this).addClass('bg1');
			}
			else{
				c.hide();
 			    $(this).removeClass('bg1');
 			    $(this).addClass('bg2');
			}
			event.preventDefault();
			return false;	
		});
    });
});
