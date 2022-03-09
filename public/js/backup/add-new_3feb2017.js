var total_goal = 0;
var count = 1;
/*d_id để tạo gán id vào sub-container */
var d_id = 1;
var drag_id = '';
/* is_drag để kiểm tra chỉ drag goal hay ko */
var is_drag = 0;
jQuery(document).ready(function() {
    var add_link = $("#add-sub");
    d_id = $("#sub-goal").data('totalsub') + 1;

    add_link.on('touchstart click', function(event) {
        var b = $(this).parent();
        var g_check = b.find('.show-goal-link');
        if (!g_check.length) {
            var goal_link = '<a id="' + count + '" class="show-goal-link bg1" href="#"><!--<i class="fa fa-chevron-right"></i>--></a>';
            count++;
            b.prepend(goal_link);
            collapse_goal();
        }

        var first_goal_txt = $('.first-text').val();

        if (typeof first_goal_txt == 'undefined' || first_goal_txt == '') {
            pop_up_error("You didn't enter anything in input box, Please enter and then continue.");
            return false;
        } else {
            new_sub($('#sub-goal'));
        }

        event.preventDefault();
        return false;
    });

    $('.new-goal').on('touchstart click', function(event) {
        if ($(this).attr('id') != 'add-sub') {
            var a = $(this).parent().parent().parent();
            var b = $(this).parent().parent();
            var g_check = b.find('.show-goal-link');
            if (!g_check.length) {
                var goal_link = '<a class="show-goal-link bg1" href="#"><i class="fa fa-chevron-right"></i></a>';
                b.prepend(goal_link);
                collapse_goal();
            }
            new_sub(a);
            event.preventDefault();
            return false;
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
});
function checking2() {
    $('#checkChanged').val(1);
    var empty = 0;
    $('input[type=text]').each(function() {
        if (this.value == "") {
            empty++;
            $("#error").show('slow');
        }
    });
    return empty;
}

function new_sub(m) {
    // countsubgoal(m);
    // var g_newlyadded = m.find('.text').val();
    var noofempty = checking2();
    if (noofempty > 0) {
        pop_up_error("You didn't enter anything in input box, Please enter and then continue.");
        return false;
    }

    /*if (m.attr('id') != 'sub-goal') {
     var count_sub = m.find('.sub-container').first().siblings('.sub-container').length;
     if (count_sub >= '4') {
     pop_up_error("You are allowed to add only 5 sub goal.");
     return false;
     }
     }*/


    $('input.text').each(function() {
        $(this).prev('span').html($(this).val());
    });

    // $('input.text').hide();
    $('.newly-added').removeClass('recent-input');
    $('span.sub-title-goal').show();


    // For sub goals
    total_goal++;
    var goal_level = parseInt(m.data('level'), 10) + 1;
    var goal_list = $("#sub-goal").data('list');
    if (goal_list !== "")
        goal_list = goal_list + ',goal-' + total_goal;
    else
        goal_list = 'goal-' + total_goal;
    $("#sub-goal").data('list', goal_list.toString());
    var parent_id = m.attr('id');

    var w = $(document).width();
    var sub_container = $("<div class='sub-container newly-add-container' draggable='true' ondragstart='dragStart(event)' data-containerid='goal-container-" + d_id + "' id='goal-" + total_goal + "' data-level='" + goal_level + "' data-pid='" + parent_id + "'></div>");
    var goal_top = $("<div class='goal-top' ondrop='drop_above(event)' ondragover='allowDrop(event)' data-id='goal-top-" + d_id + "'></div>");
    // var goal_title     = $("<span><b>Goal</b></span>");
    var input_type = $("<a class='type yellow' href='#' data-value='Undefined' data-id='0'><span class='goal-title goal-title-sub-text'>Undefined</span></a>");
    var input_lobby = $("<a class='lobby green' href='#'  data-id='1' data-value='fa fa-eye'><span class='goal-title goal-title-sub-text'><i class='fa fa-eye'></i></span></a>");
    // var input_status   = $("<a class='status yellow' href='#' id='status' data-value='Active' data-id='1'><b>Active</b></a>");
    var newlink = $("<span class='float-right'><a class='new-goal new-sub-goal' href='#'><i class='fa fa-plus'></i></a></span>");
    var removelink = $("<span class='float-right'><a class='remove-goal new-sub-goal' href='#'><i class='fa fa-close'></i></a></span>");
    var habitlink = $("<a class='habit-schedule settings'  href='#'><i class='fa fa-cog'></i></a>");
    habitlink.css('display', 'none');
    var input_date = $("<input class='goal-due date' type='text' name='sub_due_date' value='" + current_date() + "'>");
    var input_habit_date = $("<input class='habit-start-date date' type='text' name='sub_habit_start_date' value='" + current_date() + "'>");
//    var input = $("<span class='sub-title-goal newly-addedinpt' data-pid='" + d_id + "' style='display:none;'></span><input class='text newly-added recent-input' type='text' name='sub_name' placeholder='Name of sub goal' ondrop='drop_inside(event)' ondragover='allowDrop(event)' data-id='goal-input-" + d_id + "' />");
    var input = $("<input class='text newly-added recent-input' type='text' name='sub_name' placeholder='Name of sub goal' ondrop='drop_inside(event)' ondragover='allowDrop(event)' data-id='goal-input-" + d_id + "' />");
    var input_habit_type = $("<input type='hidden' name='sub_habit_type' value='1' />");
    d_id = d_id + 1;
    // goal_top.append(goal_title);
    goal_top.append(input_type);
    goal_top.append(input_habit_type);
    goal_top.append(input_lobby);
    input_lobby.hide();
    // goal_top.append(input_status);
    goal_top.append(habitlink);
    goal_top.append(input_date);
    goal_top.append(input_habit_date);
    input_date.attr('value', current_date());
    input_habit_date.attr('value', current_date());
    goal_top.append(newlink);
    goal_top.append(removelink);
//goal_top.append("<span class='float-right'><a class='remove-goal new-sub-goal' href='#'><i class='fa fa-close'></i></a><a class='new-goal new-sub-goal' href='#'><i class='fa fa-plus'></i></a></span>");
    sub_container.append(goal_top);
    sub_container.append("<div class='clearfix'></div>");
    sub_container.append(input);
    sub_container.append("<div class='clearfix'></div>");
//    input_date.pickadate({
//            clear: '',
//            close: 'Close',
//            onSet: function(thingSet) {
//                $('#checkChanged').val(1);
//                var action = 1;
//                windowloadaleart(action);
//                console.log('222');
//            },
//            onRender: function() {
//                 $('.picker__box').prepend('<a href="javascript:;" class="close_calender">X</a>');
//                $('.close_calender').on('click', function() {
//                    var esc = $.Event("keydown", {keyCode: 27});
//                    $("body").trigger(esc);
//                    console.log('333');
//                });
//            }
//        });
//    input_habit_date.pickadate({
//            clear: '',
//            close: 'Close',
//            onSet: function(thingSet) {
//                $('#checkChanged').val(1);
//                var action = 1;
//                windowloadaleart(action);
//                console.log('2222');
//            },
//            onRender: function() {
//                 $('.picker__box').prepend('<a href="javascript:;" class="close_calender">X</a>');
//                $('.close_calender').on('click', function() {
//                    var esc = $.Event("keydown", {keyCode: 27});
//                    $("body").trigger(esc);
//                    console.log('3333');
//                });
//            }
//        });
    m.append(sub_container);
    fill_status();
    fill_lobby();
    fill_type();
    fill_habit();
    check_type();
    removelink.bind('touchstart click', function(event) {
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
            var list = $("#sub-goal").data('list');
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
            $("#sub-goal").data('list', new_list.toString());
        }
        event.preventDefault();
        return false;
    });

    /*$('.newly-added').on('keypress', function(e) {
     var code = e.keyCode || e.which;
     
     if (code == 13) {
     var inpysec = $(this).data('id');
     var inpval = $(this).val();
     var pid = inpysec.split('goal-input-');
     pid = pid[1];
     $(this).prev('span').html(inpval).show();
     $(this).hide();
     
     }
     });
     
     $('.newly-addedinpt').on('click', function(e) {
     var inpysec = $(this).data('pid');
     var pid = inpysec;
     $(this).hide();
     var newre = $(this).html();
     $(this).next('input').val(newre).show();
     });
     */


    newlink.bind('touchstart click', function(event) {
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
        new_sub(a);
        event.preventDefault();
        return false;
    });
    /*----*/
    drag_hover_top(goal_top);
    /*----*/
    drag_hover_bottom(input);
    sub_container.hover(function() {
        $(this).css("cursor", "move");
    }, function() {
        $(this).css("cursor", "default");
    });
}

function fill_status() {
    $('.status').bind('touchstart click', function(event) {
        var a = $(this);
        var b = $("#light-box");
        var c = b.find('.modal-body');
        c.empty();
        var input_status = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary active" data-id="1">Active</button><button type="button" name="att" class="btn btn-primary" data-id="0">Inactive</button></div>');

        if (a.data('value') == 'Inactive') {
            input_status = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="1">Active</button><button type="button" name="att" class="btn btn-primary active" data-id="0">Inactive</button></div>');
        }
        c.append(input_status);
        b.modal();
        input_status.children("button").bind('touchstart click', function(event) {
            a.data('value', $(this).html());
            a.data('id', $(this).data('id'));
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
function fill_lobby() {
    $('.lobby').bind('touchstart click', function(event) {
        var a = $(this);
        var b = $("#light-box");
        var c = b.find('.modal-body');
        c.empty();
        var input_status = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary active" data-id="1" data-rvalue="fa fa-eye">Show in lobby</button><button type="button" name="att" class="btn btn-primary" data-id="0" data-rvalue="fa fa-eye-slash">Hide in lobby</button></div>');
        if (a.data('value') == '<i class="fa fa-eye-slash"></i>') {
            input_status = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="1" data-rvalue="fa fa-eye">Show in lobby</button><button type="button" name="att" class="btn btn-primary active" data-id="0" data-rvalue="fa fa-eye-slash">Hide in lobby</button></div>');
        }
        c.append(input_status);
        b.modal();
        input_status.children("button").bind('touchstart click', function(event) {
            a.data('value', '<i class="' + $(this).data('rvalue') + '"></i>');
            a.data('id', $(this).data('id'));
            $('#checkChanged').val(1);
            a.html("<span class='goal-title goal-title-sub-text'>" + '<i class="' + $(this).data('rvalue') + '"></i>' + "</span>");
            if ($(this).html() == "Show in lobby") {
                a.removeClass("red");
                a.addClass("green");
            }
            if ($(this).html() == "Hide in lobby") {
                a.removeClass("green");
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
function fill_type() {
    $('.type').bind('touchstart click', function(event) {
        var a = $(this);
        var b = $("#light-box");
        var c = b.find('.modal-body');
        var d = a.parent();
        var e = d.find('.goal-due');
        var f = d.find('.lobby');
        var g = d.find('.habit-start-date');
        var h = d.find('.habit-schedule');
        c.empty();
        var input_type = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary active" data-id="0">Undefined</button><button type="button" name="att" class="btn btn-primary" data-id="1">Habit</button><button type="button" name="att" class="btn btn-primary" data-id="2">Task</button><button type="button" name="att" class="btn btn-primary" data-id="3">Character</button></div>');
        if (a.data('value') == 'Habit') {
            input_type = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="0">Undefined</button><button type="button" name="att" class="btn btn-primary active" data-id="1">Habit</button><button type="button" name="att" class="btn btn-primary" data-id="2">Task</button><button type="button" name="att" class="btn btn-primary" data-id="3">Character</button></div>');
        }
        if (a.data('value') == 'Task') {
            input_type = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="0">Undefined</button><button type="button" name="att" class="btn btn-primary" data-id="1">Habit</button><button type="button" name="att" class="btn btn-primary  active" data-id="2">Task</button><button type="button" name="att" class="btn btn-primary" data-id="3">Character</button></div>');
        }
        if (a.data('value') == 'Character') {
            input_type = $('<div class="btn-group" data-toggle="buttons-radio"><button type="button" name="att" class="btn btn-primary" data-id="0">Undefined</button><button type="button" name="att" class="btn btn-primary" data-id="1">Habit</button><button type="button" name="att" class="btn btn-primary" data-id="2">Task</button><button type="button" name="att" class="btn btn-primary active" data-id="3">Character</button></div>');
        }
        c.append(input_type);
        b.modal();
        input_type.children("button").bind('touchstart click', function(event) {
            a.data('value', $(this).html());
            a.data('id', $(this).data('id'));
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);

            a.html("<span class='goal-title goal-title-sub-text'>" + $(this).html() + "</span>");
            if ($(this).html() == 'Task') {
                //alert(e.val());
                g.css('display', 'none');
                h.css('display', 'none');
                e.css('display', 'inline-block').show();
                e.attr('value', current_date());
                e.pickadate({
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
            } else if ($(this).html() == 'Habit') {
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
                e.css('display', 'none');
                g.css('display', 'none');
                h.css('display', 'none');
            }
            if ($(this).html() == 'Undefined') {
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

        var $habit_type;
        if (a.parent().parent().hasClass('add-form'))
        {
            $habit_type = a.siblings('input[name="main_habit_type"]');
            var str_habit_type = $habit_type.val();
        } else if (a.parent().parent().hasClass('sub-container'))
        {
            $habit_type = a.siblings('input[name="sub_habit_type"]');
            var str_habit_type = $habit_type.val();
        } else if (a.parent().hasClass('goal-row-parent'))
        {
            $habit_type = a.siblings('input[name="main_habit_type"]');
            var str_habit_type = $habit_type.val();
        } else
        {
            var str_habit_type = '1;';
        }
        var arr_habit_type = str_habit_type.split(';');

        var b = $("#light-box");
        var c = b.find('.modal-body');
        var d = a.parent();
        c.empty();

        var type_options = '<h5>Please choose type: </h5><div class="btn-group2 type-options" data-toggle="buttons-radio">';
        type_options += '<button type="button" name="att" data-value="1" class="btn btn-primary' + ((1 == arr_habit_type[0] || '' == arr_habit_type) ? ' active' : '') + '">7 days/week</button>';
        //type_options += '<button type="button" name="att" data-value="2" class="btn btn-primary' + ((2 == arr_habit_type[0]) ? ' active' : '') + '">X days/week</button>';
        type_options += '<button type="button" name="att" data-value="3" class="btn btn-primary' + ((3 == arr_habit_type[0]) ? ' active' : '') + '">Routine</button>';
        type_options += '</div>';

        var input_type = $(type_options);

        var number_options = '<div class="btn-group2 number-options" data-toggle="buttons-radio"><h5>Please select number of days/week: </h5>';
        number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 1 == arr_habit_type[1]) ? ' active' : '') + '">1</button>';
        number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 2 == arr_habit_type[1]) ? ' active' : '') + '">2</button>';
        number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 3 == arr_habit_type[1]) ? ' active' : '') + '">3</button>';
        number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 4 == arr_habit_type[1]) ? ' active' : '') + '">4</button>';
        number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 5 == arr_habit_type[1]) ? ' active' : '') + '">5</button>';
        number_options += '<button type="button" name="att" class="btn btn-primary' + ((2 == arr_habit_type[0] && 6 == arr_habit_type[1]) ? ' active' : '') + '">6</button>';
        number_options += '</div>';
        var option1 = $(number_options);

        var day_options = '<div class="btn-group2 day-options"><h5>Please select days:</h5>';
        day_options += '<input type="checkbox" data-value="0" id="checkbox-1-1" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('0')) ? ' checked' : '') + ' /><label for="checkbox-1-1"><span></span></label><span>Sun</span>';
        day_options += '<input type="checkbox" data-value="1" id="checkbox-1-2" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('1')) ? ' checked' : '') + ' /><label for="checkbox-1-2"><span></span></label><span>Mon</span>';
        day_options += '<input type="checkbox" data-value="2" id="checkbox-1-3" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('2')) ? ' checked' : '') + ' /><label for="checkbox-1-3"><span></span></label><span>Tue</span>';
        day_options += '<input type="checkbox" data-value="3" id="checkbox-1-4" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('3')) ? ' checked' : '') + ' /><label for="checkbox-1-4"><span></span></label><span>Wed</span>';
        day_options += '<input type="checkbox" data-value="4" id="checkbox-1-5" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('4')) ? ' checked' : '') + ' /><label for="checkbox-1-5"><span></span></label><span>Thu</span>';
        day_options += '<input type="checkbox" data-value="5" id="checkbox-1-6" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('5')) ? ' checked' : '') + ' /><label for="checkbox-1-6"><span></span></label><span>Fri</span>';
        day_options += '<input type="checkbox" data-value="6" id="checkbox-1-7" class="regular-checkbox" ' + ((3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('6')) ? ' checked' : '') + ' /><label for="checkbox-1-7"><span></span></label><span>Sat</span>';
        day_options += '</div>';

// var day_options = '<div class="btn-group2 day-options"><h5>Please select days:</h5>';
//		day_options += '<input type="checkbox" data-value="0" id="checkbox-1-1" class="regular-checkbox daycheckedUncheck" ' + ( ( 3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('0') ) ? ' checked' : '' ) + ' /><span>Sun</span>';
//		day_options += '<input type="checkbox" data-value="1" id="checkbox-1-2" class="regular-checkbox daycheckedUncheck" ' + ( ( 3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('1') ) ? ' checked' : '' ) + ' /><span>Mon</span>';
//		day_options += '<input type="checkbox" data-value="2" id="checkbox-1-3" class="regular-checkbox daycheckedUncheck" ' + ( ( 3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('2') ) ? ' checked' : '' ) + ' /><span>Tue</span>';
//		day_options += '<input type="checkbox" data-value="3" id="checkbox-1-4" class="regular-checkbox daycheckedUncheck" ' + ( ( 3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('3') ) ? ' checked' : '' ) + ' /><span>Wed</span>';
//		day_options += '<input type="checkbox" data-value="4" id="checkbox-1-5" class="regular-checkbox daycheckedUncheck" ' + ( ( 3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('4') ) ? ' checked' : '' ) + ' /><span>Thu</span>';
//		day_options += '<input type="checkbox" data-value="5" id="checkbox-1-6" class="regular-checkbox daycheckedUncheck" ' + ( ( 3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('5') ) ? ' checked' : '' ) + ' /><span>Fri</span>';
//		day_options += '<input type="checkbox" data-value="6" id="checkbox-1-7" class="regular-checkbox daycheckedUncheck" ' + ( ( 3 == arr_habit_type[0] && -1 < arr_habit_type[1].indexOf('6') ) ? ' checked' : '' ) + ' /><span>Sat</span>';
//		day_options += '</div>';
        var option2 = $(day_options);

        var button = $('<br/><br/><button class="btn btn-warning" id="habit-schedule-cancel" style="margin-right:0.5em;">Cancel</buton> <button class="btn btn-danger" id="habit-schedule-save">Save</buton>');
        c.append(input_type);
        c.append(option1);
        c.append(option2);
        c.append(button);

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
        } else
        {
            button.css('display', 'none');
        }
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
        });
        $('#habit-schedule-cancel').on('touchstart click', function() {
            b.modal('hide');
        });
        $(".type-options > .btn").on('click', function() {
            $(this).addClass("active").siblings().removeClass("active");
        });

        $('#habit-schedule-save').on('touchstart click', function() {

            $('#checkChanged').val(1);
            var hthis = $(this);
            var htype = hthis.siblings('div.type-options').children('button.active').data('value');
            var hvalue = 7;
            if (htype == 2)
            {
                hvalue = hthis.siblings('div.number-options').children('button.active').html();
            } else if (htype == 3)
            {
                hvalue = hthis.siblings('div.day-options').children('.regular-checkbox:checked').map(function() {
                    return $(this).data('value');
                }).get().join(',');
            } else
            {
                hvalue = 7;
            }

            var str = htype + ';' + hvalue;
            $habit_type.val(str);
            b.modal('hide');
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
        });
        event.preventDefault();
        return false;
    });
}
function pop_up() {
    var b = $("#light-box");
    var c = b.find('.modal-body');
    c.empty();
    var input = '<h4 style="text-align:center;">You did not save change. Do you want to save?</h4><p  style="text-align:center;"><a class="btn btn-success" href="#" style="margin-right:20px;">YES</a> <a class="btn btn-warning" href="#">NO</a></p>'
    c.append(input);
    b.modal();
}
function pop_up_error(message) {
    var b = $("#light-box");
    var c = b.find('.modal-body');
    c.empty();
    var input = '<h4 style="text-align:center;">You did not enter anything in input?</h4><a class="btn btn-warning" href="#">Close</a></p>'
    c.append(message);
    b.modal();
}
function check_status() {
    $('.status').each(function() {
        var a = $(this);
        if (a.data('value') == "Active") {
            a.removeClass("red");
            a.addClass("yellow");
        }
        if (a.data('value') == "Inactive") {
            a.removeClass("yellow");
            a.addClass("red");
        }
    });
}
function check_lobby() {
    $('.lobby').each(function() {
        var a = $(this);
        if (a.data('value') == "Show in lobby") {
            a.removeClass("red");
            a.addClass("green");
        }
        if (a.data('value') == "Hide in lobby") {
            a.removeClass("green");
            a.addClass("red");
        }
    });
}
function check_type() {
    $('.type').each(function() {
        var a = $(this);
        var d = a.parent();
        var e = d.find('.goal-due');
        var f = d.find('.lobby');
        var g = d.find('.habit-start-date');
        var h = d.find('.habit-schedule');
        if (a.data('value') == 'Task') {
            // alert(e.val());
            g.css('display', 'none');
            h.css('display', 'none');
            e.css('display', 'inline-block').show();
            // e.attr('value',current_date());
            // e.pickadate();
        } else if (a.data('value') == 'Habit') {
            // alert(g.val());
            e.css('display', 'none');
            g.css('display', 'inline-block').show();
            h.css('display', 'inline-block').show();
            // g.attr('value',current_date());
            // g.pickadate();
        } else {
            e.css('display', 'none');
            g.css('display', 'none');
            h.css('display', 'none');
        }
        if (a.data('value') == 'Undefined') {
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
    //alert(strDate);
    //$('#datepicker').attr('value',strDate);
    return strDate;
}
function collapse_goal() {
    $(".show-goal-link").each(function() {
        $(this).unbind('click');
        $(this).bind('click', function(event) {
            //console.log($(this).attr('id'));
            var a = $(this);
            var b = $(this).parent().parent();
            if (b.hasClass('add-form')) {
                b = b.find('#sub-goal');
            }

            if (a.hasClass('bg1')) {
                b.children('div.sub-container').each(function() {
                    $(this).hide();
                });
                a.removeClass('bg1');
                a.addClass('bg2');
            } else if (a.hasClass('bg2')) {
                b.children('div.sub-container').each(function() {
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
        show_tree_goal(hash);
        var v = $('#input-' + hash).val();
        $('#input-' + hash).focus();
        // console.log(v);
        $('#input-' + hash).val(v);
        //console.log(hash);
        //console.log($('#input-'+hash).offset().top);		
        $('.goal').scrollTop($('#input-' + hash).offset().top);
        return false;

    } else {
        var lastPart = document.URL.split("/").pop();
        if (lastPart != 'add') {
            //show_tree_goal(lastPart);
            var v = $('#input-' + lastPart).val();
            $('#input-' + lastPart).focus();
            $('#input-' + lastPart).val(v);
            $('.goal').scrollTop($('#input-' + lastPart).offset().top);

        }
    }
}
function show_tree_goal(id) {
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
        if (drop_id != drag_id && $('div.sub-container[data-containerid=' + drag_id + ']').has($('div.sub-container[data-containerid=' + drop_id + ']')).length == 0) {
            remove_collapse($('div.sub-container[data-containerid=' + drag_id + ']'));
            $('div.sub-container[data-containerid=' + drop_id + ']').before($('div.sub-container[data-containerid=' + drag_id + ']'));
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
            // Set parent id
            $('div.sub-container[data-containerid=' + drag_id + ']').data('pid', $('div.sub-container[data-containerid=' + drop_id + ']').data('pid'));
            // Set goal level
            $('div.sub-container[data-containerid=' + drag_id + ']').data('level', $('div.sub-container[data-containerid=' + drop_id + ']').data('level'));
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
        if (drop_id != drag_id && $('div.sub-container[data-containerid=' + drag_id + ']').has($('div.sub-container[data-containerid=' + drop_id + ']')).length == 0 && $('div.sub-container[data-containerid=' + drag_id + ']').parent().data('containerid') != drop_id) {
            remove_collapse($('div.sub-container[data-containerid=' + drag_id + ']'));
            add_collapse($('div.sub-container[data-containerid=' + drop_id + ']'));
            $('div.sub-container[data-containerid=' + drop_id + ']').append($('div.sub-container[data-containerid=' + drag_id + ']'));
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
            // Set parent id
            $('div.sub-container[data-containerid=' + drag_id + ']').data('pid', $('div.sub-container[data-containerid=' + drop_id + ']').attr('id'));
            // Set goal level
            $('div.sub-container[data-containerid=' + drag_id + ']').data('level', $('div.sub-container[data-containerid=' + drop_id + ']').data('level') + 1);
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
}
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
        a.hover(function() {
            $(this).css("cursor", "move");
        }, function() {
            $(this).css("cursor", "default");
        });
        var b = $(this).find('> .goal-top');
        // var c = $("#goal-input-"+a.attr('id').split('-')[2]);
        var c = $('input[data-id="goal-input-' + a.data('containerid').split('-')[2] + '"]');
        drag_hover_top(b);
        drag_hover_bottom(c);
    });
}