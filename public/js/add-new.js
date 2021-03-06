var total_goal = 0;
var count = 1;
/*d_id để tạo gán id vào sub-container */
var d_id = 1;
var drag_id = '';
/* is_drag để kiểm tra chỉ drag goal hay ko */
var is_drag = 0;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery(document).ready(function() {
   
   //console.log("inm dssgd gdf gdf");

   var add_link = $("#add-sub");

    d_id = $("#sub-goal").attr('data-totalsub') + 1;
    console.log(add_link);

    add_link.bind('touchstart click', function(event) {
        
    });

    var add_main_goal= function(_this){

        console.log("main goal");
        var b = _this.parent();
        var g_check = b.find('.show-goal-link');
        if (!g_check.length) {
            var goal_link = '<a id="' + count + '" class="show-goal-link bg1" href="#"><!--<i class="fa fa-chevron-right"></i>--></a>';
            count++;
            b.prepend(goal_link);
            collapse_goal();
        }
        
        var first_goal_txt = $('.first-text').val();

        console.log(first_goal_txt);

        if (typeof first_goal_txt == 'undefined' || first_goal_txt == '') {
            
            console.log("going to popup");

            pop_up_error("You didn't enter anything in input box, Please enter and then continue.");
            return false;
        } else {
            console.log("calling new_sub2");
            new_sub($('#sub-goal'));
        }

        event.preventDefault();

    };

    var add_sub_goal = function(_this){

        
            var a = _this.parents("li:first");//.parent().parent();
            
            //console.log(a);

            var b = _this.parents("div.goal-top");//.parent();
            var g_check = b.find('.show-goal-link');
            if (!g_check.length) {
                var goal_link = '<a class="show-goal-link bg1" href="#"><i class="fa fa-chevron-right"></i></a>';
                b.prepend(goal_link);
                collapse_goal();
            }
            //console.log("calling new_sub1");
            new_sub(a);
            event.preventDefault();
    };

    $('.new-goal').unbind("touchstart click").on('touchstart click', function(event) {
        console.log("subgoal");
        var _this=$(this);

        if (_this.attr('id') != 'add-sub') {
            add_sub_goal(_this);
        }else{
            add_main_goal(_this);
        }
    });

    fill_status();
    fill_lobby();
    fill_type();
    fill_habit();
    check_status();
    check_lobby();
    check_type();
    check_sub_container();
    collapse_goal();
    //statement_values();

    // Set current date for top parent goal's due date
    if ($("#top_goal_due")[0])
    {
        $("#top_goal_due").attr('value', current_date());
    }

    // Set current date for top habit start date
    if ($("#top_habit_start_date")[0])
    {
        $("#top_habit_start_date").attr('value', current_date());
    }
    
    applysortable();    
});

function applysortable(){
    /*$(".sub-container").sortable({
                connectWith: ".sub-container",
                start: function (event, ui) {
                        ui.item.toggleClass("highlight");
                },
                stop: function (event, ui) {
                        ui.item.toggleClass("highlight");
                }
    });*/
    
    /*var ns = $('div#sub-goal').nestedSortable({
        listType:"li",
        handle: 'div',
        items: 'div.sub-container',
        toleranceElement: '> div'
    });*/

    if(isDesktop){

        $('ul#sub-goal').nestedSortable({
            handle: '.handle-goal',
            items: 'li',
            listType: 'ul',
            opacity: .6,
            toleranceElement: '> div',
            stop: function(event, ui) { 
               
                rebuild_collapsible(ui.item);
                
            },
            change: function(event, ui){
                console.log("on change...");
                console.log(ui.item);
                
            }
        });
    }

    if(isMobile){

        var parent_lis=$('ul#sub-goal').sortable({
            handle: '.handle-goal',
            items: 'li',
            listType: 'ul',
            opacity: .6,
            toleranceElement: '> div',
            stop: function(event, ui) { 

            }
        });
        
        parent_lis.disableSelection();
    }

    //$(".goal-row-child").disableSelection();
}
function checking2() {

    $('#checkChanged').val(1);
    var empty = 0;
    $('input[type=text][name=name],input[type=text][name=sub_name]').each(function() {
        if (this.value == "") {
            empty++;
            $("#error").show('slow');
        }
    });
    return empty;
}

function generate_random_id(callback){
    
    $.get(site_url+"/goals/generate_autosaveid",function(res){
        var id=(res.id)?res.id:(Math.floor(100000000 + Math.random() * 900000000));
        callback(id);
    });
}

