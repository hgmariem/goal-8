 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var sort_li = function(a, b) {
    return ($(b).data('order')) > ($(a).data('order')) ? 1 : -1;
}

$(function(){

     
    $(document).on('touchstart click', ".task_maximizer", function(event) {
        event.preventDefault();
        var view_type=$("div.task__list-type>button:disabled").attr("gid");
        //console.log(view_type);
        var url = site_url+'/goals/view/'+view_type;
        window.location.href = url;
    });

    $(document).on('ifChanged', "ul.habit-list input.habits__checkbox", function(e) {
            //console.log("ifChanged called....");
            var _this = $(this);
            add_log(e,_this);
    });

    $(document).on('click', "ul.habit-list input.number", function(e) {
            
            var value = $(this).val();
            if(value == 0)
            {
                $(this).val("");
                $(this).attr("data-value","");
                $(this).attr("placeholder","N/A");
            }

    });

    $(document).on('focusout', "ul.habit-list input.number", function(e) {
            $.tloader("show","Loading..."); 
            //console.log("ifChanged called....");
            var _this = $(this);
            add_log_number(e,_this);
    });

    $(document).on('keypress', "ul.habit-list input.number", function(e) {
            //console.log("ifChanged called....");
            if (e.which == 13) {
            //var _this = $(this);
            //add_log_number(e,_this);
            $(this).blur();
        }
    });

    $(document).on('change', ".mobile-view ul.habit-list input.habits__checkbox", function(e) {
            
            //console.log("is for mobile...");
            
            var _this = $(this);
            
            if(_this.attr("data-value")==1){
                _this.attr("data-value",2);
            }else if(_this.attr("data-value")==2){
                _this.attr("data-value",0);
            }else{
                 _this.attr("data-value",1);
            }

            add_log(e,_this);
    });

    $(document).on('touchstart click', ".prev-week,.prev-date", function(event) {

        //console.log("prev-week");

         var date=$(this).attr("data-date");
         
         var type="prev";

         weekly_habits(date,type);
    });

    $(document).on('touchstart click', ".next-week,.next-date", function(event) {
         
         //console.log("next-week");
         
         var date=$(this).attr("data-date");
         
         var type="next";

         weekly_habits(date,type);
    });

    $(document).on('touchstart click', ".today", function(event) {
         
         //console.log("next-week");
         
         var date=$(this).attr("data-date");
         
         var type="today";

         weekly_habits(date,type);
    });

    

    //$("ul.habit-list").on('click', 'a.habit-datepicker', function() {
    $(document).on('touchstart click', "a.habit-datepicker", function(event) {
        
        //alert("clicked.....");

        habit_calender($(this));
    });


    $('#myModal').on('show.bs.modal', function(e) {
        indexyr = 0;
        var rowid = $(e.relatedTarget).attr('data-pid');
        $('.loader-section').show();
        $('.habit-per-graph .modal-body .modal-body-data').html("");

    });

    $(document).on('touchstart click', ".show-task-link", function(event) {
       
       var _this=$(this);

       var parent=$(this).parents("li:first");
       
       if(parent.hasClass("child-expand")){
            parent.removeClass("child-expand").addClass("child-collapse");
            _this.find("i.fa").removeClass("fa-chevron-down").addClass("fa-chevron-right");
       }else{
            parent.removeClass("child-collapse").addClass("child-expand");
            _this.find("i.fa").removeClass("fa-chevron-right").addClass("fa-chevron-down");
       }
       var auto_save_id=_this.attr("data-autosaveid");
        $.tloader("show","Loading...");

        $.post(site_url+'/goals/state',{auto_save_id:auto_save_id, self: false}, function(response) {
        
        $.tloader("hide");

        if (response.status == 1)
            {
                //console.log(response);
                //$("div#task_displayer").html(response.html);
                //window.reload();
            }
        });

    });
    
    
    $("#trophy_date").datepicker({dateFormat:"yy-mm-dd"});

    $(document).on("touchstart click", ".btnPopupTrophy",function(){

            $.tloader("show","Adding task to trophy, Please wait...");
            
            var trophy_date=$("#trophy_date").val();
            
            var trophy_name=$("#trophy_name").val();
            
            var trophy_id=$("#trophy_id").val();
            
            var error=new Array;
            
            if(!trophy_date){
                error.push("Please Enter Date.");
            }

            if(!trophy_name){
                error.push("Please Enter Trophy Name.");
            }

            if(!trophy_id){
                error.push("Invalid Task. Please report to administrator.");
            }

            if(error.length){
                
                $.tloader("hide");

                $.notify(error.join("<br/>"), {
                    type:'danger',
                    z_index: 999999999,
                });
                


                return false;
            }

           

            $.post(site_url+'/trophy/movetotrophy',{item_id:trophy_id, trophy_date:trophy_date, name:trophy_name}, function(response) {
                
                if(response.status == 1){
                    $("#myTrophyModal").modal("hide");
                }


                $.notify(response.msg, {
                    type:'success',
                    z_index: 999999999,
                });

                $.tloader("hide");
            });
    });

    $(document).on("touchstart click",".like.btnEnd",function(){
        
        var _this=$(this);

        var active_list = _this.attr("data-view");

        var id=_this.attr("data-id");
        
        $.tloader("show","Loading...");

        $.post(site_url+'/goals/task_complete',{id:id,view_type:active_list}, function(response) {
        
        $.tloader("hide");

        console.log("completing .....");

        //console.log(response);

        if (response.status == 1)
            {
                
                //$("#trophy_date").val(response.data.due_date);

                $("#trophy_name").val(response.data.name);
                $("#trophy_id").val(response.data.id);

                var myTrophyModal=$("#myTrophyModal").modal();

                if(isMobile){
                    $("div#"+active_list+"-mobile").html(response.html);
                }else{
                   $("div#task_displayer").html(response.html); 
                }
              
              init_change_task_date();  
             
            }

            init_task_sortable();
        });

    });

    $(document).on('touchstart click', ".task__list-type button", function(event) {
        $(".task__list-type button").removeAttr("disabled");
        var _this=$(this);
        var type=_this.attr("gid");
        _this.attr("disabled",true);
        load_task_tree(type);
    });

    $(document).on('touchstart click', ".tasks-mobile-tabs li a", function(event) {
        var _this=$(this);
        
        var type=_this.attr("gid");
        
        $(".tasks-mobile-tabs li").removeClass("active");
        _this.parent("li").addClass("active");
        $(".mobile_task_displayer>div").removeClass("in active");
        $(".mobile_task_displayer>div#"+type+"-mobile").addClass("in active");
        load_mobile_task_tree(type);
    });
    
    
    /*$.datepicker._findPos = function (obj) {
        var position,
            inst = this._getInst(obj),
            isRTL = this._get(inst, "isRTL");

        while (obj && (obj.type === "hidden" || obj.nodeType !== 1 || $.expr.filters.visible(obj))) {
            obj = obj[isRTL ? "previousSibling" : "nextSibling"];
        }

        position = $(obj).offset();
        console.log(position);
        // if element type isn't hidden, use show and hide to find offset
        if (!position) { position = $(obj).show().offset(); $(obj).hide();}
        // or set position manually
        if (!position) position = {left: 999, top:999};
        return [position.left, position.top]; 
    };*/

    //$(".task-list-tree").children("li");
    
    /*setTimeout(function() {
        console.log("ddddd....d");
        init_task_sortable();
        // The LI is now appended to #cart UL and you can mess around with it.
    }, 1);*/


    init_change_task_date();
    init_habit_sortable();
    init_character_sortable();  
    init_task_sortable();
    init_change_habit_date();

});


var add_log_number = function(e,_this){
    
    var full_date_php = _this.attr('gdate');
    
    var gid = _this.attr('gid');
    var is_apply = _this.attr('data-is_apply');

    var scale = _this.attr('data-scale');
    if(scale == 0)
    {
        var value = _this.attr('data-value');
    }
    else
    {
        var lowest = parseInt(_this.attr('data-min'));
        var highest = parseInt(_this.attr('data-max'));
        var value = parseFloat(_this.val());
       
        if(_this.val() !='')
        {
            //alert(value);
        if(value != 0 )
        {   
            if(is_apply == 0)
            {
                if(value < lowest)
                {
                    alert("This value range must be from "+lowest+ " to "+highest+" or 0");
                    _this.val(lowest);
                    $.tloader("hide");
                    return false;
                }
                if(value > highest)
                {
                    alert("This value range must be from " +lowest+ " to" +highest+ " or 0");
                    _this.val(lowest);
                    $.tloader("hide");
                    return false;
                }
            }
            else
            {
                //console.log(value);
               if(value > 1000)
                {
                    alert("This value range must be from 0 to 1000");
                    _this.val(0);
                    $.tloader("hide");
                    return false;
                } 

                if(value < 0)
                {
                    alert("This value range must be from 0 to 1000");
                    _this.val(0);
                    $.tloader("hide");
                    return false;
                } 
            }
        }
        else
        {

            value = 0;
        }
    }else{
        //alert("reached here...");
        value = -1;
    }
                
    }

    $.post(site_url+'/log/add', 
        {id: gid, value: value, date: full_date_php,scale:scale,is_apply:is_apply}, 
        function(response) {
            //console.log(response);
        $.tloader("hide"); 
        if (response.status == 1)
            {
                _this.removeClass("notavailable");
                _this.attr('data-value',response.data.value);
                if(parseFloat(response.data.value)>= 0){
                    //console.log(response.data.value);
                    _this.val(response.data.value);
                }
                else if(parseInt(response.data.value)==-1)
                {
                    _this.attr("placeholder", "N/A");
                }
                
                var parent_li=_this.parents(".main-li");

                var percent=response.data.percentage.percentage+" avg";
                parent_li.find(".habits__process-percent").html(percent);

                parent_li.find(".change-badge").removeClass("badge-danger badge-warning badge-success").addClass(response.data.percentage.badge);
                var _process="("+response.data.percentage.completed_days+"/"+response.data.percentage.total_days+")";
                parent_li.find(".habits__process").html(_process);
            }
    },"json");
}


var add_log = function(e,_this){
    
    var full_date_php = _this.attr('gdate');
    
    var gid = _this.attr('gid');

    var scale = _this.attr('data-scale');
    
     var value = _this.attr('data-value');
    var isChecked = e.currentTarget.checked;
    console.log("value:"+value);
    if(value == 'undefined' || value == "")
    {
        value = 0;
    }
    
    console.log("isChecked:"+isChecked);

    console.log("value:"+value);

    var notava = false;

    if(!isMobile){  // for desktop only...
        if (isChecked && value == 0) {
            value = 1;
        } else if (isChecked && value == 1) {
            //value = 2;
        } else if (!isChecked && value == 1) {
            value = 2;
            notava = true;
        } else if (!isChecked && value == 2) {
            value = 0;
        } else if (isChecked && value == 2) {
            value = 0;
        }
    }

    //$.tloader("show","Loading..."); 
    $.post(site_url+'/log/add', 
        {id: gid, value: value, date: full_date_php,scale:scale}, 
        function(response) {
            console.log(response);
            //$.tloader("hide"); 
        if (response.status == 1)
            {
                _this.removeClass("notavailable");
                _this.attr('data-value',response.data.value);
                if(parseInt(response.data.value)==2){
                    _this.addClass("notavailable");
                }else if(parseInt(response.data.value)==0){
                    _this.attr("checked", false);
                }
                else if(parseInt(response.data.value)==-1)
                {
                    _this.attr("placeholder", "N/A");
                }
                
                var parent_li=_this.parents(".main-li");

                var percent=response.data.percentage.percentage+"%";
                parent_li.find(".habits__process-percent").html(percent);

                parent_li.find(".change-badge").removeClass("badge-danger badge-warning badge-success").addClass(response.data.percentage.badge);
                var _process="("+response.data.percentage.completed_days+"/"+response.data.percentage.total_days+")";
                parent_li.find(".habits__process").html(_process);
            }
    },"json");
}


var load_mobile_task_tree = function(type){

    $.tloader("show","Loading...");

    $.post(site_url+'/goals/task_list',{view_type: type}, function(response) {
       if (response.status == 1)
        {       
                $("ul.task-main-tree").remove();
                $("div.mobile_task_displayer > div#"+type+"-mobile").html(response.html);
                init_task_sortable();
                init_change_task_date();
                mobile_task_resize(type);
        }
        $.tloader("hide");
    });
};



var load_task_tree = function(type){

    $.tloader("show","Loading...");

    $.post(site_url+'/goals/task_list',{view_type: type}, function(response) {
    
    $.tloader("hide");
    
    //console.log(response);

    if (response.status == 1)
        {
            $("div#task_displayer").html(response.html);
            init_task_sortable();
            init_change_task_date();
            task_resize(type);
        }
    });
};

var weekly_habits=function(date,type){

    $.tloader("show","Loading...");

    $.post(site_url+'/goals/weekly_habits',{date: date, type: type}, function(response) {
    
    $.tloader("hide");
    
    if (response.status == 1)
        {
            $("div.habits-container").html(response.html);
            init_habit_sortable();
            init_change_habit_date();
        }
    });
}

