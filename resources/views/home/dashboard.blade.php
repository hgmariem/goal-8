@extends('layouts.base') 
@section('content')

<div id="page-wrapper" class="no-pad">
    <div class="graphs">

      <div class="col-md-4 pull-right">
                   
        @if($reminder)
        <div class="alert alert-danger alert-dismissable fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ $reminder }}
        </div>
        @endif
      
      </div>
        <!-- Desktop Html -->

        <div class="hide-on-mobile">
            

            <script>
                
                var indexyr = 0;
                
                function display_prev_graph() {
                  
                    if(indexyr == 0) {
                      console.log(indexyr);
                        return false;
                    } else {
                        $(".yrgraph_"+indexyr).hide();
                        indexyr--;
                        $(".yrgraph_"+indexyr).show();
                        console.log(indexyr);
                        $('#next_graph').css('opacity','1');
                    }
                    if(indexyr == 0) {
                        $('#prev_graph').css('opacity','.3');
                    } else {
                        $('#prev_graph').css('opacity','1');
                    }
                    
                }

                function display_next_graph() { 
                   console.log("Hey Guys.....",total_year_event,indexyr);
                    if( indexyr == total_year_event-1) {
                        return false;
                    } else {
                        $(".yrgraph_"+indexyr).hide();
                        indexyr++;
                        $(".yrgraph_"+indexyr).show();
                        $(".yrgraph_"+indexyr).css('display','block');
                        $('#prev_graph').css('opacity','1');
                    }
                    if( indexyr == total_year_event-1) 
                    {
                        $('#next_graph').css('opacity','.3');
                    } else {
                        $('#next_graph').css('opacity','1');
                    }
                    
                }
            </script>
            <?php if($isDesktop){ ?>
            <div class="landing_habbit bootmodal">
                @include('goals.partials.statics_popup')
                
                <div class="graphs">
                    <div class="fullheightsection">
                       
                        <section class="habit custom-old-style" id="habit-id">
                            <div class="habits-container">
                                @include('goals.partials.habits',['habits'=>$habits,"days"=>$days])
                                
                            </div> <!-- habit container ends-->
                            <input class="list_calendar" style="display:none;" type="text" value="" name="calendar" id="calendar" /> 
                        </section>

                        <section class="task-reminders-wrapper">
                            
                            <section class="lobby-goals-rows col-lg-8 col-md-8 col-sm-8 col-xs-12 no-pad task tasks" id="task-id">
                                <div class="tasks-container">
                                     @include('goals.partials.tasks',['tasks'=>$tasks])
                                </div>
                            </section>
                            <section class="col-lg-4 col-md-4 col-sm-4 col-xs-12 no-pad  character reminders custom-old-style" id="character-id">
                                <div class="m-sec-rem character-container">
                                    @include('goals.partials.characters',['characters'=>$characters,"statements"=>$statements])
                                </div>
                            </section>

                        </section>
                    
                    </div>
                </div>

            </div>
            <?php } ?>
            
        </div>


<!-- Mobile Html -->