function new_sub(m) {

    generate_random_id(function(random_id){

        var noofempty = checking2();

        if (noofempty > 0) {
            console.log("going to alert again...");

            pop_up_error("You didn't enter anything in input box, Please enter and then continue.");
            return false;
        }

        $('input.text').each(function() {
            $(this).prev('span').html($(this).val());
        });

        // $('input.text').hide();
        $('.newly-added').removeClass('recent-input');
        $('span.sub-title-goal').show();


        // For sub goals
        total_goal++;
        var goal_level = parseInt(m.attr('data-level'), 10) + 1;
        var goal_list = $("#sub-goal").attr('data-list');
        if (goal_list !== "")
            goal_list = goal_list + ',goal-' + total_goal;
        else
            goal_list = 'goal-' + total_goal;
        $("#sub-goal").attr('data-list', goal_list.toString());
        var parent_id = m.attr('id');

        var w = $(document).width();
        var sub_container = $("<li class='sub-container newly-add-container' data-containerid='goal-container-" + d_id + "' id='goal-" + total_goal + "' data-level='" + goal_level + "' data-pid='" + parent_id + "'></li>");
        var goal_top = $("<div class='goal-top' data-id='goal-top-" + d_id + "'></div>");
        // var goal_title     = $("<span><b>Goal</b></span>");
        var input_type = $("<a class='type yellow' href='#' data-value='Undefined' data-id='0'><span class='goal-title goal-title-sub-text'>Undefined</span></a>");
        var input_lobby = $("<a class='lobby green' href='#'  data-id='1' data-value='fa fa-eye'><span class='goal-title goal-title-sub-text'><i class='fa fa-eye'></i></span></a>");
        // var input_status   = $("<a class='status yellow' href='#' id='status' data-value='Active' data-id='1'><b>Active</b></a>");
        var newlink = $("<span class='float-right'><a class='new-goal new-sub-goal' href='#'><i class='fa fa-plus'></i></a></span>");
        var move_goal = $('<a class="handle-goal" id="handle-sub" href="javascript:void(0);"><i class="fa fa-arrows"></i></a>');
        var removelink = $("<span class='float-right'><a class='remove-goal new-sub-goal' href='#'  data-autosaveid='"+random_id+"'><i class='fa fa-close'></i></a></span>");
        var habitlink = $("<a class='habit-schedule settings' data-goal-id='"+random_id+"' data-goal-type='' data-scale='0' data-min='' data-max='' data-is_apply='0'  href='#' id='sub_habit_type' data-habit-type='1;7'><i class='fa fa-cog'></i></a>");
        habitlink.css('display', 'none');
        var input_date = $("<input class='goal-due date' type='text' name='sub_due_date' value='" + current_date() + "'>");
        var input_habit_date = $("<input class='habit-start-date date' type='text' name='sub_habit_start_date' value='" + current_date() + "'>");
    //    var input = $("<span class='sub-title-goal newly-addedinpt' data-pid='" + d_id + "' style='display:none;'></span><input class='text newly-added recent-input' type='text' name='sub_name' placeholder='Name of sub goal' ondrop='drop_inside(event)' ondragover='allowDrop(event)' data-id='goal-input-" + d_id + "' />");
        var input = $("<input class='text newly-added recent-input' type='text' name='sub_name' placeholder='Name of sub goal' data-id='goal-input-" + d_id + "' />");
        var input_habit_type = $("<input type='hidden' name='sub_habit_type' value='1' />");
        var add_text_type = $("<input type='hidden' name='add_text_type' value='' />");
        var add_goal_auto_save_input=$("<input type='hidden' name='auto_save_id' value='"+random_id+"'>");
        d_id = d_id + 1;
        
        goal_top.append(move_goal);
        goal_top.append(input_type);
        
        goal_top.append(input_lobby);
        input_lobby.hide();
        // goal_top.append(input_status);
        goal_top.append(habitlink);
        goal_top.append(input_date);
        goal_top.append(input_habit_type);
        goal_top.append(add_text_type);
        goal_top.append(input_habit_date);
        goal_top.append(add_goal_auto_save_input);
        input_date.attr('value', current_date());
        input_habit_date.attr('value', current_date());
       
        goal_top.append(newlink);
        goal_top.append(removelink);
    //goal_top.append("<span class='float-right'><a class='remove-goal new-sub-goal' href='#'><i class='fa fa-close'></i></a><a class='new-goal new-sub-goal' href='#'><i class='fa fa-plus'></i></a></span>");
        sub_container.append(goal_top);
        sub_container.append("<div class='clearfix'></div>");
        sub_container.append(input);
        sub_container.append("<div class='clearfix'></div>");

        if(m.prop('nodeName') && m.prop('nodeName').toLowerCase()!='ul'){ // main goal child

            if(!m.children(">ul").length){
                console.log("no UL found!!");
                m.append(sub_container);
                sub_container.wrap("<ul></ul>");
            }else{
                console.log("UL found!!");
                m.find(">ul").append(sub_container);
            }
        }else{
            console.log("direct child");
            m.append(sub_container); // append direct child... for main goal
        }

        fill_status();
        fill_lobby();
        fill_type();
        fill_habit();
        check_type();

        removelink.unbind("touchstart click").bind('touchstart click', function(event) {

            //console.log($(this));
            
            console.log("delete.....");

            var autosaveid=$(this).find("a.remove-goal").attr("data-autosaveid");
            
            //console.log(autosaveid);

            var c = $(this).parent().parent().parent();

            if (c.attr('id') == 'sub-goal')
                c = c.parent();
            var b = c.find('.goal-top').find('.show-goal-link');
            var checkstr = confirm('Are you sure you want to delete this?');
            if (checkstr) {
                var a = $(this).parent().parent();
                a.remove();
                var g_check = c.find('.sub-container');
                if (!g_check.length)
                    b.remove();
                var list = $("#sub-goal").attr('data-list');
                list = list.split(',');
                var new_list = '';
                for (i = 0; i < list.length; i++) {
                    if ($('#' + list[i])[0])
                    {
                        if (!new_list)
                            new_list += list[i];
                        else
                            new_list += ',' + list[i];
                    }
                }
                $("#sub-goal").attr('data-list', new_list.toString());
                
                

                 $.post(site_url+'/goals/delete', {auto_save_id: autosaveid}, function(data) {
                       if (data.status == 1)
                       {

                        console.log(data);
                       }
                   });
            }
            
            event.preventDefault();
            return false;
        });

        newlink.unbind("touchstart click").bind('touchstart click', function(event) {
            var a = $(this).parent().parent();
            var b = $(this).parent();
            var g_check = b.find('.show-goal-link');
            if (!g_check.length) {
                var noofempty = checking2();
                if (noofempty == '0') {
                    var goal_link = '<a id="' + count + '" class="show-goal-link bg1" href="#"><i class="fa fa-chevron-right"></i></a>';
                    count++;
                    b.prepend(goal_link);
                    collapse_goal();
                }
            }
            console.log("calling new_sub3");
            new_sub(a);
            event.preventDefault();
            return false;
        });
        /*----*/
        //drag_hover_top(goal_top);
        /*----*/
        //drag_hover_bottom(input);
        /*sub_container.hover(function() {
            $(this).css("cursor", "move");
        }, function() {
            $(this).css("cursor", "default");
        });*/
    });
}

function fill_status() {

    $('.status').bind('touchstart click', function(event) {
        var a = $(this);
        var b = $("#light-box");
        var c = b.find('.modal-body');
        var h = b.find('.modal-header');
        var f = b.find('.modal-footer');
        c.empty();
        f.empty();
        h.empty();
        var input_status = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary _active" data-id="1">Active</button><button type="button" name="att" class="btn btn-primary" data-id="0">Inactive</button></div>');

        if (a.attr('data-value') == 'Inactive') {
            input_status = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="1">Active</button><button type="button" name="att" class="btn btn-primary _active" data-id="0">Inactive</button></div>');
        }
        c.append(input_status);
        b.modal();
        input_status.children("button").bind('touchstart click', function(event) {
            a.attr('data-value', $(this).html());
            a.attr('data-id', $(this).attr('data-id'));
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
            a.html("<span class='goal-title goal-title-sub-text'>" + $(this).html() + "</span>");
            if ($(this).html() == "Active") {
                a.removeClass("red");
                a.addClass("yellow");
            }
            if ($(this).html() == "Inactive") {
                a.removeClass("yellow");
                a.addClass("red");
            }
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
            b.modal('hide');
        });
        event.preventDefault();
        return false;
    });
}

var  hide_all_sub_goals = function(elem){

    var parent_li=elem.parents("li:first");

    var children = parent_li.find("ul").find(".lobby");

    if(children.length){
        /*var c = confirm("would you like to hide all subgoals?");
        if(c){
            $.each(children,function(i, eye){
                $(eye).removeClass("green").addClass("red").attr("data-value",0).attr("data-id",0);
                $(eye).find(".fa-eye").removeClass("fa-eye").addClass("fa-eye-slash");
            });
        }*/

         subgoal_action_popup(false, children);
    }
};



var  show_all_sub_goals = function(elem){

    var parent_li=elem.parents("li:first");

    var children = parent_li.find("ul").find(".lobby");
  
    if(children.length){

        subgoal_action_popup(true, children);
        /*var c = confirm("would you like to show all subgoals?");
        if(c){
            $.each(children,function(i, eye){
                $(eye).removeClass("red").addClass("green").attr("data-value",1).attr("data-id",1);
                $(eye).find(".fa-eye-slash").removeClass("fa-eye-slash").addClass("fa-eye");
            });
        }*/
    }
};

var subgoal_action_popup = function(show, children){

    var b = $("#info-light-box");
    var h = b.find('.modal-header');
    var c = b.find('.modal-body');
    var f = b.find('.modal-footer');
    h.empty();
    c.empty();
    f.empty();
    var msg = "Would you like to hide all subgoals?";
    if(show){
        msg = "Would you like to show all subgoals?";
    }

    c.append(msg);

    var button = $('<div><button class="btn btn-warning" id="goal-action-no" style="margin-right:0.5em;" data-value="no">No</buton> <button class="btn btn-success" id="goal-action-yes" data-value="yes">Yes</buton></div>');
    f.append(button);
    
    console.log("next opened....");

    b.modal("show");
    b.removeClass("hide");

    button.children("button").bind('touchstart click', function(event) {
        
        console.log("clicked");

        if($(this).attr("data-value")=='yes'){
             
             if(show){
                $.each(children,function(i, eye){
                    $(eye).removeClass("red").addClass("green").attr("data-value",1).attr("data-id",1);
                    $(eye).find(".fa-eye-slash").removeClass("fa-eye-slash").addClass("fa-eye");
                });
             }else{
                $.each(children,function(i, eye){
                    $(eye).removeClass("green").addClass("red").attr("data-value",0).attr("data-id",0);
                    $(eye).find(".fa-eye").removeClass("fa-eye").addClass("fa-eye-slash");
                });
             }
        } 

        console.log("next closed...");
        b.modal('hide');
    });
    
    return false;
}