var init_change_habit_date = function(){


    $('input._habit_datepicker').datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onSelect: function( current_date, evnt ) {
            
            var _this=evnt.input

            var habit_id=_this.data("id");
            
            //console.log(task_id);
            
            _this.val(current_date);
           
            $.tloader("show","Loading...");

            $.post(site_url+'/goals/upadate_habit_date',{date: current_date, id: habit_id}, function(response) {
            
            $.tloader("hide");

            
            if (response.status == 1)
            {   
                if(response.data.expired){
                    _this.parent().addClass("red");
                }else{
                    _this.parent().removeClass("red");
                }
            }
           

            });

        }
    });


    $.datepicker._gotoToday = function(id) { 
    $(id).datepicker('setDate', new Date()); 

};

    $("div.habits__process").click(function(){
        var habit_calender = $(this).next();
        habit_calender.focus();
    });

};

var init_change_task_date = function(){
    
    $('.task-link .task-date-picker').datepicker({
        dateFormat: "dd M, yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        onSelect: function( current_date, evnt ) {
            
            var _this=evnt.input

            var task_id=_this.data("id");
            
            //console.log(task_id);
            
            _this.val(current_date);

            var view_type=$("div.task__list-type>button:disabled").attr("gid");
           
            $.tloader("show","Loading...");

            $.post(site_url+'/goals/upadate_task_date',{date: current_date, id: task_id, type:view_type}, function(response) {
            
            $.tloader("hide");

            
            if (response.status == 1)
            {   
                if(response.data.expired){
                    _this.parent().addClass("red");
                }else{
                    _this.parent().removeClass("red");
                }
            }
           

            });

        }
    });


     $.datepicker._gotoToday = function(id) { 
    $(id).datepicker('setDate', new Date()); 
    };
};


Date.prototype.addDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}

var habit_calender = function(_this){
    //console.log("called....1");
    var highest = _this.attr("data-max");
    var lowest = _this.attr("data-min");
    var scale = _this.attr("data-scale");
    var is_apply = _this.attr("data-is_apply");

    //alert(highest);
    //alert(lowest); 
    var max_date = new Date();
    var max_date=max_date.addDays(365);

    //console.log(max_date);

    $.tloader("show","Loading Calender...");

    var input = $('.list_calendar').pickadate({
        today: '',
        clear: '',
        close: 'Close',
        selectYears: 200,
        selectMonths: true,
        max: max_date,
        onRender: function() {

            if(scale == 1)
            {
                
                $(".picker__frame").addClass("picker__scale");
                spanTagForDates(gid,scale,highest,lowest,is_apply);
                $(".picker__header").append("<div class='scale__header hide-on-mobile'></div>");
                $('.scale__header').append('<span class="monthly-average">Monthly Average <span class="monthly-average-value"></span></span>');
                $('.scale__header').append('<span class="habit-lowest">Lowest <span class="monthly-lowest-value"></span></span>');
                $('.scale__header').append('<span class="monthly-total">Monthly Total <span class="monthly-total-value"></span></span>');
                $('.scale__header').append('<span class="habit-highest">Highest <span class="monthly-highest-value"></span></span>');
                $('.scale__header').append('<span class="include-na-days"><input type="checkbox" name="include_na" value="1" class="include-notavaible-days"><label>Including Inactive Days</label><a href="#"><i class="fa fa-question-circle" aria-hidden="true"></i></a> </span>');

                $(".picker__table").after("<div class='table__header hide-on-desktop'></div>");
                $('.table__header').append('<span class="monthly-average">Monthly Average <span class="monthly-average-value"></span></span>');
                $('.table__header').append('<span class="habit-lowest">Lowest <span class="monthly-lowest-value"></span></span>');
                $('.table__header').append('<span class="monthly-total">Monthly Total <span class="monthly-total-value"></span></span>');
                $('.table__header').append('<span class="habit-highest">Highest <span class="monthly-highest-value"></span></span>');
                $('.table__header').append('<span class="include-na-days"><input type="checkbox" name="include_na" value="1" class="include-notavaible-days" style="opacity: 1;margin:20px;"><label>Including Inactive Days</label><a href="#"><i class="fa fa-question-circle" aria-hidden="true"></i></a> </span>');

            }
            else
            {
                 $(".picker__frame").removeClass("picker__scale");
            }
                 $('.picker__box').prepend('<a href="javascript:;" class="close_calender">X</a>');
            //console.log('Whoa.. rendered anew')
        },

    });

    //console.log("called....2");

    var picker = input.pickadate('picker');
    //console.log(picker);
    //var _this = $(this);

    var gid = _this.attr('gid');   
    var scale = _this.attr('data-scale');     
    $.get(site_url+'/log/list/' + gid, function(response) {
        
        $.tloader("hide");
        picker.start();
        var min_date=response.data.created_at.split(",");
        picker.set('min', [parseInt(min_date[0]), parseInt(min_date[1])-1, parseInt(min_date[2])]);
        var all_allow_days = [];
        //var startDate = new Date(response.data.start_date);
        //var currentDate = new Date();

        var result = response.data.created_at.split(',');
        var y1 = parseInt(result[0]);
        var m1 = parseInt(result[1])-1;
        var d1 = parseInt(result[2]);
        var new_start_date = new Date(y1,m1,d1);  
        var habit_start_date = formatDate(new_start_date);
        var currentDate = new Date();
        var new_currentDate = formatDate(currentDate);
        while(habit_start_date < new_currentDate)
        {
            all_allow_days.push(new Date(habit_start_date));
            habit_start_date = new Date(habit_start_date);
            habit_start_date.setDate(habit_start_date.getDate() + 1);
            habit_start_date = formatDate(habit_start_date);
        }

        var all_allow_habit_date = [];

        if(all_allow_days.length){
        $.each(all_allow_days,function(i,date){
            var allow_dates = formatDate(date);
            var new_date=allow_dates.split("-");
            all_allow_habit_date[i]=[parseInt(new_date[0]), parseInt(new_date[1])-1, parseInt(new_date[2])];//push(new Date(new_date[0],new_date[1]-1,new_date[2]));
        });
    }
        //console.log(all_allow_habit_date);
        var disabled=[];
        if(response.data.disabled_dates.length){
            $.each(response.data.disabled_dates,function(i,date){
                //console.log(date);
                var new_date=date.split(",");
                disabled[i]=[parseInt(new_date[0]), parseInt(new_date[1])-1, parseInt(new_date[2])];//push(new Date(new_date[0],new_date[1]-1,new_date[2]));
            });
        }

        var allow_dates = [];
        if(response.data.allowed_dates.length){
            $.each(response.data.allowed_dates,function(i,date){
                var new_allow_date = date.split("-");
                //console.log(new_allow_date);
                allow_dates[i]=[parseInt(new_allow_date[0]), parseInt(new_allow_date[1])-1, parseInt(new_allow_date[2])];//push(new Date(new_date[0],new_date[1]-1,new_date[2]));
                //console.log(allow_dates);
            });
        }

        var disable_days = [];
        if(response.data.disabled_dates.length){
            $.each(response.data.disabled_dates,function(i,date){
                var new_disable_date = date.split(",");
                //console.log(new_disable_date);
                disable_days[i]=[parseInt(new_disable_date[0]), parseInt(new_disable_date[1])-1, parseInt(new_disable_date[2])];//push(new Date(new_date[0],new_date[1]-1,new_date[2]));
            });
        }

        var completed_log_days = [];
        if(response.data.completed.length){
            $.each(response.data.completed,function(i,value){
                var completed_log_date = value.date+"-"+value.value;
                var new_disable_date = completed_log_date.split("-");
                completed_log_days[i]=[parseInt(new_disable_date[0]), parseInt(new_disable_date[1])-1, parseFloat(new_disable_date[2]), parseFloat(new_disable_date[3])];
                
            });
        }

        var disallowed_log_days = [];
        if(response.data.disabled_days.length){
            $.each(response.data.disabled_days,function(i,value){
                var disallowed_log_date = value.date+"-"+value.value;
                //console.log(completed_log_date);
                var new_disable_date = disallowed_log_date.split("-");
                disallowed_log_days[i]=[parseInt(new_disable_date[0]), parseInt(new_disable_date[1])-1, parseInt(new_disable_date[2]), parseFloat(new_disable_date[3])];
            });
        }

        var completed=[];
        if(response.data.completed_dates.length){
            $.each(response.data.completed_dates,function(i,date){
                var new_date=date.split(",");
                completed[i]=[parseInt(new_date[0]), parseInt(new_date[1])-1, parseInt(new_date[2])];//push(new Date(new_date[0],new_date[1]-1,new_date[2]));
            });
        }
        
        //picker.set('disable', disabled);
        picker.on({
            open: function() {
                var month = $(".picker__select--month").val();
                var year  = $(".picker__select--year").val();
                if(scale == 1)
                {
                     $(".picker__frame").addClass("picker__scale");
                     $('.picker__header .monthly-highest-value').html(response.data.month_highest.toFixed(2));
                     $('.picker__header .monthly-lowest-value').html(response.data.month_lowest.toFixed(2));
                     $('.picker__header .monthly-average-value').html(response.data.monthly_average.toFixed(2));
                     $('.picker__header .monthly-total-value').html(response.data.monthly_total.toFixed(2));

                     $('.table__header .monthly-highest-value').html(response.data.month_highest.toFixed(2));
                     $('.table__header .monthly-lowest-value').html(response.data.month_lowest.toFixed(2));
                     $('.table__header .monthly-average-value').html(response.data.monthly_average.toFixed(2));
                     $('.table__header .monthly-total-value').html(response.data.monthly_total.toFixed(2));
                      //reload_table(completed, 'checked__day');
                      set_id();
                    set_allow_habit_day(all_allow_habit_date,response.data.created_at);
                    set_disable_day(disable_days,response.data.created_at);
                    set_allow_day_log_value(completed_log_days,response.data.created_at);
                    set_disallow_day_log_value(disallowed_log_days,response.data.created_at);
                    monthlyAverage(month,year,gid,2,response.data.created_at);
                    getWeekAverage(gid,response.data.created_at);
                    dateInput(gid,response.data.created_at);
                    checkInclude(month,year,gid,response.data.created_at);
                    checkMobileInclude(month,year,gid,response.data.created_at);
                }
                else
                {
                    
                    $(".picker__frame").removeClass("picker__scale");
                    set_id();
                    allow_dates_value(allow_dates,response.data.created_at);
                    disable_dates_value(disable_days,response.data.created_at);
                    reload_table(completed, 'checked__day',response.data.created_at);
                
                pic_date(picker, gid,scale,function(pick, status){

                    var date = convertDbDate(pick);

                    var d = new Date(date);
                    
                    if(status==0){
                        remove_date(disabled, pick);
                    }else if(status==2){
                       remove_date(completed, pick);
                       disabled.push([parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]);
                       //console.log(disabled);
                    }else{
                      remove_date(completed, pick);  
                      completed.push([parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]);
                    }
                });
            }
            },
            set: function(timestamp) {
                
                var month = $(".picker__select--month").val();
                var year  = $(".picker__select--year").val();
                set_id();
                if(scale == 1)
                {
                    //reload_table(completed, 'checked__day');
                    set_allow_habit_day(all_allow_habit_date,response.data.created_at);
                    set_disable_day(disable_days,response.data.created_at);
                    set_allow_day_log_value(completed_log_days,response.data.created_at);
                    set_disallow_day_log_value(disallowed_log_days,response.data.created_at);
                    monthlyAverage(month,year,gid,2,response.data.created_at);
                    getWeekAverage(gid,response.data.created_at);
                    dateInput(gid,response.data.created_at);
                    checkInclude(month,year,gid,response.data.created_at);
                    checkMobileInclude(month,year,gid,response.data.created_at);
                    
                }
                else
                {
                        set_id();
                        allow_dates_value(allow_dates,response.data.created_at);
                        disable_dates_value(disable_days,response.data.created_at);
                        reload_table(completed, 'checked__day',response.data.created_at);
                        pic_date(picker, gid,scale,function(pick, status){

                        var date = convertDbDate(pick);

                        var d = new Date(date);
                        
                        if(status==0){
                            remove_date(disabled, pick);
                        }else if(status==2){
                           remove_date(completed, pick);
                           disabled.push([parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]);
                        }else{
                          remove_date(completed, pick);  
                          completed.push([parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]);
                        }
                    });
                }

                $('.close_calender').on('click', function() {
                    picker.stop();
                });

                
            },
            render: function() {
                    //set_id();
            }
        });

        picker.open(true);

        $(document).on('click', function() {
            picker.stop();
        });
        $('.close_calender').on('click', function() {
            //console.log('close cal');
            //   input.cl();
            picker.stop();
        });
    });
    //dateInput(gid);                                     
}

