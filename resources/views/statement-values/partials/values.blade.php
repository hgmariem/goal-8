<header class="statement-header clearfix">
    <h1 class="header_h1 header-values">Values</h1>
    <label class="show_in_lobby_level">Show in lobby </label>
</header>

<ul class="values-list statement-window halfheightsec" id="values">
    <?php if($values && !empty($values)){

        //print_r($values);

        foreach ($values as $key => $value) {
                if($value->statement_values && $value->statement_values->id)
                {
            //$value_txt=str_replace(array("<br>","<br />","<br/>"), "", $value->statement_values->meta_value);

            $value_txt= (isset($value->statement_values->meta_value) && !empty($value->statement_values->meta_value))?nl2br($value->statement_values->meta_value):"";
            //var_dump($value->show_in_lobby);
    ?>
        <li class="main-li clearfix " id="item_<?php echo $value->id?>">
            <div class="statement-value-goal-row">
                <?php
                /*<div class="arrows-holder">
                    <a class="arrow-down movedown character-down-0 btnDown btn-down" index="0" gid="<?php echo $value->id?>" href="javascript:void(0);">
                        <i class='lnr lnr-move'></i></a>
                </div>*/
                ?>

                <h2 class="statement-value-content-div list-name overflow-ellipsis-nowrap">
                    <a href="{{URL('edit/statements/'.$value->id)}}"><?php echo $value->name?></a>  
                </h2>
                <div class="name-suffix-section">
                        <input type="checkbox" name="show_in_lobby[{{$value->statement_values->id}}]" id="addto_lobby_{{$value->statement_values->id}}" class="addto_lobby regular-checkbox show_in_lobby_checkbox" data-value="{{$value->statement_values->id}}" <?php echo isset($value->statement_values->show_in_lobby)&&$value->statement_values->show_in_lobby?"checked":""; ?>>
                        <label for="addto_lobby{{$value->id}}"><span></span></label>
                </div>

            </div>

            
           <div class="statement-value-row">
                <textarea class="form-control statement-value-row-input _editor" id="values_<?php echo $value->statement_values->id?>"  data-type="<?php echo $value->statement_values->meta_type?>" data-id="<?php echo $value->statement_values->id?>"  name="values[<?php echo $value->statement_values->id?>]" style="height: auto;"></textarea>
           </div>

        </li>
    <?php } 
}
  
    }
    ?>
</ul>

