<header class="header clearfix" id="header-id">
<div class="p-left">
    <h1 class="header_h1 habits-header m-header-mobile">HABITS</h1>
</div>

<?php if(isset($days) && count($days)){ 
    $today=date("Y-m-d");
    
    $current_week=array_key_exists($today,$days)?true:false;

    $current_week_class=array_key_exists($today,$days)?"":"timetable_regular";
   ?>
    <div class="p-right habits-right-date <?php echo $current_week_class;?>">
        <?php foreach($days as $sdate=>$day){
            
            if($sdate==$today){
            ?>
            <div class="timetable m-timetable_active">
                <div class="timetable__weekday m-timetable__weekday_active">Today</div>
            </div>
        <?php }else{?>
            <div class="timetable">
                <div class="timetable__weekday"><?php echo $day['day']?></div>
                <div class="timetable__date"><?php echo $day['date']?></div>
            </div>
        <?php } } ?>

        
    </div>

    <ul class="slider-arrows">
        <li><a href="javascript:;" data-date="<?php echo key($days);?>" class="left-arrow prev-week" title="Previous"></a></li>
        <li><a href="javascript:;" data-date="<?php echo date("Y-m-d");?>" class="today <?php if($current_week){ echo "hide"; } ?>" title="Today">Today</a></li>
        <li><a href="javascript:;" data-date="<?php end($days); echo key($days);?>" class="right-arrow next-week" title="Next"></a></li>
    </ul>

<?php } ?>

</header>