var pic_date = function(picker, gid,scale, callback){
   
    $('.picker__day').unbind("click").bind("click", function() {
        
        //console.log("picking a date...");

        $.tloader("show","Please wait...");

        var value = $(this).attr('data-pick');

        var date = convertDbDate(value);

        var d = new Date(value);
        var date_id = '#' + value;

        var status=0;
        if ($(this).hasClass('picker__day--disabled')) {

            log_date(gid, 0,scale, date, date_id);
            status=0;
        } else if ($(this).hasClass('checked__day')) {
            status=2;
            log_date(gid, 2,scale, date, date_id);
        } else if (!$(this).hasClass('checked__day') && !$(this).hasClass('picker__day-disabled')) {
            status=1;
            log_date(gid, 1,scale, date, date_id);
        }
        callback(value, status);
        return false;

    });
} 

var log_date = function(gid, value,scale, date, date_id){

    //$("input[type='checkbox'][gdate='"+date+"']");
    var _this=$('input[type="checkbox"][gdate="'+date+'"][gid="'+gid+'"]');
    
    //console.log(_this);
    
    $.post(site_url+'/log/add', {id: gid, value: value,scale: scale, date: date}, function(response) {
        
        if(response.status==1){
            
            if(parseInt(response.data.value)==1){
                $(date_id).addClass('checked__day');
            }else if(parseInt(response.data.value)==2){
                $(date_id).removeClass('checked__day');
                $(date_id).addClass('picker__day--disabled');
            }else{
                $(date_id).removeClass('picker__day--disabled');
            }

            if(_this.length){
               
                _this.removeClass("notavailable");
               
                _this.attr('data-value',response.data.value);
                
                console.log(parseInt(response.data.value));

                if(parseInt(response.data.value)==2){
                     _this.parent(".icheckbox_flat-green").removeClass("checked");
                    _this.addClass("notavailable");
                }else if(parseInt(response.data.value)==0){
                    _this.parent(".icheckbox_flat-green").removeClass("checked");
                    _this.attr("checked", false);
                }else{
                    console.log("Else....");
                    _this.attr("checked", true);
                    _this.parent(".icheckbox_flat-green").addClass("checked");
                    _this.iCheck('check');
                }

                _this.iCheck("update");
            }
            
            var parent_li=$("li#item_"+gid);

            var percent=response.data.percentage.percentage+"%";
            parent_li.find(".habits__process-percent").html(percent);

            parent_li.find(".habits__process-percent").removeClass("badge-danger badge-warning badge-success").addClass(response.data.percentage.badge);
            var _process="("+response.data.percentage.completed_days+"/"+response.data.percentage.total_days+")";
            parent_li.find(".habits__process").html(_process);
        }

        $.tloader("hide");
    });
}


var log_NumberInput = function(gid, value,scale, date, date_id,month,year,is_apply,habit_date){

    //$("input[type='checkbox'][gdate='"+date+"']");
    var _this=$('input[type="number"][gdate="'+date+'"][gid="'+gid+'"]');
    $(".scale__header").html("<div style='text-align:center;'><img src='./img/ajax-loader.gif'></div>");
    $(".table__header").html("<div style='text-align:center;'><img src='./img/ajax-loader.gif'></div>");
    $.ajax({
            url : site_url+'/log/add',
            method: "post",
            data : {id: gid, value: value,scale: scale, date: date,month:month,year:year,is_apply:is_apply},
            success:function(response)
            {
                console.log(response);
                $.tloader("hide");
                if(response.status==1){
                $(".scale__header").html("");
                $(".table__header").html("");
                /*$('.scale__header').append('<span class="monthly-average">Monthly Average    ' +response.data.monthDetails.monthly_average+ '</span>');
                $('.scale__header').append('<span class="habit-lowest">Lowest     ' +response.data.monthDetails.lowest+'</span>');
                $('.scale__header').append('<span class="monthly-total">Monthly Total   '+response.data.monthDetails.monthly_total+'</span>');
                $('.scale__header').append('<span class="habit-highest">Highest   '+response.data.monthDetails.highest+'</span>');
               */ 
                $('.scale__header').append('<span class="monthly-average">Monthly Average <span class="monthly-average-value">'+response.data.monthDetails.monthly_average.toFixed(2)+'</span></span>');
                $('.scale__header').append('<span class="habit-lowest">Lowest <span class="monthly-lowest-value">'+response.data.monthDetails.lowest.toFixed(2)+'</span></span>');
                $('.scale__header').append('<span class="monthly-total">Monthly Total <span class="monthly-total-value">'+response.data.monthDetails.monthly_total.toFixed(2)+'</span></span>');
                $('.scale__header').append('<span class="habit-highest">Highest <span class="monthly-highest-value">'+response.data.monthDetails.highest.toFixed(2)+'</span></span>');
                $('.scale__header').append('<span class="include-na-days"><input type="checkbox" name="include_na" value="1" class="include-notavaible-days"><label>Including Inactive Days</label><a href="#"><i class="fa fa-question-circle" aria-hidden="true"></i></a> </span>');
                
                $('.table__header').append('<span class="monthly-average">Monthly Average <span class="monthly-average-value">'+response.data.monthDetails.monthly_average.toFixed(2)+'</span></span>');
                $('.table__header').append('<span class="habit-lowest">Lowest <span class="monthly-lowest-value">'+response.data.monthDetails.lowest.toFixed(2)+'</span></span>');
                $('.table__header').append('<span class="monthly-total">Monthly Total <span class="monthly-total-value">'+response.data.monthDetails.monthly_total.toFixed(2)+'</span></span>');
                $('.table__header').append('<span class="habit-highest">Highest <span class="monthly-highest-value">'+response.data.monthDetails.highest.toFixed(2)+'</span></span>');
                $('.table__header').append('<span class="include-na-days"><input type="checkbox" name="include_na" value="1" class="include-notavaible-days" style="opacity: 1;margin:20px;"><label>Including Inactive Days</label><a href="#"><i class="fa fa-question-circle" aria-hidden="true"></i></a> </span>');
                  checkMobileInclude(month,year,gid,habit_date);
                //$('.picker__header .habit-lowest').html("Lowest  " +response.data.monthDetails.lowest);
               // $('.picker__header .habit-highest').html("Highest  " +response.data.monthDetails.highest);
               // $('.picker__header .monthly-average').html("Monthly Average  " +response.data.monthDetails.monthly_average);
               // $('.picker__header .monthly-total').html("Monthly Total  " +response.data.monthDetails.monthly_total);
                if(parseFloat(response.data.value) >=0){
                    $(date_id).removeClass('picker__day--disabled');
                    $(date_id).addClass('checked__day');
                }else if(parseInt(response.data.value)==-1){
                    $(date_id).removeClass('checked__day');
                    $(date_id+".form-control input-scale-number").val("");
                    $(date_id+".form-control input-scale-number").attr("data-value",response.data.value);
                    $(date_id+".form-control input-scale-number").attr("placeholder","N/A");
                    $(date_id).addClass('picker__day--disabled');
                }else{
                    $(date_id).removeClass('picker__day--disabled');
                }

            if(_this.length){
               
                _this.removeClass("notavailable");
               
                _this.attr('data-value',response.data.value);
                
                //console.log(parseInt(response.data.value));

                if(parseInt(response.data.value)==-1){
                     _this.val("");
                     _this.attr("placeholder","N/A");
                }else if(parseFloat(response.data.value) >= 0){
                    _this.val(response.data.value);
                    
                }else{
                    console.log("Else....");
                    _this.attr("checked", true);
                    _this.parent(".icheckbox_flat-green").addClass("checked");
                    _this.iCheck('check');
                }

                //_this.iCheck("update");
            }
            
            var parent_li=$("li#item_"+gid);

            var percent=response.data.percentage.percentage+"avg";
            parent_li.find(".habits__process-percent").html(percent);

            parent_li.find(".habits__process-percent").removeClass("badge-danger badge-warning badge-success").addClass(response.data.percentage.badge);
            var _process="("+response.data.percentage.completed_days+"/"+response.data.percentage.total_days+")";
            parent_li.find(".habits__process").html(_process);
        }
        
       // $.tloader("hide");
        return false;
        }

    });

     return false;
    
}


var init_habit_sortable=function(){

    var habits_list=$('ul.habit-list').sortable({
        handle: 'div.arrows-holder',
        items: 'li.main-li',
        listType: 'ul',
        opacity: .6,
        stop: function(event, ui) { 
            $.tloader("show","Hang On...");
            var data = habits_list.sortable("serialize");
            


            $.post(site_url+"/goals/self_order", {data:data}, function (response) {
                //console.log(response)
                if (response.status == 0)
                {

                }
                $.tloader("hide");
            });
        }
    });
    habits_list.disableSelection();
};

var init_character_sortable=function(){

    var character_list=$('ul.character-list').sortable({
        handle: 'div.arrows-holder',
        items: '>li.main-li',
        listType: 'ul',
        opacity: .6,
        stop: function(event, ui) {
            $.tloader("show","Hang On...");
            var data = character_list.sortable("serialize");
            $.post(site_url+"/goals/self_order", {data:data}, function (response) {
               
                if (response.status == 0)
                {

                }

                $.tloader("hide");
            });

            //console.log('new parent ' + ui.item.closest('ul').closest('li').attr('name'));
        }
    });
    character_list.disableSelection();
};

var init_sort_task = function(){

    $(".task-list-tree li.mainchild").sort(sort_li).detach().appendTo('.task-list-tree');
    $("#task-mobile li.mainchild").sort(sort_li).detach().appendTo('#task-mobile');

    $("#task_displayer_loader").remove();
};

var init_task_sortable=function(){
        
        init_sort_task();

    var task_list=$('ul.task-list-tree').sortable({
        handle: 'span.arrows-holder',
        items: '>li.task-item',
        listType: 'ul',
        opacity: .6,
        stop: function(event, ui) {
           
           /* $.tloader("show","Hang On...");
            
            var data = task_list.sortable("serialize");

            var ulli=ui.item.parent().children("li");

            console.log(ulli);

            //return false;
            //console.log(ulli.find(">li:first-child"));

            var manual_serialize=new Array();

            $.each(ulli,function(i, li){
                
                //console.log(li);

                //console.log(i);

                var id = $(li).attr("id");
                
                var key_value=id.split("_");
                
                var key = key_value[0]+"[]="+key_value[1];
                
                manual_serialize.push(key);
                
                console.log(manual_serialize);

            });
            
            var params=manual_serialize.join("&");

            console.log(params);

            $.post(site_url+"/goals/self_order", {data:params}, function (response) {
               
                if (response.status == 0)
                {

                }

                $.tloader("hide");
            });
            */
        },
        update: function(event, ui){

            var ulli=ui.item.parent().children("li");

            var manual_serialize=new Array();

            $.each(ulli,function(i, li){
             
                var id = $(li).attr("id");
                
                var key_value=id.split("_");
                
                var key = key_value[0]+"[]="+key_value[1];
                
                manual_serialize.push(key);
                
                //console.log(manual_serialize);

            });
            
            var params=manual_serialize.join("&");

            //console.log(params);

            $.post(site_url+"/goals/self_order", {data:params}, function (response) {
               
                if (response.status == 0)
                {

                }

                $.tloader("hide");
            });
        }
    });
    task_list.disableSelection();

    //$("ul.task-list-list").sortable("disable");
    //$("ul.task-list-leaf").sortable("disable");
};

var convert = function (timestamp) {
    var objDate = new Date(parseInt(timestamp));
    var date = objDate.getFullYear() + ',' + objDate.getMonth() + ',' + objDate.getDate();
    return date;
}

var convertDbDate = function (timestamp) {
    var objDate = new Date(parseInt(timestamp));
    var date = objDate.getFullYear() + '-' + ("0" + (objDate.getMonth() + 1)).slice(-2) + '-' + ("0" + objDate.getDate()).slice(-2);
    return date;
}

var convertDbTimestamp = function (myDate) {
    
    /*console.log("new date");
    
    console.log(myDate);
    var y=(myDate[0])<10?"0"+myDate[0]:myDate[0];
    var m=(myDate[1] + 1)<10?"0"+(myDate[1] + 1):(myDate[1] + 1);
    var d=myDate[2]<10?"0"+myDate[2]:myDate[2];


    var customize_date_string=y + "-" + m + "-" + d + " 00:00:00".replace(/\s/, 'T')+"Z";

    var newdate=new Date(customize_date_string).getTime();
    
    console.log("customize_date_string: "+customize_date_string);

    console.log("new time");
    
    console.log(newdate);

    var final_value=Math.round(newdate);

    console.log("rounded new time");

    console.log(final_value);

    return final_value;
*/
    return Math.round(new Date(myDate[0] + "-" + (myDate[1] + 1) + "-" + myDate[2] + " 00:00:00:00").getTime());
}

var reload_table=function (arr, st,habit_date) {
    console.log("habit_date",habit_date);
    var result = habit_date.split(',');   
    var y1 = parseInt(result[0]);
    var m1 = parseInt(result[1])-1;
    var d1 = parseInt(result[2]);
    var new_start_date = new Date(y1,m1,d1);  
    var habit_start_date = formatDate(new_start_date);
    var currentDate = new Date();
    var new_currentDate = formatDate(currentDate);

    for (i = 0, max = arr.length; i < max; i++) {
       
        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];

        var date = y +"-"+ m +"-"+ d;

        var date_id=".day"+y + "" + m + "" + d;
        if(date >= habit_start_date && date < new_currentDate)
        {
         $(date_id).removeClass("picker__day--disabled");
         $(date_id).addClass(st);
        }
    }
}