function fill_lobby() {

    $('.lobby').bind('touchstart click', function(event) {
        var a = $(this);
        //console.log(a.data('value'));
        var b = $("#light-box");
        var h = b.find('.modal-header');
        var c = b.find('.modal-body');
        var f = b.find('.modal-footer');
        h.empty();
        c.empty();
        f.empty();

        var input_status = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary _active" data-id="1" data-rvalue="fa fa-eye">Show in lobby</button><button type="button" name="att" class="btn btn-primary" data-id="0" data-rvalue="fa fa-eye-slash">Hide in lobby</button></div>');
        if (a.attr('data-value') == 0) {
            input_status = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="1" data-rvalue="fa fa-eye">Show in lobby</button><button type="button" name="att" class="btn btn-primary _active" data-id="0" data-rvalue="fa fa-eye-slash">Hide in lobby</button></div>');
        }

        c.append(input_status);
        b.modal();
        input_status.children("button").bind('touchstart click', function(event) {
            a.attr('data-value', $(this).attr('data-id'));
            a.attr('data-id', $(this).attr('data-id'));
            $('#checkChanged').val(1);
            a.html("<span class='goal-title goal-title-sub-text'>" + '<i class="' + $(this).attr('data-rvalue') + '"></i>' + "</span>");
            
            if ($(this).html() == "Show in lobby") {
                a.removeClass("red");
                a.addClass("green");
                //console.log("first closed...");
                b.modal('hide');
                show_all_sub_goals(a);
            }

            if ($(this).html() == "Hide in lobby") {
                a.removeClass("green");
                a.addClass("red");

                //console.log("first closed...");
                b.modal('hide');

                hide_all_sub_goals(a);
            }

            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
            
        });
        event.preventDefault();
        return false;
    });
}

function fill_type() {
    $('.type').unbind("touchstart click").bind('touchstart click', function(event) {
        var a = $(this);
        var b = $("#light-box");
        var header = b.find('.modal-header');
        var c = b.find('.modal-body');
        var footer = b.find('.modal-footer');
        header.empty();
        c.empty();
        footer.empty();

        var d = a.parent();
        var e = d.find('.goal-due');
        var f = d.find('.lobby');
        var g = d.find('.habit-start-date');
        var h = d.find('.habit-schedule');
        //c.empty();
        //console.log(a);
        //console.log(a.data('value'));
        var input_type = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary active" data-id="0">Undefined</button><button type="button" name="att" class="btn btn-primary" data-id="1">Habit</button><button type="button" name="att" class="btn btn-primary" data-id="2">Task</button><button type="button" name="att" class="btn btn-primary" data-id="3">Character</button></div>');
        if (a.attr('data-value') == 'Habit') {
            input_type = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="0">Undefined</button><button type="button" name="att" class="btn btn-primary active" data-id="1">Habit</button><button type="button" name="att" class="btn btn-primary" data-id="2">Task</button><button type="button" name="att" class="btn btn-primary" data-id="3">Character</button></div>');
        }
        if (a.attr('data-value') == 'Task') {
            input_type = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="0">Undefined</button><button type="button" name="att" class="btn btn-primary" data-id="1">Habit</button><button type="button" name="att" class="btn btn-primary active" data-id="2">Task</button><button type="button" name="att" class="btn btn-primary" data-id="3">Character</button></div>');
        }
        if (a.attr('data-value') == 'Character') {
            input_type = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="0">Undefined</button><button type="button" name="att" class="btn btn-primary" data-id="1">Habit</button><button type="button" name="att" class="btn btn-primary" data-id="2">Task</button><button type="button" name="att" class="btn btn-primary active" data-id="3">Character</button></div>');
        }
        c.append(input_type);
        b.modal();
        input_type.children("button").unbind('touchstart click').bind('touchstart click', function(event) {
            a.attr('data-value', $(this).html());
            a.attr('data-id', $(this).attr('data-id'));
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);

            a.html("<span class='goal-title goal-title-sub-text'>" + $(this).html() + "</span>");
            if ($(this).html() == 'Task') {
                //alert(e.val());
                g.css('display', 'none');
                h.attr("data-goal-type",2);
                h.css('display', 'inline-block').show();
                e.css('display', 'inline-block').show();
                e.attr('value', current_date());
                e.pickadate({
                    clear: '',
                    close: 'Close',
                    onSet: function(thingSet) {
                        
                        console.log(thingSet);

                        $('#checkChanged').val(1);
                        var action = 1;
                        windowloadaleart(action);
                    },
                    onRender: function() {
                        $('.picker__box').prepend('<a href="javascript:;" class="close_calender">X</a>');
                        $('.close_calender').on('click', function() {
                            var esc = $.Event("keydown", {keyCode: 27});
                            $("body").trigger(esc);
                        });
                    }
                });
            } else if ($(this).html() == 'Habit') {
                h.attr("data-goal-type",1);
                e.css('display', 'none');
                g.css('display', 'inline-block').show();
                g.attr('value', current_date());
                g.pickadate({
                    clear: '',
                    close: 'Close',
                    onSet: function(thingSet) {
                        $('#checkChanged').val(1);
                        var action = 1;
                        windowloadaleart(action);
                    },
                    onRender: function() {
                        $('.picker__box').prepend('<a href="javascript:;" class="close_calender">X</a>');
                        $('.close_calender').on('click', function() {
                            var esc = $.Event("keydown", {keyCode: 27});
                            $("body").trigger(esc);
                        });
                    }
                });
                h.css('display', 'inline-block').show();
            } else {
                h.attr("data-goal-type",3);
                e.css('display', 'none');
                g.css('display', 'none');
                h.css('display', 'none');
            }
            if ($(this).html() == 'Undefined') {
                h.attr("data-goal-type",0);
                f.hide();
            } else {
                f.show();
            }
            b.modal('hide');
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
        });
        event.preventDefault();
        return false;
    });
}
function fill_habit() {
    
    $('.habit-schedule').bind('touchstart click', function(event) {
        
        var a = $(this);

        if(a.attr("data-goal-type")==2){
            task_template_popup(a);
        }else{
            habit_schedule_popup(a);
            
        }

        event.preventDefault();
        return false;
    });
}

function get_template(task_id, callback){
    
    $.get(site_url+'/goals/get-template', {task_id:task_id}, function(response) {
              
       if (response.status == 1)
       {
            //console.log(response);
            callback(response);
       }
    });

    callback(false);
}