<div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
    <?php 
        if($isMobile){?>
            @include('mobile_header')
    <?php } ?>
    <?php if($isMobile){ ?>
        <div class="mobile-content home">
            <ul class="task-tabs nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#_habits">Habits</a></li>
                <li><a data-toggle="tab" href="#_tasks">Tasks</a></li>
                <li><a data-toggle="tab" href="#_chararter">Character</a></li>
            </ul>

            <div class="tab-content bootmodal">
                @include('goals.partials.statics_popup')
                <a href="#" class="addLobby plus-icon-mobile" data-toggle="modal" data-device="isMobile" data-target="#addLobby" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus my-float"></i></a>
                <div class="tab-pane fade in active" id="_habits">
                     <!----- habits -->
                     <div class="habits-container">
                        @include('goals.partials.mobile.habits',['habits'=>$habits, "days"=>$days])
                     </div>
                </div>

                <div class="tab-pane fade" id="_tasks">
                    <!----- tasks -->
                    <div class="tasks-container">
                        @include('goals.partials.mobile.tasks',['tasks'=>$tasks])
                    </div>
                </div>

                <div class="tab-pane fade" id="_chararter">
                    <section class="col-md-12 col-sm-12 no-pad  character reminders custom-old-style" id="character-id">
                        <div class="m-sec-rem character-container">
                            <!----- characters -->
                            @include('goals.partials.mobile.characters',['characters'=>$characters])
                        </div>
                    </section>
                </div>
            </div>

<div class="modal" id="addLobby" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
        </div>
      </div>
      
    </div>
  </div>


  <div id="type_box" class="modal" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"><div class="btn-group"><button type="button" name="att" class="btn type-button btn-primary active" data-id="0">Undefined</button><button type="button" name="att" class="btn type-button btn-primary" data-id="1">Habit</button><button type="button" name="att" class="btn type-button btn-primary" data-id="2">Task</button><button type="button" name="att" class="btn type-button btn-primary" data-id="3">Character</button></div></div>
                <div class="modal-footer"></div>
              </div>
            </div>
   </div>
         


  <div id="light_box" class="modal" role="dialog">
            <div class="modal-dialog">
               <form method="post" id="habit-schedule">
              <div class="modal-content">
                <div class="modal-header">
                Please choose type:</div>
                <div class="modal-body">
                   <div class="btn-group scale-options">
                    <button type="button" name="att" id="my_boolean"  data-value="0" class="btn btn-primary active">True/False</button>
                    <button type="button" name="att" id="number" data-value="1" class="btn btn-primary">Numeric</button>
                    </div><br>

                    <input type="hidden" name="scale" value="0" id="scale">

                    <!-- <div class="Scale hide"> -->
                     <div class="btn-group2 number-options hide"><h2><b>Please Choose Scale</b><a href="#" class="choose-scale" data-toggle="modal" data-target="#choose_help"><i class="fa fa-info-circle fa-lg"></i></a></h2>
                        <div class="text-center">
                          <input type="checkbox" name="is_apply" data-value="1" id="is_apply" class="regular-checkbox" value="1" /><label for="is_apply"><span></span></label><span class="no_of_days">Does Not Apply</span>
                      </div>
                      <br>
                      <div class="form-inline">
                        <label class="lowest"><b>Lowest</b></label>
                        <input type="number" name="lowest" id="lowest" style="width: 80px; margin-left: 10px;" min="0" max="1000">
                        <label class="highest"><b>Highest</b></label>
                        <input type="number" name="highest" id="highest" style="width: 80px; margin-left: 10px;" min="0" max="1000">
                      </div>
                   </div><br>
                   <!--  </div> -->

                   <div class="btn-group type-options">
                    <button type="button" name="att" id="week"  data-value="1" class="btn btn-primary">7 days/week</button>
                    <button type="button" name="att" id="routine" data-value="3" class="btn btn-primary">Routine</button>
                    </div>
                    <input type="hidden" name="habitSchedule" value="" id="habitSchedule">
                    <!-- <div class="routine hide"> -->
                     <div class="btn-group2 day-options hide"><h2>Please select days:</h2>
                      <input type="checkbox" name="days[]" data-value="0" id="checkbox-1-1" class="regular-checkbox" value="0" /><label for="checkbox-1-1"><span></span></label><span class="no_of_days">Sun</span>
                      <input type="checkbox" name="days[]" data-value="1" id="checkbox-1-2" class="regular-checkbox" value="1"/><label for="checkbox-1-2"><span></span></label><span class="no_of_days">Mon</span>
                   
                    <input type="checkbox" name="days[]" data-value="2" id="checkbox-1-3" class="regular-checkbox" value="2"/><label for="checkbox-1-3" ><span></span></label><span class="no_of_days">Tue</span>
                   
                   <input type="checkbox" name="days[]" data-value="3" id="checkbox-1-4" class="regular-checkbox" value="3"/><label for="checkbox-1-4"><span></span></label><span class="no_of_days">Wed</span>
                   <input type="checkbox" name="days[]" data-value="4" id="checkbox-1-5" class="regular-checkbox" value="4"/><label for="checkbox-1-5"><span></span></label><span class="no_of_days">Thu</span>
                    <input type="checkbox" name="days[]" data-value="5" id="checkbox-1-6" class="regular-checkbox" value="5"/><label for="checkbox-1-6"><span></span></label><span class="no_of_days">Fri</span>
                   
                   <input type="checkbox" name="days[]" data-value="6" id="checkbox-1-7" class="regular-checkbox" value="6" /><label for="checkbox-1-6" ><span></span></label><span class="no_of_days">Sat</span>
                   </div><br>
                   <!--  </div> -->


                    <div class="add_text_type"><h2>Write down your habit loop</h2><textarea col="12" id="add_text_type" name="add_text_type"  class="form-control" style="width: 100%;"></textarea></div><br>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-warning" id="habit-schedule-cancel" style="margin-right:0.5em;">Cancel</buton> <button type="button" class="btn btn-success" id="habit-schedule-save">Save</buton>
                </div>
              </div>
               </form>
            </div>
         </div>


<div id="choose_help" class="modal" role="dialog">
<div class="modal-dialog">  
<div class="modal-content">
<div class="modal-header">Why Choose Scale</div>
<div class="modal-body">
<div style="text-align: justify;">
<p>By choosing scale you are determining the highest and lowest possible value. You will only be able to type in numbers from chosen scale.</p>
<p>Choosing the highest and lowest possible value for a habit allows the software to correctly determine the success of your average shown in your lobby (whether it is red, yellow or green).</p>
<p>If you are not sure of the scale you can press does not apply and the scaling will be automatic and dependant on the numbers entered.</p></div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-success" id="scale-template-cancel" style="margin-right:0.5em;">Cancel</buton>
</div>
</div>
</div>
</div>





<div id="info-light-box" class="modal hide fade lightbox-pop-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form method="post">
<div class="modal-body-wrapper">
<div class="modal-dialog">
<!--  <button type="button" class="close" data-dismiss="modal">&times;</button> -->
<div class="modal-content">
<div class="modal-header"><h3> Task settings </h3></div>
<div class="modal-body">
<div class="template-form" id="template-form">

<div class="repeat-every-section">
<span>Repeat Every:</span>
<input type="text"  id="_repeat_qty" value="1" name="repeat_qty" class="task-sml">
<select name="repeat_frequency" onchange="test();" id="_repeat_frequency">
<option value="weeks">Weeks</option>
<option value="months" selected="selected">Months</option>
<option value="years">Years</option>

</select>
</div><br>

<div class="template-name-section">
<span>Sub Task Name:</span>
<input type="text" name="task_name" id="_task_name" style="width: 71%;">
</div><br>

<div class="repeat-on-section weeks">

<input type="checkbox" value="0" id="weekday-0" class="regular-checkbox weekly_checkbox"  name="week_days[]" /><label for="weekday-0"><span></span></label><span class="no_of_days">Sun</span>


<input type="checkbox" value="1" id="weekday-1" class="regular-checkbox weekly_checkbox"  name="week_days[]" /><label for="weekday-1"><span></span></label><span class="no_of_days">Mon</span>


<input type="checkbox" value="2" id="weekday-2" class="regular-checkbox weekly_checkbox"  name="week_days[]" /><label for="weekday-2"><span></span></label><span class="no_of_days">Tue</span>


<input type="checkbox" value="3" id="weekday-3" class="regular-checkbox weekly_checkbox"  name="week_days[]" /><label for="weekday-3"><span></span></label><span class="no_of_days">Wed</span>


<input type="checkbox" value="4" id="weekday-4" class="regular-checkbox weekly_checkbox"  name="week_days[]" /><label for="weekday-4"><span></span></label><span class="no_of_days">Thu</span>


<input type="checkbox" value="5" id="weekday-5" class="regular-checkbox weekly_checkbox"  name="week_days[]" /><label for="weekday-5"><span></span></label><span class="no_of_days">Fri</span>


<input type="checkbox" value="6" id="weekday-6" class="regular-checkbox weekly_checkbox"  name="week_days[]" /><label for="weekday-6"><span></span></label><span class="no_of_days">Sat</span>       
<!-- <div class="form-inline">
<div class=" form-group">
<input type="checkbox" value="1" id="_add_suffix" class="regular-checkbox" name="add_suffix" />
<label for="_add_suffix"></label><span>Do you want to add name of the <span class="_repeat_on_suffix">Week</span> behind the task name?</span>
</div>
</div> -->
</div>

<div class="repeat-on-section months show">
<label class="radio-inline-"><input type="radio" value="thisday"  name="repeat_on" class="repeat_on_thisday" id="repeat_on_thisday"/></label><span>This day of Each Month</span>
<label class="radio-inline-"><input type="radio" value="firstday"  name="repeat_on" class="repeat_on_firstday" id="repeat_on_firstday" /></label><span>First Day of the month</span>
<label class="radio-inline-"><input type="radio" value="lastday"  name="repeat_on" class="repeat_on_lastday" id="repeat_on_lastday" /></label><span>Last day of the month</span>
<!-- <div class="form-inline">
<div class=" form-group">
<input type="checkbox" value="1" id="_add_suffix" class="regular-checkbox" name="add_suffix" />
<label for="_add_suffix"></label><span>Do you want to add name of the <span class="_repeat_on_suffix">Month</span> behind the task name?</span>
</div>
</div> -->
</div>

<div class="repeat-on-section years">
<label class="radio-inline-"><input type="radio" value="thisday" name="repeat_on" onchange="disableButton();" class="repeat_on_thisday" id="repeat_on_thisday"/></label><span>This day of Each Year</span>
<label class="radio-inline-"><input type="radio" value="firstday" name="repeat_on" onchange="disableButton();" class="repeat_on_firstday" id="repeat_on_firstday" /></label><span>First Day of the Year</span>
<label class="radio-inline-"><input type="radio" value="lastday" name="repeat_on" onchange="disableButton();" class="repeat_on_lastday" id="repeat_on_lastday" /></label><span>Last day of the Year</span>
</div>
<br>
<div class="name-suffix-section">
<input type="checkbox" value="1" id="_add_suffix" class="regular-checkbox" name="add_suffix" checked />
<label for="_add_suffix"></label><span>Do you want to add name of the <span class="_repeat_on_suffix">month</span> behind the task name?</span>
</div>
<br>

<div class="begins-section">
<div class="ends-on-left"><span>Begins: </span></div>
<div class="ends-on-right">

<div><label class="radio-inline-">
<input type="radio" value="now" name="begin_on" id="begin_on_now" checked/> </label> <span>Now</span>
</div>
<div><label class="radio-inline-"><input type="radio" value="date" name="begin_on" id="begin_on_date"/></label>
<span>On</span><label><input type="text" name="begin_on_date" id="begin_on_picker" class="begin_on_date" value="" style="margin-left:4px;"></label></div>
</div>
</div><br>

<div class="ends-section">
<div class="ends-on-left"><span>Ends: </span></div>
<div class="ends-on-right">
<!-- <div><label class="radio-inline-"><input type="radio" value="never" name="ends_on" id="ends_on_never"/></label> <span>Never</span></div> -->
<div><label class="radio-inline-">
<input type="radio" value="date" name="ends_on" id="ends_on_date" checked/> </label> <span>On</span>
<label for="ends_on_date"><input type="text" name="ends_on_date" id="ends_on_picker" class="ends_on_date" ></label>
</div>
<div><label class="radio-inline-"><input type="radio" value="occurrences" name="ends_on" id="ends_on_occurrences"/></label> <span>After</span> <input type="text" name="occurrences" id="occurrences_box" class="task-sml" value="1"> Occurrences</div>
<input type="hidden" value="" name="task_id" id="_task_id">
</div></div><br>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-warning" id="task-template-cancel" style="margin-right:0.5em;">Cancel</buton> <button type="button" class="btn btn-success" id="task-template-save">Done</buton>
</div>
</div>
</div>
</div>
</form>
</div>


<div id="show-lobby" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header"></div>
<div class="modal-body"><div class="btn-group"><button type="button" name="att" class="btn btn-primary lobby-show active" data-id="1" data-rvalue="fa fa-eye">Show in lobby</button><button type="button" name="att" class="btn btn-primary lobby-show" data-id="0" data-rvalue="fa fa-eye-slash">Hide in lobby</button></div></div>
<div class="modal-footer"></div>
</div>
</div>
</div>

<style type="text/css">
  .icheckbox_flat-green {
    margin: 0px auto !important;
    top: 0px !important;
    display: inline-block !important;
}
.no_of_days
{
    display: inline-block;
    margin-right: 10px;
    margin-left: 10px;
}

#select_lobby_undefined option
{
  overflow-wrap: break-word;
  word-wrap: break-word;
  word-break: break-all;
}

</style>
 <script src="{{ URL::asset('/js/add-new-lobby.js') }}"></script>
<script src="{{ URL::asset('/js/moment.js') }}"></script>

        </div>
        <script type="text/javascript">
    $(document).ready(function(){
      $("#scale-template-cancel").click(function(e){
          $("#choose_help").modal('hide');
        });
      });
  </script>
    <?php } ?>
