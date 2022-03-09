<?php 
if($children && !empty($children)){ 
?>
<ul class="task-list task-main-tree task-list-tree task-open" id="task"> 
        <?php
            foreach ($children as $key => $task) {
                $has_child = isset($task->children)&&!empty($task->children)?1:0;
                $expired_class=(date("Y-m-d") > $task->due_date)?"red":"";
				$completed_percetange=isset($task->completed_percetange)?$task->completed_percetange:0;
                $completed_badge=isset($task->completed_badge)?$task->completed_badge:"danger";
            ?>
        <li class="<?php echo $task->list_collapse?"child-expand":"child-collapse"; ?> task-item task-desk-tree" id="item_<?php echo $task->id?>" data-order="<?php echo $task->self_order?>">
            <div class="panel  panel-primary task-container has-child">
                <div class="task-row has-child">
                    <div class="sort-div" data-gid="<?php echo $task->id?>">
                        <span class="arrows-holder"> 
                            <a class="arrow-up movedown task-down-1 btnDown btn-down" index="1" gid="<?php echo $task->id?>" href="javascript:void(0);">
                                <i class='lnr lnr-move'></i>
                            </a>
                        </span>
                        <a class="like btnEnd" data-id="<?php echo $task->id?>" data-view="tree" href="javascript:void(0);">
                            <span class="thumbup-unfilled"></span>
                        </a>
                        <span class="task-content-div">
                            <div class="task-link btn" data-id="<?php echo $task->id?>" data-collapse="">
                                <span class="goal-data">
                                    <span class="gd-heading">
                                        <?php if($has_child){ ?>
                                            <a href="#" class="show-task-link" data-autosaveid="<?php echo $task->auto_save_id?>">
                                                <i class="fa fa-chevron-<?php echo $task->list_collapse?"down":"right"; ?>"></i>
                                            </a>
                                        <?php } 
										$substr_max=($has_child || $completed_percetange)?40:80;
										$task_name=mb_convert_encoding(_substr($task->name,0, $substr_max), 'UTF-8', 'UTF-8');
										?>
                                        <a class="task-link-title" href="{{URL('edit/'.$task->top_parent_id.'#'.$task->id)}}" title="<?php echo $task->name?>"><?php echo $task_name?></a>
                                    </span>
                                    <span class="gd-date">
                                        <time datetime="<?php echo $task->due_date?>" class="list-date">
                                            <span class="task-date <?php echo $expired_class?>">by <input type="text" name="d" id="_date-<?php echo $task->id; ?>" data-id="<?php echo $task->id; ?>" class="task-date-picker _date-<?php echo $task->id; ?>" value="<?php echo date("d M, Y",strtotime($task->due_date))?>" readonly/></span>
                                        </time>
                                    </span>
                                </span>
                            </div>
                            
                            <span class="<?php echo (!$has_child && $completed_percetange==0)?'collapse':'expand'?>">
                                <span class="persentage"><?php echo $completed_percetange; ?>%</span>
                                <span class="progress">
                                    <span class="progress-bar progress-bar-<?php echo $completed_badge;?>" role="progressbar" aria-valuenow="<?php echo $completed_percetange; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $completed_percetange; ?>%"></span>
                                </span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <?php
            if($has_child){
            ?>
            @include('goals.partials.task_tree_child',['children'=>$task->children])
            <?php } ?>
        </li>
    <?php }
?>
</ul>
<?php } ?>