function task_template_popup(a){
    var noofempty = checking2();

    if (noofempty > 0) {
        //console.log("going to alert again...");
        pop_up_error("You didn't enter anything in input box, Please enter and then continue.");
        return false;
    }

    var b = $("#light-box");
    var c = b.find('.modal-body');
    var h = b.find('.modal-header');
    var f = b.find('.modal-footer');
    var d = a.parent();

    console.log(a);

    var task_id= a.attr("data-goal-id");

    var sub_due_date = a.parent().find("input[name='sub_due_date']");

    var ends_on_picker = sub_due_date.val();

    var ddd = moment(ends_on_picker).format('MM/DD/YYYY');
    
    console.log(ddd);

    c.empty();
    
    f.empty();
    
    h.empty();

    //console.log(d.next("input"));

    var repeat_qty=1;
    var repeat_frequency="months";
    var repeat_on = 'thisday';
    var task_name=d.next("input").val();
    var template_name="";
    var repeat_qty=1;
    var add_suffix = false;
    var ends_on = "occurrences";
    var ends_on_value = '';

    var begin_on = "now";
    var begin_on_value = '';

    h.html('<h3> Task settings </h3>');

    var body = '<div class="template-form">';

    body +='<div class="repeat-every-section">'
    +' <span>Repeat Every:</span>'
    +' <input type="text" value="'+repeat_qty+'" id="_repeat_qty" name="repeat_qty" class="task-sml">'
    +' <select name="repeat_frequency" onchange="test();" id="_repeat_frequency">'
    if(repeat_frequency=='weeks'){
        body +=' <option value="weeks" selected>Weeks</option>';
    }else{
        body +=' <option value="weeks">Weeks</option>'; 
    }

    if(repeat_frequency=='months'){
        body +=' <option value="months" selected>Months</option>';
    }else{
        body +=' <option value="months">Months</option>';
    }

    if(repeat_frequency=='years'){
        body +=' <option value="years" selected>Years</option>';
    }else{
       body +=' <option value="years">Years</option>'; 
    }

    body +=' </select>'
    +' </div><br>';
    
    body +='<div class="template-name-section">'
    +' <span>Sub Task Name:</span>'
    +' <input type="text" value="'+task_name+'" name="task_name" id="_task_name" style="width: 71%;">'
    //+' <span>Template Name:</span>'
    //+' <input type="text" value="'+template_name+'" name="template_name" id="_template_name">'
    +' </div><br>';

    body +='<div class="repeat-on-section weeks">'
    +'<input type="checkbox" value="0" id="weekday-0" class="regular-checkbox weekly_checkbox"  name="week_days[sun]" /><label for="weekday-0"><span></span></label><span>Sun</span>'
    +'<input type="checkbox" value="1" id="weekday-1" class="regular-checkbox weekly_checkbox"  name="week_days[mon]" /><label for="weekday-1"><span></span></label><span>Mon</span>'
    +'<input type="checkbox" value="2" id="weekday-2" class="regular-checkbox weekly_checkbox"  name="week_days[tue]" /><label for="weekday-2"><span></span></label><span>Tue</span>'
    +'<input type="checkbox" value="3" id="weekday-3" class="regular-checkbox weekly_checkbox"  name="week_days[web]" /><label for="weekday-3"><span></span></label><span>Wed</span>'
    +'<input type="checkbox" value="4" id="weekday-4" class="regular-checkbox weekly_checkbox"  name="week_days[thu]" /><label for="weekday-4"><span></span></label><span>Thu</span>'
    +'<input type="checkbox" value="5" id="weekday-5" class="regular-checkbox weekly_checkbox"  name="week_days[fri]" /><label for="weekday-5"><span></span></label><span>Fri</span>'
    +'<input type="checkbox" value="6" id="weekday-6" class="regular-checkbox weekly_checkbox"  name="week_days[sat]" /><label for="weekday-6"><span></span></label><span>Sat</span>'
    
    +'</div>';

    body +='<div class="repeat-on-section months">'
    +'<label class="radio-inline-"><input type="radio" value="thisday"  name="repeat_on" class="repeat_on_thisday" id="repeat_on_thisday" /></label><span style="margin-right:10px;">This day of Each Month</span>'
    +'<label class="radio-inline-"><input type="radio" value="firstday"  name="repeat_on" class="repeat_on_firstday" id="repeat_on_firstday" /></label><span style="margin-right:10px;">First Day of the month</span>'
    +'<label class="radio-inline-"><input type="radio" value="lastday"  name="repeat_on" class="repeat_on_lastday" id="repeat_on_lastday" /></label><span style="margin-right:10px;">Last day of the month</span>'
    +'</div>';

     body +='<div class="repeat-on-section years">'
    +'<label class="radio-inline-"><input type="radio" value="thisday" name="repeat_on" onchange="test();" class="repeat_on_thisday" id="repeat_on_thisday" /></label><span style="margin-right:10px;">This day of Each Year</span>'
    +'<label class="radio-inline-"><input type="radio" value="firstday" name="repeat_on" onchange="test();" class="repeat_on_firstday" id="repeat_on_firstday" /></label><span style="margin-right:10px;">First Day of the Year</span>'
    +'<label class="radio-inline-"><input type="radio" value="lastday" name="repeat_on" onchange="test();" class="repeat_on_lastday" id="repeat_on_lastday" /></label><span style="margin-right:10px;">Last day of the Year</span>'
    +'</div>';

    body +='<br> <div class="name-suffix-section years">'
    +'<input type="checkbox" value="1" id="_add_suffix" class="regular-checkbox" name="add_suffix" />'
    +'<label for="_add_suffix"><span></span></label><span>Do you want to add name of the <span class="_repeat_on_suffix">month</span> behind the task name?</span>'
    +' </div><br>';

    body +='<div class="begins-section">'
    +'<div class="ends-on-left"><span>Begins: </span></div>'
    +'<div class="ends-on-right">'

    +'<div><label class="radio-inline-">'
    +'<input type="radio" value="now" name="begin_on" id="begin_on_now" checked/> </label> <span style="margin-left:-1px;">Now</span>'
    +'</div>'
    +'<div><label class="radio-inline-"><input type="radio" value="date" name="begin_on" id="begin_on_date"/></label>'
    +'<span style="padding-left:4px;">On</span><label> <input type="text" name="begin_on_date" id="begin_on_picker" class="begin_on_date" value="" style="margin-left:4px;"></label></div>'
    +'</div>'
    +'</div><br>';

    body +='<div class="ends-section">'
    +'<div class="ends-on-left"><span>Ends: </span></div>'
    +'<div class="ends-on-right">'
    //+'<div><label class="radio-inline-"><input type="radio" value="never" name="ends_on" id="ends_on_never"/></label> <span>Never</span></div>'
    +'<div><label class="radio-inline-">'
    +'<input type="radio" value="date" name="ends_on" id="ends_on_date" checked/> </label> <span>On</span>'
    +'<label for="ends_on_date"><input type="text" name="ends_on_date" id="ends_on_picker" class="ends_on_date" value="'+ddd+'"></label>'
    +'</div>'
    +'<div><label class="radio-inline-"><input type="radio" value="occurrences" name="ends_on" id="ends_on_occurrences"/></label> <span>After</span> <input type="text" name="occurrences" id="occurrences_box" class="task-sml" value="1"> Occurrences</div>'
    +'</div> <input type="hidden" value="'+task_id+'" name="task_id" id="_task_id"></div><br>';

    body +='</div>';
    
    c.html(body);

    var button = $('<button class="btn btn-warning" id="task-template-cancel" style="margin-right:0.5em;">Cancel</buton> <button class="btn btn-success" id="task-template-save">Done</buton>');
    f.html(button);

    b.modal();
        
        get_template(task_id,function(response){
        var _data=response.data;
       //console.log(_data);
        //if(_data){
            repeat_qty=(_data && _data.repeat_qty)?_data.repeat_qty:1;
            repeat_frequency = (_data && _data.repeat_frequency)?_data.repeat_frequency:"months";
            repeat_on = (_data && _data.repeat_on)?_data.repeat_on:'thisday';
            task_name = (_data && _data.task_name)?_data.task_name:d.next("input").val();
            template_name = (_data && _data.template_name)?_data.template_name:"";
            add_suffix = (_data && _data.add_suffix)?_data.add_suffix:"";
            ends_on = (_data && _data.ends_on)?_data.ends_on:"";
            ends_on_value = (_data && _data.end_on_value)?_data.end_on_value:ddd;

            begin_on = (_data && _data.begin_on)?_data.begin_on:"";
            begin_on_value = (_data && _data.begin_on_value)?_data.begin_on_value:"";

            //console.log(ends_on_value);
            $(".repeat-on-section").hide();
            $(".repeat-on-section."+repeat_frequency).show();
            
            //console.log(repeat_frequency);

            if(repeat_frequency=='weeks'){
                $("#begin_on_now").attr('disabled', true);
                $("#begin_on_date").prop('checked', true);
                var week_days = repeat_on.split(",");
                $.each(week_days,function(i,v){
                    $("#weekday-"+v).attr("checked",true);
                });
               $("span._repeat_on_suffix").html("week");  
            }else if(repeat_frequency=='months'){
                $("#begin_on_now").attr('disabled', false);
                $("span._repeat_on_suffix").html("month");  
                $(".repeat-on-section."+repeat_frequency+" .repeat_on_"+repeat_on).attr("checked",true);
                //console.log($(".repeat_on_"+repeat_on).attr("checked"));
            }else{
               $(".repeat-on-section."+repeat_frequency+" .repeat_on_"+repeat_on).attr("checked",true); 
               $("span._repeat_on_suffix").html("year"); 

               if($(".years #repeat_on_firstday").val()=="firstday")
                  {
               $("#begin_on_now").attr('disabled', true);
               $("#begin_on_date").prop('checked', true); 
                }
                else if($(".years #repeat_on_lastday").val()=="lastday")
                {
                  $("#begin_on_now").attr('disabled', true);
                  $("#begin_on_date").prop('checked', true);   
                }
                else{
                    $("#begin_on_now").attr('disabled', false);
                    $("#begin_on_now").prop('checked', true); 
                }
            }

            $("#_repeat_qty").val(repeat_qty);
            $("#_repeat_frequency").val(repeat_frequency);
            $("#_task_name").val(task_name);
            $("#_template_name").val(template_name);

            $("#_add_suffix").prop("checked",(add_suffix=='0')?false:true);
            $("#_repeat_qty").val(repeat_qty);

            $("#ends_on_"+ends_on).prop("checked",true);
            if(ends_on=='occurrences'){
                $("#occurrences_box").val(ends_on_value);
            }else{
                $("#ends_on_picker").val(ends_on_value);
            }

            $("#begin_on_"+begin_on).prop("checked",true);
            if(begin_on=='now'){
                //$("#occurrences_box").val(ends_on_value);
            }else{
                $("#begin_on_picker").val(begin_on_value);
            }
        //}
    });

    var repeat_frequency = $("select[name='repeat_frequency']").val();

    //console.log(repeat_frequency);
    if(repeat_frequency=='weeks'){
        $(".repeat-on-section.weeks").show();
        $(".repeat-on-section.months").hide();
        $(".repeat-on-section.years").hide();
        $("span._repeat_on_suffix").html("week");
    }else if(repeat_frequency=='months'){
        $(".repeat-on-section.weeks").hide();
        $(".repeat-on-section.months").show();
        $(".repeat-on-section.years").hide();
        $("span._repeat_on_suffix").html("month");
    }else{

        if($(".years #repeat_on_firstday").prop('checked')===true)
            {
                //alert("if");
               $("#begin_on_now").attr('disabled', true);
               $("#begin_on_date").prop('checked', true); 
            }
            else if($(".years #repeat_on_lastday").prop('checked')===true)
            {
                //alert("else");
              $("#begin_on_now").attr('disabled', true);
              $("#begin_on_date").prop('checked', true);   
            }
            else{
                $("#begin_on_now").attr('disabled', false);
                $("#begin_on_now").prop('checked', true); 
            }

        $(".repeat-on-section.weeks").hide();
        $(".repeat-on-section.months").hide();
        $(".repeat-on-section.years").show();
        $("span._repeat_on_suffix").html("year");
    }

    $("select[name='repeat_frequency']").change(function(){
        var repeat_frequency = $(this).val();

        //console.log(repeat_on);

        if(repeat_frequency=='weeks')
        {
            $("#begin_on_now").attr('disabled', true);
            $("#begin_on_date").prop('checked', true);
            $(".repeat-on-section.weeks").show();
            $(".repeat-on-section.months").hide();
            $(".repeat-on-section.years").hide();
            $("span._repeat_on_suffix").html("week");
        }
        else if(repeat_frequency=='months')
        {
            $(".repeat-on-section.weeks").hide();
            $(".repeat-on-section.months").show();
            $(".repeat-on-section.years").hide();
            $("span._repeat_on_suffix").html("month");
            //console.log($(".repeat-on-section.months .repeat_on_"+repeat_on));
            $(".repeat-on-section.years .repeat_on_"+repeat_on).attr("checked",false); 
            $(".repeat-on-section.months .repeat_on_"+repeat_on).attr("checked",true); 
            
        }
        else{

            $(".repeat-on-section.weeks").hide();
            $(".repeat-on-section.months").hide();
            $(".repeat-on-section.years").show();
            $("span._repeat_on_suffix").html("year");
            //console.log($(".repeat-on-section.years .repeat_on_"+repeat_on));
            $(".repeat-on-section.months .repeat_on_"+repeat_on).attr("checked",false); 
            $(".repeat-on-section.years .repeat_on_"+repeat_on).attr("checked",true); 
        }

    });    
            
            //console.log($(".repeat-on-section.months .repeat_on_"+repeat_on));

    $(".begin_on_date").datepicker();
    $(".ends_on_date").datepicker();

    $("#task-template-save").on('touchstart click', function(){
        
        $.tloader("show","Loading...");

        var form_data = $(".template-form").find("select, textarea, input").serialize();
        
        //console.log(form_data);
        
        $.post(site_url+'/goals/save-template', form_data, function(response) {
            console.log(response);
               if (response.status == 1)
               {

                    //console.log(response.data);

                    $.notify(response.msg, {
                        type:'success',
                        z_index: 999999999,
                    });

                    $.tloader("hide");
                    var due_date = moment(response.data.task.due_date).format('DD MMMM, YYYY');
                    //console.log(due_date);
                    sub_due_date.val(due_date);

                    sub_due_date.prop("value",due_date);
                    //d.next("input").val(response.data.task_name);

                    //location.reload();
                    if(response.data.task.is_default == 1)
                    {
                        window.location.href=site_url+"/goals/default/edit/"+response.data.task.top_parent_id;
                    }
                    else
                    {
                        window.location.href=site_url+"/edit/"+response.data.task.top_parent_id;
                    }
                    
                    b.modal('hide');
               }else{
                    $.notify(response.data.join("<br/>"), {
                        type:'danger',
                        z_index: 999999999,
                    });
                   $.tloader("hide");
               }
        });
        return false;
    });

    $('#task-template-cancel').on('touchstart click', function() {
        b.modal('hide');
    });

    $("#occurrences_box").on('touchstart click', function() {
        $("#ends_on_occurrences").prop("checked",true);
    });

    $(".ends_on_date").on('click', function() {
        $("#ends_on_date").prop("checked",true);
    });

    $(".begin_on_date").on('click', function() {
        $("#begin_on_date").prop("checked",true);
    });

    $(".weekly_checkbox").on('touchstart click', function() {
        $(".weekly_checkbox").prop("checked",false);
        $(this).prop("checked",true);
    });
}

