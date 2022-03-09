@extends('layouts.base')
@section('page_head_css_scripts')
<style>
   [draggable] {
   -moz-user-select: none;
   -khtml-user-select: none;
   -webkit-user-select: none;
   user-select: none;
   /* Required to make elements draggable in old WebKit */
   -khtml-user-drag: element;
   -webkit-user-drag: element;
   }
   .compact-view-goals .content .text{
   /*margin-top: 0px;*/
   }
   .js__datepicker,.picker__input{
   border: 0;
   background: transparent;
   padding-left: 13px;
   }
   .goal-row .lobby{
   padding-left: 10px;
   padding-right: 10px;
   }
   .goal-row .new-goal{    padding-left: 5px;}
   .status.red .fa-thumbs-o-down{color:red;}
   /*.sub-container{overflow:hidden;clear:both;}*/
</style>
@endsection
@section('content')
<div id="page-wrapper" class="no-pad">
   <div class="graphs">
      <div class="hide-on-mobile">
         <!--<div class="graphs">
            <div class="hide-on-mobile"> -->
         <!--Section-->
		{!! Form::open(array('action' => 'GoalsController@Def_update')) !!}
         <div class="col-md-6 no-pad ">
            <div class="compact-view-goals  fullhalfheightsec2">
               <div class="panel panel-primary has-child">
                  <!--Row Parent-->
                  <div class="no-mar-top ">
                     <div class="header">
                        <!--<span class="float-right"><a href="#"><i class="fa fa-plus"></i></a> <a href="#"><i class="fa fa-close"></i></a></span>-->
                        <h3>Update Goal</h3>
                        <!--<a href="#" class="settings"><i class="fa fa-cog"></i></a>-->
                        <!--<a href="create-goal-extended.php" class="view-swicther">Extended View</a>-->
                     </div>
                     <div class="content">
                        <!--Row Parent-->
                       
							<div class="goal-row goal-row-parent"> 

                        
                        <!--<span class="first-alf">H</span>--> 
                        <span class="goal-title goal-title-first-row">Goal </span>

                      <!--<a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a>--> 

                        <a class="type yellow" id="main-type" data-value="Undefined" data-id="0" href="javascript:void(0);"><span class="goal-title goal-title-sub-text  ">Undefined</span></a>
                        <input type="hidden" name="main_habit_type" value="1;7" />

                        <a class="lobby green" id="main-show-lobby" data-value="&lt;i class=&quot;fa fa-eye&quot;&gt;&lt;/i&gt;" data-id="1" style="display:none;" href="javascript:void(0);"><span class="goal-title goal-title-sub-text"><i class="fa fa-eye"></i></span></a>
                        <a class="status yellow" id="main-active" data-value="Active" data-id="1" href="javascript:void(0);"><span class="goal-title goal-title-sub-text">Active</span></a>
                        <a class="habit-schedule settings" href="javascript:void(0);"><i class='fa fa-cog'></i></a>
                        
                        <input class="date fieldset__input js__datepicker goal-due" style="display:none;" id="top_goal_due" type="text" value="13 April, 2018" name="due_date" />
                        <input class="habit-start-date date" style="display:none;" id="top_habit_start_date" type="text" value="13 April, 2018" name="habit_start_date" />
                                    
                        
                        <span class="float-right">
							<a class="new-goal new-sub-goal " id="add-sub" href="javascript:void(0);"><i class="fa fa-plus"></i></a>                        </span>



                        <div class="panel-wrapper collapse in goal-row-child">


                            <!--<span class="sub-title-goal level-1" data-pid=""></span>-->
							<input type="hidden" name="id" value="{{ $goals_default->id}}">
                            <input placeholder="Goal&#039;s name" style="" class=" first-text main-goal-text" id="" type="text" value="{{ $goals_default->name}}" name="name" />                            <div id='sub-rep'>
                                <div id="sub-goal" data-list="" data-totalsub="0" data-index="" data-level="0">
                                                                    </div>
                            </div>
                        </div>

                    </div>
                        <!--Row Parent Ends--> 
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-6 no-pad ">
            <div class="goal-create-details fullhalfheightsec">
               <div class="section">
                  <header>
                     <h4>Status<small> - What is your current situation?</small> <a href="javascript:;" id="status-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="status" id="status">{{$goals_default->status}}</textarea>
                  </header>
               </div>
               <!--//Section Ends--> 
               <div class="section">
                  <header>
                     <h4>Improvement<small> - Do you want to improve the situation? why?</small> <a href="javascript:;" id="improvement-info"><span data-toggle="tooltip" data-placement="bottom" title="Do you want to improve the situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="improvement" id="improvement">{{$goals_default->improvement}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Risk<small> - What effect will it have if you don't improve/change the situation?</small> <a href="javascript:;" id="risk-info"><span data-toggle="tooltip" data-placement="bottom" title="What effect will it have if you don't improve/change the situation?" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="risk" id="risk">{{$goals_default->risk}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Benefit<small> - What effect will it have if you improve the situation?</small> <a href="javascript:;" id="benefits-info"><span data-toggle="tooltip" data-placement="bottom" title="What effect will it have if you improve the situation?" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="benefits" id="benefits">{{$goals_default->benefits}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Vision-Years<small> - How do you want your situation to be in 1,2,3 years from now?</small> <a href="javascript:;" id="vision-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="vision" id="vision">{{$goals_default->vision}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Vision - Decades - <small>How do you want your situation to be in 10 years, 20 years etc? </small><a href="javascript:;" id="vision2-info"> <span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="vision_decades" id="vision_decades">{{$goals_default->vision_decades}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Barriers - <small>What might stop you from achieving your goals? </small> <a href="javascript:;" id="barriers-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="barriers" id="barriers">{{$goals_default->barriers}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Actions - <small>What actions do you need to start achieving your goals?</small> <a href="javascript:;" id="priority-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="priority" id="priority">{{$goals_default->priority}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Initiative - <small>What are you confident about doing yourself? </small><a href="javascript:;" id="initiative-info"> <span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="initiative" id="initiative">{{$goals_default->initiative}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Help - <small>What might you want help with?</small> <a href="javascript:;" id="help-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="help" id="help">{{$goals_default->help}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Support - <small>Which individuals or groups can help you? </small> <a href="javascript:;" id="support-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="support" id="support">{{$goals_default->support}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Environment - <small>Do you plan on using tools, distance, data/statistics or a rewards system to help you reach your goal? If so explain how. </small> <a href="javascript:;" id="environment-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="environment" id="environment">{{$goals_default->environment}}</textarea>
                  </header>
               </div>
               <div class="section">
                  <header>
                     <h4>Imagery - <small>Create a list of habits and tasks associated to this goal.  Imagine your self executing them successfully. Write down your thoughts. </small> <a href="javascript:;" id="imagery-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="imagery" id="imagery">{{$goals_default->imagery}}</textarea>
                     <input id="checkChanged" type="hidden" value="" name="modified" />
                  </header>
               </div>
               <div class="section">
                  <div class="autosave-notify"></div>
                  <input name="saveandclose" id="saveandclose" class="submit" type="submit" value="SAVE AND CLOSE" />     <input type="hidden" name="goal_id" id="_goal_id" value="57049">
                  <input name="save" id="btnSubmit" class="submit" type="submit" value="SAVE" />      
               </div>
            </div>
         </div>
         {!! Form::close() !!}
         <div id="light_box" class="modal hide fade lightbox-pop-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-body"></div>
         </div>
         <div id="info-light-box" class="modal hide fade lightbox-pop-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-body-wrapper">
               <a href="#" id="close-btn"></a>
               <div class="modal-body"></div>
            </div>
         </div>
         <input data-collapse="0" type="hidden" value="10693" name="sub_id" id="sub_id">
         <div id="light-box" class="modal hide fade lightbox-pop-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-body"></div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('footer_scripts')