</div>


</div>




</div>

<div id="myTrophyModal" class="modal fade habit-per-graph" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Would you like to move this task to trophy? </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal movetotrophy" id="movetotrophy">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="trophy_name">Name</label>
                        <div class="col-sm-10"><input type="text" class="form-control" id="trophy_name" value=""></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="trophy_date">Date</label>
                        <div class="col-sm-10"><input type="text" class="form-control" id="trophy_date" value="<?php echo date("Y-m-d");?>"></div>
                    </div>
                    <input type="hidden" id="trophy_id" value="">
                </form>
            </div>
            <div class="modal-footer ">
                <p style="text-align:center;">
                    <a class="btn btn-success btnPopupTrophy" href="javascript:void(0)" style="margin-right:20px;">Yes</a> 
                    <a class="btn btn-warning" href="javascript:void(0)" data-dismiss="modal">No</a>
                </p>
            </div>
        </div>

    </div>
</div>

@endsection @section('footer_scripts')
<script src="{{asset('js/dist/Chart.min.js')}}"></script>
<script src="{{asset('js/dist/utils.js')}}"></script>
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    $('#myModal').on('show.bs.modal', function(e) {
        indexyr = 0;
        var rowid = $(e.relatedTarget).data('pid');
        $('.loader-section').show();
        $('.habit-per-graph .modal-body .modal-body-data').html("");
        $.ajax({
            type: 'get',
            url: '{{url("/goals/monthly_statistics/")}}/'+rowid, //Here you will fetch records 
           
            success: function(data) {
                if(data.status==1){
                    $('.loader-section').hide();
                    $('.habit-per-graph .modal-body .modal-body-data').html(data.html);//Show fetched data from database
                    $('#prev_graph').css('opacity','.3');
                    if(total_year_event == 1) {
                        $('#next_graph').css('opacity','.3');
                    } else {
                        $('#next_graph').css('opacity','1');
                    }
                }
                //alert(total_year_event);
            }
        });
    });

    $('#myLinechartModal').on('show.bs.modal', function(e) {
        //$.tloader("show","Loading.....");
        var checkScreen = '';
        checkScreen = $(e.relatedTarget).data('class');
        indexyr = 0;
        var rowid = $(e.relatedTarget).data('pid');
        var habit_date = $(e.relatedTarget).attr('gdate');
        var date = new Date(habit_date);
        var days = date.getDate();
        var month = date.getMonth()+1;
        var year = date.getFullYear();

        console.log("Year",year);
        var new_currentDate =  new Date();
        var new_start_date = new_currentDate.setMonth(new_currentDate.getMonth() - 3);
        new_start_date = new Date(new_start_date);
        console.log("new_start_date",new_start_date);
        console.log("Habit Date",date);
        if(date < new_start_date)
        {
        var new_start_day = new_start_date.getDate();
        var new_start_month = new_start_date.getMonth();
        var new_start_year = new_start_date.getFullYear();
        var new_calc_day = getDaysInMonth(new_start_month, new_start_year,0);

      }else{
        var new_start_day = date.getDate();
        var new_start_month = date.getMonth();
        var new_start_year = date.getFullYear();
        var new_calc_day = getDaysInMonth(month, year,0);
      }

      if(parseInt(year) < parseInt(new_start_year)){
        month = 1;
      }
        
        var currentDate =  new Date();
        console.log("currentDate",currentDate);
        var diffDays = date_diff_indays(date,currentDate);
        var currentDay = currentDate.getDate();
        var currentM = currentDate.getMonth();
        var currentY = currentDate.getFullYear();
        var max_date=currentDate.addDays(365);
        var lastYear = max_date.getFullYear();
        var lastMonth   = max_date.getMonth();
        var lastDate    = max_date.getDate();
        var caldays = getDaysInMonth(month, year,0);
        var endMonthcaldays = getDaysInMonth(currentM, currentY,0);
        
        if(diffDays > 20)
        {
          var mobileDate = currentDate.setDate(currentDate.getDate() - 20);
        }
        else
        {
            var mobileDate = currentDate.setDate(currentDate.getDate() - diffDays);
        }
        

        mobileDate = new Date(mobileDate);

        var mobileDay = mobileDate.getDate();
        var mobileM   = mobileDate.getMonth();
        var mobileY   = mobileDate.getFullYear();
        
        var header_html = '<div class="row"><div class="col-sm-4 col-sm-push-8"><select class="graph-variation-selection form-control"><option value="days">Days</option><option value="weeks">Weeks</option><option value="month">Month</option><option value="years">Years</option></select></div></div>';
        $(".modal-header .graph").html(header_html);

        var html = '<div class="days-option">';
        if(checkScreen == "isDesktop")
        {

        html  += '<div class="startDate-options row hide-on-mobile">'
        html += '<div class="col-sm-4 col-xs-12 form-group startday-column">';
        html += '<select class="startDays startday form-control">';
        
        for(var startDay = 0; startDay<=new_calc_day;startDay++)
           {

           startDay = (startDay <=9)?"0"+startDay:startDay;
           var select = (startDay == days)?'selected':'';

            html += '<option value="'+startDay+'" '+select+'>'+startDay+'</option>';
           }
        
        html += '</select>'; 
        html += '</div>';
        html += '<div class="col-sm-4 col-xs-12 form-group startmonth-column">';
        html += '<select class="startMonth startmonth form-control">';
        for(var startMonth = month-1; startMonth<12;startMonth++)
           {
            var monthName = GetMonthName(startMonth);
            (startMonth <=9)?"0"+startMonth:startMonth;
            var select = (startMonth == new_start_month)?'selected':'';
            html += '<option value="'+startMonth+'" '+select+'>'+monthName+'</option>';
           }
        html += '</select>';
        html += '</div>';
        html += '<div class="col-sm-4 col-xs-12 form-group startyear-column">';
        html += '<select class="startYear startyear form-control">';
        for(var startYear = year; startYear <= lastYear;startYear++)
           {
            var select = (startYear == new_start_year)?'selected':'';

            html += '<option value="'+startYear+'" '+select+'>'+startYear+'</option>';
           }
        
        html += '</select>';
        html += '</div>';
        html += '</div>';
        }

        if(checkScreen == 'isMobile')
        {
             html  += '<div class="startDate-options row hide-on-desktop">'
        html += '<div class="col-sm-4 col-xs-12 form-group startday-column">';
        html += '<select class="startDays startday form-control">';
        for(var startDay = 1; startDay<=caldays;startDay++)
           {
            (startDay <=9)?"0"+startDay:startDay;
            var select = (startDay == mobileDay)?"selected":"";
            html += '<option value="'+startDay+'" '+select+'>'+startDay+'</option>';
           }
        
        html += '</select>'; 
        html += '</div>';
        html += '<div class="col-sm-4 col-xs-12 form-group startmonth-column">';
        html += '<select class="startMonth startmonth form-control">';
        for(var startMonth = month-1; startMonth<12;startMonth++)
           {
            var monthName = GetMonthName(startMonth);
            var select =  (startMonth == mobileM)?"selected":"";
            (startMonth <=9)?"0"+startMonth:startMonth;

            html += '<option value="'+startMonth+'" '+select+'>'+monthName+'</option>';
           }
        html += '</select>';
        html += '</div>';
        html += '<div class="col-sm-4 col-xs-12 form-group startyear-column">';
        html += '<select class="startYear startyear form-control">';
        for(var startYear = year; startYear <= lastYear;startYear++)
           {
            var select =  (startYear == mobileY)?"selected":"";
            html += '<option value="'+startYear+'" '+select+'>'+startYear+'</option>';
           }
        
        html += '</select>';
        html += '</div>';
        html += '</div>';
        }

        html += '<div class="endDate-options row">';
        html += '<div class="col-sm-4 col-xs-12 form-group startday-column">';
        html += '<select class="startDays endday form-control">';
        for(var startDay = 1; startDay<=endMonthcaldays;startDay++)
           {
            (startDay <=9)?"0"+startDay:startDay;
            var select = (startDay == currentDay)?'selected':'';
            html += '<option value="'+startDay+'" '+select+'>'+startDay+'</option>';
           }
        
        html += '</select>'; 
        html += '</div>';
        html += '<div class="col-sm-4 col-xs-12 form-group startmonth-column">';
        html += '<select class="startMonth endmonth form-control">';
        //currentM = parseInt(currentM)+1;
        //console.log(currentM);
        for(var startMonth = 0; startMonth<12;startMonth++)
           {
            var monthName = GetMonthName(startMonth);
            (startMonth <=9)?"0"+startMonth:startMonth;

            var select = (startMonth == parseInt(currentM))?'selected':'';
            html += '<option value="'+startMonth+'" '+select+'>'+monthName+'</option>';
           }

        html += '</select>';
        html += '</div>';
        html += '<div class="col-sm-4 col-xs-12 form-group startyear-column">';
        html += '<select class="startYear endyear form-control">';
        for(var startYear = year; startYear <= lastYear;startYear++)
           {

            var select = (startYear == currentY)?'selected':'';
            html += '<option value="'+startYear+'" '+select+'>'+startYear+'</option>';
           }
        
        html += '</select>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $('.habit-per-linegraph .modal-body .modal-body-selection-data').html("");
        $('.habit-per-linegraph .modal-body .modal-body-selection-data').html(html);
        if(checkScreen == "isDesktop")
        {
            var graph_html = '<div class="row"><div class="col-sm-12"><canvas id="canvas" class="desktop-graph"></canvas></div></div>';
        }
        else
        {
            var graph_html = '<div class="row"><div class="col-sm-12"><canvas id="canvas" class="mobile-graph"></canvas></div></div>';
        }
        $('.habit-per-linegraph .modal-body .modal-body-data').html("");
        $('.habit-per-linegraph .modal-body .modal-body-data').html(graph_html);

        var type = $(".graph-variation-selection").val();
        var selectstartday = $(".startday").val();
        (selectstartday >9)?selectstartday:"0"+selectstartday;
        var selectstartmonth = $(".startmonth").val();
        selectstartmonth = parseInt(selectstartmonth)+1;
        (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
        var startyear = $(".startyear").val();
        
        var endday = $(".endday").val();
        (endday <=9)?"0"+endday:endday;
        var endmonth = $(".endmonth").val();
        endmonth = parseInt(endmonth)+1;
        (endmonth <=9)?"0"+endmonth:endmonth;
        var endyear = $(".endyear").val();
        var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
        var end_date = endyear+"-"+endmonth+"-"+endday;
        makeLineDayChart(rowid,habit_date,start_date,end_date,type,checkScreen);
        changeDays(rowid,habit_date,checkScreen);
        changeStartYear(rowid,habit_date,checkScreen);
        changeStartMonth(rowid,habit_date,checkScreen);
        changeGraph(rowid,habit_date,checkScreen);
        changeEndYear(rowid,habit_date,checkScreen);
        changeEndMonth(rowid,habit_date,checkScreen);
        
    });

});