function habit_schedule_popup(a){

    var str_habit_type = a.attr("data-habit-type");
    var scale = a.attr("data-scale");
    var lowest = a.attr("data-min");
    var highest = a.attr("data-max");
    var is_apply = a.attr("data-is_apply");
    //var str_habit_type = $habit_type.val();
    var $habit_type_text = a.next('input[name="add_text_type"]');
    
    var arr_habit_type = str_habit_type.split(';');

    var b = $("#light-box");
    var c = b.find('.modal-body');
    var h = b.find('.modal-header');
    var f = b.find('.modal-footer');
    var d = a.parent();
    c.empty();
    f.empty();
    h.empty();

    var str_habit_type_text = $habit_type_text.val();
    
    h.html('<h2>Please choose type: </h2>');
    
    var scale_button_option = '';

    var type_options = '';

    var text_area_options = '';

    scale_button_option +='<div class="btn-group scale-options" data-toggle="buttons-radio">';
    scale_button_option += '<button type="button" name="att" data-value="0" class="btn btn-primary' + ((0 == scale || '' == scale) ? ' _active' : '') + '">True/False</button>';
   
    scale_button_option += '<button type="button" name="att" data-value="1" class="btn btn-primary' + ((1 == scale) ? ' _active' : '') + '">Numeric</button>';
    scale_button_option += '</div><br><br>';

    var input_scale_type = $(scale_button_option);


    var scale_number_options = '<div class="btn-group2 scale-type-options" style="display:none;"><h2><b>Please Choose Scale</b><a href="#" class="choose-scale" data-toggle="modal" data-target="#choose_help"><i class="fa fa-info-circle fa-lg"></i></a></h2>';
    scale_number_options += '<div class="text-center"><input type="checkbox" name="is_apply" id="is_apply" data-value="1" class="regular-checkbox" value="1" '+((is_apply == 1) ? 'checked' : '')+'  '+((is_apply == 1) ? 'disabled' : '')+' /><label for="is_apply"><span></span></label><span class="no_of_days">Does Not Apply</span></div><br>';
    scale_number_options += '<div class="form-inline"><label class="lowest"><b>Lowest</b></label><input type="text" name="lowest" id="lowest" value="'+ ((lowest) ? lowest : '')+'" style="width: 80px; margin-left: 10px;" '+((is_apply == 1) ? "readonly" : "")+'>&nbsp;&nbsp;&nbsp;<label class="highest"><b>Highest</b></label><input type="text" name="highest" value="'+((highest) ? highest : '')+'" id="highest" style="width: 80px; margin-left: 10px;" '+((is_apply == 1) ? "readonly" : "")+'></div></div><br>';
    var option3 = $(scale_number_options);

    type_options +='<div class="btn-group type-options" data-toggle="buttons-radio">';
    type_options += '<button type="button" name="att" data-value="1" class="btn btn-primary' + ((1 == arr_habit_type[0] || '' == arr_habit_type) ? ' _active' : '') + '">7 days/week</button>';
   
    type_options += '<button type="button" name="att" data-value="3" class="btn btn-primary' + ((3 == arr_habit_type[0]) ? ' _active' : '') + '">Routine</button>';
    type_options += '</div><br>';

    var input_type = $(type_options);

    var number_options = '<div class="btn-group2 number-options" data-toggle="buttons-radio"><h2>Please select number of days/week: </h2>';
    number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 1 == arr_habit_type[1]) ? ' _active' : '') + '">1</button>';
    number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 2 == arr_habit_type[1]) ? ' _active' : '') + '">2</button>';
    number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 3 == arr_habit_type[1]) ? ' _active' : '') + '">3</button>';
    number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 4 == arr_habit_type[1]) ? ' _active' : '') + '">4</button>';
    number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 5 == arr_habit_type[1]) ? ' _active' : '') + '">5</button>';
    number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 6 == arr_habit_type[1]) ? ' _active' : '') + '">6</button>';
    number_options += '</div><br>';
    var option1 = $(number_options);

    var day_options = '<div class="btn-group2 day-options"><h2>Please select days:</h2>';
    day_options += '<input type="checkbox" data-value="0" id="checkbox-1-1" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('0')) ? ' checked' : '') + ' /><label for="checkbox-1-1"><span></span></label><span>Sun</span>';
    day_options += '<input type="checkbox" data-value="1" id="checkbox-1-2" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('1')) ? ' checked' : '') + ' /><label for="checkbox-1-2"><span></span></label><span>Mon</span>';
    day_options += '<input type="checkbox" data-value="2" id="checkbox-1-3" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('2')) ? ' checked' : '') + ' /><label for="checkbox-1-3"><span></span></label><span>Tue</span>';
    day_options += '<input type="checkbox" data-value="3" id="checkbox-1-4" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('3')) ? ' checked' : '') + ' /><label for="checkbox-1-4"><span></span></label><span>Wed</span>';
    day_options += '<input type="checkbox" data-value="4" id="checkbox-1-5" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('4')) ? ' checked' : '') + ' /><label for="checkbox-1-5"><span></span></label><span>Thu</span>';
    day_options += '<input type="checkbox" data-value="5" id="checkbox-1-6" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('5')) ? ' checked' : '') + ' /><label for="checkbox-1-6"><span></span></label><span>Fri</span>';
    day_options += '<input type="checkbox" data-value="6" id="checkbox-1-7" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('6')) ? ' checked' : '') + ' /><label for="checkbox-1-7"><span></span></label><span>Sat</span>';
    day_options += '</div><br>';

    var option2 = $(day_options);

    text_area_options +='<div class="add_text_type"><h2>Write down your habit loop</h2><textarea col="12" id="add_text_type" name="add_text_type"  class="form-control" style="margin-right:0.5em;">'+str_habit_type_text+'</textarea></div><br>';

    var text_type = $(text_area_options);

    var button = $('<button class="btn btn-warning" id="habit-schedule-cancel" style="margin-right:0.5em;">Cancel</buton> <button class="btn btn-success" id="habit-schedule-save">Save</buton>');
    c.append(input_scale_type);
    c.append(option3);
    c.append(input_type);
    c.append(option1);
    c.append(option2);
    c.append(text_type);
    f.html(button);

    if (2 == arr_habit_type[0])
    {
        option1.css('display', 'block').show();
    } else
    {
        option1.css('display', 'none');
    }

    if (3 == arr_habit_type[0])
    {
        option2.css('display', 'block').show();
    } else
    {
        option2.css('display', 'none');
    }
    if (arr_habit_type[0] > 1)
    {
        button.css('display', 'inline-block').show();
    }/* else
    {
        button.css('display', 'none');
    }*/

    b.modal();
    input_type.children("button").bind('touchstart click', function(event) {
        
        if ($(this).html() == '7 days/week') {
            button.css('display', 'inline-block').show();
        } else {
            button.css('display', 'inline-block').show();
        }
        if ($(this).html() == 'X days/week') {
            option1.css('display', 'block').show();
            option2.css('display', 'none');
        } else {
            option1.css('display', 'none');
        }
        if ($(this).html() == 'Routine') {
            option2.css('display', 'block').show();
            option1.css('display', 'none');
        } else {
            option2.css('display', 'none');
        }
        
        //console.log("buttn Clicked....");
        $(this).siblings().attr("class","btn btn-primary");
        console.log("active");
        $(this).attr("class","btn btn-primary _active");
        console.log($(this));
        
    });
    if(scale == 1)
    {
       option3.css('display', 'block').show(); 
    }
    else
    {
        option3.css('display', 'none');
    }
    input_scale_type.children("button").bind('touchstart click', function(event) {
        //alert("reached here");
        if ($(this).html() == 'Numeric') {
            option3.css('display', 'block').show();
            //option1.css('display', 'none');
        } else {
            option3.css('display', 'none');
        }
        
        //console.log("buttn Clicked....");
        $(this).siblings().attr("class","btn btn-primary");
        console.log("active");
        $(this).attr("class","btn btn-primary _active");
        console.log($(this));
        
    });
    
    $('#habit-schedule-cancel').on('touchstart click', function() {
        b.modal('hide');
    });

    
    $("#scale-template-cancel").click(function(e){
          $("#choose_help").modal('hide');
        });

    $("#is_apply").unbind("touchstart click").bind("touchstart click",function(){
        if($("#is_apply").prop("checked") == true)
            {
              //$(this).attr("disabled","disabled");
              $("#highest").val("");
              $("#lowest").val("");
              $("#highest").attr("readonly","readonly");
              $("#lowest").attr("readonly","readonly");
              $("#highest").addClass("disabled");
              $("#lowest").addClass("disabled");
              $(".highest").addClass("disabled");
              $(".lowest").addClass("disabled");

            }
          else
          {
              $("#highest").removeAttr("readonly");
              $("#lowest").removeAttr("readonly");
              $("#highest").removeClass("disabled");
              $("#lowest").removeClass("disabled");
              $(".highest").removeClass("disabled");
              $(".lowest").removeClass("disabled");
          }
    });

    $('#habit-schedule-save').on('touchstart click', function() {

        $('#checkChanged').val(1);

      
        var hthis = $(this).parent().prev();
        
        //console.log(hthis);

        var htype = hthis.find('div.type-options').children('button._active').attr('data-value');
        var stype = hthis.find('div.scale-options').children('button._active').attr('data-value');
        var hvalue = 7;
        if(stype == 1)
        {

            if($('#is_apply').prop('checked') == true)
            {
                var is_apply = 1;
                var hlowest = "";
                var highest = "";
            }
            else
            {
                is_apply = 0;
                var hlowest = $('#lowest').val();
                var highest = $('#highest').val();

                if(hlowest == "")
                {
                    alert("Lowest field required here");
                    return false;
                }

                if(highest == "")
                {
                    alert("Highest field required here");
                    return false;
                }

            }
            
            a.attr("data-scale","");
            a.attr("data-min","");
            a.attr("data-max","");
            a.attr("data-is_apply","");
            a.attr("data-scale",stype);
            a.attr("data-min",hlowest);
            a.attr("data-max",highest);
            a.attr("data-is_apply",is_apply);
        }
        else
        {
            a.attr("data-scale","");
            a.attr("data-min","");
            a.attr("data-max","");
            a.attr("data-is_apply","");
            a.attr("data-scale",stype);  
        }

        if (htype == 2)
        {
            hvalue = hthis.find('div.number-options').children('button._active').html();
        } else if (htype == 3)
        {
            hvalue = hthis.find('div.day-options').children('.regular-checkbox:checked').map(function() {
                return $(this).attr('data-value');
            }).get().join(',');
        } else
        {
            hvalue = 7;
        }

        var str = htype + ';' + hvalue;

        a.attr("data-habit-type",str);
        
        a.parent("div.goal-top").find("input[name='sub_habit_type']").val(str);
        
        b.modal('hide');

        var add_text_type = $('#light-box').find("textarea[name='add_text_type']").val();
        $habit_type_text.val(add_text_type);
        
        $('#checkChanged').val(1);
        var action = 1;
        windowloadaleart(action);
    });
}