<script src="./js/add-new.js"></script>
<script src="./js/info.js"></script>
<script type="text/javascript">
   var actiontype = 'update';
   
   var goals_to_delete = '';
   
   function fixHasSub()
   {
       // fix class has_sub
       $('div.goal-top').each(function() {
           var _this = $(this);
           var goal_id = _this.siblings('input[name="sub_id"]').val();
           if (_this.parent().hasClass('add-form'))
           {
               var goal_state = _this.children('a.show-goal-link').data('collapse');
   
               if (goal_state)
               {
                   var b = $('#sub-goal');
   
                   b.children('div.sub-container').each(function() {
                       $(this).hide();
                   });
               }
           }
           else
           {
               var goal_state = _this.siblings('input[name="sub_id"]').data('collapse');
   
               if (_this.siblings('div.sub-container')[0])
               {
                   // var span_name = _this.children('span');
                   var span_name = _this.children('a.type');
   
                   // add collapse/expand icon
                   if (goal_state)
                   {
                       span_name.before('<a class="show-goal-link bg2" href="javascript:void(0);" data-id="' + goal_id + '"><i class="fa fa-chevron-right"></i></a>');
   
                       var b = span_name.parent().parent();
                       if (b.hasClass('add-form')) {
                           b = b.find('#sub-goal');
                       }
   
                       b.children('div.sub-container').each(function() {
                           $(this).hide();
                       });
                   }
                   else
                   {
                       span_name.before('<a class="show-goal-link bg1" href="javascript:void(0);" data-id="' + goal_id + '"><i class="fa fa-chevron-right"></i></a>');
                   }
               }
           }
       });
   
       $('#top-goal-link i').on('click', function() {
           var fpid = $(this).parent().data('id');
           $('#input-' + fpid).show();
   
       });
   
   
       $('.sub-title-goal').on('click', function() {
   
           $('input.text:not(:first-child)').hide();
           $('.sub-title-goal').show();
           var pid = $(this).data('pid');
           //console.log(pid,'pid');
           $('#input-' + pid).show();
           $('#checkChanged').val(1);
           var action = 1;
           windowloadaleart(action);
           $(this).hide();
       });
   
       if (typeof actiontype !== 'undefined' && actiontype != 'add') {
           $('.sub-title-goal').next('.text').on('keypress', function(e) {
               var code = e.keyCode || e.which;
   
               if (code == 13) {
                   var inpysec = $(this).attr('id');
                   var pid = inpysec.split('input-');
                   pid = pid[1];
                   $(this).hide();
                   var newre = $(this).val();
                   $(this).prev('.sub-title-goal').html(newre).show();
               }
           });
       }
   
       $('input,textarea').on('keypress', function(e) {
           $('#checkChanged').val(1);
       });
   
   
   
       $(".show-goal-link").each(function() {
           $(this).unbind('click').bind('click', function(event) {
               var a = $(this);
               var goal_id = a.data('id');
   
               $.post('/goal/state/', {id: goal_id, self: true}, function(data) {
                   if (data.error == 0)
                   {
                       //console.log(data.message);
                   }
               }, "json");
   
               var a = $(this);
               var b = $(this).parent().parent();
   
               if (b.hasClass('add-form')) {
                   b = b.find('#sub-goal');
                   b.show();
               }
   
               if (a.hasClass('bg1')) {
                   if (a.attr('id') == 'top-goal-link') {
                       $('#sub-rep').hide();
                   }
                   b.children('div.sub-container').each(function() {
                       console.log("hide hide");
                       $(this).hide();
                   });
                   a.removeClass('bg1');
                   a.addClass('bg2');
               }
               else if (a.hasClass('bg2')) {
                   if (a.attr('id') == 'top-goal-link') {
                       $('#sub-rep').show();
                   }
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
   
   function fixDataList()
   {
       // Fix data-list
       var sub_goal = $("#sub-goal");
       var list = sub_goal.data('list');
       total_goal = sub_goal.data('index');
       list = list.split(',');
   
       for (var i = 0; i < list.length; i++)
       {
           var _this = $('#' + list[i]);
           _this.data('pid', _this.parent('div').attr('id'));
       }
   }
   
   function scrollToElement(selector, time, verticalOffset) {
       var headerHeight = $('#header-id').height();
   
       time = typeof (time) != 'undefined' ? time : 1000;
       verticalOffset = typeof (verticalOffset) != 'undefined' ? verticalOffset : -headerHeight;
       element = $(selector);
       offset = element.offset();
       offsetTop = offset.top + verticalOffset;
       $('html, body').animate({
           scrollTop: offsetTop
       }, time);
   }
   
   function bindEvents()
   {
       $('.main-over-due').bind('touchstart click', function() {
           popupOverdue(this, 1);
       });
   
       $('.over-due').bind('touchstart click', function() {
           popupOverdue(this, 0);
       });
   
       $('.btnMainReactive').bind('touchstart click', function() {
           popupReactive(this, 1);
       });
   
       $('.btnReactive').bind('touchstart click', function() {
           popupReactive(this, 0);
       });
   
       $('.btnTrophy').bind('touchstart click', function(event) {
           popupTrophy(this, 0, event.target);
       });
   }
   
   function popupNotSaved(url) {
       var b = $("#light-box");
       var c = b.find('.modal-body');
       c.empty();
       var input = '<h4 style="text-align:center;">You did not save change. Do you want to save?</h4><p style="text-align:center;"><a class="btn btn-success btnPopupSave" href="javascript:void(0)" style="margin-right:20px;">YES</a> <a class="btn btn-warning btnPopupNo" href="javascript:void(0)">NO</a></p>';
       c.append(input);
   
       $('#light-box .btnPopupSave').on('click', function() {
           b.modal('hide');
           window.onbeforeunload = function() {
           }
           $('#checkChanged').val('');
           saveGoal(url);
       });
   
       $('#light-box .btnPopupNo').on('click', function() {
           window.location.href = url;
       });
   
       b.modal();
   }
   
   function popupReactive(target, isMain) {
       var b = $("#light-box");
       var c = b.find('.modal-body');
       c.empty();
       var input = '<h4 style="text-align:center;">This task is finished. Do you want to reactivate?</h4><p style="text-align:center;"><a class="btn btn-success btnPopupReactive" href="javascript:void(0)" style="margin-right:20px;">REACTIVATE</a> <a class="btn btn-warning btnPopupNotReactive" href="javascript:void(0)">NO</a></p>';
       c.append(input);
   
       var _this = $(target);
   
       $('#light-box .btnPopupReactive').on('click', function() {
           b.modal('hide');
   
           var url = '/goal/reactive/id/';
   
           $.blockUI({
               message: '<img src="/img/ajax-loader.gif" />',
               css: {backgroundColor: 'transparent', border: 0}
           });
   
           var gid = _this.attr('gid');
   
           $.post(url + gid, function(data) {
               if (data.error == 0)
               {
                   var goal_top = _this.parent('div.goal-top');
   
                   // Remove green-input class of current task
                   goal_top.siblings('input.green-input').removeClass('green-input');
   
                   if (data.overdue)
                   {
                       if (isMain)
                       {
                           _this.siblings('input[name="due_date"]').addClass('red').addClass('main-over-due');
                           goal_top.siblings('input[name="name"]').addClass('overdue-input');
                       }
                       else
                       {
                           _this.siblings('input[name="sub_due_date"]').addClass('red').addClass('over-due');
                           goal_top.siblings('input[name="sub_name"]').addClass('overdue-input');
                       }
                   }
   
                   // Reload subgoals with ajax response content
                   // $.post('/goal/subgoals/id/' + data.top_goal_id, function(response) {
                   //     if (response.html)
                   //     {
                   //         $('#sub-goal').html(response.html);
   
                   //         // fix sub goals' collapse/expand icon
                   //         fixHasSub();
   
                   //         $('.habit-start-date').pickadate();
   
                   //         $('.goal-due').pickadate();
   
                   //         // fix data-list
                   //         fixDataList();
   
                   //         // bind events
                   //         bindEvents();
                   //     }
                   // }, "json");
                   bindEvents();location.reload();
   
                   var containers = '';
                   if (isMain)
                   {
                       containers = goal_top.siblings('div#sub-goal').find('div.sub-container');
                   }
                   else
                   {
                       containers = goal_top.siblings('div.sub-container');
                   }
   
                   var today = new Date();
   
   
                   var trophyid = 'trophy-' + gid;
                   var trophyiconid = 'trophy-icon-' + gid;
                   $('#' + trophyid).remove;
                   var goal_top = _this.parent('div.goal-top');
   
   
                   $('#trophy-top').remove();
   
                   // Red-out subtasks' trophies
                   var subTask = _this.parent().parent().find('.trophy-normal');
                   $.map(subTask, function(value, index) {
                       var id = value.id.split("-");
                       id = id[id.length - 1];
                       $('#trophy-' + id).remove();
                   });
   
                   $.each(containers, function(i, obj) {
                       var $obj = $(obj);
                       var input = $obj.find('input.green-input');
                       var due_date_input = $obj.find('input[name="sub_due_date"]');
                       var due_date_timestamp = new Date(due_date_input.val());
   
                       input.removeClass('green-input');
                       $obj.find('a.like').remove();
   
                       // $obj.find('a.btnTrophy').remove();
                       $obj.find('.trophy-normal').remove();
   
                       if (due_date_timestamp.getTime() < today.getTime())
                       {
                           due_date_input.addClass('red').addClass('over-due');
                           input.addClass('overdue-input');
   
                           bindEvents();
                       }
                   });
   
                   // Remove reactive button
                   _this.remove();
               }
               else
               {
                   alert(data.message);
               }
           }, "json");
       });
   
       $('#light-box .btnPopupNotReactive').unbind('click').bind('click', function() {
           b.modal('hide');
       });
   
       b.modal();
   }
   
   function popupTrophy(target, isMain, triggerObj) {
       var b = $("#light-box");
       var c = b.find('.modal-body');
       c.empty();
   
       var input = '';
       var trophyClassName = triggerObj.className;
       if (trophyClassName.indexOf('trophy-red') != -1)
           input = '<h4 style="text-align:center;">This task has already been added to your trophy room</h4><p style="text-align:center;"><a class="btn btn-warning btnPopupNotReactive" href="javascript:void(0)">OK</a></p>';
       else
           input = '<h4 style="text-align:center;">This task is finished. Do you want to add to trophy?</h4><p style="text-align:center;"><a class="btn btn-success btnPopupReactive" href="javascript:void(0)" style="margin-right:20px;">ADD TO TROPHY</a> <a class="btn btn-warning btnPopupNotReactive" href="javascript:void(0)">NO</a></p>';
   
       c.append(input);
   
       var _this = $(target);
   
       $('#light-box .btnPopupReactive').on('click', function() {
           b.modal('hide');
   
           var url = '/goal/trophy/id/';
   
   
           $.blockUI({
               message: '<img src="/img/ajax-loader.gif" />',
               css: {backgroundColor: 'transparent', border: 0}
           });
   
           var gid = _this.attr('gid');
   
           $.post(url + gid, function(data) {
               if (data.error == 0)
               {
   
                   var trophyid = 'trophy-' + gid;
                   var trophyiconid = 'trophy-icon-' + gid;
                   $('#' + trophyid).addClass('trophy-red');
                   $('#' + trophyiconid).addClass('trophy-red');
   
                   if (_this.attr('id') == 'trophy-top') {
                       $('#trophy-top').addClass('trophy-red');
                       //$('#' + trophyiconid).addClass('trophy-red');
                   }
   
                   var goal_top = _this.parent('div.goal-top');
   
               }
               else
               {
                   alert(data.message);
               }
           }, "json");
       });
   
       $('#light-box .btnPopupNotReactive').unbind('click').bind('click', function() {
           b.modal('hide');
       });
   
       b.modal();
   }
   
   function popupOverdue(target, isMain) {
       var b = $("#light-box");
       var c = b.find('.modal-body');
       c.empty();
       var input = '<h4 style="text-align:center;">This task is overdue. Do you want to finish or extend due date?</h4><p style="text-align:center;"><a class="btn btn-success btnPopupFinish" href="javascript:void(0)" style="margin-right:20px;">FINISH</a> <a class="btn btn-warning btnPopupExtend" href="javascript:void(0)">EXTEND</a></p>';
       c.append(input);
   
       var _this = $(target);
   
       $('#light-box .btnPopupFinish').on('click', function() {
           b.modal('hide');
   
           var gid = _this.attr('gid');
   
           var url = '';
   
           if (isMain)
           {
               url = '/goal/end/id/' + gid + "/topid/57049";
           }
           else
           {
               url = '/goal/sub/id/' + gid + "/topid/57049";
           }
   
           $.blockUI({
               message: '<img src="/img/ajax-loader.gif" />',
               css: {backgroundColor: 'transparent', border: 0}
           });
   
   
   
           $.post(url, function(data) {
               if (data.error == 0)
               {
   
                   $('#top-rep').html(data.tophtml);
                   $('#sub-rep').html(data.indexhtml);
   
                   fixHasSub();
   
                   bindEvents();console.log('ff55');
   
                   $('.js__datepicker').pickadate({
                       clear: '',
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
                              console.log('3333');
                          });
                      }
                   });
               }
           }, "json");
       });
   
       $('#light-box .btnPopupExtend').unbind('click').bind('click', function() {
           b.modal('hide');
   
           _this.pickadate({
               clear: '',
               min: true,
               onSet: function(thingSet) {
                   $('#checkChanged').val(1);
                   var action = 1;
                   windowloadaleart(action);
                   if (isMain)
                   {
                       _this.removeClass('red').removeClass('main-over-due');
                   }
                   else
                   {
                       _this.removeClass('red').removeClass('over-due');
                   }
   
                   if (isMain)
                   {
                       $('input[name="name"]').removeClass('overdue-input');
                   }
                   else
                   {
                       _this.parents('div.goal-top').siblings('input[name="sub_name"]').removeClass('overdue-input');
                   }
   
               }
           });
           var picker = _this.pickadate('picker');
           picker.open(false);
       });
   
       if (_this.hasClass('over-due') || _this.hasClass('main-over-due'))
       {
           b.modal();
       }
   }
   
   function saveGoal(redirect_url, autosave)
   {
       // set detail order for goals
       var i = 2;
       $('.sub-container').each(function() {
           $(this).data('order', i);
           i++;
       });
   
       var type_id = $("#main-type").data('id');
       var is_show_lobby = $("#main-show-lobby").data('id');
       var is_active = $("#main-active").data('id');
       var due_date = $("input[name='due_date']").val();
       var habit_start_date = $("input[name='habit_start_date']").val();
       var habit_type = $('input[name="main_habit_type"]').val();
       var name = $("input[name='name']").val();
       var status = $("textarea[name='status']").val();
       var improvement = $("textarea[name='improvement']").val();
       var risk = $("textarea[name='risk']").val();
       var benefits = $("textarea[name='benefits']").val();
       var vision = $("textarea[name='vision']").val();
       var vision_decades = $("textarea[name='vision_decades']").val();
       var barriers = $("textarea[name='barriers']").val();
       var priority = $("textarea[name='priority']").val();
       var initiative = $("textarea[name='initiative']").val();
       var help = $("textarea[name='help']").val();
       var support = $("textarea[name='support']").val();
       var environment = $("textarea[name='environment']").val();
       var imagery = $("textarea[name='imagery']").val();
   
       var order = 1;
   
       if (type_id == 2)
       {
           if (!due_date)
           {
               alert("Please choose task's due date!");
               scrollToElement("input[name='due_date']");
               return false;
           }
       }
   
       if (type_id == 1)
       {
           if (!habit_start_date)
           {
               alert("Please choose habit's start date!");
               scrollToElement("input[name='habit_start_date']");
               return false;
           }
       }
   
       if (!name)
       {
           alert("Please enter goal's name!");
           scrollToElement("input[name='name']");
           return false;
       }
   
       var data = {
           type_id: type_id,
           due_date: due_date,
           habit_start_date: habit_start_date,
           habit_type: habit_type,
           is_active: is_active,
           is_show_lobby: is_show_lobby,
           name: name,
           status: status,
           improvement: improvement,
           risk: risk,
           benefits: benefits,
           vision: vision,
           vision_decades: vision_decades,
           barriers: barriers,
           priority: priority,
           initiative: initiative,
           help: help,
           support: support,
           environment: environment,
           imagery: imagery,
           detail_order: order,
           goals_to_delete: goals_to_delete
       };
   
       var check_sub = true;
       var goals_list = $('#sub-goal').data('list');
   
       if (goals_list)
       {
           goals_list = goals_list.split(',');
   
           var sub_goals = new Array();
           for (var i = 0; i < goals_list.length; i++) {
               var goal = {};
               var _this = $('#' + goals_list[i]);
               if (_this[0])
               {
                   var temp = _this.data('pid');
                   if (temp === 'sub-goal')
                   {
                       temp = 0;
                   }
                   else
                   {
                       temp = temp.replace('goal-', '');
                   }
                   goal.vid = goals_list[i];
                   // goal.level         = _this.data('level');
                   goal.level = _this.parent().data('level') + 1;
                   // goal.parent        = _this.data('pid');
                   goal.parent = _this.parent().attr('id');
                   goal.detail_order = _this.data('order');
                   goal.type_id = _this.children('.goal-top').children('a.type').data('id');
                   goal.is_show_lobby = _this.children('.goal-top').children('a.lobby').data('id');
                   goal.is_active = _this.children('.goal-top').children('a.status').data('id');
                       goal.id = _this.children("input[name='sub_id']").val();
   
                   if (!_this.children("input[name='sub_name']").val())
                   {
                       alert("Please enter sub goal's name!");
                       scrollToElement("#" + goals_list[i] + " > input[name='sub_name']");
                       check_sub = false;
                   }
                   goal.name = _this.children("input[name='sub_name']").val();
                  goal.add_text_type = _this.children('.goal-top').children('input[name="add_text_type"]').val();
                   console.log(goal.type_id);
                   if (goal.type_id == 2)
                   {
                       if (!_this.children('.goal-top').children("input[name='sub_due_date']").val())
                       {
                           alert("Please choose sub task's due date!");
                           scrollToElement("#" + goals_list[i] + " > input[name='sub_due_date']");
                           check_sub = false;
                       }
                       goal.due_date = _this.children('.goal-top').children("input[name='sub_due_date']").val();
                   }
   
                   if (goal.type_id == 1)
                   {
                       if (!_this.children('.goal-top').children("input[name='sub_habit_start_date']").val())
                       {
                           alert("Please choose sub habit's start date!");
                           scrollToElement("#" + goals_list[i] + " > input[name='sub_habit_start_date']");
                           check_sub = false;
                       }
                       goal.habit_start_date = _this.children('.goal-top').children("input[name='sub_habit_start_date']").val();
                       goal.habit_type = _this.children('.goal-top').children('input[name="sub_habit_type"]').val();
                   }
   
                   if (typeof sub_goals[temp] === 'undefined')
                   {
                       sub_goals[temp] = new Array();
                   }
                   sub_goals[temp][i] = goal;
   
                   if (!check_sub)
                   {
                       return false;
                   }
               }
           }
           
   
           if (sub_goals)
           {
               data.sub_goals = sub_goals;
           }
       }
       
       if(!autosave){
           $.blockUI({
               message: '<img src="/img/ajax-loader.gif" />',
               css: {backgroundColor: 'transparent', border: 0}
           });
       }
   
   var goal_id=$("#_goal_id").val();
   
       
   var url = '/add';
   if(goal_id){
       var url = '/update?id=' + goal_id;
   }
   
   // console.log(data,'data');return false;
       $.post(url, data, function(response) {
           
           if (response.error == 0)
           {
               $("#_goal_id").val(response.gid);
               
               $('#checkChanged').val('');
               // window.location.href = '/list';
               if(redirect_url){
                   window.location.href = redirect_url;
               }
           }
       }, "json");
   }
   
   jQuery(window).bind("load", function() {
       $('textarea').autosize();
   });
   
   function windowloadaleart(action) {
       window.onbeforeunload = function() {
           // Add your code here
           var url = $('ul.nav li a').attr('href');
           setTimeout(function() {
               if (action != '1') {
                   popupNotSaved(url);
               }
           }, 2000);
           return false;
       }
   }
   
   
   $(document).ready(function() {
   
       var top_collapse = '0';
       top_collapse = parseInt(top_collapse);
       if (top_collapse == 1)
           $('#sub-rep').hide();
       else
           $('#sub-rep').show();
   
       $('.show-task-link').on('click', function() {
           $(this).html($(this).html() == '<i class="fa fa-chevron-down"></i>' ? '<i class="fa fa-chevron-down"></i>' : '<i class="fa fa-chevron-right"></i>');
       });
   
       $('.picker__day').on('click', function() {
           $('#checkChanged').val(1);
           var action = 1;
           windowloadaleart(action);
       });
   
       $('.habit-start-date').pickadate({
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
   
   
       var issavedevent = $('#checkChanged').val();
   
       console.log('issavedevent', issavedevent);
   
   
       $('input,textarea').on('keypress', function() {
           console.log('34343434');
           var action = 1;
           windowloadaleart(action);
       });
   
   
       // fix sub goals' collapse/expand icon
       fixHasSub();
   
       // unblock when ajax activity stops
       $(document).ajaxStop($.unblockUI);
   
       $('div.add-form').on('change', 'input', function() {
           $('#checkChanged').val(1);
           var action = 1;
           windowloadaleart(action);
       });
   
       $('div.add-form').on('change', 'textarea', function() {
           $('#checkChanged').val(1);
           var action = 1;
           windowloadaleart(action);
       });
   
       var is_safari = navigator.userAgent.indexOf("Safari") > -1;
   
       $('ul.nav li a').bind('touchstart click', function() {
           var url = $(this).attr('href');
           if ($('#checkChanged').val())
           {
               popupNotSaved(url);
               return false;
           }
       });
   
       $('.sticky-left-side .logo-icon a').bind('touchstart click', function() {
           var url = $(this).attr('href');
           if ($('#checkChanged').val())
           {
               popupNotSaved(url);
               return false;
           }
       });
   
                           fixDataList();
               
       /*
       window.setInterval(function(){
         var goal_id=$("#_goal_id").val();
           if(goal_id){
             saveGoal(false,true);
             var timenow="LastSync: " +  new Date().toLocaleTimeString('en-US', { hour12: true,  hour: "numeric", minute: "numeric",second:"numeric"});
             $(".autosave-notify").html(timenow);
           }
           
       }, 5000);
           
       */
   
       $('#btnSubmit').click(function() {
           window.onbeforeunload = function() {
           }
           saveGoal(false);
       });
       
       $('#saveandclose').click(function() {
           window.onbeforeunload = function() {
           }
           
           saveGoal('/list');
       });
       
   
   
           $('.remove-goal').unbind('touchstart click').bind('touchstart click', function() {
               if (!confirm('Are you sure you want to delete this item?'))
                   return false;
   
               var _this = $(this);
               $('#checkChanged').val(1);
               var action = 1;
               windowloadaleart(action);
   
               if (_this.attr('id') == 'remove-main')
               {
                   $.blockUI({
                       message: '<img src="/img/ajax-loader.gif" />',
                       css: {backgroundColor: 'transparent', border: 0}
                   });
   
                   var _this = $(this);
                   var gid = _this.attr('gid');
   
                   $.post('/goal/delete/', {id: gid, type: 'list'}, function(data) {
                       if (data.error == 0)
                       {
                           window.location.href = '/list';
                       }
                       else
                       {
                           alert('ERROR!');
                       }
                   }, "json");
               }
               else
               {
                   var sub_container = _this.parents('div.sub-container').first();
   
                   /* Get goals id to delete */
                   var inputs = sub_container.find('input[name="sub_id"]');
                   if (inputs.length > 0)
                   {
                       for (var i = 0; i < inputs.length; i++)
                       {
                           if (!goals_to_delete)
                           {
                               goals_to_delete += $(inputs[i]).val();
                           }
                           else
                           {
                               goals_to_delete += ',' + $(inputs[i]).val();
                           }
                       }
                   }
   
                   $(sub_container).fadeOut('slow', function() {
                       sub_container.remove();
                   });
               }
           });
   
           bindEvents();
   
       focus_input();
   });
   
</script>    <!-- </div>
   </div> -->
<script>
   $(document).ready(function () {
   
       /** ******************************
        * Collapse Panels
        * [data-perform="panel-collapse"]
        ****************************** **/
       (function ($, window, document) {
           var panelSelector = '[data-perform="panel-collapse"]';
   
           $(panelSelector).each(function () {
               var $this = $(this),
                       parent = $this.closest('.panel'),
                       wrapper = parent.find('.panel-wrapper'),
                       collapseOpts = {toggle: false};
   
               if (!wrapper.length) {
                   wrapper =
                           parent.children('.panel-heading').nextAll()
                           .wrapAll('<div/>')
                           .parent()
                           .addClass('panel-wrapper');
                   collapseOpts = {};
               }
               wrapper
                       .collapse(collapseOpts)
                       .on('hide.bs.collapse', function () {
                           $this.children('i').removeClass('fa-minus').addClass('fa-plus');
                       })
                       .on('show.bs.collapse', function () {
                           $this.children('i').removeClass('fa-plus').addClass('fa-minus');
                       });
           });
           $(document).on('click', panelSelector, function (e) {
               e.preventDefault();
               var parent = $(this).closest('.panel');
               var wrapper = parent.find('.panel-wrapper');
               wrapper.collapse('toggle');
           });
       }(jQuery, window, document));
   
       /** ******************************
        * Remove Panels
        * [data-perform="panel-dismiss"]
        ****************************** **/
       (function ($, window, document) {
           var panelSelector = '[data-perform="panel-dismiss"]';
           $(document).on('click', panelSelector, function (e) {
               e.preventDefault();
               var parent = $(this).closest('.panel');
               removeElement();
   
               function removeElement() {
                   var col = parent.parent();
                   parent.remove();
                   col.filter(function () {
                       var el = $(this);
                       return (el.is('[class*="col-"]') && el.children('*').length === 0);
                   }).remove();
               }
           });
       }(jQuery, window, document));
   
   });
</script> 
@endsection