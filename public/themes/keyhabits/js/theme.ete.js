$(function(){
	$("div.alert a.close").click(function(){
		$(this).parent().parent().remove();
	});
});