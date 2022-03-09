<?php 
if($tasks['items'] && !empty($tasks['items'])){   
    $list_type=isset($tasks['view_type'])?$tasks['view_type']:'tree';
?>
<ul class="task-list task-main-tree" id="task-mobile"> 
        <input type="hidden" id="view_type" value="<?php echo $list_type; ?>"/>
    <?php
        
        foreach ($tasks['items'] as $key => $_task) {

        if($_task && !empty($_task) && isset($_task->id)){
           $expired_class=(date("Y-m-d") > $_task->due_date)?"red":""; 
    ?>
    <li class="<?php echo $_task->list_collapse?"child-expand":"child-collapse"; ?> task-item" id="item_<?php echo $_task->id?>" data-order="<?php echo $_task->self_order?>">
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
                                $substr_max=50;
                                $task_name=mb_convert_encoding(_substr($_task->name,0, $substr_max), 'UTF-8', 'UTF-8');
                                ?>
                                    <a class="task-link-title" href="{{URL('edit/'.$_task->top_parent_id.'#'.$_task->id)}}" data-title="<?php echo $_task->name ?>"><?php echo $task_name?></a>
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

<?php /*<ul class="task-list-mobile">
    <li class="no-child">
        <div class="inner-wrapper">
            <div class="arrow-exp-coll">
                <a href="#"><i class="fa fa-chevron-down"></i></a>
            </div>
            <div class="thumb">
                <span class="thumbup-filled"></span>
            </div>
            <div class="persentage-show">
                <span class="progress">
                <span class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="width: 3%"></span>
                </span>
                <span class="persentage">3%</span>
            </div>
            <div class="text-content">
                <h4 class="title">Re-Branding</h4>
                <p class="date grey">by Mar 21 2018</p>
            </div>
        </div>
    </li>
    <li class="no-child">
        <div class="inner-wrapper">
            <div class="arrow-exp-coll">
                <a href="#"><i class="fa fa-chevron-down"></i></a>
            </div>
            <div class="thumb">
                <span class="thumbup-filled"></span>
            </div>
            <div class="persentage-show">
                <span class="progress">
                <span class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="width: 3%"></span>
                </span>
                <span class="persentage">3%</span>
            </div>
            <div class="text-content">
                <h4 class="title">Re-Branding</h4>
                <p class="date grey">by Mar 21 2018</p>
            </div>
        </div>
    </li>
    <li class="no-child">
        <div class="inner-wrapper">
            <div class="arrow-exp-coll">
                <a href="#"><i class="fa fa-chevron-down"></i></a>
            </div>
            <div class="thumb">
                <span class="thumbup-filled"></span>
            </div>
            <div class="persentage-show">
                <span class="progress">
                <span class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="width: 3%"></span>
                </span>
                <span class="persentage">3%</span>
            </div>
            <div class="text-content">
                <h4 class="title">Re-Branding</h4>
                <p class="date grey">by Mar 21 2018</p>
            </div>
        </div>
    </li>
    <li class="no-child">
        <div class="inner-wrapper">
            <div class="arrow-exp-coll">
                <a href="#"><i class="fa fa-chevron-down"></i></a>
            </div>
            <div class="thumb">
                <span class="thumbup-filled"></span>
            </div>
            <div class="persentage-show">
                <span class="progress">
                <span class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="width: 3%"></span>
                </span>
                <span class="persentage">3%</span>
            </div>
            <div class="text-content">
                <h4 class="title">Re-Branding</h4>
                <p class="date grey">by Mar 21 2018</p>
            </div>
        </div>
    </li>
</ul>*/?>