function pop_up() {
    var b = $("#light-box");
    var c = b.find('.modal-body');
    var h = b.find('.modal-header');
    var f = b.find('.modal-footer');
    c.empty();
    f.empty();
    h.empty();
    var input = '<h4 style="text-align:center;">You did not save change. Do you want to save?</h4><p  style="text-align:center;"><a class="btn btn-success" href="#" style="margin-right:20px;">YES</a> <a class="btn btn-warning" href="#">NO</a></p>'
    c.append(input);
    b.modal();
}
function pop_up_error(message) {
    var b = $("#light-box");
    var c = b.find('.modal-body');
    var h = b.find('.modal-header');
    var f = b.find('.modal-footer');
    c.empty();
    f.empty();
    h.empty();

    var input = '<h4 style="text-align:center;">You did not enter anything in input?</h4><a class="btn btn-warning" href="#">Close</a></p>'
    c.append(message);
    b.modal();
}
function check_status() {
    $('.status').each(function() {
        var a = $(this);
        if (a.attr('data-value') == "Active") {
            a.removeClass("red");
            a.addClass("yellow");
        }
        if (a.attr('data-value') == "Inactive") {
            a.removeClass("yellow");
            a.addClass("red");
        }
    });
}

function check_lobby() {
    $('.lobby').each(function() {
        var a = $(this);
        if (a.attr('data-value') == "Show in lobby") {
            a.removeClass("red");
            a.addClass("green");
        }
        if (a.attr('data-value') == "Hide in lobby") {
            a.removeClass("green");
            a.addClass("red");
        }
    });
}