var changeDays = function(habit_id,habit_date,screen)
{

    $(".startDays").unbind("change").bind("change",function(){
        var type = $(".graph-variation-selection").val();
        var selectstartday = $(".startday").val();
        (selectstartday >9)?selectstartday:"0"+selectstartday;
        var selectstartmonth = $(".startmonth").val();
        selectstartmonth = parseInt(selectstartmonth)+1;
        (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
        var startyear = $(".startyear").val();
        
        var endday = $(".endday").val();
        (endday <=9)?"0"+endday:endday;
        var endmonth = $(".endmonth").val();
        endmonth = parseInt(endmonth)+1;
        (endmonth <=9)?"0"+endmonth:endmonth;
        var endyear = $(".endyear").val();

        var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
        var end_date = endyear+"-"+endmonth+"-"+endday;
        makeLineDayChart(habit_id,habit_date,start_date,end_date,type,screen);

    });
}

var changeStartYear = function(habit_id,habit_date,screen)
{
    $(".startYear").unbind("change").bind("change",function(){
       var year = $(this).val();
       var type = $(".graph-variation-selection").val();
       var selectMonth = $(this).parent().parent().find(".startMonth").val();
       var calcSelectMonth = getDaysInMonth(selectMonth, year,0);
       var date = new Date(habit_date);
       var habitday = date.getDate();
       var habitMonth = date.getMonth()+1; 
       var habitYear = date.getFullYear();
       var currentDate = new Date();
       var currentDay = currentDate.getDate();
       var currentMonth = currentDate.getMonth();
       var currentYear = currentDate.getFullYear();
       var caldays = getDaysInMonth(habitMonth, habitYear,0);
       var lastDate = currentDate.addDays(365);
       var lastMonth = lastDate.getMonth();
       var lastYear = lastDate.getFullYear();
       var caldays = getDaysInMonth(habitMonth, habitYear,0);
       var htmlMonth;
       var htmldays;
       if(year == habitYear)
       {
        
            for(var startDay = habitday; startDay <= caldays;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                 htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }
            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
             for(var startMonth = habitMonth-1; startMonth <12;startMonth++)
            {
                var monthName = GetMonthName(startMonth);
                (startMonth <=9)?"0"+startMonth:startMonth;
                htmlMonth += '<option value="'+startMonth+'">'+monthName+'</option>'
            }
            $(this).parent().parent().find(".startMonth").html("");
            $(this).parent().parent().find(".startMonth").html(htmlMonth);
       }
       else if(year == currentYear)
       {

        var end_month = $(".startmonth").val();
        var caldays = getDaysInMonth(end_month, year,0);

        for(var startDay = 1; startDay <= caldays;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }
            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
             for(var startMonth = 0; startMonth <12;startMonth++)
            {
                var monthName = GetMonthName(startMonth);
                (startMonth <=9)?"0"+startMonth:startMonth;
                htmlMonth += '<option value="'+startMonth+'">'+monthName+'</option>'
            }

            $(this).parent().parent().find(".startMonth").html("");
            $(this).parent().parent().find(".startMonth").html(htmlMonth);
       }
       else if(year == lastYear)
       {

        var end_month = $(".startmonth").val();
        var caldays = getDaysInMonth(end_month, year,0);

        for(var startDay = 1; startDay <= currentDay;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }

            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
             for(var startMonth = 0; startMonth <=lastMonth;startMonth++)
            {
                var monthName = GetMonthName(startMonth);
                (startMonth <=9)?"0"+startMonth:startMonth;
                htmlMonth += '<option value="'+startMonth+'">'+monthName+'</option>'
            }
            $(this).parent().parent().find(".startMonth").html("");
            $(this).parent().parent().find(".startMonth").html(htmlMonth);
       }
       else
       {
            for(var startDay = 1; startDay <= caldays;startDay++)
            {

                (startDay <=9)?"0"+startDay:startDay;
                htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }

            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
             for(var startMonth = 0; startMonth <12;startMonth++)
            {
                var monthName = GetMonthName(startMonth);
                (startMonth <=9)?"0"+startMonth:startMonth;
                 htmlMonth += '<option value="'+startMonth+'">'+monthName+'</option>'
            }

            $(this).parent().parent().find(".startMonth").html("");
            $(this).parent().parent().find(".startMonth").html(htmlMonth);
       }
            $("#canvas").empty();
            if(type == "days")
            {
                var selectstartday = $(".startday").val();
                (selectstartday >9)?selectstartday:"0"+selectstartday;
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endday = $(".endday").val();
                (endday <=9)?"0"+endday:endday;
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
                var end_date = endyear+"-"+endmonth+"-"+endday;
            }
            else if(type == "weeks")
            {
                var selectstartday = $(".startday").val();
                (selectstartday >9)?selectstartday:"0"+selectstartday;
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endday = $(".endday").val();
                (endday <=9)?"0"+endday:endday;
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
                var end_date = endyear+"-"+endmonth+"-"+endday;
            }
            else if(type == "month")
            {
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth;
                var end_date = endyear+"-"+endmonth;
               // makeLineMonthChart(habit_id,habit_date,start_date,end_date,type);
            }
            else
            {
                var startyear = $(".startyear").val(); 
                var endyear = $(".endyear").val();
                var start_date = startyear;
                var end_date = endyear;
                //makeLineYearChart(habit_id,habit_date,start_date,end_date,type);
            }
                makeLineDayChart(habit_id,habit_date,start_date,end_date,type,screen);
    });
}




var changeEndYear = function(habit_id,habit_date,screen)
{
    $(".endyear").unbind("change").bind("change",function(){
       var year = $(this).val();
       var type = $(".graph-variation-selection").val();
       var selectMonth = $(this).parent().parent().find(".endyear").val();
       var calcSelectMonth = getDaysInMonth(selectMonth, year,0);
       var date = new Date(habit_date);
       var habitday = date.getDate();
       var habitMonth = date.getMonth()+1; 
       var habitYear = date.getFullYear();
       var currentDate = new Date();
       var currentDay = currentDate.getDate();
       var currentMonth = currentDate.getMonth();
       var currentYear = currentDate.getFullYear();
       var caldays = getDaysInMonth(habitMonth, habitYear,0);
       var lastDate = currentDate.addDays(365);
       var lastMonth = lastDate.getMonth();
       var lastYear = lastDate.getFullYear();
       

       var htmlMonth;
       var htmldays;
       if(year == habitYear)
       {
            var caldays = getDaysInMonth(habitMonth, habitYear,0);
            for(var startDay = habitday; startDay <= caldays;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                 htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }
            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
             for(var startMonth = habitMonth-1; startMonth <12;startMonth++)
            {
                var monthName = GetMonthName(startMonth);
                (startMonth <=9)?"0"+startMonth:startMonth;
                htmlMonth += '<option value="'+startMonth+'">'+monthName+'</option>'
            }
            $(this).parent().parent().find(".startMonth").html("");
            $(this).parent().parent().find(".startMonth").html(htmlMonth);
       }
       else if(year == currentYear)
       {

        var end_month = $(".endmonth").val();
        var caldays = getDaysInMonth(end_month, year,0);

        for(var startDay = 1; startDay <= caldays;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }
            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
             for(var startMonth = 0; startMonth <12;startMonth++)
            {
                var monthName = GetMonthName(startMonth);
                (startMonth <=9)?"0"+startMonth:startMonth;
                htmlMonth += '<option value="'+startMonth+'">'+monthName+'</option>'
            }

            $(this).parent().parent().find(".startMonth").html("");
            $(this).parent().parent().find(".startMonth").html(htmlMonth);
       }
       else if(year == lastYear)
       {

        var end_month = $(".endmonth").val();
        var caldays = getDaysInMonth(end_month, year,0);

        for(var startDay = 1; startDay <= currentDay;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }

            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
             for(var startMonth = 0; startMonth <=lastMonth;startMonth++)
            {
                var monthName = GetMonthName(startMonth);
                (startMonth <=9)?"0"+startMonth:startMonth;
                htmlMonth += '<option value="'+startMonth+'">'+monthName+'</option>'
            }
            $(this).parent().parent().find(".startMonth").html("");
            $(this).parent().parent().find(".startMonth").html(htmlMonth);
       }
       else
       {
            for(var startDay = 1; startDay <= caldays;startDay++)
            {

                (startDay <=9)?"0"+startDay:startDay;
                htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }

            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
             for(var startMonth = 0; startMonth <12;startMonth++)
            {
                var monthName = GetMonthName(startMonth);
                (startMonth <=9)?"0"+startMonth:startMonth;
                 htmlMonth += '<option value="'+startMonth+'">'+monthName+'</option>'
            }

            $(this).parent().parent().find(".startMonth").html("");
            $(this).parent().parent().find(".startMonth").html(htmlMonth);
       }
            $("#canvas").empty();
            if(type == "days")
            {
                var selectstartday = $(".startday").val();
                (selectstartday >9)?selectstartday:"0"+selectstartday;
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endday = $(".endday").val();
                (endday <=9)?"0"+endday:endday;
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
                var end_date = endyear+"-"+endmonth+"-"+endday;
            }
            else if(type == "weeks")
            {
                var selectstartday = $(".startday").val();
                (selectstartday >9)?selectstartday:"0"+selectstartday;
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endday = $(".endday").val();
                (endday <=9)?"0"+endday:endday;
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
                var end_date = endyear+"-"+endmonth+"-"+endday;
            }
            else if(type == "month")
            {
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth;
                var end_date = endyear+"-"+endmonth;
               // makeLineMonthChart(habit_id,habit_date,start_date,end_date,type);
            }
            else
            {
                var startyear = $(".startyear").val(); 
                var endyear = $(".endyear").val();
                var start_date = startyear;
                var end_date = endyear;
                //makeLineYearChart(habit_id,habit_date,start_date,end_date,type);
            }
                makeLineDayChart(habit_id,habit_date,start_date,end_date,type,screen);
    });
}



var changeStartMonth = function(habit_id,habit_date,screen)
{
   
    $(".startMonth").unbind("change").bind("change",function(){
       var type = $(".graph-variation-selection").val();
       var month = $(this).val();
       month = parseInt(month)+1;
       var selectYear = $(".startYear").val();
       var calcSelectMonth = getDaysInMonth(month, selectYear,0);
       var date = new Date(habit_date);
       var habitday = date.getDate();
       var habitMonth = date.getMonth();
       habitMonth = parseInt(habitMonth)+1;
       var habitYear = date.getFullYear();
       var currentDate = new Date();
       var currentDay = currentDate.getDate();
       var currentMonth = currentDate.getMonth();
       var currentYear = currentDate.getFullYear();
       var caldays = getDaysInMonth(habitMonth, habitYear,0);
       var lastDate = currentDate.addDays(365);
       var lastMonth = lastDate.getMonth();
       var lastYear = lastDate.getFullYear();
       var caldays = getDaysInMonth(habitMonth, habitYear,0);
       var htmlMonth;
       var htmldays;
       if(month == habitMonth && selectYear == habitYear)
       {
            
            for(var startDay = habitday; startDay <= calcSelectMonth;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                 htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }

            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
       }
       else 
       {
        for(var startDay = 1; startDay <= calcSelectMonth;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }

            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
       }
              $("#canvas").empty();
              if(type == "days")
            {
                var selectstartday = $(".startday").val();
                (selectstartday >9)?selectstartday:"0"+selectstartday;
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endday = $(".endday").val();
                (endday <=9)?"0"+endday:endday;
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
                var end_date = endyear+"-"+endmonth+"-"+endday;
            }
            else if(type == "weeks")
            {
                var selectstartday = $(".startday").val();
                (selectstartday >9)?selectstartday:"0"+selectstartday;
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endday = $(".endday").val();
                (endday <=9)?"0"+endday:endday;
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
                var end_date = endyear+"-"+endmonth+"-"+endday;
            }
            else if(type == "month")
            {
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth;
                var end_date = endyear+"-"+endmonth;
                //makeLineMonthChart(habit_id,habit_date,start_date,end_date,type);
            }
            else
            {
                var startyear = $(".startyear").val(); 
                var endyear = $(".endyear").val();
                var start_date = startyear;
                var end_date = endyear;
                //makeLineYearChart(habit_id,habit_date,start_date,end_date,type);
            }

                    makeLineDayChart(habit_id,habit_date,start_date,end_date,type,screen);
    });
  
}


var changeEndMonth = function(habit_id,habit_date,screen)
{
   
    $(".endmonth ").unbind("change").bind("change",function(){
       var type = $(".graph-variation-selection").val();
       var month = $(this).val();
       month = parseInt(month)+1;
       var selectYear = $(".endyear").val();
       var calcSelectMonth = getDaysInMonth(month, selectYear,0);
       var date = new Date(habit_date);
       var habitday = date.getDate();
       var habitMonth = date.getMonth();
       habitMonth = parseInt(habitMonth)+1;
       var habitYear = date.getFullYear();
       var currentDate = new Date();
       var currentDay = currentDate.getDate();
       var currentMonth = currentDate.getMonth();
       var currentYear = currentDate.getFullYear();
       var caldays = getDaysInMonth(habitMonth, habitYear,0);
       var lastDate = currentDate.addDays(365);
       var lastMonth = lastDate.getMonth();
       var lastYear = lastDate.getFullYear();
       var caldays = getDaysInMonth(habitMonth, habitYear,0);
       var htmlMonth;
       var htmldays;
       if(month == habitMonth && selectYear == habitYear)
       {
            
            for(var startDay = habitday; startDay <= calcSelectMonth;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                 htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }

            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
       }
       else 
       {
        for(var startDay = 1; startDay <= calcSelectMonth;startDay++)
            {
                (startDay <=9)?"0"+startDay:startDay;
                htmldays += '<option value="'+startDay+'">'+startDay+'</option>'
            }

            $(this).parent().parent().find(".startDays").html("");
            $(this).parent().parent().find(".startDays").html(htmldays);
       }
              $("#canvas").empty();
              if(type == "days")
            {
                var selectstartday = $(".startday").val();
                (selectstartday >9)?selectstartday:"0"+selectstartday;
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endday = $(".endday").val();
                (endday <=9)?"0"+endday:endday;
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
                var end_date = endyear+"-"+endmonth+"-"+endday;
            }
            else if(type == "weeks")
            {
                var selectstartday = $(".startday").val();
                (selectstartday >9)?selectstartday:"0"+selectstartday;
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endday = $(".endday").val();
                (endday <=9)?"0"+endday:endday;
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
                var end_date = endyear+"-"+endmonth+"-"+endday;
            }
            else if(type == "month")
            {
                var selectstartmonth = $(".startmonth").val();
                selectstartmonth = parseInt(selectstartmonth)+1;
                (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
                var startyear = $(".startyear").val(); 
                var endmonth = $(".endmonth").val();
                endmonth = parseInt(endmonth)+1;
                (endmonth <=9)?"0"+endmonth:endmonth;
                var endyear = $(".endyear").val();
                var start_date = startyear+"-"+selectstartmonth;
                var end_date = endyear+"-"+endmonth;
                //makeLineMonthChart(habit_id,habit_date,start_date,end_date,type);
            }
            else
            {
                var startyear = $(".startyear").val(); 
                var endyear = $(".endyear").val();
                var start_date = startyear;
                var end_date = endyear;
                //makeLineYearChart(habit_id,habit_date,start_date,end_date,type);
            }

             makeLineDayChart(habit_id,habit_date,start_date,end_date,type,screen);
    });
  
}


var changeGraph = function(habit_id,habit_date,screen)
{
   //makeLineDayChart(habit_id,habit_date,start_date,end_date,type);
    $(".graph-variation-selection").unbind("change").bind("change",function(){
        var type = $(this).val();
        $("#canvas").empty();
        if(type == 'days')
        {
            $(".startday-column").removeClass("hide");
            $(".startday-column").addClass("show");
            $(".startmonth-column").removeClass("hide");
            $(".startmonth-column").addClass("show");
            var selectstartday = $(".startday").val();
            (selectstartday >9)?selectstartday:"0"+selectstartday;
            var selectstartmonth = $(".startmonth").val();
            selectstartmonth = parseInt(selectstartmonth)+1;
            (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
            var startyear = $(".startyear").val(); 
            var endday = $(".endday").val();
            (endday <=9)?"0"+endday:endday;
            var endmonth = $(".endmonth").val();
            endmonth = parseInt(endmonth)+1;
            (endmonth <=9)?"0"+endmonth:endmonth;
            var endyear = $(".endyear").val();
            var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
            var end_date = endyear+"-"+endmonth+"-"+endday;
        }
        else if(type == 'weeks')
        {
            $(".startday-column").removeClass("hide");
            $(".startday-column").addClass("show");
            $(".startmonth-column").removeClass("hide");
            $(".startmonth-column").addClass("show");
            var selectstartday = $(".startday").val();
            (selectstartday >9)?selectstartday:"0"+selectstartday;
            var selectstartmonth = $(".startmonth").val();
            selectstartmonth = parseInt(selectstartmonth)+1;
            (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
            var startyear = $(".startyear").val(); 
            var endday = $(".endday").val();
            (endday <=9)?"0"+endday:endday;
            var endmonth = $(".endmonth").val();
            endmonth = parseInt(endmonth)+1;
            (endmonth <=9)?"0"+endmonth:endmonth;
            var endyear = $(".endyear").val();
            var start_date = startyear+"-"+selectstartmonth+"-"+selectstartday;
            var end_date = endyear+"-"+endmonth+"-"+endday;
        }
        else if(type == 'month')
        {
            $(".startday-column").removeClass("show");
            $(".startday-column").addClass("hide");
            $(".startmonth-column").removeClass("hide");
            $(".startmonth-column").addClass("show");
            var selectstartmonth = $(".startmonth").val();
            selectstartmonth = parseInt(selectstartmonth)+1;
            (selectstartmonth > 9)?selectstartmonth:"0"+selectstartmonth;
            var startyear = $(".startyear").val(); 
            var endmonth = $(".endmonth").val();
            endmonth = parseInt(endmonth)+1;
            (endmonth <=9)?"0"+endmonth:endmonth;
            var endyear = $(".endyear").val();
            var start_date = startyear+"-"+selectstartmonth;
            var end_date = endyear+"-"+endmonth;
            //makeLineMonthChart(habit_id,habit_date,start_month,end_month,type);
        }
        else
        {
            $(".startday-column").removeClass("show");
            $(".startday-column").addClass("hide");
            $(".startmonth-column").removeClass("show");
            $(".startmonth-column").addClass("hide");
            var start_date = $(".startyear").val(); 
            var end_date = $(".endyear").val();
            //makeLineYearChart(habit_id,habit_date,startyear,endyear,type);
        }

        makeLineDayChart(habit_id,habit_date,start_date,end_date,type,screen);
    });
}

function GetMonthName(monthNumber) {
      var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      return months[monthNumber];
}

var makeLineDayChart = function(habit_id,habit_date,start_date,end_date,type,screen){
        $.tloader("show","Loading.....");
       $.ajax({
        url:"{{url('/log/dayAverage')}}",
        method:"post",
        data:{habit_id:habit_id,start_date:start_date,end_date:end_date,habit_date:habit_date,type:type},
        success:function(response)
        {
            //console.log(response);
            var ctx = document.getElementById('canvas').getContext('2d');
            if(screen == "isMobile")
            {
              $("#canvas").attr('height','');
              $("#canvas").attr('height',300);
              //ctx.height = 500;
            }
            //window.myLineChart = new Chart(ctx,config);
            if(window.myLineChart != undefined)
            {
            window.myLineChart.destroy();
            }
            
            if(response.data.xAxes == "Days")
            {
             var config = {
            type: 'line',
            data: {
                labels: response.data.labels,

                datasets: [{
                    label: 'Total',
                    backgroundColor: "#df2e12",
                    borderColor: "#df2e12",
                    data: response.data.datasets,
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Days Graph',
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: response.data.xAxes
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: response.data.yAxes
                        }
                    }]
                }
            }
        };
            }

            
            if(response.data.xAxes == "weekly")
            {
                var config = {
            type: 'line',
            data: {
                labels: response.data.labels,
                datasets: [
                {
                    label: 'Weekly Total',
                    backgroundColor: "#df2e12",
                    borderColor: "#df2e12",
                    data: response.data.weekly_tot,
                    fill: false,
                },{
                    label: 'Weekly Average',
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: response.data.weekly_aver,
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Weekly Graph',
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: response.data.xAxes
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: response.data.yAxes
                        }
                    }]
                }
            }
        };

            }


         if(response.data.xAxes == "Month")
            {
                var config = {
            type: 'line',
            data: {
                labels: response.data.labels,
                datasets: [
                {
                    label: 'Monthly Total',
                    backgroundColor: "#df2e12",
                    borderColor: "#df2e12",
                    data: response.data.monthly_tot,
                    fill: false,
                },{
                    label: 'Monthly Average',
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: response.data.monthly_aver,
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Month Graph',
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: response.data.xAxes
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: response.data.yAxes
                        }
                    }]
                }
            }
        };

            }


            if(response.data.xAxes == "Years")
            {

            var config = {
            type: 'line',
            data: {
                labels: response.data.labels,
                datasets: [{
                    label: 'Yearly Total',
                    backgroundColor: "#df2e12",
                    borderColor: "#df2e12",
                    data: response.data.yearly_tot,
                    fill: false,
                },{
                    label: 'Yearly Average',
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: response.data.yearly_aver,
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Yearly Graph',
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: false
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: response.data.xAxes
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: response.data.yAxes
                        }
                    }]
                }
            }
        };

            }
        window.myLineChart = new Chart(ctx, config);

        $.tloader("hide");
        }

       });
  }

  var getDaysInMonth = function(month,year,days) {
  // Here January is 1 based
  //Day 0 is the last day in the previous month
 return new Date(year, month, days).getDate();
// Here January is 0 based
// return new Date(year, month+1, 0).getDate();
};

