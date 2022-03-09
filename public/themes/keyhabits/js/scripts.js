(function () {
    "use strict";

    // custom scrollbar

    //$("html").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});

//    $(".left-side").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0'});

//    $(".goals-tamplates").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
//console.log(deviceType,'deviceType');

if(deviceType == 'Desktop'){
// $(document).ready(function(){
//    $("html").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
//    $(".show-goals-tamplates").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
//     $(document).ajaxSuccess(function () {
//        $("html").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
//        $(".show-goals-tamplates").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
//     });
//     })
}
    $('.flashes').fadeOut(4000);

    $(".left-side").getNiceScroll();

    if ($('body').hasClass('left-side-collapsed')) {
      //  $(".left-side").getNiceScroll().hide();
    }
    $(".trigger").click(function (ev) {
//           ev.stopImmediatePropagation();
//           ev.stopPropagation();
//            ev.preventDefault();
          var iclass=  $(this).toggleClass('menu-opened');
          if($(this).hasClass('menu-opened')){
             $(".header-mobile").addClass("show-menu");
         }
    });
    $(".trigger.menu-opened").click(function (ev) {
        $(".header-mobile").removeClass("show-menu");
    })
    
$('.main-content').mouseup(function(e){
   var menu = $('.header-mobile');
   if (!menu.is(e.target) // The target of the click isn't the container.
   && menu.has(e.target).length === 0) // Nor a child element of the container
   {
       if(menu.hasClass('show-menu')){
          if($(".trigger").hasClass('menu-opened')){
                menu.removeClass('show-menu');
          }else{
             
          }
       
       }
   }
});


    // Toggle Left Menu
    jQuery('.menu-list > a').click(function () {

        var parent = jQuery(this).parent();
        var sub = parent.find('> ul');

        if (!jQuery('body').hasClass('left-side-collapsed')) {
            if (sub.is(':visible')) {
                sub.slideUp(200, function () {
                    parent.removeClass('nav-active');
                    jQuery('.main-content').css({height: ''});
                    mainContentHeightAdjust();
                });
            } else {
                visibleSubMenuClose();
                parent.addClass('nav-active');
                sub.slideDown(200, function () {
                    mainContentHeightAdjust();
                });
            }
        }
        return false;
    });

    function visibleSubMenuClose() {
        jQuery('.menu-list').each(function () {
            var t = jQuery(this);
            if (t.hasClass('nav-active')) {
                t.find('> ul').slideUp(200, function () {
                    t.removeClass('nav-active');
                });
            }
        });
    }

    function mainContentHeightAdjust() {
        // Adjust main content height
        var docHeight = jQuery(document).height();
        if (docHeight > jQuery('.main-content').height())
            jQuery('.main-content').height(docHeight);
    }

    //  class add mouse hover
    jQuery('.custom-nav > li').hover(function () {
        jQuery(this).addClass('nav-hover');
    }, function () {
        jQuery(this).removeClass('nav-hover');
    });


    // Menu Toggle
    jQuery('.toggle-btn').click(function () {
       // $(".left-side").getNiceScroll().hide();

        if ($('body').hasClass('left-side-collapsed')) {
         //   $(".left-side").getNiceScroll().hide();
        }
        var body = jQuery('body');
        var bodyposition = body.css('position');

        if (bodyposition != 'relative') {

            if (!body.hasClass('left-side-collapsed')) {
                body.addClass('left-side-collapsed');
                jQuery('.custom-nav ul').attr('style', '');

                jQuery(this).addClass('menu-collapsed');

            } else {
                body.removeClass('left-side-collapsed chat-view');
                jQuery('.custom-nav li.active ul').css({display: 'block'});

                jQuery(this).removeClass('menu-collapsed');

            }
        } else {

            if (body.hasClass('left-side-show'))
                body.removeClass('left-side-show');
            else
                body.addClass('left-side-show');

            mainContentHeightAdjust();
        }

    });


    searchform_reposition();

    jQuery(window).resize(function () {

        if (jQuery('body').css('position') == 'relative') {

            jQuery('body').removeClass('left-side-collapsed');

        } else {

            jQuery('body').css({left: '', marginRight: ''});
        }

        searchform_reposition();

    });

    function searchform_reposition() {
        if (jQuery('.searchform').css('position') == 'relative') {
            jQuery('.searchform').insertBefore('.left-side-inner .logged-user');
        } else {
            jQuery('.searchform').insertBefore('.menu-right');
        }
    }
})(jQuery);

/*
// Dropdowns Script
$(document).ready(function () {
    $(document).on('click', function (ev) {
        ev.stopImmediatePropagation();
        $(".dropdown-toggle").dropdown("active");
    });
});

*/


/************** Search ****************/
$(function () {
    var button = $('#loginButton');
    var box = $('#loginBox');
    var form = $('#loginForm');
    button.removeAttr('href');
    button.mouseup(function (login) {
        box.toggle();
        button.toggleClass('active');
    });
    form.mouseup(function () {
        return false;
    });
    $(this).mouseup(function (login) {
        if (!($(login.target).parent('#loginButton').length > 0)) {
            button.removeClass('active');
            box.hide();
        }
    });
});
//$(document).ready(function () {
//    $('.has-sub').on('click', function () {
//        $(this).children('td.goal').children('.fa').toggleClass('fa-caret-right fa-caret-down');
//        var hasSubId = (this.id).split("_")[1];
//        $('#withsub_' + hasSubId).slideToggle();
//    });
//    $('.task-tabs a').on('click', function () {
//        $('.task-tabs a').removeClass('active');
//        $(this).addClass('active');
//    });
//});



