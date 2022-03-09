<style type="text/css">
/*.nav-pills>li.active>a,a:hover{
    color: #fff;
    background-color: #7ed320;
}*/
.picker__holder{
  overflow-y:visible; 
}
/*.nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover{
    color: #fff;
    background-color: #7ed320 !important;
}*/
.input-group {
    position: relative;
    display: table;
    width: 100%;
    float: none;
    margin: 0 0 0 0em;
    border-collapse: separate;
}
</style>
<header class="header clearfix">
    <h1 class="header_h1 header-character">Character</h1>
     <a href="#" class="addLobby" data-toggle="modal" data-target="#addLobby" data-backdrop="static" data-keyboard="false"><span class="pull-right" title="Add From Lobby" style="font-size: 39px;color: #ffffff;padding-right: 20px;">&#43;</span></a>
</header>

<ul class="character-list halfheightsec" id="character">
    <?php if($characters && !empty($characters)){

        foreach ($characters as $key => $character) {
    ?>
        <li class="main-li clearfix " id="item_<?php echo $character->id?>">
            <div class="arrows-holder">
                <a class="arrow-down movedown character-down-0 btnDown btn-down" index="0" gid="<?php echo $character->id?>" href="javascript:void(0);">
                    <i class="lnr lnr-move"></i></a>
            </div>
            <h2 class="character-content-div list-name overflow-ellipsis-nowrap">
              <?php $url = ($character->top_parent_id)?URL("edit/".$character->top_parent_id."#".$character->id):URL("edit/".$character->id); ?>
                <a href="{{$url}}"><?php echo $character->name?></a> </h2>
        </li>
    <?php } 
  
    }
    ?>
     <?php 
    if($statements && !empty($statements)){
        foreach ($statements as $key => $statement) {
    ?>

     <li class="main-li clearfix " id="item_<?php echo $statement->id?>">
            <div class="arrows-holder">
                <a class="arrow-down movedown character-down-0 btnDown btn-down" index="0" gid="<?php echo $statement->id?>" href="javascript:void(0);">
                    <i class="lnr lnr-move"></i></a>
            </div>
            <h2 class="character-content-div list-name overflow-ellipsis-nowrap">
              <?php $url = ($statement->goal_id)?URL("edit/statements/".$statement->goal_id):URL("#"); ?>
                <a href="{{$url}}">{!! $statement->meta_value !!}</a> </h2>
        </li>
    <?php } 
  
    }
    ?>
</ul>
    <!-- Modal -->
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
                    <button type="button" name="att" id="week"  data-value="1" class="btn btn-primary active">7 days/week</button>
                    <button type="button" name="att" id="routine" data-value="3" class="btn btn-primary">Routine</button>
                    </div>
                    <input type="hidden" name="habitSchedule" value="1" id="habitSchedule">
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


         <div id="type_box" class="modal" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body"><div class="btn-group"><button type="button" name="att" class="btn type-button btn-primary active" data-id="0">Undefined</button><button type="button" name="att" class="btn type-button btn-primary" data-id="1">Habit</button><button type="button" name="att" class="btn type-button btn-primary" data-id="2">Task</button><button type="button" name="att" class="btn type-button btn-primary" data-id="3">Character</button></div></div>
                <div class="modal-footer"></div>
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

  <script type="text/javascript">
    $(document).ready(function(){
      $("#scale-template-cancel").click(function(e){
          $("#choose_help").modal('hide');
        });
      });
  </script>