var date_diff_indays = function(date1, date2) {
dt1 = new Date(date1);
dt2 = new Date(date2);
return Math.floor((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate()) ) /(1000 * 60 * 60 * 24));
}


    $(function(){
        /*var habits_list=$('ul.habit-list').nestedSortable({
            handle: 'div',
            items: 'li.main-li',
            listType: 'ul',
            opacity: .6,
            stop: function(event, ui) { 
                $.tloader("show","Hang On...");
                var data = habits_list.nestedSortable("serialize");
                $.post("{{url('goals/self_order')}}", {data:data}, function (response) {
                    //console.log(response)
                    if (response.status == 0)
                    {

                    }
                    $.tloader("hide");
                });
            }
        });

        var character_list=$('ul.character-list').nestedSortable({
            handle: 'div',
            items: 'li.main-li',
            listType: 'ul',
            opacity: .6,
            stop: function(event, ui) {
                $.tloader("show","Hang On...");
                var data = character_list.nestedSortable("serialize");
                $.post("{{url('goals/self_order')}}", {data:data}, function (response) {
                   
                    if (response.status == 0)
                    {

                    }

                    $.tloader("hide");
                });

                //console.log('new parent ' + ui.item.closest('ul').closest('li').attr('name'));
            }
        });
        */
        /*
        $("ul.habit-list" ).sortable({

            stop: function(event, ui) {

            }

        });
        $( "ul.habit-list" ).disableSelection();*/

       /*
       var task_list=$('ul.task-list').nestedSortable({
            handle: 'div',
            items: '>li',
            listType: 'ul',
            opacity: .6,
            stop: function(event, ui) {
                $.tloader("show","Hang On...");
                var data = task_list.nestedSortable("serialize");
                $.post("{{url('goals/self_order')}}", {data:data}, function (response) {
                   
                    if (response.status == 0)
                    {

                    }

                    $.tloader("hide");
                });

                //console.log('new parent ' + ui.item.closest('ul').closest('li').attr('name'));
            }
        });
       
        lmdd.set(document.getElementById('task-mobile'), {
            draggableItemClass: 'task-item'
        });*/

        /*var sortable = $('task-mobile');

        dragula([document.getElementById(sortable)], {
          removeOnSpill: true
        });*/
     
    });
    