function check_type() {
    $('.type,.new-type').each(function() {
        var a = $(this);
        var d = a.parent();
        var e = d.find('.goal-due');
        var f = d.find('.lobby');
        var g = d.find('.habit-start-date');
        var h = d.find('.habit-schedule');
        if (a.attr('data-value') == 'Task') {
            // alert(e.val());
            g.css('display', 'none');

            h.attr("data-goal-type",2);
            h.css('display', 'inline-block').show();
            e.css('display', 'inline-block').show();
            // e.attr('value',current_date());
            // e.pickadate();
        } else if (a.attr('data-value') == 'Habit') {
            // alert(g.val());
            e.css('display', 'none');
            g.css('display', 'inline-block').show();
            h.css('display', 'inline-block').show();
            h.attr("data-goal-type","");
            // g.attr('value',current_date());
            // g.pickadate();
        } else {
            e.css('display', 'none');
            g.css('display', 'none');
            h.css('display', 'none');
        }
        if (a.attr('data-value') == 'Undefined') {
            f.hide();
        } else {
            f.show();
        }
    });
}
function current_date() {
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var d = new Date();
    var strDate = d.getDate() + " " + monthNames[d.getMonth()] + ", " + d.getFullYear();
    console.log(strDate);
    
    //alert(strDate);
    //$('#datepicker').attr('value',strDate);
    return strDate;
}

function rebuild_collapsible(ele){
       
    var parent_li=ele.parents("li");

    parent_li.find(">.goal-top").find(">#handle-sub").next(".show-goal-link").remove();

    //console.log(parent_li);
    
    if(parent_li){
        $('<a id="" class="show-goal-link bg1" href="#"><i class="fa fa-chevron-right"></i></a>').insertAfter(parent_li.find(">.goal-top").find(">#handle-sub"));
    }

    /*console.log(ele.prev("li").find("li"));
    
    console.log(ele.prev("li").find("li").length);
    */
    
    if(!ele.prev("li").find("li").length){ // check if previous element has child..
        ele.prev("li").find(">.goal-top").find(">#handle-sub").next(".show-goal-link").remove();
    }

    /*if(!ele.prev("li").find("li").length){
        rebuild_collapsible(ele.prev("li"));
    }*/

    if(!ele.find(">ul").length){ // remove current element collapse
      ele.find(">.goal-top").find(">#handle-sub").next(".show-goal-link").remove();  
    }
    collapse_goal();
};


function collapse_goal() {

    $(".show-goal-link").each(function() {
        $(this).unbind('click');
        $(this).bind('click', function(event) {
            
            console.log($(this).attr('id'));
            
            var a = $(this);
            var b = $(this).parent().parent();
            if (b.hasClass('add-form')) {
                b = b.find('#sub-goal');
            }

            if (a.hasClass('bg1')) {
                b.children('ul').each(function() {
                    $(this).hide();
                });
                a.removeClass('bg1');
                a.addClass('bg2');
            } else if (a.hasClass('bg2')) {
                b.children('ul').each(function() {
                    $(this).show();
                });
                a.removeClass('bg2');
                a.addClass('bg1');
            }
            event.preventDefault();
            return false;
        });
    });
}

function focus_input() {
    var hash = window.location.hash;
    if (hash != '') {
        hash = hash.substr(1);
        /*var show_all_goals = $('#sub-goal').parent().find('> .goal-top').find('.show-goal-link');
         if (show_all_goals.hasClass('bg2')){
         show_all_goals.removeClass('bg2');
         show_all_goals.addClass('bg1');
         }*/
         
        if($('#input-' + hash).length){
            show_tree_goal(hash);
            var v = $('#input-' + hash).val();
            $('#input-' + hash).focus();
            // console.log(v);
            $('#input-' + hash).val(v);
                
            $('.goal').scrollTop($('#input-' + hash).offset().top);
        }
        return false;

    } /*else {
        var lastPart = document.URL.split("/").pop();
        if (lastPart != 'add') {
            //show_tree_goal(lastPart);
            console.log(lastPart);

            var v = $('#input-' + lastPart).val();
            $('#input-' + lastPart).focus();
            $('#input-' + lastPart).val(v);
            $('.goal').scrollTop($('#input-' + lastPart).offset().top);

        }
    }*/
}

function show_tree_goal(id) {

    if($('#input-' + id).length){
        var a = $('#input-' + id).parent();
        kt = false;
        if (!a.hasClass('add-form')) {
            while (kt == false) {
                var b = a.parent();
                a.show();
                if ((b.attr('id') == 'sub-goal'))
                    kt = true;
                else {
                    b.show();
                    var c = b.find('> .goal-top').find('.show-goal-link');
                    //c.removeClass('bg2');
                    //c.addClass('bg1');
                    if (b.parent().attr('id') == 'sub-goal')
                        kt = true;
                    else
                        a = b;
                }
            }
        }
    }
}


