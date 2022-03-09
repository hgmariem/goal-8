<a class="add-new" href="#"></a>
<div class="tasks-mobile-tabs">
<ul class="task-tabs nav">
    <li class="active"><a data-toggle="tab" href="#tree-mobile">Tree</a></li>
    <li><a data-toggle="tab" href="#list-mobile">List</a></li>
    <li><a data-toggle="tab" href="#leaf-mobile">Leaf</a></li>
</ul>
</div>
<div class="tab-content">
    <div id="tree-mobile" class="tab-pane fade in active">
      <?php include ("tasks-tree-mobile.php"); ?>
    </div>
    <div id="list-mobile" class="tab-pane fade">
      <?php include ("tasks-list.php"); ?>
    </div>
    <div id="leaf-mobile" class="tab-pane fade">
      <?php include ("tasks-leaf.php"); ?>
    </div>
</div>