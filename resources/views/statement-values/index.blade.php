@extends('layouts.base') 
@section('content')

<?php ?>
<style type="text/css">
.statement_maximizer{
    background-image: url(../img/Maximize_BTN-red.png);
    background-color: #ffffff;
    border: none;
    cursor: pointer;
    width: 20px;
    height: 20px;
    position: absolute;
    bottom: 24px;
    background-size: 100%;
    background-repeat: no-repeat;
    background-position: center center;
    right: 0px;
    margin: 0;
    float: none;
    padding: 0;
}

.cke_top{
    display: none;
}
</style>
<script src="{{ URL::asset('/ckeditor/ckeditor.js') }}"></script>


<div id="page-wrapper" class="no-pad">
    <div class="graphs">


        <!-- Desktop Html -->

        <div class="hide-on-mobile">

            <?php if($isDesktop){ ?>
            <div class="landing_habbit bootmodal">
                @include('goals.partials.statics_popup')
                
                <div class="graphs">
                    <div class="fullheightsection">                       
                        <section class="habit custom-old-style" id="habit-id">
                            <header class="header clearfix">
                                <h1 class="header_h1 header-values">Personal Statement</h1>
                            </header>
                            <div class="goal-create-details values-list statement-window halfheightsec">
                                <div class="habits-container" style="height: 90%;">
                                	<textarea name="content" id="content"> <?php echo isset($single_statement->meta_value)?$single_statement->meta_value:''; ?></textarea>

                                    <!-- <div class="contenteditable-textarea personal_statement_box form-control" id="personal_statement_box" rows="5" style="height: 100%;"><?php echo isset($single_statement->meta_value)?$single_statement->meta_value:''; ?></div> -->
                                    <input type="hidden" name="single_statement_id" id="single_statement_id" value="<?php echo isset($single_statement->id)?$single_statement->id:0; ?>">
                                    <div class="statement-panel sheets-panel personal-statement" style="margin-top:-1px;">
                                        <div>       
                                           <div class="plus_sheet" ><button type="button">+</button></div>
                                           <div class="sheet_pages" data-attr="statement"></div>
                                           <a href="{{URL('statement-values/statement-view')}}"><button class="fullstatement statement_maximizer tasks-link"></button></a>
                                        </div>
                                    </div>
                                    <!-- habit container ends-->
                                    <div class="statement_submit_panel">
                                        <div class="d-inline-block statement_show_in_lobby">
                                            <div class="name-suffix-section">
                                            <label class="statement_show_in_lobby_lbl">Show in lobby </label>

                                                <input type="checkbox" name="show_in_lobby" id="show_in_lobby" class="show_in_lobby  show_in_lobby_checkbox" <?php echo isset($single_statement->show_in_lobby)&&$single_statement->show_in_lobby?"checked":""; ?>>
                                                <label for="addto_lobby"><span></span></label>
                                            </div>
                                        </div>


                                        
                                    </div>
                                </div> 
                            </div>
                        </section>
                        <?php /**/?>
                        <section class="task-reminders-wrapper">                            
                            <section class="lobby-goals-rows col-lg-6 col-md-6 col-sm-6 col-xs-12 no-pad task tasks" id="task-id">
                                <div class="tasks-container">
                                     @include('statement-values.partials.statements',['statements'=>$statements])
                                </div>
                            </section>
                            <section class="col-lg-6 col-md-6 col-sm-6 col-xs-12 no-pad  character reminders custom-old-style" id="character-id">
                                <div class="m-sec-rem character-container">
                                    @include('statement-values.partials.values',['values'=>$values])
                                </div>
                            </section>
                        </section>
                        <div class="col-lg-12 form-group d-inline-block statement_submit_btn"><input name="save" id="btnSubmit" class="common_btn" type="button" value="SAVE"></div>
                    </div>
                </div>

            </div>
            <?php } ?>
            
        </div>


<!-- Mobile Html -->

