$(document).ready(function(){

       /* $('.habit-start-date').pickadate({
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
        */
        
        add_to_lobby();
        //habitLobby();
        saveTaskSchedule();
        cancelTaskTemplate();
        //taskLobby();
        cancelLobbyModal();
        checkInput();
        checkIsapply();
        habitLobby();
        taskLobby();



});
  
var add_to_lobby = function()
{
        $(".addLobby").click(function(){
        var b = $("#addLobby");
        var c = b.find('.modal-body');
        var h = b.find('.modal-header');
        var f = b.find('.modal-footer');
        c.empty();
        f.empty();
        h.empty();

        h.html('<h3> Add From Lobby </h3>');

        /*var body = '<div class="btn-group lobbyPart">'+
        '<button type="button" name="att" id="lobby_habit" class="btn type-button btn-primary active" data-id="1">Habit</button>'+
        '<button type="button" name="att" id="lobby_task" class="btn type-button btn-primary" data-id="2">Task</button>'+
        '<button type="button" name="att" id="lobby_character" class="btn type-button btn-primary" data-id="3">Character</button>'+
        '</div><br><br>';*/
        var body = "";
        body += '<div id="habit_lobby" style="text-align: center;">'+
        '<form method="post" id="habit_lobby_form">'+
        '<div class="row">'+
        '<div class="col-sm-6 col-sm-push-3">'+
        '<div class="form-group">'+
        '<select name="lobby_undefined" id="select_lobby_habit" class="form-control">'+ 
        '</select> '+
        '</div>'+
        '<div class="clearfix"></div>'+
        '<div class="fullhalfheightsec2" style="height: auto;overflow-y: visible;">'+
        '<div class="form-group">'+
        '<div class="goal-row goal-row-parent">'+
        '<div class="goal-top" data-id="goal-top-">'+
        '<a class="type" href="#" data-value="Undefined" data-id="0" data-toggle="modal" data-target="#type_box"><span class="goal-title goal-title-sub-text">Undefined</span></a>'+
        /*'<a class="lobby green" href="#" id="habit-eye" data-target="#show-lobby" data-toggle="modal"  data-value="fa fa-eye"><span class="goal-title goal-title-sub-text"><i class="fa fa-eye"></i></span></a>'+*/
        '<a class="habit-schedule settings" style="display:none;"  data-goal-type="" data-toggle="modal" data-target="#light_box"  href="#" id="sub_habit_type" data-habit-type="1;7" data-form=""><i class="fa fa-cog"></i></a>'+
        '<input class="date fieldset__input js__datepicker habit-start-date picker__input" style="display:none;" type="text" name="sub_habit_start_date" value="' + current_date() + '" readonly>'+
        '</div>'+
        '</div>'+
        '<div class="control-group after-add-more-habit">'+
        '<input type="hidden" name="id" id="sub_goals_id" value="">'+                   
        '<input type="text" name="sub_goals" class="form-control sub-goal" placeholder="Name of subgoal...">'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="clearfix"></div>'+
        '</div>'+
        '</div>'+
        '</form>'+
        '</div>';
        c.html(body);
        var button = $('<div class="lobby-footer"><button type="button" class="btn btn-warning" id="lobby-template-cancel" style="margin-right:0.5em;">Cancel</buton> <button  type="button" class="btn btn-success" id="lobby-template-save">Done</buton></div>');
        f.html(button);

        //$(".ends_on_date").datepicker();

            $.ajax({
              url: '/goals/type',
              type: 'get',
              dataType: 'json',
              success: function(response)
              {
                var len = 0;
                $('#select_lobby_habit').empty(); // Empty <tbody>
                if(response['data'] != null){
                  len = response['data'].length;
                }

                if(len > 0){

                  for(var i=0; i<len; i++){
                    var id = response['data'][i].id; 
                    var name = response['data'][i].name;

                    var tr_str = "<option value='"+id+"'>" +name + "</option>";

                    $("#select_lobby_habit").append(tr_str);
                  }
                }
              }
            })
          typeChange();
          save_subGoal();
          cancelLobbyModal();
          /*$(".begin_on_date").datepicker();
          $(".ends_on_date").datepicker();*/
        });
}