<ul class="habit-list halfheightsec" id="habits"> 
<?php 
if($habits && !empty($habits)){

    foreach ($habits as $key => $habit) {
    ?>
<li class="main-li clearfix media-main-li" id='item_<?php echo $habit->id;?>' data-id="<?php echo $habit->id;?>" data-habit="<?php echo $habit->id;?>">
    <div class="sort-div padding-right-350">
        <div class="arrows-holder">
            <a class="arrow-down movedown habit-down-0 btnDown btn-down" index="0" gid="<?php echo $habit->id;?>" href="javascript:void(0);">
                <i class='lnr lnr-move'></i>
            </a>
        </div>

        <h2 class="list-name">
            <div class="overflow-ellipsis-nowrap name-min-height isDesktop">
            <?php  $edit_goal=($habit->top_parent_id)?URL('edit/'.$habit->top_parent_id.'#'.$habit->id):URL('edit/'.$habit->id)?>
            <a href="{{$edit_goal}}"><?php echo $habit->name;?></a> </div>
            <div class="habits__process">(<?php echo isset($habit->percentage)?$habit->percentage->completed_days:0?>/<?php echo isset($habit->percentage)?$habit->percentage->total_days:0?>)</div><input type="text" name="_date" value="<?php echo $habit->habit_start_date; ?>" class="_habit_datepicker" id="_habit_datepicker_{{$habit->id}}" data-id="{{$habit->id}}">
        </h2>

    </div>
    

    <div class="m-sec">
        <form>
        <?php if(isset($habit->days) && !empty($habit->days)){
        //echo "<pre/>";
        //print_r($habit);die;
        ?> 
            <ul class="habit-checkbox-container  media-habits-cb-group <?php echo $current_week_class;?>">
                <?php 
                foreach ($habit->days as $date => $_day) { 

                $log_value=( (isset($_day['log']) &&  isset($_day['log']->value) && $date >= $habit->habit_start_date)  )  ? $_day['log']->value:"";

                if(isset($habit->habit_type) && $habit->habit_type->is_scale == 1)
                {
                    if(isset($habit->habit_type) && $log_value==0 && $habit->habit_type->value!=7){
                    /*$habit_type_value=explode(",", $habit->habit_type->value);
                    if(!in_array($_day['day_of_week'], $habit_type_value)){
                         $log_value= -1;
                    }*/


                    $habit_type_value = (isset($habit->habit_type->value) && $habit->habit_type->value != "") ? explode(",", $habit->habit_type->value):"";
                    // echo "<pre/>";
                     //print_r($habit_type_value);
                     if($habit_type_value != "")
                     {

                        if(!in_array($_day['day_of_week'], $habit_type_value)){
                             $log_value=-1;
                        }
                    }
                    else
                    {
                       $log_value=-1; 
                    }

                }

                if($date < $today && $date >= $habit->created_at  && $log_value == "")
                {
                    $log_value = 0;
                    setZeroValue($habit->id,$date,$log_value,$habit->habit_type->is_scale,$habit->habit_type);
                }
                
                ?>
                <li class="habit-checkbox checkbox-li first-checkbox <?php echo ($date==date("Y-m-d"))?'current_date':''; ?>">
                    <input type="number" name="number" class="number scale-number" data-min="<?php echo (isset($habit->habit_type) && $habit->habit_type->minimum)?$habit->habit_type->minimum:0;?>" data-max="<?php echo (isset($habit->habit_type) && $habit->habit_type->maximum)?$habit->habit_type->maximum:0;?>" min="<?php echo (isset($habit->habit_type) && $habit->habit_type->minimum)?$habit->habit_type->minimum:0;?>" max="<?php echo (isset($habit->habit_type) && $habit->habit_type->maximum)?$habit->habit_type->maximum:0;?>" data-value="" data-is_apply="<?=$habit->habit_type->is_apply?>" data-scale="<?php echo (isset($habit->habit_type) && $habit->habit_type->is_scale)?$habit->habit_type->is_scale:0;?>" gid="<?php echo $habit->id;?>" gdate="<?php echo $date;?>"  value="<?php echo ($log_value=='-1')?"":round($log_value, 2); ?>" placeholder="<?php echo ($log_value==-1)?"N/A":""?>" />
                    <label for="checkbox-0"></label>
                </li>
                <?php }else{ 

                            
                    $log_value=( (isset($_day['log']) &&  isset($_day['log']->value) && $date >= $habit->habit_start_date)  )  ? $_day['log']->value:0;
                    if(isset($habit->habit_type) && $log_value >=0 && $habit->habit_type->value!=7){
                     $habit_type_value = (isset($habit->habit_type->value) && $habit->habit_type->value != "") ? explode(",", $habit->habit_type->value):"";
                    // echo "<pre/>";
                     //print_r($habit_type_value);
                     if($habit_type_value != "")
                     {
                         if(!in_array($_day['day_of_week'], $habit_type_value)){
                             $log_value=2;    
                        }
                    }
                    else
                    {
                       $log_value=2; 
                    }
                }
                
                    ?>   

                    <li class="habit-checkbox checkbox-li first-checkbox <?php echo ($date==date("Y-m-d"))?'current_date':''; ?>">
                    <input type="checkbox" id="checkbox-<?php echo $habit->id;?>-0" data-scale="<?php echo (isset($habit->habit_type) && $habit->habit_type->is_scale)?$habit->habit_type->is_scale:0;?>" data-value="<?php echo $log_value; ?>" class="regular-checkbox habits__checkbox past-checkbox <?php echo ($log_value==2)?"notavailable":""?>" gid="<?php echo $habit->id;?>" gdate="<?php echo $date;?>"  <?php echo $log_value?"checked":""?>/>
                    <label for="checkbox-<?php echo $habit->id;?>-0"></label>
                </li>

                <?php } }  ?>
                
            </ul>
        <?php } ?>
        </form>
    </div>
    <div class="habit-content-div overflow-hidden">

        <div class="habbits__bar">
            <?php
            if(isset($habit->habit_type) && $habit->habit_type->is_scale == 0)
            {
                ?>
            <div data-toggle="modal" data-pid="<?php echo $habit->id;?>" data-target="#myModal" data-class="isDesktop" class="change-badge habits__process-percent <?php echo $habit->percentage->badge?>" data-scale=""><?php echo $habit->percentage->percentage;?>%</div>
            <?php
            }
            else if(isset($habit->habit_type) && $habit->habit_type->is_scale == 1)
            {
                ?>
            <div data-toggle="modal" data-pid="<?php echo $habit->id;?>" data-target="#myLinechartModal" data-class="isDesktop" class="change-badge habits__process-percent <?php echo $habit->percentage->badge?>" gdate="<?php echo $habit->habit_start_date; ?>"><?php echo $habit->percentage->percentage;?> avg</div>
           <?php }else{ ?>
            <div data-toggle="modal" data-pid="<?php echo $habit->id;?>" data-target="#myModal" data-class="isDesktop" class="change-badge habits__process-percent <?php echo $habit->percentage->badge?>" data-scale=""><?php echo $habit->percentage->percentage;?>%</div>
           <?php }?>
            <div class="mob-checkbox-div">
                <input type="checkbox" class="mob-checkbox">
            </div>
            <a class="calendar habit-datepicker" gid="<?php echo $habit->id;?>" data-scale="<?php echo (isset($habit->habit_type) && $habit->habit_type->is_scale)?$habit->habit_type->is_scale:0;?>" data-max="<?php echo (isset($habit->habit_type) && $habit->habit_type->maximum)?$habit->habit_type->maximum:0;?>" data-is_apply="<?php echo (isset($habit->habit_type) && $habit->habit_type->is_apply)?:0;?>" data-min="<?php echo (isset($habit->habit_type) && $habit->habit_type->minimum)?$habit->habit_type->minimum:0;?>" href="javascript:void(0);">
                <img src="./themes/keyhabits/images/calendar.svg" alt="" class="cal">
            </a>

        </div>
        
        
    </div>

</li>
<?php } } ?>

</ul>