var disable_dates_value = function(arr,habit_date)
{
    var result = habit_date.split(',');   
    var y1 = parseInt(result[0]);
    var m1 = parseInt(result[1])-1;
    var d1 = parseInt(result[2]);
    var new_start_date = new Date(y1,m1,d1);  
    var habit_start_date = formatDate(new_start_date);
    var currentDate = new Date();
    var new_currentDate = formatDate(currentDate);

    for (i = 0, max = arr.length; i < max; i++) {
        //var date_id = '#' + convertDbTimestamp(arr[i]);

        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];
        var date = y +"-"+ m +"-"+ d;

        var date_id=".day"+y + "" + m + "" + d;
        if(date >= habit_start_date && date < new_currentDate)
        {
            $(date_id).removeClass("checked__day");
            $(date_id).removeClass("picker__day--infocus");
            $(date_id).addClass("picker__day--disabled");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",-1);
            $(""+date_id+" .number-scale-calender .input-scale-number").val("");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("placeholder","N/A");
        }
        
    }
}




var allow_dates_value = function(arr,habit_date)
{
    console.log(habit_date);
    var result = habit_date.split(',');
    console.log(result);
    var y1 = parseInt(result[0]);
    var m1 = parseInt(result[1])-1;
    var d1 = parseInt(result[2]);
    var new_start_date = new Date(y1,m1,d1);  
    var habit_start_date = formatDate(new_start_date);
    var currentDate = new Date();
    var new_currentDate = formatDate(currentDate);

    for (i = 0, max = arr.length; i < max; i++) {
        //var date_id = '#' + convertDbTimestamp(arr[i]);

        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];
        var date = y +"-"+ m +"-"+ d;

        var date_id=".day"+y + "" + m + "" + d;
        if(date >= habit_start_date && date < new_currentDate)
        {
            $(date_id).removeClass("checked__day");
            $(date_id).removeClass("picker__day--disabled");
            $(date_id).addClass("picker__day--infocus");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",-1);
            $(""+date_id+" .number-scale-calender .input-scale-number").val("");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("placeholder","N/A");
        }
        
    }
}

var set_allow_habit_day=function (arr,habit_date) {

    var result = habit_date.split(',');
    var y1 = parseInt(result[0]);
    var m1 = parseInt(result[1])-1;
    var d1 = parseInt(result[2]);
    var new_start_date = new Date(y1,m1,d1);  
    var habit_start_date = formatDate(new_start_date);
    var currentDate = new Date();
    var new_currentDate = formatDate(currentDate);
    
    for (i = 0, max = arr.length; i < max; i++) {

        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];
        
        var date = y +"-"+ m +"-"+ d;

        var date_id=".day"+y + "" + m + "" + d;

        if(date >= habit_start_date && date < new_currentDate)
        {
            $(date_id).addClass("checked__day");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","0");
            $(""+date_id+" .number-scale-calender .input-scale-number").val("0");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("placeholder","-");
        }
     }
}

var set_allow_day=function (arr,habit_date) {

    var result = habit_date.split(',');
    var y1 = parseInt(result[0]);
    var m1 = parseInt(result[1])-1;
    var d1 = parseInt(result[2]);
    var new_start_date = new Date(y1,m1,d1);  
    var habit_start_date = formatDate(new_start_date);
    var currentDate = new Date();
    var new_currentDate = formatDate(currentDate);
    //console.log(arr);
    for (i = 0, max = arr.length; i < max; i++) {
        //var date_id = '#' + convertDbTimestamp(arr[i]);

        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];
        
        var date = y +"-"+ m +"-"+ d;

        var date_id=".day"+y + "" + m + "" + d;
        
        //console.log($(date_id));

        if(date >= habit_start_date && date < new_currentDate)
        {
        $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","");
        $(""+date_id+" .number-scale-calender .input-scale-number").val("0");
        $(""+date_id+" .number-scale-calender .input-scale-number").attr("placeholder","");
        }

    }
}

var set_disable_day = function (arr,habit_date) {
    var result = habit_date.split(',');
    var y1 = parseInt(result[0]);
    var m1 = parseInt(result[1])-1;
    var d1 = parseInt(result[2]);
    var new_start_date = new Date(y1,m1,d1);  
    var habit_start_date = formatDate(new_start_date);
    var currentDate = new Date();
    var new_currentDate = formatDate(currentDate);

    for (i = 0, max = arr.length; i < max; i++) {
        //var date_id = '#' + convertDbTimestamp(arr[i]);

        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];
        var date = y +"-"+ m +"-"+ d;

        var date_id=".day"+y + "" + m + "" + d;
        if(date >= habit_start_date && date < new_currentDate)
        {
            $(date_id).removeClass("checked__day");
            $(date_id).addClass("picker__day--disabled");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",-1);
            $(""+date_id+" .number-scale-calender .input-scale-number").val("");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("placeholder","N/A");
        }
        
    }
}


var set_allow_day_log_value = function (arr,habit_date) {
    
    var result = habit_date.split(',');
    var y1 = parseInt(result[0]);
    var m1 = parseInt(result[1])-1;
    var d1 = parseInt(result[2]);
    var new_start_date = new Date(y1,m1,d1);  
    var habit_start_date = formatDate(new_start_date);
    var currentDate = new Date();
    var new_currentDate = formatDate(currentDate);
    for (i = 0, max = arr.length; i < max; i++) {
        
        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];
        var v=arr[i][3];
        var date = y + "-" + m + "-" + d;
        var date_id=".day"+y + "" + m + "" + d;
        if(date >= habit_start_date && date < new_currentDate && v >= 0)
        {
             //console.log("Reached here.....");
            $(date_id).removeClass("picker__day--disabled");
            $(date_id).addClass("checked__day");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",v);
            $(""+date_id+" .number-scale-calender .input-scale-number").val("");
            if(Number.isInteger(v)){
            $(""+date_id+" .number-scale-calender .input-scale-number").val(v);
            }else{
                $(""+date_id+" .number-scale-calender .input-scale-number").val(v.toFixed(2));
            }

        }
        else if(date >= habit_start_date && date < new_currentDate && v == -1)
        {
            $(date_id).removeClass("checked__day");
            $(date_id).addClass("picker__day--disabled");
            $(""+date_id+" .number-scale-calender .input-scale-number").val("");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","");
            if(Number.isInteger(v)){
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",v);
            }else{     
                $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",v.toFixed(2));
            }
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("placeholder","N/A");
        }
        
    }
}

var set_allow_day_with_monthlyAverage = function (arr,habit_date) {
    console.log(arr);
    console.log("habit_date",habit_date);
    var result = habit_date.split(',');
    var y1 = parseInt(result[0]);
    var m1 = parseInt(result[1])-1;
    var d1 = parseInt(result[2]);
    var new_start_date = new Date(y1,m1,d1);  
    var habit_start_date = formatDate(new_start_date);
    var currentDate = new Date();
    var new_currentDate = formatDate(currentDate);
    for (i = 0, max = arr.length; i < max; i++) {
        
        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];
        var v=arr[i][3];
        var date = y + "-" + m + "-" + d;
        var date_id=".day"+y + "" + m + "" + d;
        if(date >= habit_start_date && date < new_currentDate && v >= 0)
        {
             //console.log("Reached here.....");
            $(date_id).removeClass("picker__day--disabled");
            $(date_id).addClass("checked__day");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","");
            $(""+date_id+" .number-scale-calender .input-scale-number").val("");
            if(Number.isInteger(v)){
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",v);
            $(""+date_id+" .number-scale-calender .input-scale-number").val(v);
            }else{
                 $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",v.toFixed(2));
                $(""+date_id+" .number-scale-calender .input-scale-number").val(v.toFixed(2));
            }
            //$(""+date_id+" .number-scale-calender .input-scale-number").val(v);

        }
        else if(date >= habit_start_date && date < new_currentDate && v == -1)
        {
            $(date_id).removeClass("checked__day");
            $(date_id).addClass("picker__day--disabled");
            $(""+date_id+" .number-scale-calender .input-scale-number").val("");
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","");
            if(Number.isInteger(v)){
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",v);
            }else{     
                $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",v.toFixed(2));
            }
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("placeholder","N/A");
        }
        
    }
        
    
}

var set_disallow_day_log_value = function (arr,habit_date) {
    //console.log(arr);
    var result = habit_date.split(',');
    var y1 = parseInt(result[0]);
    var m1 = parseInt(result[1])-1;
    var d1 = parseInt(result[2]);
    var new_start_date = new Date(y1,m1,d1);  
    var habit_start_date = formatDate(new_start_date);
    var currentDate = new Date();
    var new_currentDate = formatDate(currentDate);
    for (i = 0, max = arr.length; i < max; i++) {
        
        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];
        var v=arr[i][3];
        var date = y +"-" + m +"-"+ d;
        var date_id=".day"+y + "" + m + "" + d;

        if(date >= habit_start_date && date < new_currentDate && v == -1)
        {
           $(date_id).removeClass("checked__day");
           $(date_id).addClass("picker__day--disabled");
           $(""+date_id+" .number-scale-calender .input-scale-number").val("");
           $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value","");
           if(Number.isInteger(v)){
            $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",v);
            }else{     
                $(""+date_id+" .number-scale-calender .input-scale-number").attr("data-value",v.toFixed(2));
            }
           $(""+date_id+" .number-scale-calender .input-scale-number").attr("placeholder","N/A");
        }
        
    }
}

var uncheck_table = function (arr, st) {
    
    for (i = 0, max = arr.length; i < max; i++) {
       
        var y=(arr[i][0])<10?"0"+arr[i][0]:arr[i][0];
        var m=(arr[i][1] + 1)<10?"0"+(arr[i][1] + 1):(arr[i][1] + 1);
        var d=arr[i][2]<10?"0"+arr[i][2]:arr[i][2];
        var date_id=".day"+y + "" + m + "" + d;

        $(date_id).removeClass(st);
    }
}

var set_id = function () {
    $('.picker__day').each(function() {
        var select_date = $(this);
        var value = $(this).attr('data-pick');
        select_date.attr('id', value);
        var d=convertDbDate(value);
        var day_class="day"+d.replace(/-/g,"");
        select_date.addClass(day_class);
    });
}

var remove_date = function (arr, st) {
    for (i = 0, max = arr.length; i < max; i++) {
        var date_id = convertDbTimestamp(arr[i]);
        if (date_id == st) {
            arr.splice(i, 1);
            break;
        }
    }
}