var typeChange = function()
{
    $(".type-button").click(function(){
          var t = $("#habit_lobby_form");
          var z = t.find(".type");
          z.attr("data-id","");
          z.find("span").html("");
          $(".type-button").removeClass("active").addClass("inactive");
          $(this).removeClass("inactive").addClass("active");

          var type = $(this).attr("data-id");
          if(type == 0)
          {
                $("#habit_lobby_form .type").attr("data-id",type);
                $("#habit_lobby_form .type span").html("Undefined");
                $(".settings").css("display","none");
                $(".habit-start-date").css("display","none");
          }
          else if(type == 1)
          {
                $("#habit_lobby_form .type").attr("data-id",type);
                $("#habit_lobby_form .settings").removeClass("task-schedule");
                $("#habit_lobby_form .settings").addClass("habit-schedule");
                $("#habit_lobby_form .settings").attr("data-target","");
                $("#habit_lobby_form .settings").attr("data-target","#light_box");
                $("#habit_lobby_form .type span").html("Habit");
                $(".settings").css("display","inline-block");
                $(".habit-start-date").css("display","inline-block");
                    
          }
          else if(type == 2)
          {
                    $("#habit_lobby_form .type").attr("data-id",type);
                    $("#habit_lobby_form .settings").removeClass("habit-schedule");
                    $("#habit_lobby_form .settings").addClass("task-schedule");
                    $("#habit_lobby_form .settings").attr("data-target","");
                    $("#habit_lobby_form .settings").attr("data-target","#info-light-box");
                    $("#habit_lobby_form .type span").html("Task");
                    $(".settings").css("display","inline-block");
                    $(".habit-start-date").css("display","inline-block");
                    
          }
          else
          {
                $("#habit_lobby_form .type").attr("data-id",type);
                $("#habit_lobby_form .type span").html("Character");
                $(".settings").css("display","none");
                $(".habit-start-date").css("display","none");
          }

            $("#type_box").modal("hide");
            changeDate();
            habitLobby();
            taskLobby();

});
        changeDate();
  }




var changeDate = function()
{
  $(".habit-start-date").datepicker({dateFormat: 'd MM, yy'});
}

 /*----------------------------------------Add Habit Loop---------------------------------*/