</script>
<script type="text/javascript">
  $(document).ready(function(){
    var habitData = [];

        $(".name-min-height a").each(function( index ) {
          var habit_title = $(this).html();
              habitData.push(habit_title);

        });

    $(window).resize(function() 
        { 
          var wid = $(window).width();
          var checkMobile = $(".name-min-height").hasClass("isDesktop");
        if(checkMobile)
        {
          
          if(wid < 775)
          {
            
            $(".name-min-height a").each(function( index ) {
            var habit_title = $(this).html();
            var length = habit_title.length;

            var subStr = habit_title.substring(0, 1);
              
               subStr  = subStr+"...";
              if(length > 4)
              {
                $(this).html("");
                $(this).html(subStr);

              }
        });
          }
          else if(wid < 790)
          {
              
              $(".name-min-height a").each(function(key) {
               var habit_title = "";
               habit_title = habitData[key];
               length = habit_title.length;

               var subStr = habit_title.substring(0, 5);
              
               subStr  = subStr+"...";
              if(length > 5)
              {
                $(this).html("");
                $(this).html(subStr);

              }
            });
              
          }
          else if(wid < 900)
          {
            
            $(".name-min-height a").each(function(key) {
               var habit_title = "";
               habit_title = habitData[key];
               length = habit_title.length;

               var subStr = habit_title.substring(0, 8);
              
               subStr  = subStr+"...";
              if(length > 8)
              {
                $(this).html("");
                $(this).html(subStr);

              }
            });            
          }
          else if(wid < 1110)
          {
            
            $(".name-min-height a").each(function(key) {
               var habit_title = "";
               habit_title = habitData[key];
               length = habit_title.length;

               var subStr = habit_title.substring(0, 15);
              
               subStr  = subStr+"...";
              if(length > 15)
              {
                $(this).html("");
                $(this).html(subStr);

              }
            });
            
          }
          else if(wid < 1490)
          {

            $(".name-min-height a").each(function(key) {
               var habit_title = "";
               habit_title = habitData[key];
               length = habit_title.length;

               var subStr = habit_title.substring(0, 50);
              
               subStr  = subStr+"...";
              if(length > 50)
              {
                $(this).html("");
                $(this).html(subStr);

              }
            });
          }
          else
          {
  
            $(".name-min-height a").each(function(key) {
               var habit_title = "";
               habit_title = habitData[key];
               
               $(this).html("");
               $(this).html(habit_title);

        });
      }   
     }

    }).resize();
  });
