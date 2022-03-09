<?php
$date=key($days);
?>
<section class="lobby-goals-rows col-lg-12 col-md-12 col-sm-12 col-xs-12 no-pad">
    <div class="calander text-center">
        <a class="pre_date prev-date" data-date="<?php echo $date?>" href="javascript:void(0)"><i class="lnr lnr-chevron-left"></i></a>
        <a class="next_date next-date" data-date="<?php echo $date?>" href="javascript:void(0)"><i class="lnr lnr-chevron-right"></i></a>
        <div class="date"><input class="top_calendar" id="date_picker" readonly="readonly" type="text" value="<?php echo date("d M, Y",strtotime($date)); ?>" name="name"></div>
    </div>
    <input class="list_calendar" style="display:none;" type="text" value="" name="calendar" id="calendar">
    <ul class="habit-list" id="habits"> 
        <?php 
            if($habits && !empty($habits)){

                foreach ($habits as $key => $habit) { ?>
                <li class="main-li clearfix goal-row" id='item_<?php echo $habit->id;?>' data-id="<?php echo $habit->id;?>" data-habit="<?php echo $habit->id;?>">
                    <div class="arrows-holder">
                        <a class="arrow-down movedown habit-down-0 btnDown btn-down" index="0" gid="<?php echo $habit->id;?>" href="javascript:void(0);">
                            <i class='lnr lnr-move'></i>
                        </a>
                    </div>
                    <div class="m-sec-name">
                        <div class="overflow-ellipsis-nowrap name-min-height">
                        <?php $edit_option=($habit->top_parent_id)?$habit->top_parent_id."#".$habit->id:$habit->id?>
                        <a href="{{URL('edit/'.$edit_option)}}"><?php echo $habit->name;?></a> </div>
                        <div class="habits__process">(<?php echo isset($habit->percentage)?$habit->percentage->completed_days:0?>/<?php echo isset($habit->percentage)?$habit->percentage->total_days:0?>)</div>
                        <input type="text" name="_date" value="<?php echo $habit->habit_start_date; ?>" class="_habit_datepicker" id="_habit_datepicker_{{$habit->id}}" data-id="{{$habit->id}}">
                    </div>
                    <?php
                        if(isset($habit->habit_type) && $habit->habit_type->is_scale == 0)
                        {
                         ?>
                    <div class="m-sec-bar">
                         <span data-toggle="modal" data-pid="{{$habit->id}}" data-target="#myModal" data-class="isMobile" class="change-badge habits__process-percent badge1 <?php echo $habit->percentage->badge?>">
                            <?php echo $habit->percentage->percentage;?>%
                        </span>
                    </div>
                <?php }else if(isset($habit->habit_type) && $habit->habit_type->is_scale == 1){ ?>
                            <div class="m-sec-bar">
                         <span data-toggle="modal" data-pid="<?php echo $habit->id;?>" data-target="#myLinechartModal"  data-class="isMobile" class="change-badge habits__process-percent <?php echo $habit->percentage->badge?>" gdate="<?php echo $habit->habit_start_date; ?>">
                            <?php echo $habit->percentage->percentage;?>avg
                        </span>
                    </div>
                <?php }else{ ?>
                    <div class="m-sec-bar">
                         <span data-toggle="modal" data-pid="{{$habit->id}}" data-target="#myModal" data-class="isMobile" class="change-badge habits__process-percent badge1 <?php echo $habit->percentage->badge?>">
                            <?php echo $habit->percentage->percentage;?>%
                        </span>
                    </div>
                <?php } ?>
                    <div class="m-sec-calender">
                        <a class="view_link habit-datepicker calendar" gid="<?php echo $habit->id;?>" data-scale="<?php echo (isset($habit->habit_type) && $habit->habit_type->is_scale)?$habit->habit_type->is_scale:0;?>" data-max="<?php echo (isset($habit->habit_type) && $habit->habit_type->maximum)?$habit->habit_type->maximum:0;?>" data-is_apply="<?php echo (isset($habit->habit_type) && $habit->habit_type->is_apply)?:0;?>" data-min="<?php echo (isset($habit->habit_type) && $habit->habit_type->minimum)?$habit->habit_type->minimum:0;?>" href="javascript:void(0);"><img src="{{URL('images/calendar.svg')}}" alt="" class="cal"></a>
                    </div>
                    <?php 
                    if(isset($habit->days) && !empty($habit->days)){ 
                        
                        foreach ($habit->days as $date => $_day) { 
                        
                        //$log_value=isset($_day['log'])&&isset($_day['log']->value)?$_day['log']->value:0;
                        $log_value=( (isset($_day['log']) &&  isset($_day['log']->value) ) && $date >= $habit->habit_start_date )  ? $_day['log']->value:"";
                        if(isset($habit->habit_type) && $habit->habit_type->is_scale == 1)
                            {
                        if(isset($habit->habit_type) && $log_value==0 && $habit->habit_type->value!=7){
                            $habit_type_value=explode(",", $habit->habit_type->value);
                            if(!in_array($_day['day_of_week'], $habit_type_value)){
                                $log_value=-1;
                            }
                        }
                        $today=date("Y-m-d");
                        if($date < $today && $date >= $habit->habit_start_date  && $log_value == "")
                        {
                         $log_value = 0;
                         setZeroValue($habit->id,$date,$log_value,$habit->habit_type->is_scale,$habit->habit_type);
                        }

                    ?>
                    <div class="m-sec-checkbox">
                        <input type="number" name="number" class="number scale-number" data-min="<?=$habit->habit_type->minimum?>" data-max="<?=$habit->habit_type->maximum?>" min="<?=$habit->habit_type->minimum?>" max="<?=$habit->habit_type->maximum?>" data-value="" data-is_apply="<?=$habit->habit_type->is_apply?>" data-scale="<?=$habit->habit_type->is_scale?>" gid="<?php echo $habit->id;?>" gdate="<?php echo $date;?>"  value="<?php echo ($log_value=='-1')?"":$log_value; ?>" placeholder="<?php echo ($log_value==-1)?"N/A":""?>" />
                    <label for="checkbox-0"></label>
                    <label for="checkbox-<?php echo $habit->id;?>-1"><span></span></label>
                    </div>
                <?php }else{ 

                    if(isset($habit->habit_type) && $log_value==0 && $habit->habit_type->value!=7){
                    $habit_type_value=explode(",", $habit->habit_type->value);
                    if(!in_array($_day['day_of_week'], $habit_type_value)){
                        $log_value=2;
                    }
                }
                    ?>

                    <div class="m-sec-checkbox">
                        <input type="checkbox" id="checkbox-<?php echo $habit->id;?>-1" class="habits__checkbox regular-checkbox <?php echo $log_value==2?"notavailable":""?>" gid="<?php echo $habit->id;?>" data-value="<?php echo $log_value; ?>" data-scale="0" gid="<?php echo $habit->id;?>" gdate="<?php echo $date;?>"  <?php echo $log_value?"checked":""?>>
                    <label for="checkbox-<?php echo $habit->id;?>-1"><span></span></label>
                    </div>
                    <?php } }  } ?>
                </li>
        <?php 
               }
            }
        ?>
    </ul>
    <?php /*
    <table class="table-responsive habit_list" cellpadding="0" cellspacing="0">
        <tbody>
            <?php 
            if($habits && !empty($habits)){

                foreach ($habits as $key => $habit) { ?>
            <tr class="goal-row" id='item_<?php echo $habit->id;?>' data-id="<?php echo $habit->id;?>" data-habit="<?php echo $habit->id;?>">
                <td width="50"><a class="movedown down-0 btnDown" index="0" gid="<?php echo $habit->id;?>" href="javascript:void(0);">
                    <i class="lnr lnr-chevron-down"></i></a>
                </td>
                <td class="goal"><a href="#"><?php echo $habit->name;?></a><span class="count"><?php echo isset($habit->percentage)?$habit->percentage->completed_days:0?>/<?php echo isset($habit->percentage)?$habit->percentage->total_days:0?></span></td>
                <td width="50">
                    <span data-toggle="modal" data-target="#myModal" class="badge1 <?php echo $habit->percentage->badge?>"><!--<span class="badge1 badge-danger">-->  
                        <span class="habit_percent">(<?php echo $habit->percentage->percentage;?>%)</span>
                    </span>
                </td>
                <td width="50"><a class="view_link" gid="<?php echo $habit->id;?>" href="javascript:void(0);"><img src="{{URL('images/calendar.svg')}}" alt="" class="cal"></a></td>
                <?php if(isset($habit->days) && !empty($habit->days)){ 
                        foreach ($habit->days as $date => $_day) { 
                        $log_value=isset($_day['log'])&&isset($_day['log']->value)?$_day['log']->value:0;
                    ?>
                <td width="50">
                    <input type="checkbox" id="checkbox-<?php echo $habit->id;?>-1" class="regular-checkbox <?php echo $log_value==2?"notavailable":""?>" gid="<?php echo $habit->id;?>" data-value="<?php echo $log_value; ?>" gid="<?php echo $habit->id;?>" gdate="<?php echo $date;?>"  <?php echo $log_value?"checked":""?>>
                    <label for="checkbox-<?php echo $habit->id;?>-1"><span></span></label>
                </td>
                <?php } } ?>
            </tr>
        <?php } 

        } ?>

        </tbody>
    </table>*/?>
</section>
                