var habitLobby = function()
{
          $(".habit-schedule").click(function(){
          var subgoal = $(".sub-goal").val();
          if(subgoal == "")
          {
              alert("Please Enter sub goals here");
              return false;
          }
              checkHabitSchedule();
              checkNumberSchedule()
              habitSchedule();
              checkInput();
              checkIsapply();
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


var taskLobby = function()
{
    $(".task-schedule").click(function(){
    var subgoal = $(".sub-goal").val();
    if(subgoal == "")
    {
        alert("Please Enter sub goals here");
        return false;
    }

    var result = [];
    var form_data = $(this).attr("data-form");

    if(form_data != "" && form_data !== "undefined")
    {
      result = jQuery.parseJSON(form_data);
    }

    var now = moment().format('MM/DD/YYYY');
    var repeat_qty=(result && result.repeat_qty)?result.repeat_qty:1;
    var repeat_frequency = (result && result.repeat_frequency)?result.repeat_frequency:"months";
    var repeat_on = (result && result.repeat_on)?result.repeat_on:'thisday';
    var task_name = (result && result.task_name)?result.task_name:"";
    var template_name = (result && result.template_name)?result.template_name:"";
    var add_suffix = (result && result.add_suffix)?result.add_suffix:"";
    var ends_on = (result && result.ends_on)?result.ends_on:"date";
    var ends_on_value = (result && result.end_on_value)?result.end_on_value:"";

    var begin_on = (result && result.begin_on)?result.begin_on:"now";
    var begin_on_value = (result && result.begin_on_value)?moment(result.begin_on_value).format('MM/DD/YYYY'):"";
    console.log(begin_on);
    var body = '';
    
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
    +' <span>Subtask Name:</span>'
    +' <input type="text" value="" name="task_name" id="_task_name" style="width: 71%;">'
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
    +'<label class="radio-inline-"><input type="radio" value="thisday" name="repeat_on" onchange="disableButton();" class="repeat_on_thisday" id="repeat_on_thisday" /></label><span style="margin-right:10px;">This day of Each Year</span>'
    +'<label class="radio-inline-"><input type="radio" value="firstday" name="repeat_on" onchange="disableButton();" class="repeat_on_firstday" id="repeat_on_firstday" /></label><span style="margin-right:10px;">First Day of the Year</span>'
    +'<label class="radio-inline-"><input type="radio" value="lastday" name="repeat_on" onchange="disableButton();" class="repeat_on_lastday" id="repeat_on_lastday" /></label><span style="margin-right:10px;">Last day of the Year</span>'
    +'</div>';

    body +='<br> <div class="name-suffix-section years">'
    +'<input type="checkbox" value="1" id="_add_suffix" class="regular-checkbox" name="add_suffix" />'
    +'<label for="_add_suffix"><span></span></label><span>Do you want to add name of the <span class="_repeat_on_suffix">month</span> behind the task name?</span>'
    +' </div><br>';

    body +='<div class="begins-section">'
    +'<div class="ends-on-left"><span>Begins: </span></div>'
    +'<div class="ends-on-right">'

    +'<div><label class="radio-inline-">'
    +'<input type="radio" value="now" name="begin_on" id="begin_on_now" /> </label> <span style="margin-left:-1px;">Now</span>'
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
    +'<input type="radio" value="date" name="ends_on" id="ends_on_date"/> </label> <span>On</span>'
    +'<label for="ends_on_date"><input type="text" name="ends_on_date" id="ends_on_picker" class="ends_on_date" value=""></label>'
    +'</div>'
    +'<div><label class="radio-inline-"><input type="radio" value="occurrences" name="ends_on" id="ends_on_occurrences"/></label> <span>After</span> <input type="text" name="occurrences" id="occurrences_box" class="task-sml" value="1"> Occurrences</div>'
    +'</div> <input type="hidden" value="" name="task_id" id="_task_id"></div><br>';

    body +='</div>';
    
    $("#template-form").html(body);

               if(repeat_frequency=='weeks'){

                $("#begin_on_now").attr('disabled', true);
                $("#begin_on_date").prop('checked', true);
                var week_days = repeat_on.split(",");
                console.log(week_days);
                $.each(week_days,function(i,v){
                  
                    $("#weekday-"+v).prop("checked",true);
                });
               $("span._repeat_on_suffix").html("week");  
            }else if(repeat_frequency=='months'){
                $("#begin_on_now").attr('disabled', false);
                $("span._repeat_on_suffix").html("month");  
                $(".repeat-on-section."+repeat_frequency+" .repeat_on_"+repeat_on).attr("checked",true);
                //console.log($(".repeat_on_"+repeat_on).attr("checked"));
            }else{

              console.log("hey....");
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
                $("#ends_on_picker").val(moment(result.end_on_value).format('MM/DD/YYYY'));
            }

            //$("#begin_on_"+begin_on).prop("checked",true);
            if(begin_on=='now'){
                //$("#occurrences_box").val(ends_on_value);
            }else{
                $("#begin_on_picker").val(begin_on_value);
            }


            if(repeat_frequency == 'months' || repeat_frequency == 'years'){
                  
              $("#begin_on_"+begin_on).prop("checked",true);

            }


            if(repeat_frequency == "years" || repeat_frequency == "months")
            {
              $(".repeat-on-section."+repeat_frequency+" .repeat_on_"+repeat_on).attr("checked",true);

            }

              // $("#info-light-box #_repeat_qty").val('1');
              // $("#info-light-box #_task_name").val('');
              // $("#info-light-box #_task_id").val('');
              // $("#info-light-box .months .repeat_on_thisday").prop("checked",true);
              // $("#info-light-box .years .repeat_on_thisday").prop("checked",false);
              // $("#info-light-box .weeks").removeClass("show").addClass("hide");
              // $("#info-light-box .years").removeClass("show").addClass("hide");
              // $("#info-light-box .months").removeClass("hide").addClass("show");
              // $("#info-light-box #_add_suffix").prop("checked",true);
              // $("span._repeat_on_suffix").html("month"); 
              
              test();
              disableButton();
              
              $(".weekly_checkbox").on('touchstart click', function() {
              $(".weekly_checkbox").prop("checked",false);
              $(this).prop("checked",true);
              });

              $("#occurrences_box").on('touchstart click', function() {
                console.log("Reached Here....");
                $("#ends_on_occurrences").prop("checked",true);
            });

              $(".begin_on_date").datepicker({
              onSelect: function() {
                  $("#begin_on_date").prop("checked",true);
              }
          });

              $(".begin_on_date").on("click",function(){
              
                $("#begin_on_date").prop("checked",true);
              
              });

              $(".ends_on_date").on("click",function(){
              
                $("#ends_on_date").prop("checked",true);
              
              });

              $(".ends_on_date").datepicker({
              onSelect: function() {
                  $("#ends_on_date").prop("checked",true);
              }
          });

            

        });
}


/*var setgoalDate = function()
{
  var _repeat_frequency = $("#_repeat_frequency").val();
  var _repeat_qty = $("#_repeat_qty").val();
  var begin_on = $('input[name=begin_on]:checked').val();
  var ends_on =  $('input[name=ends_on]:checked').val();
  var ends_on_occurrences = $("#occurrences_box").val();
  var begin_on_date = $("#begin_on_picker").val();
  var ends_on_date = $("#ends_on_picker").val();
  var task_name = $("#_task_name").val();


  var ttt = 0;
  if(begin_on == "now")
  {
    var start_date = new Date(); 
    
  }
  else
  {
    var start_date = new Date(begin_on_date);
  }


  if(ends_on == "date")
  {
    var end_date = new Date(ends_on_date); 
    
  }
  


  if(_repeat_frequency == "weeks")
  {
     var repeat_on = $(".weekly_checkbox:checked").val();
     
     var interval_day = parseInt(_repeat_qty)*7;
     
      if((begin_on == "now" && ends_on == "date") || (begin_on == "date" && ends_on == "date"))
       {
          
          if(end_date > start_date && _repeat_qty <= 1)
          {

              var week_days = moment(start_date).weekday();
              
              if(week_days >= repeat_on)
              {
                var next_day = 6 - parseInt(week_days);
                var prev_day = parseInt(repeat_on) - 0;

                var total_days = parseInt(next_day)+parseInt(prev_day)+1;
              }
              else
              {
                var total_days = parseInt(repeat_on)-parseInt(week_days);
              }

              
              start_date = new Date(start_date.setDate(start_date.getDate() + parseInt(total_days)));
              
              
              while(start_date < end_date)
              {
                start_date = new Date(start_date.setDate(start_date.getDate() + 7));
                if(start_date > end_date)
                {
                  start_date = new Date(start_date.setDate(start_date.getDate() - 7));
                  break;
                }
                
              }
              
              end_date = start_date;
              
          }
          else if(end_date > start_date && _repeat_qty > 1)
          {

          
      var week_days = moment(start_date).weekday();
      if(week_days > repeat_on)
      {
        var diff_day = parseInt(week_days)-parseInt(repeat_on);
        var start_date = new Date(start_date.setDate(start_date.getDate() - parseInt(diff_day)));
      }
      else{

        var diff_day = parseInt(repeat_on)-parseInt(week_days);
        var start_date = new Date(start_date.setDate(start_date.getDate() + parseInt(diff_day)));
      }

      var new_start = '';
      while (start_date < end_date) { 

            var a = moment(end_date);
            var b = moment(start_date);
            var days = a.diff(b, 'days') // 1
            

          if(interval_day > days)
          {
            break;
          }
              var start_date = new Date(start_date.setDate(start_date.getDate() + parseInt(interval_day)));
            
          } 

          }
          else if(end_date < start_date)
          {
             var start_date = $('.habit-start-date').val();
             start_date = new Date(start_date);
             var end_date = new Date(start_date.setMonth(start_date.getMonth() + parseInt(ttt)));
          }

          
          

       }
       else if((begin_on == "now" && ends_on == "occurrences") || (begin_on == "date" && ends_on == "occurrences"))
       {

              var week_days = moment(start_date).weekday();
              
              if(week_days >= repeat_on)
              {
                var next_day = 6 - parseInt(week_days);
                var prev_day = parseInt(repeat_on) - 0;

                var total_days = parseInt(next_day)+parseInt(prev_day)+1;
              }
              else
              {
                var total_days = parseInt(repeat_on)-parseInt(week_days);
              }

             
              start_date = new Date(start_date.setDate(start_date.getDate() + parseInt(total_days)));

              if(_repeat_qty <=1)
            {
              var i = 0;
                while(i < parseInt(ends_on_occurrences)-1)
                {
                  start_date = new Date(start_date.setDate(start_date.getDate() + 7));
                    i = parseInt(i)+1
                  
                }

            }
            else
            {
                var interval_day = parseInt(_repeat_qty)*7;
                
                var i =0;
                while (i < parseInt(ends_on_occurrences)-1) { 
     
                 var start_date = new Date(start_date.setDate(start_date.getDate() + parseInt(interval_day)));
                i = parseInt(i)+1; 
              } 
            }

            var end_date = start_date;
              
              
       }



  }
  else if(_repeat_frequency == "months")
  {

       if((begin_on == "now" && ends_on == "date") || (begin_on == "date" && ends_on == "date"))
       {
          
          if(end_date < start_date && _repeat_qty <= 1)
          {
             var start_date = $('.habit-start-date').val();
             start_date = new Date(start_date);
          }
          else if(end_date > start_date && _repeat_qty > 1)
          {
        
              var end_month = end_date.getMonth();
              var start_month = start_date.getMonth();
              var end_year = end_date.getFullYear();
              var start_year = start_date.getFullYear();
              if(end_month > start_month && end_year == start_year)
              {
                diff_month = parseInt(end_month) - parseInt(start_month);
                if(diff_month > _repeat_qty)
                {
                  var sss = 1;
                  sss = parseInt(diff_month)/parseInt(_repeat_qty);
                  var ttt = parseInt(sss)*parseInt(_repeat_qty);
                }
                else
                {
                  end_date = start_date;
                }
              }
              else if(end_month > start_month && end_year > start_year)
              {
                di_month = 12- parseInt(end_month);
                diff_month = parseInt(di_month) + parseInt(start_month)+1;
                var sss = 1;

                if(diff_month > _repeat_qty)
                {
                  sss = parseInt(diff_month)/parseInt(_repeat_qty);
                  var ttt = parseInt(sss)*parseInt(_repeat_qty);
                }

              }
              else if(end_month < start_month && end_year > start_year)
              {
                
                di_month = 12- parseInt(start_month);
                diff_month = parseInt(di_month) + parseInt(end_month)+1;
                
                var sss = 1;

                if(diff_month > _repeat_qty)
                {
                  sss = parseInt(diff_month)/parseInt(_repeat_qty);
                  var ttt = parseInt(sss)*parseInt(_repeat_qty);
                }

              }

          }
          else if(end_date > start_date && _repeat_qty <= 1)
          {
              var end_month = end_date.getMonth();
              var start_month = start_date.getMonth();
              var end_year = end_date.getFullYear();
              var start_year = start_date.getFullYear();
              var start_day = start_date.getDate();
              end_month = parseInt(end_month)+1;
              var new_date = end_year + "-" + end_month + "-" + start_day;

              start_date = new Date(new_date);
              
          }
          else if(end_date < start_date && _repeat_qty > 1)
          {
            var start_date = $('.habit-start-date').val();
             start_date = new Date(start_date);
          }
        
          var end_date = new Date(start_date.setMonth(start_date.getMonth() + parseInt(ttt)));
          

       }
       else if((begin_on == "now" && ends_on == "occurrences") && (begin_on == "date" && ends_on == "occurrences"))
       {
          if(_repeat_qty <=1)
          {
            start_date = new Date(start_date);
            var end_date = new Date(start_date.setMonth(start_date.getMonth() + parseInt(ends_on_occurrences)));
          }
          else
          {
            
            var inter_val = parseInt(ends_on_occurrences)*parseInt(_repeat_qty);
            start_date = new Date(start_date);
            var end_date = new Date(start_date.setMonth(start_date.getMonth() + parseInt(inter_val)));
          }
       }


  }
  else if(_repeat_frequency == "years")
  {
      

       if((begin_on == "now" && ends_on == "date") || (begin_on == "date" && ends_on == "date"))
       {
          
          if(end_date < start_date && _repeat_qty <= 1)
          {
              var start_date = $('.habit-start-date').val();
              start_date = new Date(start_date);
          }
          else if(end_date > start_date && _repeat_qty > 1)
          {
        
              var end_month = end_date.getMonth();
              var start_month = start_date.getMonth();
              var end_year = end_date.getFullYear();
              var start_year = start_date.getFullYear();
              if(end_year == start_year)
              {
                diff_month = parseInt(end_year) - parseInt(start_year);
                if(diff_month > _repeat_qty)
                {
                  var sss = 1;
                  sss = parseInt(diff_month)/parseInt(_repeat_qty);
                  var ttt = parseInt(sss)*parseInt(_repeat_qty);
                }
                else
                {
                  end_date = start_date;
                }
              }
              else if(end_year > start_year)
              {
                
                diff_month = parseInt(end_year) - parseInt(start_year);
                
                var sss = 1;

                if(diff_month > _repeat_qty)
                {
                  sss = parseInt(diff_month)/parseInt(_repeat_qty);
                  var ttt = parseInt(sss)*parseInt(_repeat_qty);
                }

              }
              else if(end_year < start_year)
              {
                var start_date = $('.habit-start-date').val();
                start_date = new Date(start_date);

              }

          }
          else if(end_date > start_date && _repeat_qty <= 1)
          {
              var start_day = start_date.getDate();
              var end_month = end_date.getMonth();
              var start_month = start_date.getMonth();
              var end_year = end_date.getFullYear();
              var start_year = start_date.getFullYear();
              end_month = parseInt(end_month)+1;
              new_end_year = parseInt(end_year)-2;

              if(new_end_year < start_year)
              {
                var start_date = $('.habit-start-date').val();
                start_date = new Date(start_date);
                //start_date = new Date();
                //start_date = new Date();
              }
              else if(new_end_year >= start_year)
              {
                var new_date = new_end_year+"-"+start_month+"-"+start_day;
                start_date = new Date(new_date);
              }
          }
          else if(end_date < start_date && _repeat_qty > 1)
          {
              var start_date = $('.habit-start-date').val();
               start_date = new Date(start_date);
          }

          var end_date = new Date(start_date.setFullYear(start_date.getFullYear() + parseInt(ttt)));
          
          

       }
       else if((begin_on == "now" && ends_on == "occurrences") || (begin_on == "date" && ends_on == "occurrences"))
       {

         if(_repeat_qty <= 1)
          {
            
            start_date = new Date(start_date);
            var end_date = new Date(start_date.setFullYear(start_date.getFullYear() + parseInt(ends_on_occurrences)));
          }
          else
          {
            //console.log("Hiiii....");
            
            var inter_val = parseInt(ends_on_occurrences)*parseInt(_repeat_qty);
            start_date = new Date(start_date);
            var end_date = new Date(start_date.setFullYear(start_date.getFullYear() + parseInt(inter_val)));
          }
       }

      
  }

  $(".habit-start-date").val(moment(end_date).format('DD MMMM, YYYY'));

}*/



var saveGoal = function(formdata)
{
  var savexhr  =  $.ajax({
                      url : site_url+"/goals/add-lobby",
                      method : "post",
                      data : formdata,
                      success : function(res)
                      {
                        
                     if(res.status ==1)
                        {

                          $("#_task_id").val(res.data.auto_save_id);
                          $(".settings").attr("data-goal-id",res.data.auto_save_id);
                          $("#sub_goals_id").val(res.data.auto_save_id);
                      }
                      else{
                          $.notify(res.data.join("<br/>"), {
                          type:'danger',
                          z_index: 999999999,
                          });
                          $.tloader("hide");
                          }
                  }
                });

}

var saveTaskSchedule = function()
{
          $("#task-template-save").on('touchstart click', function(){
          var form_data = $(".template-form").find("select, textarea, input").serialize();
          $.post(site_url+'/validate-task-template', form_data, function(response) {

        if (response.status == 1)
        {
            var result = jQuery.parseJSON(response.data);
            
             //setgoalDate();
             $(".task-schedule").attr("data-form",response.data);
             if(result['new_due_date'] != "" && result['new_due_date'] != 'Undefined')
             {
               end_date = result['new_due_date'];
               $(".habit-start-date").val(moment(end_date).format('DD MMMM, YYYY'));
             }


            $("#info-light-box").modal('hide');
        }else{
            $.notify(response.data.join("<br/>"), {
            type:'danger',
            z_index: 999999999,
            });
          }
        });
        });

}


var checkHabitSchedule = function()
{
          $("#routine").click(function(){
              $("#week").removeClass('active').addClass('inactive');
              $("#routine").removeClass('inactive').addClass('active');
              $(".day-options").removeClass('hide').addClass('show');
              var schedule = $(this).data("value");
              $("#habitSchedule").val("");
              $("#habitSchedule").val(schedule);

          });

          $("#week").click(function(){
              $("#week").removeClass('inactive').addClass('active');
              $("#routine").removeClass('active').addClass('inactive');
              $(".day-options").removeClass('show').addClass('hide');
              var schedule = $(this).data("value");
              $("#habitSchedule").val("");
              $("#habitSchedule").val(schedule);
          }); 
}


var checkNumberSchedule = function()
{
          $("#number").click(function(){
              $("#my_boolean").removeClass('active').addClass('inactive');
              $("#number").removeClass('inactive').addClass('active');
              $(".number-options").removeClass('hide').addClass('show');
              var scale = $("#number").data("value");
              $("#scale").val('');
              $("#scale").val(scale);
              checkInput();
      
          });

          $("#my_boolean").click(function(){
              $("#number").removeClass('active').addClass('inactive');
              $("#my_boolean").removeClass('inactive').addClass('active');
              $(".number-options").removeClass('show').addClass('hide');
              var scale = $("#my_boolean").data("value");
              $("#scale").val('');
              $("#scale").val(scale);
          }); 
}


    $("#habit-schedule-cancel").click(function(){
    $('#light_box').modal('hide');

});


var checkInput = function()
  {
   $("#is_apply").unbind("ifChanged").on("ifChanged",function(){
    if($("#is_apply").prop("checked") == true)
    {
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
      $("#highest").removeClass("disabled");
      $("#lowest").removeClass("disabled");
      $(".highest").removeClass("disabled");
      $(".lowest").removeClass("disabled");
      $("#highest").removeAttr("readonly");
      $("#lowest").removeAttr("readonly");
  }
      
  });
  }

  var checkIsapply = function()
  {
   $("#is_apply").unbind("change").on("change",function(){
    if($("#is_apply").prop("checked") == true)
    {
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
      $("#highest").removeClass("disabled");
      $("#lowest").removeClass("disabled");
      $(".highest").removeClass("disabled");
      $(".lowest").removeClass("disabled");
      $("#highest").removeAttr("readonly");
      $("#lowest").removeAttr("readonly");
  }
      
  });
}

var savexhr;
var save_subGoal = function()
{
      $("#lobby-template-save").unbind('touchstart click').bind('touchstart click', function() {
          var sub_goals = $(".sub-goal").val();
          if(sub_goals == "")
          {
            alert("Enter sub goal here");
            return false;
          }
          $.tloader("show","Loading...");
          var type =  $(".type").attr("data-id");
          
          var form_data =  $("#habit_lobby_form").serialize();
          if(type == 1)
          {
            var habit_form_data = $("#habit-schedule").serialize();
            
            var formData =  form_data+"&type="+type+"&"+habit_form_data;
          }
          else if(type == 2)
          {
            var task_form_data = $(".template-form").find("select, textarea, input").serialize();
            var formData =  form_data+"&type="+type+"&"+task_form_data;
            
          }
          else
          {
            var formData =  form_data+"&type="+type;
          }
          
          var savexhr  =  $.ajax({
                      url : site_url+"/goals/add-lobby",
                      method : "post",
                      data : formData,
                      success : function(res)
                      {
                        console.log(res);
                     if(res.status ==1)
                        {
                          $("#habit-schedule")[0].reset();
                          $.notify(res.msg, {
                          type:'success',
                          z_index: 999999999,
                          });

                          $.tloader("hide");

                          location.reload();
                          $("#addLobby").modal('hide');
                      }
                      else{
                          $.notify(res.data.join("<br/>"), {
                          type:'danger',
                          z_index: 999999999,
                          });
                          $.tloader("hide");
                          }
                  }
                })
      });

}

// When the user clicks anywhere outside of the modal, close it
/*window.onclick = function(event) {
  var modal = document.getElementById('addLobby');
    if (event.target == modal) {
        if(confirm("Are you sure you want to close this window? All your changes will be lost."))
        {  
        $('#addLobby').modal('hide');
        }
        else
        {
          $('#addLobby').modal('toggle');
        }
    }
}*/


var habitSchedule = function()
{
        $("#habit-schedule-save").unbind('touchstart click').bind('touchstart click', function() {

            if($(".scale-options .active").attr("data-value") == 1)
            {
            if($("#is_apply").prop("checked")== true)
            {
              var check = 1;
            }
            else
            {
              check = 0;
            }
            var highest = $("#highest").val();
            var lowest = $("#lowest").val();
            if(check == 0)
            {
              if(highest == "")
              {
                alert("Please enter Highest value");
                return false;
              }

              if(lowest == "")
              {
                alert("Please enter Lowest value");
                return false;
              }
          }
          }
                $("#light_box").modal('hide');

        });
}


function test()
{
      
      var repeat_frequency = $("#_repeat_frequency").val();
      console.log(repeat_frequency);
      if(repeat_frequency=='weeks')
      {
          //console.log("hiiiiii");
          $(".repeat-on-section.weeks").removeClass('hide').addClass('show');
          $(".repeat-on-section.months").removeClass('show').addClass('hide');
          $(".repeat-on-section.years").removeClass('show').addClass('hide');
          $("#begin_on_now").prop('checked', false);
          $("#begin_on_now").attr('disabled', true);
          $("#begin_on_date").prop('checked', true);
          //$(".weekly_checkbox").prop('checked',false);
          $("span._repeat_on_suffix").html("week");     
      }
      else if(repeat_frequency=='months')
      {
          
          $(".repeat-on-section.weeks").removeClass('show').addClass('hide');
          $(".repeat-on-section.months").removeClass('hide').addClass('show');
          $(".repeat-on-section.years").removeClass('show').addClass('hide');
          //$("#begin_on_date").prop('checked', true); 
          //$("#begin_on_now").attr('disabled', false); 
          //$("span._repeat_on_suffix").html("month");
          $(".months .repeat_on_thisday").prop("checked",true);
          //$(".years .repeat_on_thisday").prop("checked",false); 
      }
      else
      {
            $(".repeat-on-section.weeks").removeClass('show').addClass('hide');
            $(".repeat-on-section.months").removeClass('show').addClass('hide');
            $(".repeat-on-section.years").removeClass('hide').addClass('show');
            //$(".months .repeat_on_thisday").prop("checked",false);
            $(".years .repeat_on_thisday").prop("checked",true);
            //$("span._repeat_on_suffix").html("year"); 
       /*         if($(".years #repeat_on_firstday").prop('checked')===true)
                {
                    $("#begin_on_now").attr('disabled', false);
                    $("#begin_on_now").prop('checked', true); 
                }
                else
                {
                    $("#begin_on_now").attr('disabled', true);
                    $("#begin_on_now").prop('checked', false); 
                }*/
      }
}


function disableButton()
{
  var repeat_frequency = $("#_repeat_frequency").val();
    if(repeat_frequency == "years" || repeat_frequency == "months")
    {

      if($(".years #repeat_on_firstday").prop('checked')===true)
      {
            $("#begin_on_now").attr('disabled', true);
            $("#begin_on_date").prop('checked', true); 
      }
      else if($(".years #repeat_on_lastday").prop('checked')===true)
      {
            $("#begin_on_now").attr('disabled', true);
            $("#begin_on_date").prop('checked', true);   
      }
      else{
            $("#begin_on_now").attr('disabled', false);
            $("#begin_on_now").prop('checked', true); 
      }

      } 
}



function current_date() {
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var d = new Date();
    var strDate = d.getDate() + " " + monthNames[d.getMonth()] + ", " + d.getFullYear();
    console.log(strDate);
    return strDate;
}


var cancelTaskTemplate = function()
{
    $('#task-template-cancel').on('touchstart click', function() {
    $("#info-light-box").modal('hide');
});
}



var cancelLobbyModal = function()
{
      $("#lobby-template-cancel").click(function(){
        if(confirm("Are you sure you want to close this window? All your changes will be lost."))
        {  
          $("#habit_lobby_form")[0].reset();
          $("#addLobby").modal("hide");
        }
      
      });
}