/*---DRAG AND DROP --*/
function dragStart(ev)
{
    //var data_send = ev.target.dataset.id + "," + ev.target.dataset.width + "," + ev.target.dataset.height+ "," + ev.target.dataset.wimg + "," + ev.target.dataset.simg;
    // var data_send = ev.target.id;
    var data_send = ev.target.dataset.containerid; // get by data-containerid
    // drag_id = ev.target.id;
    drag_id = ev.target.dataset.containerid;
    is_drag = 1;
    ev.dataTransfer.setData("Text", data_send);
// var dragIcon = document.createElement('img');
// dragIcon.src = 'themes/keyhabits/images/logo-icon.png';
// dragIcon.width = 30;
// dragIcon.height = 50;
// ev.dataTransfer.setDragImage(dragIcon, -10, -10);


    var crt = ev.target.cloneNode(true);
    // crt.style.backgroundColor = "red";
    //crt.style.position = "absolute"; crt.style.top = "0px"; crt.style.left = "100px";

    // document.body.appendChild(crt);
    ev.dataTransfer.setDragImage(crt, 0, 0);



// ev.target.style.overflow = "hidden ";
//      ev.target.style.height = "20px ";
}

/*
function dragEnd(ev) {
    ev.target.style.display = "none ";
    ev.target.style.position = "relative ";
}
function allowDrop(ev)
{
    ev.preventDefault();
}
function drop_above(ev)
{
    if (is_drag == 1) {
        var id = ev.dataTransfer.getData("Text");
        drag_id = id;
        // drop_id = "goal-container-"+ev.target.parentNode.id.split('-')[2];
        if ($(event.target).hasClass('goal-top'))
            drop_id = "goal-container-" + ev.target.dataset.id.split('-')[2];
        else {
            drop_id = "goal-container-" + $(event.target).closest('div[class^="goal-top"]').data('id').split('-')[2];
        }
        if (drop_id != drag_id && $('.sub-container[data-containerid=' + drag_id + ']').has($('.sub-container[data-containerid=' + drop_id + ']')).length == 0) {
            remove_collapse($('.sub-container[data-containerid=' + drag_id + ']'));
            $('.sub-container[data-containerid=' + drop_id + ']').before($('.sub-container[data-containerid=' + drag_id + ']'));
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
            // Set parent id
            $('.sub-container[data-containerid=' + drag_id + ']').data('pid', $('.sub-container[data-containerid=' + drop_id + ']').data('pid'));
            // Set goal level
            $('.sub-container[data-containerid=' + drag_id + ']').data('level', $('.sub-container[data-containerid=' + drop_id + ']').data('level'));
        }
        $('.goal-top').css('border-top', 'none');
        $('.goal-top').css('padding-top', '0px');
        is_drag = 0;
        drag_id = '';
        $('.sub-container').hover(function() {
            $(this).css("cursor", "move");
        }, function() {
            $(this).css("cursor", "default");
        });
    }
    ev.preventDefault();
}
function drop_inside(ev)
{
    if (is_drag == 1) {
        var id = ev.dataTransfer.getData("Text");
        drag_id = id;
        // drop_id = "goal-container-"+ev.target.parentNode.id.split('-')[2];
        drop_id = "goal-container-" + ev.target.dataset.id.split('-')[2];
        if (drop_id != drag_id && $('.sub-container[data-containerid=' + drag_id + ']').has($('.sub-container[data-containerid=' + drop_id + ']')).length == 0 && $('.sub-container[data-containerid=' + drag_id + ']').parent().data('containerid') != drop_id) {
            remove_collapse($('.sub-container[data-containerid=' + drag_id + ']'));
            add_collapse($('.sub-container[data-containerid=' + drop_id + ']'));
            $('.sub-container[data-containerid=' + drop_id + ']').append($('.sub-container[data-containerid=' + drag_id + ']'));
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
            // Set parent id
            $('.sub-container[data-containerid=' + drag_id + ']').data('pid', $('.sub-container[data-containerid=' + drop_id + ']').attr('id'));
            // Set goal level
            $('.sub-container[data-containerid=' + drag_id + ']').data('level', $('.sub-container[data-containerid=' + drop_id + ']').data('level') + 1);
        }
        $('.sub-container').css('border-bottom', 'none');
        $('.sub-container').css('padding-bottom', '0px');
        is_drag = 0;
        drag_id = '';
        $('.sub-container').hover(function() {
            $(this).css("cursor", "move");
        }, function() {
            $(this).css("cursor", "default");
        });
    }
    ev.preventDefault();
}

function drag_hover_top(a) {
    a.on('dragenter dragover', function() {
        // hover_drop_id = "goal-container-"+a.attr('id').split('-')[2];
        hover_drop_id = "goal-container-" + a.data('id').split('-')[2];
        if (drag_id != hover_drop_id) {
            a.css('border-top', 'solid 1px #00cc00');
            a.css('padding-top', '5px');
        }
    });
    a.on('dragleave dragend', function() {
        $('.goal-top').css('border-top', 'none');
        $('.goal-top').css('padding-top', '0px');
    });
}

function drag_hover_bottom(a) {
    a.on('dragenter dragover', function() {
        // hover_drop_id = "goal-container-"+a.attr('id').split('-')[2];
        hover_drop_id = "goal-container-" + a.data('id').split('-')[2];
        if (drag_id != hover_drop_id) {
            a.parent().css('border-bottom', 'solid 1px #cc0000');
            a.parent().css('padding-bottom', '5px');
        }
    });
    a.on('dragleave dragend', function() {
        $('.sub-container').css('border-bottom', 'none');
        $('.sub-container').css('padding-bottom', '0px');
    });
}*/

function add_collapse(a) {
    var b = a.find('> .goal-top');
    var g_check = b.find('.show-goal-link');
    if (!g_check.length) {
        var goal_link = '<a id="' + count + '" class="show-goal-link bg1" href="#"><i class="fa fa-chevron-right"></i></a>';
        count++;
        b.prepend(goal_link);
        collapse_goal();
    }
}

function remove_collapse(a) {
    var b = a.parent();
    if (b.attr('id') != 'sub-goal') {
        var c = b.find('> .goal-top').find('.show-goal-link');
        var g_check = b.find('> .sub-container');
        if (g_check.length == 1)
            c.remove();
    }
}

function check_sub_container() {
    $('.sub-container').each(function() {
        var a = $(this);
        /*a.hover(function() {
            $(this).css("cursor", "move");
        }, function() {
            $(this).css("cursor", "default");
        });*/

        var b = $(this).find('> .goal-top');
        // var c = $("#goal-input-"+a.attr('id').split('-')[2]);
        var c = $('input[data-id="goal-input-' + a.attr('data-containerid').split('-')[2] + '"]');
        //drag_hover_top(b);
        //drag_hover_bottom(c);
    });
}


        function test ()
        {
        var repeat_frequency = $("#_repeat_frequency").val();
        if(repeat_frequency=='weeks')
        {
            $("#begin_on_now").attr('disabled', true);
            $("#begin_on_date").prop('checked', true);    
        }
        else
        {
         $("#begin_on_now").attr('disabled', false); 
         $('.years input[type=radio]').change(function()
          {
            if($(".years #repeat_on_firstday").prop('checked')===true)
            {
                //alert("if");
               $("#begin_on_now").attr('disabled', true);
               $("#begin_on_date").prop('checked', true); 
            }
            else if($(".years #repeat_on_lastday").prop('checked')===true)
            {
                //alert("else");
              $("#begin_on_now").attr('disabled', true);
              $("#begin_on_date").prop('checked', true);   
            }
            else{
                $("#begin_on_now").attr('disabled', false);
                $("#begin_on_now").prop('checked', true); 
            }

        })  
        }
}