<div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
    <?php 
   /* echo "<pre/>";
    print_r($habits);die;*/
        if($isMobile){?>
            @include('mobile_header')
    <?php } ?>
    <?php if($isMobile){?>
        <div class="mobile-content home">
            <div class="landing_habbit bootmodal">
                @include('goals.partials.statics_popup')
                
                <div class="graphs">
                    <div class="fullheightsection">                       
                        <section class="habit custom-old-style" id="habit-id">
                            <header class="header clearfix">
                                <h1 class="header_h1 header-values">Personal Statement</h1>
                            </header>
                            <div class="goal-create-details values-list statement-window halfheightsec">
                                <div class="habits-container" style="height: 90%;">
                                <textarea name="content" id="content"> <?php echo isset($single_statement->meta_value)?$single_statement->meta_value:''; ?></textarea>

                                     <input type="hidden" name="single_statement_id" id="single_statement_id" value="<?php echo isset($single_statement->id)?$single_statement->id:0; ?>">
                                    <div class="statement-panel sheets-panel personal-statement isMobile" style="margin-top:-1px;">
                                        <div>       
                                           <div class="plus_sheet" ><button type="button" class="isMobile">+</button></div>
                                           <div class="sheet_pages isMobile" data-attr="statement"></div>
                                           <!-- <a href="{{URL('statement-values/statement-view')}}"><button class="fullstatement statement_maximizer tasks-link"></button></a> -->
                                        </div>
                                    </div>
                                    <!-- habit container ends-->
                                    <div class="statement_submit_panel">
                                        <div class="d-inline-block statement_show_in_lobby">
                                            <div class="name-suffix-section">
                                                <label class="statement_show_in_lobby_lbl">Show in lobby </label>
                                                <input type="checkbox" name="show_in_lobby" class="show_in_lobby  show_in_lobby_checkbox" <?php echo isset($single_statement->show_in_lobby)&&$single_statement->show_in_lobby?"checked":""; ?>>
                                                <label for="addto_lobby"><span></span></label>
                                            </div>
                                        </div>


                                        
                                    </div>
                                </div> 
                            </div>
                        </section>
                        <?php /**/?>
                        <section class="task-reminders-wrapper">                            
                            <section class="lobby-goals-rows col-lg-6 col-md-6 col-sm-6 col-xs-12 no-pad task tasks" id="task-id">
                                <div class="tasks-container">
                                     @include('statement-values.partials.statements',['statements'=>$statements])
                                </div>
                            </section>
                            <section class="col-lg-6 col-md-6 col-sm-6 col-xs-12 no-pad  character reminders custom-old-style" id="character-id">
                                <div class="m-sec-rem character-container">
                                    @include('statement-values.partials.values',['values'=>$values])
                                </div>
                            </section>
                        </section>
                        <div class="col-lg-12 form-group d-inline-block statement_submit_btn"><input name="save" id="btnSubmit" class="common_btn" type="button" value="SAVE"></div>
                    </div>
                </div>

            </div>

        </div>
    <?php } ?>
</div>


</div>

</div>

<div id="myTrophyModal" class="modal fade habit-per-graph" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Would you like to move this task to trophy? </h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal movetotrophy" id="movetotrophy">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="trophy_name">Name</label>
                        <div class="col-sm-10"><input type="text" class="form-control" id="trophy_name" value=""></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="trophy_date">Date</label>
                        <div class="col-sm-10"><input type="text" class="form-control" id="trophy_date" value="<?php echo date("Y-m-d");?>"></div>
                    </div>
                    <input type="hidden" id="trophy_id" value="">
                </form>
            </div>
            <div class="modal-footer ">
                <p style="text-align:center;">
                    <a class="btn btn-success btnPopupTrophy" href="javascript:void(0)" style="margin-right:20px;">Yes</a> 
                    <a class="btn btn-warning" href="javascript:void(0)" data-dismiss="modal">No</a>
                </p>
            </div>
        </div>

    </div>
</div>

@endsection @section('footer_scripts')
<script src="{{ URL::asset('/js/statement-values.ete.js') }}?{{ time() }}"></script>

<script type="text/javascript">
	CKEDITOR.replace( 'content',{
    removePlugins: 'magicline,elementspath',
    autoGrow_minHeight: 250,
    autoGrow_maxHeight: 250,
});

CKEDITOR.instances.content.on('change', function() { 
    var elem= $(".personal-statement"); 

    var sheet_pages=$(elem).find(".sheet_pages");

    var last_sheet=sheet_pages.find(".sheet-container.active");

    var content = CKEDITOR.instances["content"].getData();

    console.log("content is here......",content);

  //var content = note_editable.html();
  
  if(last_sheet_data=last_sheet.data()){
    var _attr = sheet_pages.data("attr");
    last_sheet_data.html=content;
    last_sheet_data.attr=_attr;
    savePersonalSheet(last_sheet_data);
  }

});


$(document).on('ifChanged', ".show_in_lobby", function(e) {
    console.log("ifChanged called....");
    var elem= $(".personal-statement"); 
    var sheet_pages=$(elem).find(".sheet_pages");
    var last_sheet=sheet_pages.find(".sheet-container.active");

    var show_in_lobby= ($(this).prop('checked'))?1:0;
    addto_lobby_request(last_sheet,show_in_lobby);
            
    });


var addto_lobby_request = function(sheet, show_in_lobby){



    var sheet_data = {};
    sheet_data.id= sheet.data("sheet_id");
    sheet_data.show_in_lobby=show_in_lobby;
    var xhr = $.ajax({
        url: site_url+"/statement-values/addto_lobby",
        data:sheet_data,
        method:"POST",
        success: function(response) {
            
        }
    });

};




</script>
@endsection