$(document).ready(function () {
    $(document).on('click', ".title-g-t", function () {
        $(".goals-tamplates").toggleClass("show-goals-tamplates", 100);
        $(".main-content").toggleClass("push-right", 100);
       // $(".lobby-goals-rows").toggle();
    //    if(deviceType == 'Desktop'){
     //   $(".show-goals-tamplates").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
      //  }
    });
});

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

   setTimeout(function () {
     var _winHeight=$('.fullheightsection').height()*0.60-$('#header-id').height();
    var _winHeight2 = ($(window).height()/2-$('#header-id').height());
   // console.log(_winHeight,_winHeight2,'333');
    $('.halfheightsec').css('height',_winHeight2);

    //var devider_height=_winHeight2/2;
    
    //$(".halfheightsec#character").css('height',devider_height);

    //$(".halfheightsec#statement-values").css('height',devider_height);

       // var _winHeight = $(window).height();
       // if (typeof _winHeight == 'undefined' || _winHeight == 0) {
       //     _winHeight = '400';
       // }
       // $('.hide-on-mobile #habit-id ul.habit-list').css({'height': (_winHeight * 0.5 - 60)}); // 0.5 = 50%, 0.8 = 80%
       // $('.hide-on-mobile .task-reminders-wrapper ul.task-list').css({'height': _winHeight * 0.5 - 60}); // 0.25 = 25%
       // $('.hide-on-mobile ul.character-list').css({'height': _winHeight * 0.5 - 60}); // 0.25 = 25%
       // $('.compact-view-goals').css({'height': _winHeight}); // 0.25 = 25%
       // $('.goal-create-details').css({'height': _winHeight}); // 0.25 = 25%
   }, 1);

});

jQuery(window).bind("load resize", function() {
    var _winHeight=$('.fullheightsection').height()*0.60-$('#header-id').height();
    var _winHeight2 = ($(window).height()/2-$('#header-id').height());
   // console.log(_winHeight,_winHeight2,'333');
    $('.halfheightsec').css('height',_winHeight2);

    //var devider_height=_winHeight2/2;
    //$(".halfheightsec#character").css('height',devider_height);
    //$(".halfheightsec#statement-values").css('height',devider_height);
})
//jQuery(window).bind("load resize", function() {
//    console.log($('.fullheightsection').height()*0.60-$('#header-id').height(),'333');
//    $('#task').height($('.fullheightsection').height()*0.60-$('#header-id').height());
//	$('#chararter').height($('.fullheightsection').height()*0.60-$('#header-id').height());
//	$('#chararter').width($('.fullheightsection').width()-$('#task').width()-5);
//var _winHeight=$('.fullheightsection').height()*0.60-$('#header-id').height();
//    $('.hide-on-mobile .task-reminders-wrapper ul.task-list').css({'height': _winHeight * 0.5 - 60}); // 0.25 = 25%
//    $('.hide-on-mobile ul.character-list').css({'height': _winHeight * 0.5 - 60}); // 0.25 = 25%
//})
//$(window).bind("load resize", function () {
//    _winHeight = $(window).height();
    //    $('ul.habit-list .notavailable').parent().find('.checked').css('height','20px !important');
//    $('ul.habit-list .notavailable').parent('.checked').css('height','20px !important');
    // Setting Height
//
//    if (typeof (Storage) !== "undefined") {
//        // Store
//        localStorage.setItem("windowheight", _winHeight);
//        // Retrieve
//
//    } else {
//        _winHeight = "400";
//        // Sorry! No Web Storage support..
//    }

//
//    $('.hide-on-mobile #habit-id ul.habit-list').css({'height': (_winHeight * 0.5 - 60)}); // 0.5 = 50%, 0.8 = 80%
//    $('.hide-on-mobile .task-reminders-wrapper ul.task-list').css({'height': _winHeight * 0.5 - 60}); // 0.25 = 25%
//    $('.hide-on-mobile ul.character-list').css({'height': _winHeight * 0.5 - 60}); // 0.25 = 25%
//    $('.compact-view-goals').css({'height': _winHeight}); // 0.25 = 25%
//    $('.goal-create-details').css({'height': _winHeight}); // 0.25 = 25%
//});


//$(document).ajaxSuccess(function () {
//    _winHeight = $(window).height();
//    // Setting Height
//    $('.hide-on-mobile #habit-id ul.habit-list').css({'height': (_winHeight * 0.5 - 60)}); // 0.5 = 50%, 0.8 = 80%
//    $('.hide-on-mobile .task-reminders-wrapper ul.task-list').css({'height': _winHeight * 0.5 - 60}); // 0.25 = 25%
//    $('.hide-on-mobile ul.character-list').css({'height': _winHeight * 0.5 - 60}); // 0.25 = 25%
//    $('.goasl').css({'height': _winHeight * 0.5 - 60}); // 0.25 = 25%
//    $('.compact-view-goals').css({'height': _winHeight}); // 0.25 = 25%
//    $('.goal-create-details').css({'height': _winHeight}); // 0.25 = 25%
//    $('.goasl').css({'height': _winHeight}); // 0.25 = 25%
//})