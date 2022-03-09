<?php 
if($tasks['items'] && !empty($tasks['items'])){ 
    
    $list_type=isset($tasks['view_type'])?$tasks['view_type']:'tree';
?>
<ul class="task-list task-main-tree task-list-list" id="task"> 
        <input type="hidden" id="view_type" value="<?php echo $list_type; ?>"/>
    <?php
        
        foreach ($tasks['items'] as $key => $_task) {
       
        if($_task && !empty($_task) && isset($_task->id)){
           $expired_class=(date("Y-m-d") > $_task->due_date)?"red":"";
    ?>
    <li class="<?php echo $_task->list_collapse?"child-expand":"child-collapse"; ?> task-item task-desk-list" id="item_<?php echo $_task->id?>" data-order="<?php echo $_task->self_order?>">
        <div class="panel  panel-primary task-container has-child">
            <div class="task-row has-child">
                <div class="sort-div" data-gid="<?php echo $_task->id?>">
                    <a class="like btnEnd" data-id="<?php echo $_task->id?>" data-view="<?php echo $list_type; ?>" href="javascript:void(0);">
                        <span class="thumbup-unfilled"></span>
                    </a>
                    <span class="task-content-div">
                        <div class="task-link btn" data-id="<?php echo $_task->id?>" data-collapse="">
                            <span class="goal-data">
                                <span class="gd-heading">
                                <?php
                                $substr_max=120;
                                $task_name=mb_convert_encoding(_substr($_task->name,0, $substr_max), 'UTF-8', 'UTF-8');
                                ?>
                                    <a class="task-link-title" href="{{URL('edit/'.$_task->top_parent_id.'#'.$_task->id)}}" title="<?php echo $_task->name?>"><?php echo $task_name?></a>
                                </span>
                                <span class="gd-date">
                                    <time datetime="<?php echo $_task->due_date?>" class="list-date">
                                        <span class="task-date <?php echo $expired_class?>">by <input type="text" name="d" id="_date-<?php echo $_task->id; ?>" data-id="<?php echo $_task->id; ?>" class="task-date-picker _date-<?php echo $_task->id; ?>" value="<?php echo date("d M, Y",strtotime($_task->due_date))?>" readonly/></span>
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