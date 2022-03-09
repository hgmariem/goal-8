<div class="tasks-mobile-tabs">
    <ul class="task-tabs nav">
        <li class="<?php echo ($tasks['view_type']=='tree')?"active":"";?>"><a gid="tree" data-toggle="tab" href="#tree-mobile">Tree</a></li>
        <li class="<?php echo ($tasks['view_type']=='list')?"active":"";?>"><a gid="list" data-toggle="tab" href="#list-mobile">List</a></li>
        <li class="<?php echo ($tasks['view_type']=='leaf')?"active":"";?>"><a gid="leaf" data-toggle="tab" href="#leaf-mobile">Leaf</a></li>
    </ul>
</div>
<div class="mobile_task_displayer tab-content ">
    <div class="tab-pane fade in active mobile-task-container" id="tree-mobile">
        <?php if($tasks['view_type']=='tree'){?>
                @include('goals.partials.mobile.task_tree',['tasks'=>$tasks])
                <!-- task tree-->
        <?php } ?>
    </div>
    <div class="tab-pane fade" id="list-mobile">
        <?php if($tasks['view_type']=='list'){ 
            ?>
                @include('goals.partials.mobile.task_list',['tasks'=>$tasks])
                <!-- task list-->
        <?php } ?>
    </div>
    <div class="tab-pane fade" id="leaf-mobile">
        <?php if($tasks['view_type']=='leaf'){ ?>
                @include('goals.partials.mobile.task_leaf',['tasks'=>$tasks])
                <!-- task leaf-->
        <?php } ?>
    </div>
</div>