/*
                                        $(document).ready(function() {
                                            $('#myModal').on('show.bs.modal', function(e) {
                                                indexyr = 0;
                                                var rowid = $(e.relatedTarget).data('pid');
                                                $('.loader-section').show();
                                                $('.habit-per-graph .modal-body .modal-body-data').html("");
                                                $.ajax({
                                                    type: 'post',
                                                    url: './goal/update2', //Here you will fetch records 
                                                    data: 'rowid=' + rowid, //Pass $id
                                                    success: function(data) {
                                                        $('.loader-section').hide();
                                                        $('.habit-per-graph .modal-body .modal-body-data').html(data);//Show fetched data from database
                                                        $('#prev_graph').css('opacity','.3');
                                                        if(total_year_event == 1) {
                                                            $('#next_graph').css('opacity','.3');
                                                        } else {
                                                            $('#next_graph').css('opacity','1');
                                                        }
                                                        //alert(total_year_event);
                                                    }
                                                });
                                            });
                                        });
                                        //         $(".hide-on-mobile #habit-id ul.habit-list").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        // $(".hide-on-mobile ul.character-list").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        // $(".compact-view-goals").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        // $(".goal-create-details").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        // $(".hide-on-mobile #task_displayer").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        // $(document).ajaxSuccess(function () {
                                        //     $(".hide-on-mobile #habit-id ul.habit-list").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        //     $(".hide-on-mobile #task_displayer").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        //     $(".hide-on-mobile ul.character-list").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        //     $(".compact-view-goals").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        //     $(".goal-create-details").niceScroll({styler: "fb", cursorcolor: "#27cce4", cursorwidth: '8', cursorborderradius: '10px', background: '#424f63', spacebarenabled: false, cursorborder: '0', zindex: '1000'});
                                        // });
                                        // })
                                        var isajax = "Yes";
                                        function showAllTasks() {
                                            $(".show-task-link").each(function() {
                                                var a = $(this);
                                                var task_id = a.data('id');

                                                $.post('./goal/state/', {id: task_id, self: 0}, function(data) {
                                                    if (data.error == 0)
                                                    {
                                                        // console.log('Successful!');
                                                    }
                                                }, "json");

                                                var b = $(this).parent().parent().parent().parent();
                                                var c = b.find('> ul');
                                                console.log("is show all");
                                                c.show();
                                                $(this).removeClass('bg2');
                                                $(this).addClass('bg1');

                                            });
                                        }

                                        function display() {
                                            var e = document.getElementById('sbox1');
                                            var x = e.options[e.selectedIndex].value;
                                            if (x == 'task') {
                                                document.getElementById('task-form').style.display = 'block';
                                            } else {
                                                document.getElementById('task-form').style.display = 'none';
                                            }
                                            document.getElementById("sub-goal").innerHTML = "";
                                        }

                                        function fixHabit(position)
                                        {
                                            

                                            // Scroll to last position
                                            $('#habit-id').scrollTop(position);
                                        }

                                        function fixCheckbox()
                                        {
                                            var a = $("#habit-id");
                                           
                                            $("ul.habit-list li .habit-checkbox .regular-checkbox").each(function() {
                                                var a = $(this);
                                                // set_checkbox(a);
                                                a.on('touchstart click', function(event) {
                                                    var d = (parseInt(a.data('value')) + 1) % 3;
                                                    a.data('value', d);
                                                    // console.log(a.data('value'));
                                                    //   set_checkbox(a);
                                                });
                                            });
                                        }


                                        function fixHasSub()
                                        {
                                            // fix class has_sub
                                            $('ul.task-list li').each(function() {
                                                var _this = $(this);
                                                if (_this.children('ul')[0])
                                                {

                                                    var task_link = _this.children('div.task-container').find('span.task-content-div').children('div.task-link');
                                                    var task_id = task_link.data('id');

                                                    var task_state = task_link.data('collapse');
                                                    var task_link_title = task_link.find('a.task-link-title');

                                                    var sub_data_count= _this.children('ul').children('li').length;
                                                    
                                                    // add collapse/expand icon
                                                    if (task_state)
                                                    {

                                                        if(sub_data_count > 0){
                                                            task_link_title.before('<a href="#" class="show-task-link  " data-id="' + task_id + '"><i class="fa fa-chevron-down"></i></a>');
                                                        }

                                                        var c = task_link_title.parent().parent().parent().parent().find('> ul');
                                                        if (c.css('display') != 'none')
                                                        {
                                                            c.hide();
                                                        }


                                                        $('#subpanel_' + task_id).show();
                                                    }
                                                    else
                                                    {
                                                        if(sub_data_count > 0){
                                                            task_link_title.before('<a href="#" class="show-task-link  " data-id="' + task_id + '"><i class="fa fa-chevron-right"></i></a>');
                                                        }
                                                    }

                                                    // display progress bar
                                                    _this.children('div.task-container').children('div.task-progress-bar').css('display', 'block');
                                                }
                                            });

                                            $('.show-task-link').on('click', function() {
                                                var pid = $(this).data('id');
                                                $('#subpanel_' + pid).toggle();
                                                $(this).html($(this).html() == '<i class="fa fa-chevron-right"></i>' ? '<i class="fa fa-chevron-down"></i>' : '<i class="fa fa-chevron-right"></i>');
                                            });


                                            $(".show-task-link").each(function() {
                                                var a = $(this);
                                                var task_id = a.data('id');


                                                a.bind('touchstart click', function(event) {
                                                    $.post('./goal/state/', {id: task_id, self: 0}, function(data) {
                                                        if (data.error == 0)
                                                        {
                                                            // console.log('Successful!');
                                                        }
                                                    }, "json");

                                                    var b = $(this).parent().parent().parent().parent();

                                                    var c = b.find('> ul');
                                                    if (c.css("display") == "none") {
                                                        c.show();
                                                        $(this).removeClass('bg2');
                                                        $(this).addClass('bg1');
                                                    } else {
                                                        c.hide();
                                                        $(this).removeClass('bg1');
                                                        $(this).addClass('bg2');
                                                    }
                                                    event.preventDefault();
                                                    return false;
                                                });
                                            });
                                        }

                                        function convert(timestamp) {
                                            var objDate = new Date(parseInt(timestamp));
                                            var date = objDate.getFullYear() + ',' + objDate.getMonth() + ',' + objDate.getDate();
                                            return date;
                                        }

                                        function convertDbDate(timestamp) {
                                            var objDate = new Date(parseInt(timestamp));
                                            var date = objDate.getFullYear() + '-' + ("0" + (objDate.getMonth() + 1)).slice(-2) + '-' + ("0" + objDate.getDate()).slice(-2);
                                            return date;
                                        }

                                        function convertDbTimestamp(myDate) {
                                            return Math.round(new Date(myDate[0] + "-" + (myDate[1] + 1) + "-" + myDate[2] + " 00:00:00:00").getTime());
                                        }
                                        function reload_table(arr, st) {
                                            for (i = 0, max = arr.length; i < max; i++) {
                                                var date_id = '#' + convertDbTimestamp(arr[i]);
                                                $(date_id).addClass(st);
                                            }
                                        }
                                        function uncheck_table(arr, st) {
                                            for (i = 0, max = arr.length; i < max; i++) {
                                                var date_id = '#' + convertDbTimestamp(arr[i]);
                                                $(date_id).removeClass(st);
                                            }
                                        }
                                        function set_id() {
                                            $('.picker__day').each(function() {
                                                var select_date = $(this);
                                                var value = $(this).data('pick');
                                                select_date.attr('id', value);
                                            });
                                        }
                                        function remove_date(arr, st) {
                                            for (i = 0, max = arr.length; i < max; i++) {
                                                var date_id = convertDbTimestamp(arr[i]);
                                                if (date_id == st) {
                                                    arr.splice(i, 1);
                                                    break;
                                                }
                                            }
                                        }

                                        var monthNames = ["January", "February", "March", "April", "May", "June",
                                            "July", "August", "September", "October", "November", "December"];

                                        var d = new Date();

                                        var strDate = d.getDate() + " " + monthNames[d.getMonth()] + ", " + d.getFullYear();

                                        $('#datepicker').attr('value', strDate);
                                        $('#datepicker').pickadate({clear: ''});

                                        var currentTaskType = 1;
                                        var current_view_typ = $('#view_type').val();

                                        console.log(current_view_typ,'current_view_typ');

                                        function taskDown() {

                                            var current_view_typ = $('#view_type').val();

                                            $.blockUI({
                                                message: '<img src="./img/ajax-loader.gif" />',
                                                css: {backgroundColor: 'transparent', border: 0}
                                            });

                                            var _this = $(this);
                                            var index = _this.attr('index');
                                            var downid = _this.attr('gid');
                                            var nexttask = _this.parent().parent().parent().parent().parent();
                                            var upid = nexttask.find('div > div.sort-div').data('gid');
                                            if (isajax == "No") {
                                                return false;
                                            }
                                            isajax = "No";
                                            $.post('./goal/swap', {downid: downid, upid: upid, type: 'task',current_view_typ:current_view_typ}, function(data) {
                                                if (data.error == 0)
                                                {

                                                    $('#task_displayer').html(data.html);
                                                    isajax = "Yes";
                                                    fixHasSub();
                                                }
                                            }, "json");
                                        }

                                        function taskUp() {
                                            var current_view_typ = $('#view_type').val();
                                            $.blockUI({
                                                message: '<img src="./img/ajax-loader.gif" />',
                                                css: {backgroundColor: 'transparent', border: 0}
                                            });

                                            var _this = $(this);
                                            var index = _this.attr('index');
                                            var upid = _this.attr('gid');

                                            var prevtask = _this.parent().parent().parent().parent().parent();
                                            var downid = prevtask.find('div > div.sort-div').data('gid');
                                            if (isajax == "No") {
                                                return false;
                                            }
                                            isajax = "No";
                                            $.post('./goal/swap', {downid: downid, upid: upid, type: 'task',current_view_typ:current_view_typ}, function(data) {
                                                if (data.error == 0)
                                                {
                                                    console.log(data.html, 'down');
                                                    $('#task_displayer').html(data.html);
                                                    isajax = "Yes";
                                                    fixHasSub();
                                                }
                                            }, "json");
                                        }

                                        function taskSub() {
                                            $.blockUI({
                                                message: '<img src="./img/ajax-loader.gif" />',
                                                css: {backgroundColor: 'transparent', border: 0}
                                            });

                                            var _this = $(this);
                                            var gid = _this.attr('gid');

                                            $.post('./goal/sub/id/' + gid, function(data) {
                                                if (data.error == 0)
                                                {
                                                    window.location.reload();
                                                }
                                            }, "json");
                                        }

                                        function taskEnd() {
                                            $.blockUI({
                                                message: '<img src="./img/ajax-loader.gif" />',
                                                css: {backgroundColor: 'transparent', border: 0}
                                            });

                                            var _this = $(this);
                                            var gid = _this.attr('gid');

                                            $.post('./goal/end/id/' + gid, function(data) {
                                                if (data.error == 0)
                                                {
                                                    var url = './goal/tasks/view_type/' + currentTaskType;
                                                    if (currentTaskType == 1)
                                                        url = './goal/index';
                                                    $.get(url, function(data) {
                                                        if (data.error == 0)
                                                        {
                                                            $('#task_displayer').html(data.html);
                                                            fixHasSub();
                                                            bindEvents();
                                                        }
                                                    }, "json");
                                                }
                                            }, "json");
                                        }


                                        function bindEvents()
                                        {

                                            $("ul.task-list").on('click', 'a.btnEnd', taskEnd);
                                            $("ul.task-list").on('click', 'a.btnSub', taskSub);
                                            $("ul.task-list").on('click', 'a.btnUp', taskUp);
                                            $("ul.task-list").on('click', 'a.btnDown', taskDown);
                                        }

                                        // unblock when ajax activity stops
                                        $(document).ajaxStop($.unblockUI);

                                        $(document).ready(function() {

                                            bindEvents();
                                            showAllTasks();

                                            var currentTaskType = 1;

                                            $(".task_trigger").each(function(index, value) {
                                                if ($(this).attr('gid') == currentTaskType) {
                                                    $(this).attr('disabled', true);
                                                } else {
                                                    $(this).attr('disabled', false);
                                                }
                                            });

                                            console.log('View type is: ' + currentTaskType);

                                            $('.task_trigger').click(function(event) {

                                                var _this = $(this);

                                                console.log(_this.attr('disabled'));

                                                if (_this.attr('disabled') == 'disabled')
                                                    return false;

                                                else {
                                                    var id = $(this).attr('gid');
                                                    currentTaskType = id;
                                                    event.preventDefault();

                                                    var current_view_typ = $('#view_type').val();

                                                    $.blockUI({
                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                        css: {backgroundColor: 'transparent', border: 0}
                                                    });

                                                    var url = './goal/tasks/view_type/' + id;
                                                    if (id == 1)
                                                        url = './goal/index/passedViewType/' + id;
                                                    $.get(url, function(data) {
                                                        if (data.error == 0)
                                                        {
                                                            $(".task_trigger").attr('disabled', false);
                                                            _this.attr('disabled', true);
                                                            $('#task_displayer').html(data.html);
                                                            fixHasSub();
                                                            bindEvents();
                                                            // showAllTasks();
                                                        }
                                                    }, "json");
                                                }

                                            });

                                            $('.task_maximizer').click(function(event) {
                                                event.preventDefault();
                                                var url = './goal/tasks/view_type/' + currentTaskType;
                                                window.location.href = url;
                                            });

                                            $('#normal_task_trigger').click(function(event) {
                                                event.preventDefault();
                                                $.blockUI({
                                                    message: '<img src="./img/ajax-loader.gif" />',
                                                    css: {backgroundColor: 'transparent', border: 0}
                                                });

                                                $.get('./goal/tasks/view_type/1', function(data) {
                                                    if (data.error == 0)
                                                    {
                                                        $('#task_displayer').html(data.html);

                                                        fixHabit(position);
                                                        fixCheckbox();
                                                        fixHasSub();
                                                    }
                                                }, "json");
                                            });

                                            // Use for scrolling to habit after ajax calling
                                            var position = 0;

                                            // fix sub goals' progress bar and collapse/expand icon
                                            fixHasSub();

                                            $("ul.habit-list").on('click', 'a.btnDown', function() {
                                                $.blockUI({
                                                    message: '<img src="./img/ajax-loader.gif" />',
                                                    css: {backgroundColor: 'transparent', border: 0}
                                                });

                                                var _this = $(this);
                                                var date = '8 February, 2018';
                                                var index = _this.attr('index');
                                                var downid = _this.attr('gid');
                                                var nextindex = parseInt(index) + 1;
                                                var upid = $('.habit-up-' + nextindex).attr('gid');

                                                // Get current position
                                                position = $('#habit-id').scrollTop();


                                                $.post('./goal/swap', {downid: downid, upid: upid, date: date, type: 'habit'}, function(data) {
                                                    if (data.error == 0)
                                                    {
                                                        $('ul.habit-list').html(data.html);



                                                        fixHabit(position);
                                                        fixCheckbox();
                                                    }
                                                }, "json");
                                            });

                                            $("ul.habit-list").on('click', 'a.btnUp', function() {
                                                $.blockUI({
                                                    message: '<img src="./img/ajax-loader.gif" />',
                                                    css: {backgroundColor: 'transparent', border: 0}
                                                });

                                                var _this = $(this);
                                                var date = '8 February, 2018';
                                                var index = _this.attr('index');
                                                var upid = _this.attr('gid');
                                                var previndex = parseInt(index) - 1;
                                                var downid = $('.habit-down-' + previndex).attr('gid');

                                                // Get current position
                                                position = $('#habit-id').scrollTop();

                                                $.post('./goal/swap', {downid: downid, upid: upid, date: date, type: 'habit'}, function(data) {
                                                    if (data.error == 0)
                                                    {
                                                        $('ul.habit-list').html(data.html);

                                                        fixHabit(position);
                                                        fixCheckbox();
                                                    }
                                                }, "json");
                                            });

                                            $("ul.habit-list").on('click', 'a.habit-datepicker', function() {
                                                $.blockUI({
                                                    message: '<img src="./img/ajax-loader.gif" />',
                                                    css: {backgroundColor: 'transparent', border: 0}
                                                });

                                                var input = $('.list_calendar').pickadate({
                                                    today: '',
                                                    clear: '',
                                                    close: 'Close',
                                                    selectYears: 200,
                                                    selectMonths: true,
                                                    onRender: function() {
                                                        $('.picker__box').prepend('<a href="javascript:;" class="close_calender">X</a>');

                                                        //console.log('Whoa.. rendered anew')
                                                    }
                                                });


                                                var picker = input.pickadate('picker');

                                                var _this = $(this);
                                                var gid = _this.attr('gid');

                                                // Get current position
                                                position = $('#habit-id').scrollTop();

                                                $.post('./log/list/id/' + gid, function(data) {
                                                    var disable = data.na;
                                                    var checked = data.checked;
                                                    var unchecked = data.unchecked;
                                                    var date_disable = [];
                                                    var date_checked = [];
                                                    var date_unchecked = [];
                                                    if (disable.length > 0)
                                                    {
                                                        var array = disable.split(';');

                                                        if (array.length > 0)
                                                        {
                                                            for (i = 0; i < array.length; i++)
                                                            {
                                                                if (array[i].indexOf(',') != -1)
                                                                {
                                                                    var temp = array[i].split(',');
                                                                    date_disable[i] = [parseInt(temp[0]), parseInt(temp[1]), parseInt(temp[2])];
                                                                } else
                                                                {
                                                                    date_disable[i] = parseInt(array[i]);
                                                                }
                                                            }
                                                        }
                                                    }

                                                    if (checked.length > 0)
                                                    {
                                                        var array_checked = checked.split(';');

                                                        if (array_checked.length > 0)
                                                        {
                                                            for (i = 0; i < array_checked.length; i++)
                                                            {
                                                                var temp_checked = array_checked[i].split(',');
                                                                date_checked[i] = [parseInt(temp_checked[0]), parseInt(temp_checked[1]), parseInt(temp_checked[2])];
                                                            }
                                                        }
                                                    }

                                                    if (unchecked.length > 0)
                                                    {
                                                        var array_unchecked = unchecked.split(';');

                                                        if (array_unchecked.length > 0)
                                                        {
                                                            for (i = 0; i < array_unchecked.length; i++)
                                                            {
                                                                var temp_unchecked = array_unchecked[i].split(',');
                                                                date_unchecked[i] = [parseInt(temp_unchecked[0]), parseInt(temp_unchecked[1]), parseInt(temp_unchecked[2])];
                                                            }
                                                        }
                                                    }

                                                    picker.start();
                                                    picker.set('disable', date_disable);

                                                    picker.on({
                                                        open: function() {
                                                            set_id();
                                                            reload_table(date_checked, 'checked__day');
                                                            uncheck_table(date_unchecked, 'picker__day--disabled');



                                                            // $('.picker__day--selected, .picker__day--highlighted:hover, .picker--focused, .picker__day--highlighted').css('background','#fff');
                                                            // $('.picker__day--selected, .picker__day--highlighted:hover, .picker--focused, .picker__day--highlighted').css('color','#000');
                                                            // $('.picker__day--selected, .picker__day--highlighted:hover, .picker--focused, .picker__day--highlighted').css('border','none');
                                                            $('.picker__day').click(function() {
                                                                var value = $(this).data('pick');
                                                                var date = convertDbDate(value);

                                                                var d = new Date(value);
                                                                var date_id = '#' + value;

                                                                if ($(this).hasClass('picker__day--disabled')) {
                                                                    // Remove log
                                                                    $.blockUI({
                                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                                        css: {backgroundColor: 'transparent', border: 0}
                                                                    });

                                                                    $.post('./log/delete/', {id: gid, date: date}, function(data) {
                                                                        if (data.error == 0)
                                                                        {
                                                                            $.post('./goal/habit/', function(data) {
                                                                                if (data.error == 0)
                                                                                {
                                                                                    // picker.set('enable', [[parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]]);

                                                                                    $(date_id).removeClass('picker__day--disabled');
                                                                                    remove_date(date_disable, value);

                                                                                    $('ul.habit-list').html(data.html);

                                                                                    fixHabit(position);
                                                                                    fixCheckbox();
                                                                                }
                                                                            }, "json");
                                                                        }
                                                                    }, "json");
                                                                } else if ($(this).hasClass('checked__day')) {
                                                                    // else if ($(this).hasClass('picker__day--infocus')){
                                                                    // Add new N/A log
                                                                    $.blockUI({
                                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                                        css: {backgroundColor: 'transparent', border: 0}
                                                                    });

                                                                    $.post('./log/add/', {id: gid, value: 2, date: date}, function(data) {
                                                                        if (data.error == 0)
                                                                        {
                                                                            $.post('./goal/habit/', function(data) {
                                                                                if (data.error == 0)
                                                                                {
                                                                                    // picker.set('disable', [[parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]]);

                                                                                    $(date_id).removeClass('checked__day');
                                                                                    remove_date(date_checked, value);
                                                                                    $(date_id).addClass('picker__day--disabled');
                                                                                    date_disable.push([parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]);

                                                                                    $('ul.habit-list').html(data.html);

                                                                                    fixHabit(position);
                                                                                    fixCheckbox();
                                                                                }
                                                                            }, "json");
                                                                        }
                                                                    }, "json");
                                                                } else if (!$(this).hasClass('checked__day') && !$(this).hasClass('picker__day-disabled')) {
                                                                    // Add new N/A log
                                                                    $.blockUI({
                                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                                        css: {backgroundColor: 'transparent', border: 0}
                                                                    });

                                                                    $.post('./log/add/', {id: gid, value: 1, date: date}, function(data) {
                                                                        if (data.error == 0)
                                                                        {
                                                                            $.post('./goal/habit/', function(data) {
                                                                                if (data.error == 0)
                                                                                {
                                                                                    $(date_id).addClass('checked__day');
                                                                                    remove_date(date_unchecked, value);

                                                                                    date_checked.push([parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]);

                                                                                    $('ul.habit-list').html(data.html);

                                                                                    fixHabit(position);
                                                                                    fixCheckbox();
                                                                                }
                                                                            }, "json");
                                                                        }
                                                                    }, "json");
                                                                }

                                                                //picker.set('select', parseInt(value));
                                                                return false;
                                                            });
                                                        },
                                                        set: function(timestamp) {
                                                            set_id();
                                                            reload_table(date_checked, 'checked__day');
                                                            uncheck_table(date_unchecked, 'picker__day--disabled');
                                                            // $('.picker__day--selected, .picker__day--highlighted:hover, .picker--focused, .picker__day--highlighted').css('background','#fff');
                                                            // $('.picker__day--selected, .picker__day--highlighted:hover, .picker--focused, .picker__day--highlighted').css('color','#000');
                                                            // $('.picker__day--selected, .picker__day--highlighted:hover, .picker--focused, .picker__day--highlighted').css('border','none');

                                                            $('.close_calender').on('click', function() {
                                                                console.log('close cal');
                                                                //   input.cl();
                                                                picker.stop();
                                                            });

                                                            $('.picker__day').click(function() {
                                                                var value = $(this).data('pick');
                                                                var date = convertDbDate(value);

                                                                var d = new Date(value);
                                                                var date_id = '#' + value;

                                                                if ($(this).hasClass('picker__day--disabled')) {
                                                                    // // Remove log
                                                                    $.blockUI({
                                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                                        css: {backgroundColor: 'transparent', border: 0}
                                                                    });

                                                                    $.post('./log/delete/', {id: gid, date: date}, function(data) {
                                                                        if (data.error == 0)
                                                                        {
                                                                            $.post('./goal/habit/', function(data) {
                                                                                if (data.error == 0)
                                                                                {
                                                                                    // picker.set('enable', [[parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]]);

                                                                                    $(date_id).removeClass('picker__day--disabled');
                                                                                    remove_date(date_disable, value);

                                                                                    $('ul.habit-list').html(data.html);

                                                                                    fixHabit(position);
                                                                                    fixCheckbox();
                                                                                }
                                                                            }, "json");
                                                                        }
                                                                    }, "json");
                                                                } else if ($(this).hasClass('checked__day')) {
                                                                    // else if ($(this).hasClass('picker__day--infocus')){
                                                                    // Add new log
                                                                    $.blockUI({
                                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                                        css: {backgroundColor: 'transparent', border: 0}
                                                                    });

                                                                    $.post('./log/add/', {id: gid, value: 2, date: date}, function(data) {
                                                                        if (data.error == 0)
                                                                        {
                                                                            $.post('./goal/habit/', function(data) {
                                                                                if (data.error == 0)
                                                                                {
                                                                                    // picker.set('disable', [[parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]]);

                                                                                    $(date_id).removeClass('checked__day');
                                                                                    remove_date(date_checked, value);
                                                                                    $(date_id).addClass('picker__day--disabled');
                                                                                    date_disable.push([parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]);

                                                                                    $('ul.habit-list').html(data.html);

                                                                                    fixHabit(position);
                                                                                    fixCheckbox();
                                                                                }
                                                                            }, "json");
                                                                        }
                                                                    }, "json");
                                                                } else if (!$(this).hasClass('checked__day') && !$(this).hasClass('picker__day-disabled')) {
                                                                    // Add new N/A log
                                                                    $.blockUI({
                                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                                        css: {backgroundColor: 'transparent', border: 0}
                                                                    });

                                                                    $.post('./log/add/', {id: gid, value: 1, date: date}, function(data) {
                                                                        if (data.error == 0)
                                                                        {
                                                                            $.post('./goal/habit/', function(data) {
                                                                                if (data.error == 0)
                                                                                {
                                                                                    $(date_id).addClass('checked__day');
                                                                                    remove_date(date_unchecked, value);

                                                                                    date_checked.push([parseInt(d.getFullYear()), parseInt(d.getMonth()), parseInt(d.getDate())]);

                                                                                    $('ul.habit-list').html(data.html);

                                                                                    fixHabit(position);
                                                                                    fixCheckbox();
                                                                                }
                                                                            }, "json");
                                                                        }
                                                                    }, "json");
                                                                }

                                                                // picker.set('select', parseInt(value));
                                                                return false;
                                                            });
                                                        },
                                                        render: function() {
                                                            set_id();
                                                        }
                                                    });

                                                    picker.open();
                                                    $(document).on('click', function() {
                                                        picker.stop();
                                                    });
                                                    $('.close_calender').on('click', function() {
                                                        console.log('close cal');
                                                        //   input.cl();
                                                        picker.stop();
                                                    });
                                                }, "json");
                                            });

                                            $("ul.habit-list input.habits__checkbox").on('click', function() {
                                                //console.log(value + '=>1');
                                            });


                                            $(document).on('ifChanged', "ul.habit-list input.habits__checkbox", function(e) {
                                                var _this = $(this);
                                                var full_date_php = _this.attr('gdate');
                                                var gid = _this.attr('gid');
                                                var value = _this.data('value');

                                                var isChecked = e.currentTarget.checked;
                                                var notava = false;

                                                console.log(isChecked, value, '<==');
                                                if (isChecked && value == 0) {
                                                    value = 1;
                                                } else if (isChecked && value == 1) {
                                                    //value = 2;
                                                } else if (!isChecked && value == 1) {
                                                    value = 2;
                                                    notava = true;
                                                } else if (!isChecked && value == 2) {
                                                    value = 0;
                                                }

                                                console.log(isChecked, value, '<==>');
                                                //   return false;

                                                // return false;

                                                // Get current position
                                                position = $('#habit-id').scrollTop();

                                                if (value === 1 || value === 2)
                                                {
                                                    $.blockUI({
                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                        css: {backgroundColor: 'transparent', border: 0}
                                                    });

                                                    $.post('./log/add/', {id: gid, value: value, date: full_date_php}, function(data) {
                                                        if (data.error == 0)
                                                        {
                                                            $.post('./goal/habit/', function(data) {
                                                                if (data.error == 0)
                                                                {
                                                                    $('ul.habit-list').html(data.html);

                                                                    fixHabit(position);
                                                                    fixCheckbox();

                                                                }
                                                            }, "json");
                                                        }
                                                    }, "json");
                                                } else {
                                                    $.blockUI({
                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                        css: {backgroundColor: 'transparent', border: 0}
                                                    });
                                                    $.post('./log/delete/', {id: gid, date: full_date_php}, function(data) {
                                                        if (data.error == 0)
                                                        {
                                                            $.post('./goal/habit/', function(data) {
                                                                if (data.error == 0)
                                                                {
                                                                    $('ul.habit-list').html(data.html);

                                                                    fixHabit(position);
                                                                    fixCheckbox();
                                                                }
                                                            }, "json");
                                                        }
                                                    }, "json");
                                                }
                                                //$('.landing_habbit input').iCheck('update');
                                                if (notava) {
                                                    $(this).next('.iCheck-helper').css('border', '1px solid red !important');
                                                    $(this).next('.iCheck-helper').css('opacity', '1 !important');
                                                }

                                            });

                                            $("ul.character-list").on('click', 'a.arrow-up', function() {
                                                $.blockUI({
                                                    message: '<img src="./img/ajax-loader.gif" />',
                                                    css: {backgroundColor: 'transparent', border: 0}
                                                });

                                                var _this = $(this);
                                                var index = _this.attr('index');
                                                var downid = _this.attr('gid');
                                                var nextindex = parseInt(index) + 1;
                                                var upid = $('.character-up-' + nextindex).attr('gid');

                                                $.post('./goal/swap', {downid: downid, upid: upid, type: 'character'}, function(data) {
                                                    if (data.error == 0)
                                                    {
                                                        $('ul.character-list').html(data.html);
                                                    }
                                                }, "json");
                                            });

                                            $("ul.character-list").on('click', 'a.arrow-down', function() {
                                                $.blockUI({
                                                    message: '<img src="./img/ajax-loader.gif" />',
                                                    css: {backgroundColor: 'transparent', border: 0}
                                                });

                                                var _this = $(this);
                                                var index = _this.attr('index');
                                                var upid = _this.attr('gid');
                                                var previndex = parseInt(index) - 1;
                                                var downid = $('.character-down-' + previndex).attr('gid');

                                                $.post('./goal/swap', {downid: downid, upid: upid, type: 'character'}, function(data) {
                                                    if (data.error == 0)
                                                    {
                                                        $('ul.character-list').html(data.html);
                                                    }
                                                }, "json");
                                            });

                                            bindEvents();

                                        });

                                        // DND START 

                                        var is_drag_1 = 0;
                                        var drag_id_1 = '';
                                        var is_drag_2 = 0;
                                        var drag_id_2 = '';
                                        var is_drag_3 = 0;
                                        var drag_id_3 = '';

                                        jQuery(document).bind("ready", function() {

                                            //Habit
                                            $('.habit-list li').each(function() {
                                                var a = $(this);
                                                drag_hover_1(a);
                                                a.hover(function() {
                                                    $(this).css("cursor", "move");
                                                }, function() {
                                                    $(this).css("cursor", "default");
                                                });
                                            });

                                            //Task
                                            $('.task-list li > div').each(function() {
                                                var a = $(this);
                                                drag_hover_2(a);
                                                a.hover(function() {
                                                    $(this).css("cursor", "move");
                                                }, function() {
                                                    $(this).css("cursor", "default");
                                                });
                                            });

                                            //Character
                                            $('.character-list li').each(function() {
                                                var a = $(this);
                                                drag_hover_3(a);
                                                a.hover(function() {
                                                    $(this).css("cursor", "move");
                                                }, function() {
                                                    $(this).css("cursor", "default");
                                                });
                                            });
                                        });

                                        //---DRAG AND DROP --/

                                        function allowDrop(ev)
                                        {
                                            ev.preventDefault();
                                        }

                                        //--Habit List--/

                                        function dragStart_1(ev)
                                        {
                                            var data_send = ev.target.id;
                                            drag_id_1 = ev.target.id;
                                            is_drag_1 = 1;
                                            ev.dataTransfer.setData("Text", data_send);
                                            is_drag_2 = 0;
                                            drag_id_2 = '';
                                            is_drag_3 = 0;
                                            drag_id_3 = '';
                                        }

                                        function drop_above_1(ev)
                                        {
                                            if (is_drag_1 == 1) {
                                                var id = ev.dataTransfer.getData("Text");
                                                drag_id_1 = id;
                                                var p = $(ev.target);
                                                while (!p.is("li"))
                                                    p = p.parent();
                                                drop_id_1 = p.attr('id');
                                                if (drop_id_1 != drag_id_1) {
                                                    $('#' + drop_id_1).before($('#' + drag_id_1));

                                                    // Get current position
                                                    position = $('#habit-id').scrollTop();

                                                    var date = '8 February, 2018';

                                                    // begin ajax call
                                                    $.blockUI({
                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                        css: {backgroundColor: 'transparent', border: 0}
                                                    });

                                                    $.post('./goal/dragndrop', {dragid: drag_id_1, dropid: drop_id_1, type: 'habit', order: 'self_order', date: date}, function(response) {
                                                        if (response.error == 0)
                                                        {
                                                            $('ul.habit-list').html(response.html);
                                                            fixHabit(position);
                                                            fixCheckbox();
                                                            $('.habit-list li').each(function() {
                                                                var a = $(this);
                                                                drag_hover_1(a);
                                                                a.hover(function() {
                                                                    $(this).css("cursor", "move");
                                                                }, function() {
                                                                    $(this).css("cursor", "default");
                                                                });
                                                            });
                                                        }
                                                    }, "json");
                                                }
                                                $('.habit-list li').css('border-top', 'none');
                                                $('.habit-list li').css('padding-top', '0px');
                                                is_drag_1 = 0;
                                                drag_id_1 = '';
                                            }
                                            ev.preventDefault();
                                        }
                                        function drag_hover_1(a) {
                                            a.on('dragenter dragover', function() {
                                                hover_drop_id = a.attr('id');
                                                if (drag_id_1 != "" && drag_id_1 != hover_drop_id && $('#' + drag_id_1).next().attr('id') != hover_drop_id) {
                                                    a.css('border-top', 'solid 1px #000');
                                                }
                                            });
                                            a.on('dragleave dragend', function() {
                                                $('.habit-list li').css('border-top', 'none');
                                                $('.habit-list li').css('padding-top', '0px');
                                            });
                                        }

                                        //--Task List--//

                                        function dragStart_2(ev)
                                        {
                                            var data_send = ev.target.id;
                                            drag_id_2 = ev.target.id;
                                            is_drag_2 = 1;
                                            ev.dataTransfer.setData("Text", data_send);
                                            is_drag_1 = 0;
                                            drag_id_1 = '';
                                            is_drag_3 = 0;
                                            drag_id_3 = '';
                                        }

                                        function drop_above_2(ev)
                                        {
                                            ////alert(is_drag_2);
                                            if (is_drag_2 == 1) {
                                                //console.log(ev);
                                                var id = ev.dataTransfer.getData("Text");

                                                drag_id_2 = id;
                                                var p = $(ev.target);
                                                //alert(p.is("div"));
                                                while (!p.is("div"))
                                                    p = p.parent();

                                                p = p.parent().parent();
//                                                        drop_id_2 = p.find('> div').attr('id');
                                                drop_id_2 = p.attr('id');


                                                if (drop_id_2 != drag_id_2) {
                                                    var task_drop = $('#' + drop_id_2).parent();
                                                    var task_drag = $('#' + drag_id_2).parent();
                                                    if (task_drop.parent().get(0) === task_drag.parent().get(0)) {
                                                        task_drop.before(task_drag);

                                                        // begin ajax call
                                                        $.blockUI({
                                                            message: '<img src="./img/ajax-loader.gif" />',
                                                            css: {backgroundColor: 'transparent', border: 0}
                                                        });

                                                        var current_view_typ = $('#view_type').val();

                                                        $.post('./goal/dragndrop', {dragid: drag_id_2, dropid: drop_id_2, type: 'task', order: 'self_order',current_view_typ:current_view_typ}, function(response) {
                                                            if (response.error == 0)
                                                            {
                                                                //alert(11);
                                                                $('#task_displayer').html(response.html);
                                                                fixHasSub();
                                                                bindEvents();
                                                                $('.task-list li > div').each(function() {
                                                                    var a = $(this);
                                                                    drag_hover_2(a);
                                                                    a.hover(function() {
                                                                        $(this).css("cursor", "move");
                                                                    }, function() {
                                                                        $(this).css("cursor", "default");
                                                                    });
                                                                });
                                                            }
                                                        }, "json");
                                                    }
                                                }
                                                $('.task-list li').css('border-top', 'none');
                                                $('.task-list li').css('padding-top', '0px');
                                                is_drag_2 = 0;
                                                drag_id_2 = '';
                                            }
                                            ev.preventDefault();
                                        }
                                        function drag_hover_2(a) {
                                            a.on('dragenter dragover', function() {
                                                hover_drop_id = a.attr('id');
                                                // console.log("hover id:"+hover_drop_id);
                                                var parent1 = $('#' + drag_id_2).parent().parent();
                                                var parent2 = a.parent().parent();
                                                // console.log("p1:"+parent1+";p2:"+parent2);
                                                var kt2 = $('#' + drag_id_2).parent().next().find('> div').attr('id');
                                                if (drag_id_2 != "" && drag_id_2 != hover_drop_id && parent1.get(0) === parent2.get(0) && kt2 != hover_drop_id) {
                                                    a.css('border-top', 'solid 1px #000');
                                                }
                                            });
                                            a.on('dragleave dragend', function() {
                                                $('.task-list li > div').css('border-top', 'none');
                                                $('.task-list li > div').css('padding-top', '0px');
                                                //console.log($('#'+drag_id_2).parent("ul").attr('id')+"    ;    "+ $('#'+hover_drop_id).parent("ul").attr('id'));
                                            });
                                        }

                                     

                                        function dragStart_3(ev)
                                        {
                                            var data_send = ev.target.id;
                                            drag_id_3 = ev.target.id;
                                            is_drag_3 = 1;
                                            ev.dataTransfer.setData("Text", data_send);
                                            is_drag_1 = 0;
                                            drag_id_1 = '';
                                            is_drag_2 = 0;
                                            drag_id_2 = '';
                                        }

                                        function drop_above_3(ev)
                                        {
                                            if (is_drag_3 == 1) {
                                                var id = ev.dataTransfer.getData("Text");
                                                drag_id_3 = id;
                                                var p = $(ev.target);
                                                while (!p.is("li"))
                                                    p = p.parent();
                                                drop_id_3 = p.attr('id');
                                                if (drop_id_3 != drag_id_3) {
                                                    $('#' + drop_id_3).before($('#' + drag_id_3));

                                                    // begin ajax call
                                                    $.blockUI({
                                                        message: '<img src="./img/ajax-loader.gif" />',
                                                        css: {backgroundColor: 'transparent', border: 0}
                                                    });

                                                    $.post('./goal/dragndrop', {dragid: drag_id_3, dropid: drop_id_3, type: 'character', order: 'self_order'}, function(response) {
                                                        if (response.error == 0)
                                                        {
                                                            $('ul.character-list').html(response.html);
                                                            $('.character-list li').each(function() {
                                                                var a = $(this);
                                                                drag_hover_3(a);
                                                                a.hover(function() {
                                                                    $(this).css("cursor", "move");
                                                                }, function() {
                                                                    $(this).css("cursor", "default");
                                                                });
                                                            });
                                                        }
                                                    }, "json");
                                                }
                                                $('.character-list li').css('border-top', 'none');
                                                $('.character-list li').css('padding-top', '0px');
                                                is_drag_3 = 0;
                                                drag_id_3 = '';
                                            }
                                            ev.preventDefault();
                                        }
                                        function drag_hover_3(a) {
                                            a.on('dragenter dragover', function() {
                                                hover_drop_id = a.attr('id');
                                                if (drag_id_3 != "" && drag_id_3 != hover_drop_id && $('#' + drag_id_3).next().attr('id') != hover_drop_id) {
                                                    a.css('border-top', 'solid 1px #000');
                                                }
                                            });
                                            a.on('dragleave dragend', function() {
                                                $('.character-list li').css('border-top', 'none');
                                                $('.character-list li').css('padding-top', '0px');
                                            });
                                        }
*/


