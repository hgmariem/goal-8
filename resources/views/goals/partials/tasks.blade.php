<header class="header clearfix">
    <h1 class="header_h1 header-tasks">Tasks</h1>
    <div class="task__list-type">
        <button class="task__btn m-list-type__tree task_trigger" gid="tree" <?php echo ($tasks['view_type']=='tree')?"disabled='true'":"";?>>Tree</button>
        <button class="task__btn m-list-type__list task_trigger" gid="list" <?php echo ($tasks['view_type']=='list')?"disabled='true'":"";?>>List</button>
        <button class="task__btn m-list-type__leaf task_trigger" gid="leaf" <?php echo ($tasks['view_type']=='leaf')?"disabled='true'":"";?>>Leaf</button>
    </div>
    <?php if(isset($tasks['expanded'])){ ?>
        <button class="minscreen task_minimizer tasks-link"></button>
    <?php }else{ ?>
        <button class="fullscreen task_maximizer tasks-link"></button>
    <?php } ?>
    
</header>
<div id="task_displayer" class="halfheightsec">
    <?php if($tasks['view_type']=='tree'){?>
        @include('goals.partials.task_tree',['tasks'=>$tasks])
    <?php }else if($tasks['view_type']=='list'){ 
        ?>
        @include('goals.partials.task_list',['tasks'=>$tasks])
    <?php }else{ ?>
        @include('goals.partials.task_leaf',['tasks'=>$tasks])
    <?php } ?>
</div>
<div id="task_displayer_loader">
    <div>Loading... Please wait..</div>
</div>