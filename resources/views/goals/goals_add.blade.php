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

   .goal-row .new-lobby {
    padding-left: 10px;
    padding-right: 10px;
}

.goal-row-parent .new-handle-goal .fa {
    color: #99989d;
    padding-left: 5px;
}

   .goal-row .new-goal{    padding-left: 5px;}

   .goal-row .new-add-goal{    padding-left: 5px;}

   .status.red .fa-thumbs-o-down{color:red;}
   /*.sub-container{overflow:hidden;clear:both;}*/
   .goal-row ul{
    list-style: none;
   }
   .goal-row ul li{
    list-style: none;
   }

   ._editor{
    visibility:hidden;
   }

   .goal-create-details #closeSubmit {
    position: fixed;
    bottom: 0px;
    right: 24px;
    z-index: 1;
}

  /*.fixed {
    position: fixed;
    top:0; left:0;
    width: 100%; 
    display: block !important;
    transform: translateZ(0);
  }*/

</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js"></script>

<script src="{{ URL::asset('/ckeditor/ckeditor.js') }}"></script>
<script src="{{ URL::asset('/ckeditor/plugins/timestamp/plugin.js') }}"></script>


@endsection

@section('content')
<div id="page-wrapper" class="no-pad">
   <div class="graphs">
      <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
        <?php 
        if($isMobile){?>
            @include('mobile_header')
        <?php } ?>
         <!--<div class="graphs">
            <div class="hide-on-mobile"> -->
         <!--Section-->
      {!! Form::open(array('action' => 'GoalsController@create_goals')) !!}
        
        <?php if(!is_admin() && $is_default){ ?>
          <fieldset disabled>
        <?php } ?>
        

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
                      <?php $main_self_closed=(isset($goals_edit->self_collapse)&&$goals_edit->self_collapse==1)?1:0; 
                      
                      $status_class="";

                      $active_status_text="Acitve";
                      $active_status="1";
                      if(isset($goals_edit->is_active) && $goals_edit->is_active==1){
                        $active_status_text="Active";
                        $active_status="1";
                      }else if(isset($goals_edit->is_active) && $goals_edit->is_active==0){
                         $active_status_text="Inactive";
                         $active_status="0";
                         $status_class="red";
                      }
                      ?> 
                      <div class="goal-row goal-row-parent"> 

                        <?php if(!empty($html)){ ?>
                          <a id="top-goal-link" data-perform="panel-collapse" class="btn show-goal-link <?php echo (isset($goals_edit->self_collapse)&&$goals_edit->self_collapse==1) ? 'bg2' : 'bg1' ?>" data-collapse="<?php echo (isset($goals_edit->self_collapse)?$goals_edit->self_collapse:0) ?>" data-id="<?php echo isset($goals_edit->id)?$goals_edit->id:0; ?>" data-autosaveid="<?php echo isset($goals_edit->auto_save_id)?$goals_edit->auto_save_id:0; ?>" href="javascript:void(0);">
                            <i class="fa fa-chevron-right"></i>
                          </a>
                        <?php } ?>

                        <!--<span class="first-alf">H</span>--> 
                        <span class="goal-title goal-title-first-row">Goal</span>

                      <!--<a href="#" data-perform="panel-collapse" class="btn"><i class="fa fa-plus"></i></a>--> 

                        <a class="type yellow hidden" id="main-type" data-value="<?php echo ((isset($goals_edit->type->name)) ? $goals_edit->type->name : "Undefined") ?>" data-id="<?php echo ((isset($goals_edit->type_id)) ? $goals_edit->type_id : 0);?>" href="javascript:void(0);">
                        <?php echo ((isset($goals_edit->type->name)) ? "<span class='goal-title goal-title-sub-text'>  " . $goals_edit->type->name . "</span>" : "<span class='goal-title goal-title-sub-text'>Habit</span>")?></a>
                         <?php
                            $goal_status = (!is_admin() && $is_default)?"new-status":"status";
                          
                         ?>
                        <a class="lobby green hidden" id="main-show-lobby" data-value="<?php echo isset($goals_edit->is_show_lobby)?$goals_edit->is_show_lobby:1?>" data-id="1" style="display:none;" href="javascript:void(0);"><span class="goal-title goal-title-sub-text"><i class="fa fa-eye"></i></span></a>
                        <a class="{{$goal_status}} <?php echo $status_class?>" id="main-active" data-value="<?php echo $active_status_text; ?>" data-id="<?php echo $active_status; ?>" href="javascript:void(0);">
                          <span class="goal-title goal-title-sub-text"><?php echo $active_status_text; ?></span>
                        </a>
                        
                        <a class="statements_values" id="main-active" href="javascript:void(0);">
                          <span class="goal-title goal-title-sub-text">Statements</span>
                        </a>
                        
                        <?php $schedule_display = (isset($goals_edit->type_id) && $goals_edit->type_id != 1) ? 'display:none;' : '';?>
                        
                        <a class="habit-schedule  hidden" href="javascript:void(0);" style="<?php echo $schedule_display;?>" id="main_habit_type" data-habit-type="<?php echo ( (isset($goals_edit->type_id) && $goals_edit->type_id ==1 && !empty($goals_edit->habit_types)) ? $goals_edit->habit_types->type . ';' . $goals_edit->habit_types->value : '1;7' )?>"><i class='fa fa-cog'></i></a>
                        <input type="hidden" id="add_main_text_type" name="add_text_type" value="<?php echo ((isset($goals_edit->habit_types) && !empty($goals_edit->habit_types)) ? $goals_edit->habit_types->text  : '' )?>">
                        <input class="date fieldset__input js__datepicker goal-due" style="display:none;" id="top_goal_due" type="text" value="<?php echo date('j F, Y');?>" name="due_date" />
                        <input class="habit-start-date date" style="display:none;" id="top_habit_start_date" type="text" value="<?php echo date('j F, Y');?>" name="habit_start_date" />
                                    
                        
                                <span class="float-right">
                                  
                                  <a href="javascript:void(0);" class="remove-goal new-sub-goal btnDelete" id="{{(!is_admin() && $is_default)?'':'remove-main'}}" data-autosaveid="<?php echo isset($goals_edit->auto_save_id)?$goals_edit->auto_save_id:0?>"><i class="fa fa-close"></i></a>

                                  <a class="new-goal new-sub-goal " id="{{(!is_admin() && $is_default)?'':'add-sub'}}" href="javascript:void(0);"><i class="fa fa-plus"></i></a>                        
                                </span>
                                <div class="panel-wrapper collapse in goal-row-child">
                                    <!--<span class="sub-title-goal level-1" data-pid=""></span>-->
                                    <input placeholder="Goal's name" class="first-text main-goal-text" id="" value="<?php echo isset($goals_edit->name)?$goals_edit->name:'';?>" type="text" name="name" />                            
                                    <div id="sub-rep" style="display:<?php echo ($main_self_closed)?"none":"block"; ?>">
                                        <ul id="sub-goal" data-list="" data-totalsub="0" data-index="" data-level="0">
                                          <?php echo $html;?>
                                        </ul>
                                    </div>
                                </div>

                                </div>
                        <!--Row Parent Ends--> 
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <?php $attributes=isset($goals_edit->goal_attributes)&&!empty($goals_edit->goal_attributes)?$goals_edit->goal_attributes:array();?>
         <div class="col-md-6 no-pad ">
            <div class="goal-create-details fullhalfheightsec">
              <div class="section first-container" id="status-container">
                  <header>
                     <h4>Status<small> - What is your current situation?</small> 
                      <a href="javascript:;" id="status-info">
                        <span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span>
                      </a>
                        <?php /*
                        <div class="editor-checkbox">
                          <label>
                            Allow Editor?
                            <input type="checkbox" data-toggle="toggle" id="toggle-editor" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" data-size="small">
                          </label>
                        </div>
                        */?>
                    </h4>
                    <?php
                    

                    $status_attrs=process_goal_attr($attributes,"status");
                    
                   /* echo "<pre/>";
                    print_r($status_attrs);die;*/
                    ?>
                     <textarea name="status" id="status" class="_editor" data-attrs='<?php echo $status_attrs['attrs']; ?>'>{{ isset($status_attrs['default_html'])?nl2br($status_attrs['default_html']):''}}</textarea>
                  </header>
              </div> 
               <!--//Section Ends--> 
               <div class="section" id="improvement-container">
                  <header>
                    <?php
                    $improvement_attrs=process_goal_attr($attributes,"improvement");
                    ?>

                     <h4>Improvement<small> - Do you want to improve the situation? why?</small> <a href="javascript:;" id="improvement-info"><span data-toggle="tooltip" data-placement="bottom" title="Do you want to improve the situation" class="info-tt fa fa-info-circle"></span></a></h4>
                     <textarea name="improvement" id="improvement" class="_editor" data-attrs='<?php echo $improvement_attrs['attrs']; ?>'>{{ isset($improvement_attrs['default_html'])?nl2br($improvement_attrs['default_html']):''}}</textarea>
                  </header>
              </div>
             <div class="section" id="risk-container">
                <header>
                  <?php
                    $risk_attrs=process_goal_attr($attributes,"risk");
                  ?>

                   <h4>Risk<small> - What effect will it have if you don't improve/change the situation?</small> <a href="javascript:;" id="risk-info"><span data-toggle="tooltip" data-placement="bottom" title="What effect will it have if you don't improve/change the situation?" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="risk" id="risk" class="_editor" data-attrs='<?php echo $risk_attrs['attrs']; ?>'>{{ isset($risk_attrs['default_html'])?nl2br($risk_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="benefits-container">
                <header>
                  <?php
                    $benefits_attrs=process_goal_attr($attributes,"benefits");
                  ?>

                   <h4>Benefit<small> - What effect will it have if you improve the situation?</small> <a href="javascript:;" id="benefits-info"><span data-toggle="tooltip" data-placement="bottom" title="What effect will it have if you improve the situation?" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="benefits" id="benefits" class="_editor" data-attrs='<?php echo $benefits_attrs['attrs']; ?>'>{{ isset($benefits_attrs['default_html'])?nl2br($benefits_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="vision-container">
                <header>
                  <?php
                    $vision_attrs=process_goal_attr($attributes,"vision");
                  ?>

                   <h4>Vision-Years<small> - How do you want your situation to be in 1,2,3 years from now?</small> <a href="javascript:;" id="vision-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="vision" id="vision" class="_editor" data-attrs='<?php echo $vision_attrs['attrs']; ?>'>{{ isset($vision_attrs['default_html'])?nl2br($vision_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="vision_decades-container">
                <header>
                  <?php
                    $vision_decades_attrs=process_goal_attr($attributes,"vision_decades");
                  ?>

                   <h4>Vision - Decades - <small>How do you want your situation to be in 10 years, 20 years etc? </small><a href="javascript:;" id="vision2-info"> <span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="vision_decades" id="vision_decades" class="_editor" data-attrs='<?php echo $vision_decades_attrs['attrs']; ?>'>{{ isset($vision_decades_attrs['default_html'])?nl2br($vision_decades_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="barriers-container">
                <header>
                  <?php
                    $barriers_attrs=process_goal_attr($attributes,"barriers");
                  ?>
                   <h4>Barriers - <small>What might stop you from achieving your goals? </small> <a href="javascript:;" id="barriers-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="barriers" id="barriers" class="_editor" data-attrs='<?php echo $barriers_attrs['attrs']; ?>'>{{ isset($barriers_attrs['default_html'])?nl2br($barriers_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="priority-container">
                <header>
                  <?php
                    $priority_attrs=process_goal_attr($attributes,"priority");
                  ?>

                   <h4>Actions - <small>What actions do you need to start achieving your goals?</small> <a href="javascript:;" id="priority-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="priority" id="priority" class="_editor" data-attrs='<?php echo $priority_attrs['attrs']; ?>'>{{ isset($priority_attrs['default_html'])?nl2br($priority_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="initiative-container">
                <header>
                  <?php
                    $initiative_attrs=process_goal_attr($attributes,"initiative");
                  ?>
                   <h4>Initiative - <small>What are you confident about doing yourself? </small><a href="javascript:;" id="initiative-info"> <span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="initiative" id="initiative" class="_editor" data-attrs='<?php echo $initiative_attrs['attrs']; ?>'>{{ isset($initiative_attrs['default_html'])?nl2br($initiative_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="help-container">
                <header>
                  <?php
                    $help_attrs=process_goal_attr($attributes,"help");
                  ?>
                   <h4>Help - <small>What might you want help with?</small> <a href="javascript:;" id="help-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="help" id="help" class="_editor" data-attrs='<?php echo $help_attrs['attrs']; ?>'>{{ isset($help_attrs['default_html'])?nl2br($help_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="support-container">
                <header>
                  <?php
                    $support_attrs=process_goal_attr($attributes,"support");
                  ?>

                   <h4>Support - <small>Which individuals or groups can help you? </small> <a href="javascript:;" id="support-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="support" id="support" class="_editor" data-attrs='<?php echo $support_attrs['attrs']; ?>'>{{ isset($support_attrs['default_html'])?nl2br($support_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="environment-container">
                <header>
                  <?php
                    $environment_attrs=process_goal_attr($attributes,"environment");
                  ?>

                   <h4>Environment - <small>Do you plan on using tools, distance, data/statistics or a rewards system to help you reach your goal? If so explain how. </small> <a href="javascript:;" id="environment-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="environment" id="environment" class="_editor" data-attrs='<?php echo $environment_attrs['attrs']; ?>'>{{ isset($environment_attrs['default_html'])?nl2br($environment_attrs['default_html']):''}}</textarea>
                </header>
             </div>
             <div class="section" id="imagery-container">
                <header>
                  <?php
                    $imagery_attrs=process_goal_attr($attributes,"imagery");
                  ?>

                   <h4>Imagery - <small>Create a list of habits and tasks associated to this goal.  Imagine your self executing them successfully. Write down your thoughts. </small> <a href="javascript:;" id="imagery-info"><span data-toggle="tooltip" data-placement="bottom" title="What is your current situation" class="info-tt fa fa-info-circle"></span></a></h4>
                   <textarea name="imagery" id="imagery" class="_editor" data-attrs='<?php echo $imagery_attrs['attrs']; ?>'>{{ isset($imagery_attrs['default_html'])?nl2br($imagery_attrs['default_html']):''}}</textarea>
                   <input id="checkChanged" type="hidden" value="" name="modified" />
                </header>
             </div>

 
             <!-- <div class="section" id="imagery-container">
                <header>
                 <textarea name="mycontent" id="mycontent"></textarea>
                </header>
             </div> -->


             <div class="section">
                <div class="autosave-notify"></div>
                <?php /*<input name="saveandclose" id="saveandclose" class="submit" type="button" value="SAVE AND CLOSE" />*/?>    
                <input type="hidden" name="goal_id" id="_goal_id" value="<?php echo isset($goals_edit->id)?$goals_edit->id:0?>">
                <input type="hidden" name="temp_id" id="temp_id" value="<?php echo isset($goals_edit->auto_save_id)?$goals_edit->auto_save_id:0?>"/>
                <input type="hidden" name="is_default" id="is_default" value="<?php echo isset($is_default)?$is_default:0?>">
                @if(!is_admin() && $is_default)
                <a name="close" id="closeSubmit" class="closeSubmit submit" type="button" value="Close" onclick="closeBtn();" >Close</a>
                @else
                <input name="save" id="btnSubmit" class="submit" type="button" value="SAVE" />
                @endif
                <!-- <a href="#" id="btnDownload" style="margin-right: 100px;position: fixed;bottom: 0px;right: 24px;z-index: 1;" class="submit">DOWNLOAD AS PDF</a> -->           
             </div>
            </div>
         </div>
         {!! Form::close() !!}
        <?php if(!is_admin() && $is_default){ ?>
        </fieldset>
        <?php } ?>

         <div id="light_box" class="modal hide fade lightbox-pop-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">&nbsp;&nbsp;</div>
                <div class="modal-body"></div>
                <div class="modal-footer"></div>
              </div>
            </div>
         </div>

         <div id="info-light-box" class="modal hide fade lightbox-pop-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-body-wrapper">
               
                <div class="modal-dialog">
                  <a href="#" id="close-btn"></a>
                  <div class="modal-content">
                    <div class="modal-header"></div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                    </div>
                  </div>
                </div>
            </div>
         </div>


         <input data-collapse="0" type="hidden" value="10693" name="sub_id" id="sub_id">
         <div id="light-box" class="modal" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"></div>
                <div class="modal-footer">
              </div>
              </div>
            </div>
         </div>

         <div id="choose_help" class="modal" role="dialog">
            <div class="modal-dialog">  
              <div class="modal-content">
                <div class="modal-header">Why Choose Scale</div>
                <div class="modal-body">
                  <div style="text-align: justify;">
                  <p>By choosing scale you are determining the highest and lowest possible value. You will only be able to type in numbers from chosen scale.</p>
                  <p>Choosing a scale allows the software to use your data to correctly calculate your level of success with that particular habit.</p>
                  <p>If you are not sure of the scale you can press does not apply and the scaling will be automatic and dependant on the numbers entered.</p></div>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-success" id="scale-template-cancel" style="margin-right:0.5em;">Close</buton>
                </div>
              </div>
            </div>
         </div>
      </div>
   </div>
   <div class="prefill_status hide" id="prefill_status"><?php echo isset($goals_edit)&&isset($goals_edit->prefill_status)?nl2br($goals_edit->prefill_status):""?></div>
</div>
@endsection
@section('footer_scripts')
<!-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('/js/editor/summernote/dist/summernote.css') }}"> -->
<script src="{{ URL::asset('/js/moment.js') }}"></script>
<script src="{{ URL::asset('/js/add-new.js') }}"></script>
<script src="{{ URL::asset('/js/info.js') }}"></script>
<!-- <script src="{{ URL::asset('/js/editor/summernote/dist/summernote.js') }}"></script>
<script src="{{ URL::asset('/js/editor/summernote/plugin/sheet/summernote-ext-sheet.js') }}"></script> -->
<!-- <script src="{{ URL::asset('/js/editor/summernote/summernote-cleaner.js') }}"></script>
 -->
<style type="text/css">
  .note-toolbar-wrapper{
    display: block;
    position: fixed;
    top: 0px;
    z-index: 2;
    width: 44%;
  }

.hide-on-desktop .note-toolbar-wrapper{
    display: none;
}

div.first-container{
  margin-top: 75px !important
}

.cke_top {
    /*border-bottom: 1px solid #d1d1d1;
    background: #f8f8f8;*/
    /*padding: 6px 8px 2px;*/
    /*white-space: normal;*/
    display: block;
    position: fixed;
    top: 0px;
    z-index: 2;
    width: 44%;
}

</style>

  <script type="text/javascript">
    $(document).ready(function(){
      statementClick();
    var editor = CKEDITOR.replaceClass = '_editor';
      CKEDITOR.config.autoGrow_onStartup =  true;
      CKEDITOR.config.hidpi=true;
      CKEDITOR.config.disallowedContent = 'li{list-style-type}';
      CKEDITOR.config.extraPlugins =  ['timestamp','autogrow','liststyle'];
      
      
       //$('.first-container').find('.cke_contents').focus();

      //tool_less();
      
      $(".goal-create-details").scroll(function(){
      	set_editables_height();
      scroll = $(this).scrollTop();
      
      var k = 0;
      var j = 0;
      var m = 0;
      $.each($(".cke_contents"),function(i,e){
      	
      	
      var id = $(this).parents().parents().attr("id");
      var new_position = $(this).attr("data-position");
      
      k = k+10;
      if(id == "cke_status")
      {
      	k = 0;
      	j = 0;
      }
      ++j;
      

      if(j >= 5 && j < 8)
      {
      	m = k*(j-1);

      	m = Math.round(m/2);
       //console.log("M is Here",m);
       scroll = scroll-m;

      }

      if(j >= 9 && j < 10)
      {
      	m = k*(j-1);

      	m = Math.round(m/3);
       //console.log("M is Here",m);
       scroll = scroll-m;

      }


      if(j >= 12)
      {
      	m = k*(j-1);

      	m = Math.round(m/4);
      	//console.log("This is Greater than 12 or equal.....");
       //console.log("M is Here",m);
       scroll = scroll-m;

      }


      //console.log("j is",j);
     // console.log("scroll",scroll);     
      var _that=$(this);
      
      var height=_that.data("position");
      
      if(height > scroll){
      $(".cke_top").hide();
      _that.parent().find(".cke_top").show();
      return false;
      }
      });  

      });
      
      $(".goal-create-details .cke_contents").on("select",function(){

      var _that = $(this);
      var data =  _that.html();
      $(".cke_top").hide();
      _that.parent().find(".cke_top").show();
      set_editables_height();

      });

 });
    
      CKEDITOR.on('instanceReady', function(evt) {
      var editor = evt.editor;
      //CKEDITOR.instances['status'].focus();
      var ckediting = editor.container.$;
      $(".hide-on-desktop").find(ckediting).find(".cke_top").find(".cke_toolbox").after("<button type='button' class='btn btn-default tool-less' id='tool-less'>....</button>");
      tool_less();

      $(".cke_top").hide();
      $("#cke_status .cke_top").show();
      
      $(".cke_bottom").remove();
      editor.on('focus', function(e) {
          
          $(".sheet-action").hide();
          $(".cke_top").hide();
          var editingArea = e.editor.container.$;
          $(editingArea).find(".cke_top").show();
          
          set_editables_height();
      });

      editor.on('change', function(e) {


        saveGoal(false, true);
        
        var _that = $(this);

        console.log("That Here",_that[0].container.$);

    
        var current_sheet = $(_that[0].container.$).find(".sheets-panel").find(".sheet-container.active");

        var sheet_container=$(_that[0].container.$).find(".sheets-panel").find(".sheet_pages");

        console.log("sheet_container",current_sheet);
        save_sheet(current_sheet, sheet_container);
        
        set_editables_height();
      });


    });
  </script>


<script type="text/javascript">
  //var prefill_status = '';
  var http_referer = document.referrer;
  //console.log(http_referer);
   var xhr;
  <?php if(!isset($goals_edit->auto_save_id)){ ?>
    $("#temp_id").val(temp_id);
  <?php } ?>
   var actiontype = 'update';
   $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
   var goals_to_delete = '';
   
   function fixHasSub()
   {
     
     //console.log("fix class has_sub");
     
       // fix class has_sub
       $('div.goal-top').each(function() {
       
           var _this = $(this);
           var goal_id = _this.siblings('input[name="sub_id"]').val();
           if (_this.parent().hasClass('add-form'))
           {
             //console.log("inside if");
               var goal_state = _this.children('a.show-goal-link').data('collapse');
   
               if (goal_state)
               {
                   var b = $('#sub-goal');
   
                   b.children('ul').each(function() {
                       $(this).hide();
                   });
               }
           }
           else
           {
               //console.log("inside else");
               var goal_state = _this.siblings('input[name="sub_id"]').data('collapse');
               //console.log(goal_state);
               //console.log(_this.siblings('.sub-container'));
               //console.log(_this.siblings('.sub-container')[0]);
        
               if (_this.closest('.sub-container').children("ul").length)
               {
                   // var span_name = _this.children('span');
                   var span_name = _this.children('a.type');
                   var autosaveid=_this.closest('.sub-container').data("autosaveid");
                   // add collapse/expand icon
                   if (goal_state)
                   {
                       span_name.before('<a class="show-goal-link bg2" href="javascript:void(0);" data-id="' + goal_id + '" data-autosaveid="'+autosaveid+'"><i class="fa fa-chevron-right"></i></a>');
   
                       var b = span_name.parent().parent();
                       if (b.hasClass('add-form')) {
                           b = b.find('#sub-goal');
                       }
   
                       b.children('ul').each(function() {
                           $(this).hide();
                       });
                   }
                   else
                   {
                       span_name.before('<a class="show-goal-link bg1" href="javascript:void(0);" data-id="' + goal_id + '" data-autosaveid="'+autosaveid+'"><i class="fa fa-chevron-right"></i></a>');
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
               var auto_save_id = a.data('autosaveid');
               
              <?php if(is_admin() || !$is_default){  // users are not allowed to edit default goal?>
               $.post('/goals/state', {auto_save_id: auto_save_id, self: true}, function(data) {
                   if (data.error == 0)
                   {
                       //console.log(data.message);
                   }
               }, "json");
              <?php } ?>

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
                   b.children('ul').each(function() {
                       //console.log("hide hide");
                       $(this).hide();
                   });
                   a.removeClass('bg1');
                   a.addClass('bg2');
               }
               else if (a.hasClass('bg2')) {
                   if (a.attr('id') == 'top-goal-link') {
                       $('#sub-rep').show();
                   }
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
    if($('#checkChanged').val()!=''){
      $('#checkChanged').val('');
         var b = $("#light-box");
         var c = b.find('.modal-body');
         var f= b.find('.modal-footer');
         c.empty();
         var input = '<div class="bootbox-body">You did not save change. Do you want to save?</div>';
         c.append(input);
         f.html('<p style="text-align:center;"><a class="btn btn-success btnPopupSave" href="javascript:void(0)" style="margin-right:20px;">YES</a> <a class="btn btn-warning btnPopupNo" href="javascript:void(0)">NO</a></p>');
     
         $('#light-box .btnPopupSave').on('click', function() {
             b.modal('hide');
            saveGoal(url);
         });
     
         $('#light-box .btnPopupNo').on('click', function() {
             window.location.href = url;
         });
     
         b.modal();
     }
   }
   
   function popupReactive(target, isMain) {
       var b = $("#light-box");
       var c = b.find('.modal-body');
       var f = b.find('.modal-footer');
       c.empty();
       var input = '<div class="bootbox-body">This task is finished. Do you want to reactivate?</div>';
       c.append(input);
       f.html('<p style="text-align:center;"><a class="btn btn-success btnPopupReactive" href="javascript:void(0)" style="margin-right:20px;">REACTIVATE</a> <a class="btn btn-warning btnPopupNotReactive" href="javascript:void(0)">NO</a></p>');
       var _this = $(target);
   
       $('#light-box .btnPopupReactive').on('click', function() {
           b.modal('hide');
          <?php if(!is_admin() && $is_default){  // users are not allowed to edit default goal?>
            return false;
          <?php } ?>

           var url = '/goals/reactive_task';
   
           $.blockUI({
               message: '<img src="/img/ajax-loader.gif" />',
               css: {backgroundColor: 'transparent', border: 0}
           });
   
           var gid = _this.attr('gid');
   
           $.post(url ,{id:gid}, function(data) {
               if (data.status == 1)
               {
                  

                  var goal_top = _this.parent('div.goal-top');

                  goal_top.find(".btnReactive").remove();

                  goal_top.find(".btnTrophy").remove();

                  /*
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
                   bindEvents();

                   location.reload();
   
                   var containers = '';
                   if (isMain)
                   {
                       containers = goal_top.siblings('#sub-goal').find('.sub-container');
                   }
                   else
                   {
                       containers = goal_top.siblings('.sub-container');
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
                   _this.remove();*/

               }
               else
               {
                   alert(data.msg);
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
       var f = b.find('.modal-footer');
       c.empty();
   
       var input = '';
       var footer='';

       var trophyClassName = triggerObj.className;
        if (trophyClassName.indexOf('trophy-red') != -1){
           input = '<div class="bootbox-body">This task has already been added to your trophy room</div>';
           footer= '<p style="text-align:center;"><a class="btn btn-warning btnPopupNotReactive" href="javascript:void(0)">OK</a></p>';
        }else{
           input = '<div class="bootbox-body">This task is finished. Do you want to add to trophy?</div>';
           footer= '<p style="text-align:center;"><a class="btn btn-success btnPopupReactive" href="javascript:void(0)" style="margin-right:20px;">ADD TO TROPHY</a> <a class="btn btn-warning btnPopupNotReactive" href="javascript:void(0)">NO</a></p>';
        }
       c.append(input);
       f.html(footer);

       var _this = $(target);
   
       $('#light-box .btnPopupReactive').on('click', function() {

        <?php if(!is_admin() && $is_default){  // users are not allowed to edit default goal?>
          return false;
        <?php } ?>

           b.modal('hide');
   
           var url = '/trophy/add';
   
   
           $.blockUI({
               message: '<img src="/img/ajax-loader.gif" />',
               css: {backgroundColor: 'transparent', border: 0}
           });
   
           var gid = _this.attr('gid');
   
           $.post(url, {id: gid}, function(data) {
               if (data.status ==1)
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
       var f = b.find('.modal-footer');
       
       f.empty();

       c.empty();
       
       var input = '<div class="bootbox-body">This task is overdue. Do you want to finish or extend due date?</div>';
       c.append(input);
       
       f.append('<p style="text-align:center;"><a class="btn btn-success btnPopupFinish" href="javascript:void(0)" style="margin-right:20px;">FINISH</a> <a class="btn btn-warning btnPopupExtend" href="javascript:void(0)">EXTEND</a></p>');

       var _this = $(target);
   
       $('#light-box .btnPopupFinish').on('click', function() {
        
        <?php if(!is_admin() && $is_default){  // users are not allowed to edit default goal?>
          return false;
        <?php } ?>

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
   
                   bindEvents();
                   //console.log('ff55');
   
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
                              //console.log('3333');
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


      <?php if(!is_admin() && $is_default){  // users are not allowed to edit default goal?>
        return false;
      <?php } ?>

      $('#checkChanged').val('');
       // set detail order for goals
       var i = 2;
       $('.sub-container').each(function() {
           $(this).attr('data-order', i);
           i++;
       });
   
       var type_id = $("#main-type").attr('data-id');
       var is_show_lobby = $("#main-show-lobby").attr('data-id');
       var is_active = $("#main-active").attr('data-id');
       var due_date = $("input[name='due_date']").val();
       var habit_start_date = $("input[name='habit_start_date']").val();
       var habit_type =$("#main_habit_type").attr("data-habit-type");// $('input[name="main_habit_type"]').val();
       var add_text_type =$("#add_main_text_type").val();
       var name = $("input[name='name']").val();

       setTimeout(function(){
        var status = CKEDITOR.instances.status.getData();
        var improvement = CKEDITOR.instances.improvement.getData();
        var risk = CKEDITOR.instances.risk.getData();
        var benefits = CKEDITOR.instances.benefits.getData();
        var vision = CKEDITOR.instances.vision.getData();
        var vision_decades = CKEDITOR.instances.vision_decades.getData();
        var barriers = CKEDITOR.instances.barriers.getData();
        var priority = CKEDITOR.instances.priority.getData();
        var initiative = CKEDITOR.instances.initiative.getData();
        var help = CKEDITOR.instances.help.getData();
        var support = CKEDITOR.instances.support.getData();
        var environment = CKEDITOR.instances.environment.getData();
        var imagery = CKEDITOR.instances.imagery.getData();


       var auto_save_id=$("#temp_id").val();
       var is_default = $("#is_default").val();

       var order = 1;
        
        //console.log(redirect_url, autosave);

      var error=false;
       if (type_id == 2)
       {
           if (!due_date && redirect_url)
           {
               alert("Please choose task's due date!");
               scrollToElement("input[name='due_date']");
               error=true;
               return false;
           }
       }
   
       if (type_id == 1)
       {
           if (!habit_start_date && redirect_url)
           {
             error=true;
               alert("Please choose habit's start date!");
               scrollToElement("input[name='habit_start_date']");
               return false;
           }
       }
   
        if(!name && redirect_url)
          {
             error=true;
             alert("Please enter goal's name!");
             scrollToElement("input[name='name']");
             return false;
          }
        
        var allfiled=$("input[name='sub_name']").filter(function () {
              return $.trim($(this).val()).length == 0
            }).length;

        if(allfiled >0 && redirect_url){
           error=true;
            alert("Please Enter Goal Name.");
            return false;
        }

        if(error){
          return false;
        }

       var data = {
          type_id: type_id,
          due_date: due_date,
          habit_start_date: habit_start_date,
          habit_type: habit_type,
          add_text_type:add_text_type,
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
          is_default:is_default,
           //goals_to_delete: goals_to_delete,
          auto_save_id:auto_save_id,
       };
   
       var check_sub = true;
       var goals_list = $('#sub-goal').attr('data-list');
     
      data.childs=get_goal_hierarchy();

    //console.log(data);
       
        if(!autosave){
           $.blockUI({
               message: '<img src="/img/ajax-loader.gif" />',
               css: {backgroundColor: 'transparent', border: 0}
           });
        }
   
      var goal_id=$("#_goal_id").val();
   
       
      var url = "{{url('goals/create')}}";
        
        if(xhr && xhr.readyState != 4){
            xhr.abort();
        }

        //return false;
        //console.log(http_referer);

        xhr = $.ajax({
            url: url,
            data:data,
            method:"POST",
            success: function(response) {
                //do something

                if (response.status == 1)
                {
                   $("#_goal_id").val(response.gid);

                   var download_id = $("#_goal_id").val();
                   if(download_id == 'false')
                   {
                    var download_url = "#";
                   }
                   else
                   {
                    var download_url = site_url+'/getActiveSheet/'+download_id;
                   }
                   $("#btnDownload").attr("href",download_url);
                   
                   $('#checkChanged').val('');
                   // window.location.href = '/list';
                   if(redirect_url){
                       window.location.href = http_referer;
                       //window.location.reload(http_referer);
                   }
                }

            }
        });

        xhr.abort();
       },0);
       
       /*var status = $("textarea[name='status']").val();
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
       var imagery = $("textarea[name='imagery']").val();*/



        /*
        $.post(url, data, function(response) {
           
           if (response.status == 1)
           {
               $("#_goal_id").val(response.gid);
               
               $('#checkChanged').val('');
               // window.location.href = '/list';
               if(redirect_url){
                   window.location.href = redirect_url;
               }
           }
        }, "json");
        */
   }
   
    jQuery(window).bind("load", function() {
       $('textarea').autosize();
    });
   
    function get_goal_hierarchy(){
      
      var _elements=new Array();
      
      $("#sub-goal").children(".sub-container").each(function () {

        var $currentElement = $(this);
        var _element={};

         _element.level = $currentElement.parent().attr('data-level') + 1;
         // goal.parent        = _this.data('pid');
         _element.parent = $currentElement.parent().attr('id');
         _element.detail_order = $currentElement.attr('data-order');
         _element.type_id = $currentElement.children('.goal-top').children('a.type').attr('data-id');
         _element.is_show_lobby = $currentElement.children('.goal-top').children('a.lobby').attr('data-id');
         _element.is_active = $("#main-active").attr('data-id');//$currentElement.children('.goal-top').children('a.status').data('id');
         _element.is_default = $("#is_default").val();
         _element.id = $currentElement.children("input[name='sub_id']").val();
         _element.auto_save_id = $currentElement.children('.goal-top').children("input[name='auto_save_id']").val();
         _element.name = $currentElement.children("input[name='sub_name']").val();
         _element.add_text_type = $currentElement.children('.goal-top').children('input[name="add_text_type"]').val();
         //console.log(_element.type_id);
          if (_element.type_id == 2)
          {
           _element.due_date = $currentElement.children('.goal-top').children("input[name='sub_due_date']").val();
          }

          if (_element.type_id == 1)
          {
           _element.habit_start_date = $currentElement.children('.goal-top').children("input[name='sub_habit_start_date']").val();
           _element.habit_type = $currentElement.children('.goal-top').children('#sub_habit_type').attr("data-habit-type");

           _element.scale_type = $currentElement.children('.goal-top').children('#sub_habit_type').attr("data-scale");
           _element.lowest = $currentElement.children('.goal-top').children('#sub_habit_type').attr("data-min");
           _element.highest = $currentElement.children('.goal-top').children('#sub_habit_type').attr("data-max");
           _element.is_apply = $currentElement.children('.goal-top').children('#sub_habit_type').attr("data-is_apply");
          }
             
          if($currentElement.children("ul").length>0){
              _element.childs=recursiveEach($currentElement);
          }  

        _elements.push(_element);
      });
      
      return _elements;
    }
   
    var recursiveEach = function($element){

        var _elements=new Array();
    
        $element.children("ul").children("li.sub-container").each(function () {
            
          var $currentElement = $(this);
          var _element={};
          _element.level = $currentElement.parent().parent().attr('data-level') + 1;
          _element.parent = $currentElement.parent().parent().attr('id');
          _element.detail_order = $currentElement.attr('data-order');
          _element.type_id = $currentElement.children('.goal-top').children('a.type').attr('data-id');
          _element.is_show_lobby = $currentElement.children('.goal-top').children('a.lobby').attr('data-id');
          _element.is_active = $("#main-active").attr('data-id');//$currentElement.children('.goal-top').children('a.status').data('id');
          _element.is_default = $("#is_default").val();
          _element.id = $currentElement.children("input[name='sub_id']").val();
          _element.auto_save_id = $currentElement.children('.goal-top').children("input[name='auto_save_id']").val();
          _element.name = $currentElement.children("input[name='sub_name']").val();
         
          _element.add_text_type = $currentElement.children('.goal-top').children('input[name="add_text_type"]').val();
         //console.log(_element.type_id);
          if (_element.type_id == 2)
          {
           _element.due_date = $currentElement.children('.goal-top').children("input[name='sub_due_date']").val();
          }

          if (_element.type_id == 1)
          {
           _element.habit_start_date = $currentElement.children('.goal-top').children("input[name='sub_habit_start_date']").val();
           _element.habit_type = $currentElement.children('.goal-top').children('input[name="sub_habit_type"]').val();
           _element.scale_type = $currentElement.children('.goal-top').children('#sub_habit_type').attr("data-scale");
           _element.lowest = $currentElement.children('.goal-top').children('#sub_habit_type').attr("data-min");
           _element.highest = $currentElement.children('.goal-top').children('#sub_habit_type').attr("data-max");
           _element.is_apply = $currentElement.children('.goal-top').children('#sub_habit_type').attr("data-is_apply");
          }
             
          if($currentElement.children("ul").length>0){
                _element.childs=recursiveEach($currentElement);
          }  
          _elements.push(_element);
      
        });

        return _elements;
    };
  
    function windowloadaleart(action) {

       window.onbeforeunload = function() {

           if($('#checkChanged').val()!=''){
              $('#checkChanged').val("");
             var url = $('ul.nav li a').attr('href');
             setTimeout(function() {
                 if (action != '1') {
                     popupNotSaved(url);
                 }
             }, 2000);
             return false;
         }
       }
    }
   
   
   $(document).ready(function() {

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
               //$(this).addClass("picker__input--active");
               //$('.picker').addClass("picker--opened picker--focused");
           }
           
       });
      $('.habit-start-date').on( "mouseover",function(){
        $(this).focus();
      });

      $('.habit-start-date').on("mouseleave",function(){
        $(this).blur();
      })

      
      $('#toggle-editor').change(function() {
         if($(this).prop('checked')){
            $(".note-toolbar-wrapper").show();
         }else{
             $(".note-toolbar-wrapper").hide();
         }
      });

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
      
       var issavedevent = $('#checkChanged').val();
   
       //console.log('issavedevent', issavedevent);
   
   
       $('input,textarea').on('keypress', function() {
           var action = 1;
           windowloadaleart(action);
       });


      
/*       var savexhr;

      var editor = $("._editor").summernote({
          tooltip: false,
          toolbar: [
            // [groupName, [list of button]]
            ['custom', ['sheet']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['style', ['style','bold', 'italic', 'underline', 'clear', 'fontname']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']]
          ],
          fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48' , '64', '82', '150'],
          callbacks: {
            onFocus: function(a,b,c) {
              
              $(".note-toolbar-wrapper").hide();
              $(a.target).parent().parent().find(".note-toolbar-wrapper").show();
            }
          },
          sheet:{
            addSheet: function(sheet_data){
              
             // console.log("sheet added..");
             // console.log(sheet_data);
              if(sheet_data.attr=='status'){

                var container = $(editor[0]).parent();
                
                var prefill_status = $("#prefill_status").html();
                
                console.log(prefill_status);

                container.find(".note-editable").html(prefill_status);
                //console.log(editor[0]);
              }

            },
            deleteSheet: function(sheet_data){
              
              //console.log("sheet deleted...");
              
              var auto_save_id = $("#temp_id").val();
                
                sheet_data.auto_save_id=auto_save_id;


                var xhr = $.ajax({
                    url: "{{url('goals/attr/delete_sheet')}}",
                    data:sheet_data,
                    method:"POST",
                    success: function(response) {

                    }
                });

              //console.log(sheet_data);
            },
            saveSheet : function(sheet_data){
              var savexhr;
              var auto_save_id = $("#temp_id").val();

              //console.log("sheet saved...");
              
              sheet_data.auto_save_id=auto_save_id;

              //console.log(sheet_data);

              if(savexhr && savexhr.readyState != 4){
                  savexhr.abort();
              }

              savexhr = $.ajax({
                 
                  url: "{{url('goals/save_attributes')}}",

                  data:sheet_data,
                  
                  method:"POST",
                  
                  success: function(response) {
                      //do something
                  }
              });
              //console.log(savexhr);

            },
            renameSheet : function(sheet_data){

              var savexhr;

              var auto_save_id = $("#temp_id").val();

              //console.log("sheet renamed...");
              
              sheet_data.auto_save_id=auto_save_id;

              //console.log(sheet_data);

              if(savexhr && savexhr.readyState != 4){
                  savexhr.abort();
              }

              var savexhr = $.ajax({
                 
                  url: "{{url('goals/attr/rename_sheet')}}",

                  data:sheet_data,
                  
                  method:"POST",
                  
                  success: function(response) {
                      //do something
                  }
              });
            },
            getSheet : function(sheet_data, callback){
                
                //console.log("getting sheet..");
                console.log(sheet_data);

                var auto_save_id = $("#temp_id").val();

                sheet_data.auto_save_id=auto_save_id;


                var xhr = $.ajax({
                    url: "{{url('goals/attr/get_sheet')}}",
                    data:sheet_data,
                    method:"POST",
                    success: function(response) {
                      
                      if(response.status==1){
                        callback(response.data);
                      }

                    }
                });
            },

            duplicateSheet: function(sheet_data, callback){

               // console.log("duplicating sheet..");

                var auto_save_id = $("#temp_id").val();

                sheet_data.auto_save_id=auto_save_id;
                
               // console.log(sheet_data);

                var xhr = $.ajax({
                    url: "{{url('goals/attr/duplicate_sheet')}}",
                    data:sheet_data,
                    method:"POST",
                    success: function(response) {
                      
                      if(response.status==1){
                        callback(response.data);
                      }

                    }
                });

            }
          }

        });

      editor.on('summernote.change', function(we, contents, $editable) {
        saveGoal(false, true);
        set_editables_height();
      });*/



     

      $(window).scroll(function(){
      tool_less();
      var first_container = $(".first-container");
      var sticky = $(".mobile-view .note-toolbar-wrapper"),
      scroll = $(window).scrollTop();

      var first_container_height=(first_container.offset().top)-190;

      if(scroll >= first_container_height){
      //sticky.addClass('fixed');
      sticky.css('position','fixed');
      $(".note-toolbar").css("position","");
      $(".note-toolbar").css("position","relative");
      $(".note-toolbar").css("top",0);
      $(".note-toolbar").css("width","100%");
      }else{
      //sticky.removeClass('fixed');
      sticky.css('position','');
      $(".note-toolbar").css("position","relative");
      $(".note-toolbar").css("top",0);
      $(".note-toolbar").css("width","100%");
      } 



      });



      <?php if(!is_admin() && $is_default){ ?>
      /*$('._editor').each(function( index ) {
      $(this).summernote('disable');
      });*/

	    CKEDITOR.config.readOnly = true;


      <?php } ?>
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

      $('#btnSubmit').click(function() {
      saveGoal("/list", false);
      });

      /*$('#saveandclose').click(function() {
      window.onbeforeunload = function() {

      }

      saveGoal('/list');
      });*/

      (function(){
      // do some stuff

      saveGoal(false, true);
      setTimeout(arguments.callee, 6000);
      })();

      $('.remove-goal').unbind('touchstart click').bind('touchstart click', function() {

      <?php if(!is_admin() && $is_default){  // users are not allowed to edit default goal?>
      return false;
      <?php } ?>

      if (!confirm('Are you sure you want to delete this item?'))
      return false;

      var _this = $(this);
      //$('#checkChanged').val(1);
      //var action = 1;
      //windowloadaleart(action);
      var autosaveid = _this.data('autosaveid');


      // console.log(_this.attr('id'));

      if (_this.attr('id') == 'remove-main')
      {
      $.blockUI({
      message: '<img src="/img/ajax-loader.gif" />',
      css: {backgroundColor: 'transparent', border: 0}
      });

      var _this = $(this);


      $.post('/goals/delete', {auto_save_id: autosaveid, type: 'list'}, function(data) {
      if (data.status == 1)
      {

      
      //window.location.href = '/list';
      //window.history.back();
      }
      else
      {
      alert('ERROR!');
      }
      }, "json");
      }
      else
      {
      var sub_container = _this.parents('.sub-container').first();

      //console.log(autosaveid);

      $(sub_container).fadeOut('slow');

      $.post('/goals/delete', {auto_save_id: autosaveid}, function(data) {
      if (data.status == 1)
      {
      sub_container.remove();
      //fixHasSub();
      //window.location.href = '/list';
      }
      else
      {
      //alert('ERROR!');
      }
      });
      }
      });

      bindEvents();

      focus_input();
      });

      </script>    <!-- </div>
      </div> -->

      <script type="text/javascript">
      var tool_less = function()
      {
      $(".tool-less").unbind("click").bind("click",function(){

      if($(".cke_top").hasClass("tool__editor"))
      {
      $(".cke_top").removeClass("tool__editor");
      }
      else
      {
      $(".cke_top").addClass("tool__editor");
      }

      if($(".content").attr("id") == "tool-content")
      {
      $("#tool-content").css("margin-top","");
      $(".content").attr("id","");

      }
      else
      {
      $(".content").attr("id","tool-content");
      $("#tool-content").css("margin-top","70px");
      }

      });
      }



      var sheetFocus = function()
      {
      $(".note-editable").on("click",function(){
      var elem = $(this).next();
      var sheet = elem.find(".sheet-container").hasClass("active");
      if(!sheet)
      {
      elem.find(".sheet-container:last").addClass("active");
      }
      });
      }

      </script>
      <script>
      $(document).ready(function () {
      /** ******************************
      * Collapse Panels
      * [data-perform="panel-collapse"]
      ****************************** **/
     var closeBtn = function(){
        $('.closeSubmit').on("click",function() {
          window.location.href = "/list";
        });
     }

     closeBtn();
      

      sheetFocus();

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


      $(window).resize(function() 
      {
      var height = $(".panel-heading").height();
      if(height > 35 && height !=null)
      {
      $(".first-container").css("margin-top","");
      $(".first-container").css("margin-top",60);

      }
      else
      {
      $(".first-container").css("margin-top","");
      $(".first-container").css("margin-top",55);
      }

      //$(".note-toolbar-wrapper").css("width","");
      //$(".note-toolbar-wrapper").css("width",width);

      }).resize();

      });
      </script> 

      <script type="text/javascript">
      // $(document).ready(function(){

      //     $(".note-editable").unbind("paste").bind("paste", function(e){

      //      $(this).one("focusout", function(){

      //         var pastedData = $(this).html();

      //         /*if (/<!--StartFragment-->([^]*)<!--EndFragment-->/.test(pastedData)) {*/
      //          //console.log(pastedData);
      //         var result = pastedData.replace(/<p class="MsoNormal">([\s\S]*?)<\/p>/g, "<p>$1</p>\n");
      //         // Fix titles
      //         result = result.replace(/<p class="MsoTitle">([\s\S]*?)<\/p>/g, "## $1");
      //         var $desc = $('<div>' + result + '</div>');
      //         $desc.contents().each(function() {
      //           if (this.nodeType == 8) { // nodeType 8 = comments
      //             $(this).remove();
      //           }
      //         });

      //           var firstItems = $desc.find('p').filter(function() {
      //           return /MsoList.*?First/g.test(this.className);
      //         });

      //         var lists = [];

      //         firstItems.each(function() {
      //           lists.push($(this).nextUntil('.MsoListParagraphCxSpLast').addBack().next().addBack());
      //         });

      //         // Add lists with one item
      //         lists.push($desc.find(".MsoListParagraph"));

      //         // Change between ordered and un-ordered lists
      //         if (lists.length != 0) {
      //           lists.forEach(function(list) {
      //             if (list.length > 0) {
      //               if (/[\s\S]*?(Symbol|Wingdings)[\s\S]*?/.test(list.html()))
      //                 var unordered = true;
      //               else if (/[^0-9]/.test(list.text()[0]))
      //                 var unordered = true;

      //               list.each(function() {
      //                 if (/[\s\S]*?level[2-9][\s\S]*/.test(this.outerHTML))
      //                   var nested = true;

      //                 var $this = $(this);
      //                 if (unordered)
      //                   var newText = $this.text().replace(/[^0-9]([\s\S]*)/, "$1");
      //                 else {
      //                   var newText = $this.text().replace(/[0-9]*?[0-9](\.|\))([\s\S]*)/, "$2");
      //                 }

      //                 $this.html(newText);
      //                 if (nested) {
      //                   if (unordered)
      //                     $this.wrapInner('<ul><li>');
      //                   else
      //                     $this.wrapInner('<ol><li>');
      //                 }

      //               });
      //               list.wrapInner('<li>');
      //               if (unordered)
      //                 list.wrapAll('<ul>');
      //               else
      //                 list.wrapAll('<ol>');
      //               // Filter to make sure that we don't unwrap nested lists
      //               list.find('li').filter(function() {
      //                 return this.parentNode.tagName == 'P'
      //               }).unwrap();
      //             }
      //           });
      //         }
      //         out = $desc.html();
      //        // regex = /<li[^>]&nbsp;/g;
      //         out = out.replace(/&nbsp;/g, '');
      //         //out = out.replace(/<\/?span[^>]*>/g,"");
      //         var tS = new RegExp('<(/)*(meta|link|\\?xml:|st1:|o:|font)(.*?)>', 'gi');
      //         out = out.replace(tS, '');
      //         out = out.replace(/<p[^>]<p[\/^>]>/g, '');
      //         /*var badAttributes = ['start'];
      //      for (var i=0; i< badAttributes.length; i++) {
      //        var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
      //        out = out.replace(attributeStripper, '');
      //      }*/
      //         $(this).html("");
      //         $(this).html(out);
      //       //}

      //     });

      // });

      // });
      </script>

      <script>
          var set_editables_height = function(){
          var total_height=0;

          $(".cke_contents").each(function(){
          total_height+=$(this).height()+20;
          var hieght = $(this).attr("data-position", total_height);

          });  


          };

          var deleteSheet= function(sheet_data){

          //console.log("sheet deleted...");

          var auto_save_id = $("#temp_id").val();

          sheet_data.auto_save_id=auto_save_id;


          var xhr = $.ajax({
          url: "{{url('goals/attr/delete_sheet')}}",
          data:sheet_data,
          method:"POST",
          success: function(response) {

          }
          });

          //console.log(sheet_data);
          };

          var saveSheet = function(sheet_data){

            var savexhr;

          var auto_save_id = $("#temp_id").val();

          console.log("sheet saved...",sheet_data);

          sheet_data.auto_save_id=auto_save_id;

          //console.log(sheet_data);

          if(savexhr && savexhr.readyState != 4){
          savexhr.abort();
          }

          savexhr =  $.ajax({

          url: "{{url('goals/save_attributes')}}",

          data:sheet_data,

          method:"POST",

          success: function(response) {
          //do something
          }
          });
          //console.log(savexhr);

          };

          var renameSheet = function(sheet_data){
          var savexhr;
          var auto_save_id = $("#temp_id").val();

          //console.log("sheet renamed...");

          sheet_data.auto_save_id=auto_save_id;

          //console.log(sheet_data);

          if(savexhr && savexhr.readyState != 4){
          savexhr.abort();
          }

          savexhr = $.ajax({

          url: "{{url('goals/attr/rename_sheet')}}",

          data:sheet_data,

          method:"POST",

          success: function(response) {
          //do something
          }
          });
          };
          var getSheet = function(sheet_data, callback){
            var savexhr;
          //console.log("getting sheet..");
          var auto_save_id = $("#temp_id").val();

          sheet_data.auto_save_id=auto_save_id;

          if(savexhr && savexhr.readyState != 4){
             savexhr.abort();
          }

          savexhr = $.ajax({
          url: "{{url('goals/attr/get_sheet')}}",
          data:sheet_data,
          method:"POST",
          success: function(response) {

          if(response.status==1){
          callback(response.data);
          }

          }
          });
          };

          var duplicateSheet = function(sheet_data, callback){
            
          // console.log("duplicating sheet..");

          var auto_save_id = $("#temp_id").val();

          sheet_data.auto_save_id=auto_save_id;

          // console.log(sheet_data);

          var xhr = $.ajax({
          url: "{{url('goals/attr/duplicate_sheet')}}",
          data:sheet_data,
          method:"POST",
          success: function(response) {

          if(response.status==1){
          callback(response.data);
          }

          }
          });

          }


          var save_sheet = function(sheet, sheet_pages){

          var attribut = sheet_pages.parents("header:first").find("._editor").attr("id");
          
          var content = CKEDITOR.instances[''+attribut+''].getData();
          
          //var note_editable=sheet_pages.parents(".cke").find(".cke_contents");

          //var content = note_editable.html();
          
          if(last_sheet_data=sheet.data()){
            var _attr = sheet_pages.data("attr");
            last_sheet_data.html=content;
            last_sheet_data.attr=_attr;
            saveSheet(last_sheet_data);

          }
      };

      var getLastSheet = function(sheet_id, callback){

          var auto_save_id = $("#temp_id").val();
          
          var attr = "status";

          var xhr = $.ajax({
          url: "{{url('goals/attr/lastsheet')}}",
          data:{sheet_id:sheet_id,auto_save_id:auto_save_id,attr:attr},
          method:"POST",
          success: function(response) {

          if(response.status==1){
          callback(response.data);
          }

          }
          });

          }


          var statementClick = function(){
            $(".statements_values").unbind("click").bind("click",function(){
              var goal_id = $("#_goal_id").val();
              if(goal_id == "" || goal_id == undefined || goal_id == 0 || goal_id == "false"){
                alert("Please Enter Your Goal.");
                return false;
              }else{
                window.location.href = "/edit/statements/"+goal_id;
              }
            })
          }


      var set_scroll = function(note_editable){

        var position = $("#"+note_editable+"-container").offset().top;
        var div_height = $("#"+note_editable+"-container").height();
        
        var scroll = $(".goal-create-details").scrollTop();
       
        if(parseInt(position) > parseInt(scroll) && parseInt(position) > 45)
        {
            position = position+scroll-75;
           $(".goal-create-details").animate({ scrollTop:position}, 1000); 
        }
        else if(position < scroll && position > 45 && scroll > position)
        {
            new_position = position+scroll-75;
           $(".goal-create-details").animate({ scrollTop:new_position}, 1000); 
        }
        else if(position < scroll && position < 0 && scroll > 0)
        {
               new_position = scroll+position-75; 
               $(".goal-create-details").animate({ scrollTop:new_position}, 1000);   
        }
        else
        {
          $(".goal-create-details").animate({ scrollTop:scroll}, 1000);  
        }
      
};


      </script>

      @endsection