</script>
<script src="{{ URL::asset('js/dash.js') }}"></script>
<!--TODO: Corrupts checkboxes, remove this-->
<!--<script src="./js/home.js"></script>-->

<script type="text/javascript">
$(document).ready(function(){
  var type = "tree";
  task_resize(type);
  mobile_task_resize(type);
});

 var task_resize =  function(type)
 {
  var desktopTaskData = [];
    var li_type = "";
    var ul_type = "";
    if(type == "list")
    {
      ul_type = ".task-list-list";
      li_type = ".task-desk-list";
        $(""+ul_type+" "+li_type+"").each(function( index ) {
          var task_desk_title = $(this).find(".gd-heading .task-link-title").attr("title");
              desktopTaskData.push(task_desk_title);
        });
        
    }
    else if(type == "leaf")
    {
      ul_type = ".task-list-leaf";
      li_type = ".task-desk-leaf";
        $(""+ul_type+" "+li_type+"").each(function( index ) {
          var task_desk_title = $(this).find(".gd-heading .task-link-title").attr("title");
              desktopTaskData.push(task_desk_title);
        });
        
    }
    else
    {
      ul_type = ".task-list-tree";
      li_type = ".task-desk-tree";
        $(""+ul_type+" "+li_type+"").each(function( index ) {
          var task_desk_title = $(this).find(".gd-heading .task-link-title").attr("title");
              desktopTaskData.push(task_desk_title);
        });
    }

    $(window).resize(function() 
        { 
          var width = $(window).width();
          if(width < 775)
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              
              var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 5);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 10);
              }

              if((length > 5 && checkArrow) || length > 10)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }

                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
        });
          }
          else if(width < 825 && width > 775)
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;

             // var subStr = task_desk_title.substring(0, 5);
              var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 5);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 20);
              }

              if((length > 15 && checkArrow) || length > 20)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }

                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
        });
          }
          else if(width < 890 && width > 825)
          {
            $(ul_type+" "+li_type).each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 8);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 5);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 30);
              }

              if((length > 5 && checkArrow) || length > 30)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
          });
          }
          else if(width < 1100 && width > 890)
          {
              $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 15);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 10);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 40);
              }
              if((length > 10 && checkArrow) || length > 60)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });
              
          }
          else if(width < 1270 && width > 1100)
          {
          
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 20);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 20);

              }
              else
              {
                var subStr = task_desk_title.substring(0, 50);
              }

              if((length > 20 && checkArrow) || length > 60)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });     
          }
          else if(width < 1410 && width > 1270)
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 30);

              var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 40);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 70);
              }
              if(length > 90)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });
            
          }
          else if(width < 1490 && width > 1410)
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 40);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 50);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 90);
              }
              if(length > 90)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });
          }
          else
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 40);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 50);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 90);
              }
              if(length > 90)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });
          }

    }).resize();
  }