/*var changeMonth = function(gid)
{
    $(".picker__select--month").change(function(){
        var month = $(this).val();
        var year  = $(".picker__select--year").val();
        
    });
}


var changeYear = function(gid)
{
    $(".picker__select--year").change(function(){
        var  year  = $(this).val();
        var month  = $(".picker__select--month").val();
        monthlyAverage(month,year,gid,2);
    });
}*/

var monthlyAverage = function(month,year,gid,check,habit_date){
    $.tloader("show","Loading...");
    $.ajax({
            url : site_url+'/log/monthlyAverage',
            method : "post",
            data : {month:month,year:year,gid:gid,check:check},

            success : function(response)
            {
                var completed_log_days = [];
                if(response.data.log_date_value.length > 0){
             $.each(response.data.log_date_value,function(i,value){
                var completed_log_date = value;
                var new_disable_date = completed_log_date.split("-");
                var getVal = (new_disable_date[3]) ? new_disable_date[3]:"-"+new_disable_date[4];
                
                completed_log_days[i]=[parseInt(new_disable_date[0]), parseInt(new_disable_date[1])-1, parseInt(new_disable_date[2]), parseFloat(getVal)];
            });

               set_allow_day_with_monthlyAverage(completed_log_days,habit_date); 
            }
                $.tloader("hide");
                $('.picker__header .monthly-highest-value').html(response.data.highest.toFixed(2));
                $('.picker__header .monthly-lowest-value').html(response.data.lowest.toFixed(2));
                $('.picker__header .monthly-average-value').html(response.data.monthly_average.toFixed(2));
                $('.picker__header .monthly-total-value').html(response.data.monthly_total.toFixed(2));

                $('.table__header .monthly-highest-value').html(response.data.highest.toFixed(2));
                $('.table__header .monthly-lowest-value').html(response.data.lowest.toFixed(2));
                $('.table__header .monthly-average-value').html(response.data.monthly_average.toFixed(2));
                $('.table__header .monthly-total-value').html(response.data.monthly_total.toFixed(2));


            }
        });
    //return false;   
}


