<?php 
if($tasks['items'] && !empty($tasks['items'])){ 
    
    $list_type=isset($tasks['view_type'])?$tasks['view_type']:'tree';
?>
<ul class="task-list task-main-tree" id="task"> 
        <input type="hidden" id="view_type" value="<?php echo $list_type; ?>"/>
    <?php
        
        foreach ($tasks['items'] as $key => $task) {

        if($task && !empty($task) && isset($task->id)){

        $expired_class=(date("Y-m-d") > $task->due_date)?"red":"";
    ?>
    <li class="<?php echo $task->list_collapse?"child-expand":"child-collapse"; ?> task-item" id="item_<?php echo $task->id?>" data-order="<?php echo $task->self_order?>">
        <div class="panel  panel-primary task-container has-child">
            <div class="task-row has-child">
                <div class="sort-div" data-gid="<?php echo $task->id?>">
                    <a class="like btnEnd" data-id="<?php echo $task->id?>" data-view="<?php echo $list_type; ?>" href="javascript:void(0);">
                        <span class="thumbup-unfilled"></span>
                    </a>
                    <span class="task-content-div">
                        <div class="task-link btn" data-id="<?php echo $task->id?>" data-collapse="">
                            <span class="goal-data">
                                <span class="gd-heading">
                                    <a class="task-link-title" href="{{URL('edit/'.$task->top_parent_id.'#'.$task->id)}}" title="<?php echo $task->name;?>" data-title="<?php echo $task->name ?>"><?php echo _substr($task->name,0,50)?></a>
                                </span>
                                <span class="gd-date">
                                    <time datetime="<?php echo $task->due_date?>" class="list-date">
                                        <span class="task-date <?php echo $expired_class?>">by <input type="text" name="d" id="_date-<?php echo $task->id; ?>" data-id="<?php echo $task->id; ?>" class="task-date-picker _date-<?php echo $task->id; ?>" value="<?php echo date("d M, Y",strtotime($task->due_date))?>" readonly/></span>
                                    </time>
                                </span>
                            </span>
                        </div>
                       
                    </span>
                </div>
            </div>
        </div>
    </li>
    <?php 
        }  
    }
?>
</ul>
<?php } ?>