</script>

<script type="text/javascript">
  var mobile_task_resize = function(type)
  {

     var li_type = " ";
     if(type == "leaf")
     {
      li_type = "#leaf-mobile";
     }
     else if(type == "list")
     {
      li_type = "#list-mobile";
     }
     else
     {
      li_type = "#tree-mobile";
     }

    $(window).resize(function() 
        { 
          var wid = $(window).width();
          var taskData = [];
          var subStr="";
        $(""+li_type+" .gd-heading a").each(function(index ) {
          var task_title = $(this).attr("data-title");
              taskData.push(task_title);
        });
        
          if(wid < 420)
          {
            
            $(""+li_type+" .gd-heading a").each(function(key) {
              var main_div = $(this).parent().parent().parent().find("a").hasClass("show-task-link"); 
              var task_title = taskData[key];
              var length = task_title.length;
              if(li_type == "#tree-mobile" && main_div)
              {
                subStr = task_title.substring(0, 20); 
              }
              else
              {
                subStr = task_title.substring(0, 40);
              }
            if(length > 40)
              { 
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
            $(this).html("");
            $(this).html(subStr);
        });
          }
          else if(wid > 420 && wid < 600)
          {
              $(""+li_type+" .gd-heading a").each(function(key) {
                var task_title = taskData[key];
                var length = task_title.length;
                //subStr = task_title.substring(0, 50);
              var main_div = $(this).parent().parent().parent().find("a").hasClass("show-task-link"); 
              var task_title = taskData[key];
              var length = task_title.length;
              if(li_type == "#tree-mobile" && main_div)
              {
                subStr = task_title.substring(0, 50); 
              }
              else
              {
                subStr = task_title.substring(0, 70); 
              }
                if((length > 50 && main_div) || length > 70)
                  { 
                    subStr  = subStr+"...";
                  }
                  else
                  {
                    subStr  = subStr;
                  }
                    $(this).html("");
                    $(this).html(subStr);
        });
              
          }
          else if(wid > 600 && wid < 740)
          {
            $(""+li_type+" .gd-heading a").each(function(key) {
            var task_title = taskData[key];
            var length = task_title.length;
            var main_div = $(this).parent().parent().parent().find("a").hasClass("show-task-link"); 
              var task_title = taskData[key];
              var length = task_title.length;
              if(li_type == "#tree-mobile" && main_div)
              {
                subStr = task_title.substring(0, 70); 
              }
              else
              {
                subStr = task_title.substring(0, 90); 
              }

            if((length > 70 && main_div) || length > 90)
              { 
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).html("");
                $(this).html(subStr);              
            });           
          }
          else
          {
            $(""+li_type+" .gd-heading a").each(function(key) {
            var task_title = taskData[key];
            var length = task_title.length;
            var main_div = $(this).parent().parent().parent().find("a").hasClass("show-task-link"); 
              var task_title = taskData[key];
              var length = task_title.length;
      
                $(this).html("");
                $(this).html(task_title);              
            });           
          }
     
    }).resize();
};
</script>
@endsection