/*var checkInputMonthlyAverage = function(month,year,gid,check){
    $.tloader("show","Loading......");
    $.ajax({
            url : site_url+'/log/monthlyAverage',
            method : "post",
            data : {month:month,year:year,gid:gid,check:check},

            success : function(response)
            {
                //console.log(response);
                $('.picker__header .habit-lowest').html("Lowest  " +response.data.lowest);
                $('.picker__header .habit-highest').html("Highest  " +response.data.highest);
                $('.picker__header .monthly-average').html("Monthly Average  " +response.data.monthly_average);
                $('.picker__header .monthly-total').html("Monthly Total  " +response.data.monthly_total);
                $.tloader("hide");
                //return false;

            }
        });
    //return false;   
}
*/

/*var inputMonthlyAverage = function(month,year,gid,check){
    //$.tloader("show","Loading...");
    $.ajax({
            url : site_url+'/log/monthlyAverage',
            method : "post",
            data : {month:month,year:year,gid:gid,check:check},

            success : function(response)
            {
                $('.picker__header .habit-lowest').html("Lowest  " +response.data.lowest);
                $('.picker__header .habit-highest').html("Highest  " +response.data.highest);
                $('.picker__header .monthly-average').html("Monthly Average  " +response.data.monthly_average);
                $('.picker__header .monthly-total').html("Monthly Total  " +response.data.monthly_total);
            }
        });
            //return false; 
    //return false;   
}*/

