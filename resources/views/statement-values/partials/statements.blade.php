<script src="{{ URL::asset('/ckeditor/ckeditor.js') }}"></script>

<header class="statement-header clearfix">
    <h1 class="header_h1 header-statement">Areas of life</h1>
    <label class="show_in_lobby_level">Show in lobby </label>
</header>

<ul class="statement-list statement-window halfheightsec" id="statement">
    <?php if($statements && !empty($statements)){

        foreach ($statements as $key => $statement) {

            if($statement->statement_values && $statement->statement_values->id)
            {
            //$statement_txt=str_replace(array("<br>","<br />","<br/>"), "", $statement->statement_values->meta_value);
            $statement_txt=(isset($statement->statement_values->meta_value) && !empty($statement->statement_values->meta_value))?nl2br($statement->statement_values->meta_value):"";
    ?>
        <li class="main-li clearfix " id="item_<?php echo $statement->id?>">
            <div class="statement-value-goal-row">
                <?php 
            $statement_valuesId = (isset($statement->statement_values->id) && !empty($statement->statement_values->id))?$statement->statement_values->id:"";
            $statement_valuesMetaType = (isset($statement->statement_values->meta_type) && !empty($statement->statement_values->meta_type))?$statement->statement_values->meta_type:"";
                /*<div class="arrows-holder">
                    <a class="arrow-down movedown character-down-0 btnDown btn-down" index="0" gid="<?php echo $statement->id?>" href="javascript:void(0);">
                        <i class='lnr lnr-move'></i></a>
                </div>
                */
                ?>
                <h2 class="statement-value-content-div list-name overflow-ellipsis-nowrap">
                    <a href="{{URL('edit/statements/'.$statement->id)}}"><?php echo $statement->name?></a> 
                </h2>
                <div class="name-suffix-section">
                    <input type="checkbox" name="show_in_lobby[{{$statement_valuesId}}]" id="addto_lobby_{{$statement_valuesId}}" class="addto_lobby regular-checkbox show_in_lobby_checkbox" data-value="{{$statement_valuesId}}" <?php echo isset($statement->statement_values->show_in_lobby)&&$statement->statement_values->show_in_lobby?"checked":""; ?>>
                    <label for="addto_lobby_statement_{{$statement->id}}"><span></span></label>
                </div>
            </div>
            
            <div class="statement-value-row">
                <textarea class="form-control statement-value-row-input _editor" id="statement_<?php echo $statement_valuesId?>" data-type="<?php echo $statement_valuesMetaType?>" data-id="<?php echo $statement_valuesId?>" name="values[<?php echo $statement_valuesId?>]" style="height: auto;"></textarea>
                
           </div>
        </li>
    <?php } 
}
  
    }
    ?>
</ul>


<script type="text/javascript">
    $(document).ready(function(){

    var editor = CKEDITOR.replaceClass = '_editor';
      CKEDITOR.config.autoGrow_onStartup =  true;
      CKEDITOR.config.hidpi=true;
      CKEDITOR.config.resize_enabled = false;
      CKEDITOR.config.autoGrow_minHeight = 50;
      CKEDITOR.config.resize_minHeight= 50;
      //CKEDITOR.config.resize_minWidth = auto;
      CKEDITOR.config.disallowedContent = 'li{list-style-type}';
      CKEDITOR.config.extraPlugins =  ['autogrow','liststyle'];

      getStatementandValues();

 });

    var getStatementandValues = function(){

        $.ajax({
            url:"/get-statements-values",
            method:"get",
            success:function(response){
            if(response.status == 1){
                console.log("response",response);
                $.each(response.data,function(i,d){
                    console.log("d.meta_type",d.meta_type);
                    if(d.meta_type == "statement"){
                        
                        CKEDITOR.instances['statement_'+d.id].setData(d.meta_value);
                    }else{
                        CKEDITOR.instances['values_'+d.id].setData(d.meta_value);
                    }
                })

            }
                }
        })
    }
</script>