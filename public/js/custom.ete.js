var temp_id=Math.floor(100000000 + Math.random() * 900000000);

$(document).ready(function() {
	$('.task_minimizer').click(function(event) {
		event.preventDefault();
		window.history.go(-1);
	});
});


$( document ).ready(function() {
	$(function(){
	   $(".has-child-level .cta").click(function() {
		$(this).parents("li").children('ul.child-level').slideToggle(300);
	    $(this).toggleClass("plus");
	    return false;
	  });
	});
});

$( document ).ready(function() {
	
  $(function(){
	   $(".trophy-room .year-trophy > h4,.trophy-room .month-trophy > h4").click(function() {
		$(this).next('ul').slideToggle(300);
	    return false;
	  });
	});

  
});

jQuery(window).bind("load resize", function() {

    resize_fix_window();

});

var resize_fix_window = function(){

  setTimeout(function () {

    var window_height = $(window).height();

    var statement_values_section = $(".statement-values-section").height();
    
    var personal_statement_section = $(".personal-statement-section").height();


    //$(".statement-values-section").css({height:(window_height-personal_statement_section),'overflow':"hidden"});

    var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    var calc_height = h-personal_statement_section;
    $("ul.statement-list,ul.values-list").css({"max-height":calc_height-55});

    //console.log("window_height",window_height);
    //console.log("statement_values_section",statement_values_section);
    //console.log("personal_statement_section",personal_statement_section);

  },100);

};

(function($) {
  $.fn.serializeFiles = function() {
    var form = $(this),
        formData = new FormData();
        formParams = form.serializeArray();

    $.each(form.find('input[type="file"]'), function(i, tag) {
      $.each($(tag)[0].files, function(i, file) {
        formData.append(tag.name, file);
      });
    });

    $.each(formParams, function(i, val) {
      formData.append(val.name, val.value);
    });

    return formData;
  };

  $.fn._resetForm = function() {
    var form = $(this);
    form.find('input:text, input:password, input:file, select, textarea').val('');
    form.find('input:radio, input:checkbox')
         .removeAttr('checked').removeAttr('selected');
  };
  

  $.extend({
    tloader: function(show,text) {
        if(show=='show'){
         
         $(".tloader").show();
         
         $(".tloader .loading-text").html(text);

        }else{
           $(".tloader").hide();
        }
    }
});


})(jQuery)