/*var averageMonthly = function(month,monthly_tot,monthly_aver,monthly_lowest,monthly_highest,selectMonth,selectYear){
        var total   ='';
        var average ='';
        var lowest  = '';
        var highest = '';
        selectMonth = parseInt(selectMonth)+1;
        var new_month = (selectMonth <= 9)?"0"+selectMonth:selectMonth;
        var selectValue = selectYear+"-"+new_month;
        $.each(month, function( key, value ) {
            if(selectValue == value)
            {
                total   = monthly_tot[key];
                average = monthly_aver[key];
                lowest  = monthly_lowest[key];
                highest = monthly_highest[key];
            }
        });


        $('.picker__header .habit-highest').html("Highest  <span class='highest-value'>"+highest+"</span>");
        $('.picker__header .habit-lowest').html("Lowest  <span class='lowest-value'>"+lowest+"</span>");
        $('.picker__header .monthly-average').html("Monthly Average  " +average);
        $('.picker__header .monthly-total').html("Monthly Total  " +total);
        
}*/

/*var changePrev = function(gid,habit_date)
{
    var habit_start_date = formatDate(habit_date);
    var habit_date = new Date(habit_start_date);
    var habit_month = habit_date.getMonth()+1;
    
    $(".picker__nav--prev").click(function(){
        var month  = $(".picker__select--month").val();
        var  year  = $(".picker__select--year").val();
        (month <=0)?12:month;
        //month = parseInt(month)-parseInt(1);
        if(month == 12)
        {
            year = parseInt(year)-parseInt(1);
        }

        var month_with_year =  year + '-' +
        (month<10 ? '0' : '') + month;
        //console.log(month_with_year);
        var c = new Date(habit_date);
        //console.log(c);
        var next_month = c.getMonth()+1;
        
        var next_month_with_years = c.getFullYear() + '-' +
        (next_month<10 ? '0' : '') + next_month;

         if(month_with_year >= next_month_with_years)
         {
        $.ajax({
            url : site_url+'/log/monthlyAverage',
            method : "post",
            data : {month:month,year:year,gid:gid},
            success : function(response)
            {
                //console.log(response);
                $('.picker__header .monthly-average').html("");
                $('.picker__header .monthly-total').html("");
                $('.picker__header .monthly-average').html("Monthly Average  " +response.data.monthly_average);
                $('.picker__header .monthly-total').html("Monthly Total  " +response.data.monthly_total);
            }
        });
        // getWeekAverage(gid,habit_date);
    }
       
    });
}

var changeNext = function(gid,habit_date)
{
    $(".picker__nav--next").click(function(){
        var month  = $(".picker__select--month").val();
        var  year  = $(".picker__select--year").val();
        month = parseInt(month)+parseInt(1);
        //console.log(month);
        /*if(month == 0)
        {
            year = parseInt(year)+parseInt(1);
        }
        var month_with_year =  year + '-' +
        (month<10 ? '0' : '') + month;
        var d = new Date();
        var current_year = d.getFullYear();
        var current_month = d.getMonth();
        var current_day = d.getDate();
        var c = new Date(current_year + 1, current_month, current_day);
        //console.log(c);
        var next_month = c.getMonth()+1;
        
        var next_month_with_years = c.getFullYear() + '-' +
        (next_month<10 ? '0' : '') + next_month;
         if(month_with_year < next_month_with_years)
         {
            if(month == 12)
            {
                month = 0;
                year = parseInt(year)+parseInt(1);
            }
            //alert("reached here....");            
            $.ajax({
                url : site_url+'/log/monthlyAverage',
                method : "post",
                data : {month:month,year:year,gid:gid},
                success : function(response)
                {
                    $('.picker__header .monthly-average').html("");
                    $('.picker__header .monthly-total').html("");
                    $('.picker__header .monthly-average').html("Monthly Average  " +response.data.monthly_average);
                    $('.picker__header .monthly-total').html("Monthly Total  " +response.data.monthly_total);
                }
            });
        //getWeekAverage(gid,habit_date);
    }
    });
}
*/

function getWeekAverage (id,habit_date) {
    //alert(id);
    var habit_start_date = formatDate(habit_date);

    $(".picker__wrap .picker__table tbody tr").each(function(k,v){
        var days = 0; 
        var allow_days = 0;
        var total = 0;
        var weekly_average = 0;
        var not_allow_days = 0;
      $(this).find("td").each(function(){
      var value = $(this).children("div").attr("data-pick");
      var date = convertDbDate(value);
      var currentDate = new Date();
      var currentD = formatDate(currentDate);
    if(date >= habit_start_date && date < currentD)
    {
       days = $(this).children("div").children(".number-scale-calender").children(".input-scale-number").attr("data-value");
       
       if(days >=0 && days !="")
       {
        total = parseFloat(total)+parseFloat(days);
       }
      
       if(days == "" || days >=0)
       {
        allow_days = parseInt(allow_days)+parseInt(1);
       }
       if(days == -1)
       {
        not_allow_days = parseInt(not_allow_days)+parseInt(1);
       }
    } 
      });

      if(total > 0)
       {
        weekly_average = parseInt(total)/parseInt(allow_days);
       }

     $(this).find("td:last").after("<td><span class='weekly-average'>avg</span><span class='weekly-average average-value'>"+weekly_average.toFixed(2)+"</span></td>");
     $(this).find("td:last").after("<td><span class='weekly-total'>ttl</span><span class='weekly-average total-value'>"+total.toFixed(2)+"</span></td>");
    });
}

function spanTagForDates(gid,scale,h,l,is_apply) {
    //console.log(h);
    $(".picker__wrap .picker__table tbody tr td").each(function(k,v){
        var days = $(this).children("div").html();
        var dateValue = $(this).children("div").attr("data-pick");
        var date = convertDbDate(dateValue);
        $(this).children("div").html("");
        $(this).children("div").html("<span class='day-scale-number'>"+days+"</span><span class='number-scale-calender'><input type='number' min='"+l+"' max='"+h+"' data-min='"+l+"' data-max='"+h+"' gid='"+gid+"' gdate='"+date+"' data-scale='"+scale+"' data-is_apply='"+is_apply+"' data-value='' class='form-control input-scale-number' value='' placeholder='-'></span>");
    });
}
var formatDate = function(habit)
{
    var d = new Date(habit);
    var month = d.getMonth()+1;
    var day = d.getDate();

var output = d.getFullYear() + '-' +
    (month<10 ? '0' : '') + month + '-' +
    (day<10 ? '0' : '') + day;
    return output;
}

var dateInput = function(habit_id,habit_date){
        var month = $(".picker__select--month").val();
        var year  = $(".picker__select--year").val();

        console.log("month",month);
        console.log("year",year);
        $(".input-scale-number").unbind("touchstart click").bind('touchstart click', function(e){
            var input = $(this).val();
            if(input == 0 || input =='')
            {
                $(this).val("");
                $(this).attr("placeholder","N/A");
            }
        }); 
       
       $(".input-scale-number").on('focusout', function(e) { 
        var va = $(this).parent().parent().attr('data-pick');

        var date = convertDbDate(va);

        var d = new Date(va);
        var date_id = '#' + va;

         var scale = $(this).attr("data-scale");
         var lowest   = $(this).attr("data-min");
         var highest   = $(this).attr("data-max");
         var gid   = $(this).attr("gid");
         var is_apply = $(this).attr("data-is_apply");

          if(scale == 0)
            {
                var value = $(this).attr('data-value');
            }
            else
            {
            var value = $(this).val();
            
            if($(this).val() !='')
            {
                if(value != 0 )
                {
                    if(is_apply == 0)
                    {
                        if(parseFloat(value) < lowest)
                        {
                            alert("This value range must be from "+lowest+ " to "+highest+" or 0");
                            $(this).val(lowest);
                            $.tloader("hide");
                           return false;
                        }
                        if(parseFloat(value) > highest)
                        {   

                            alert("This value range must be from " +lowest+ " to" +highest+ " or 0");
                            $(this).val(lowest);
                            $.tloader("hide");
                           return false;
                        }
                }
                else
                {
                        if(value > 1000)
                        {
                            alert("This value range must be from 0 to 1000");
                            $(this).val(0);
                            $.tloader("hide");
                            //return false;
                        } 

                        if(value < 0)
                        {
                            alert("This value range must be from 0 to 1000");
                            $(this).val(0);
                            $.tloader("hide");
                            //return false;
                        } 

                    }  
                }
                else
                {
                    value = 0;
                }
            }else{
                value = -1;
            }
                    
        }
        $(date_id+" .number-scale-calender .input-scale-number").attr("data-value",value);
        log_NumberInput(gid, value,scale, date, date_id,month,year,is_apply,habit_date);
        getAverage(2,habit_date);
      });  
}

function getAverage (check,habit_date) {
    var habit_start_date = formatDate(habit_date);
    $(".picker__table tbody tr").each(function(k,v){
        var days = 0; 
        var allow_days = 0;
        var total = 0;
        var weekly_average = 0;
        var not_allow_days = 0;
        $(this).find("td").each(function(){
        var value = $(this).children("div").attr("data-pick");
        var date = convertDbDate(value);
        var currentDate = new Date();
        var currentD = formatDate(currentDate);
        if(date >= habit_start_date && date < currentD)
        {
       days = $(this).children("div").children(".number-scale-calender").children(".input-scale-number").attr("data-value");
       if(days >=0 && days != "")
       {
        total = parseFloat(total)+parseFloat(days);
       }
       if(days == "" || days >=0)
       {
        allow_days = parseInt(allow_days)+parseInt(1);
       }
       if(days == -1)
       {
        not_allow_days = parseInt(not_allow_days)+parseInt(1);
       }
       
    } 
      });

      if(check == 1)
       {
        allow_days = parseInt(allow_days)+parseInt(not_allow_days);
       }

      if(total > 0 && allow_days > 0)
       {
        weekly_average = parseFloat(total)/parseInt(allow_days);
       }
     
     $(this).find("td .average-value").html("");
     $(this).find("td .average-value").html(weekly_average.toFixed(2));
     $(this).find("td .total-value").html("");
     $(this).find("td .total-value").html(total.toFixed(2));
    });
    
}

var checkInclude = function(m,y,gid,habit_date){
    $(document).unbind("ifChanged").on("ifChanged",".include-notavaible-days",function(e){
       if($(this).is(":checked"))
       {
        var check = 1;
        monthlyAverage(m,y,gid,check,habit_date);
        getAverage (check,habit_date)
        
       }
       else
       {
        check = 2;
        monthlyAverage(m,y,gid,check,habit_date);
        getAverage (check,habit_date)
       }
    });
}

var checkMobileInclude = function(m,y,gid,habit_date){
    $(".include-notavaible-days").unbind("click").on("click",function(e){
       if($(this).is(":checked"))
       {
        var check = 1;
        monthlyAverage(m,y,gid,check,habit_date);
        getAverage (check,habit_date)
        
       }
       else
       {
        check = 2;
        monthlyAverage(m,y,gid,check,habit_date);
        getAverage (check,habit_date)
       }
    });
} 