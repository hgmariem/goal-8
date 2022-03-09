<section class="lobby-goals-rows col-lg-8 col-md-8 col-sm-8 col-xs-12 no-pad">
<div class="yellow-head">
  <h1>Tasks</h1>
  <a href="#" class="o-new"></a>
  <ul class="task-tabs nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#tree">Tree</a></li>
    <li><a data-toggle="tab" href="#list">List</a></li>
    <li><a data-toggle="tab" href="#leaf">Leaf</a></li>
  </ul>
</div>
<div class="tab-content">
    <div id="tree" class="tab-pane fade in active">
      <?php include ("tasks-tree.php"); ?>
    </div>
    <div id="list" class="tab-pane fade">
      <?php include ("tasks-list.php"); ?>
    </div>
    <div id="leaf" class="tab-pane fade">
      <?php include ("tasks-leaf.php"); ?>
    </div>
</div>
</section>
