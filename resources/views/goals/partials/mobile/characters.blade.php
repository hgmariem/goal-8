<ul class="character-list" id="character">
    <?php if($characters && !empty($characters)){

        foreach ($characters as $key => $character) {
    ?>
        <li class="main-li clearfix " id="item_<?php echo $character->id?>">
            <div class="arrows-holder">
                <a class="arrow-down movedown character-down-0 btnDown btn-down" index="0" gid="<?php echo $character->id?>" href="javascript:void(0);">
                    <i class='lnr lnr-move'></i></a>
            </div>
            <h2 class="character-content-div list-name overflow-ellipsis-nowrap">
                <a href="{{URL('edit/'.$character->top_parent_id.'#'.$character->id)}}"><?php echo $character->name?></a> </h2>
        </li>
    <?php } 
  
    }
    ?>

    <?php 
    if($statements && !empty($statements)){
        foreach ($statements as $key => $statement) {
    ?>

     <li class="main-li clearfix " id="item_<?php echo $statement->id?>">
            <div class="arrows-holder">
                <a class="arrow-down movedown character-down-0 btnDown btn-down" index="0" gid="<?php echo $statement->id?>" href="javascript:void(0);">
                    <i class="lnr lnr-move"></i></a>
            </div>
            <h2 class="character-content-div list-name overflow-ellipsis-nowrap">
              <?php $url = ($statement->goal_id)?URL("edit/".$statement->goal_id):URL("#"); ?>
                <a href="{{$url}}"><?php echo nl2br($statement->meta_value)?></a> </h2>
        </li>
    <?php } 
  
    }
    ?>
    
</ul>