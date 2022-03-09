@extends('layouts.base') 
@section('content')

<?php ?>
<style type="text/css">
.personal_statement_box {
    width: 100%;
    resize: vertical;
    max-height: 100%;
    overflow: scroll;
}
.statement_submit_btn {
    position: relative;
    bottom: 0px;
    right: 24px;
    z-index: 1;
}
.statement_submit_panel .statement_show_in_lobby {
    display: inline-block;
    vertical-align: middle;
}
.statement_minimizer{
    background-image: url(../img/minimizer_BTN-red.png);
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
#habit-id > div > div.statement_submit_panel > div > div > div{
    top: -2px;
    margin-left: 6px !important;
}
.statement_submit_btn .common_btn, .statement_submit_btn {
    right: 115px;
}

.cke_top{
    display: none;
}


.cke_bottom {
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
                <section class="task-reminders-wrapper">     
                <div class="graphs">
                    <div class="fullheightsection">                       
                        <section class="habit custom-old-style" id="habit-id">
                            <header class="header clearfix">
                                <h1 class="header_h1 header-values">Personal Statement</h1>
                            </header>
                                <div class="habits-container" style="height: 100%;margin:15px 15px;">
                                    <textarea name="content" id="content"> <?php echo isset($single_statement->meta_value)?$single_statement->meta_value:''; ?></textarea>

                                    <!-- <div contenteditable="true" class="contenteditable-textarea personal_statement_box form-control" id="personal_statement_box" rows="5" style="height: 100%;"><?php echo isset($single_statement->meta_value)?$single_statement->meta_value:''; ?></div> -->

                                    <input type="hidden" name="single_statement_id" id="single_statement_id" value="<?php echo isset($single_statement->id)?$single_statement->id:0; ?>">
                                    <div class="statement-panel sheets-panel personal-statement" style="margin-top:-1px;">
                                        <div>       
                                           <div class="plus_sheet" ><button type="button">+</button></div>
                                           <div class="sheet_pages" data-attr="statement"></div>
                                            <a href="{{URL('statement-values')}}"><button class="minscreen statement_minimizer tasks-link"></button></a>
                                        </div>
                                    </div>
                                    <!-- habit container ends-->
                                    <div class="statement_submit_panel">
                                        <div class="d-inline-block statement_show_in_lobby">
                                            <div class="name-suffix-section">
                                                <label class="statement_show_in_lobby_lbl">Show in lobby </label>
                                            <input type="checkbox" name="show_in_lobby" class="show_in_lobby  show_in_lobby_checkbox" <?php echo isset($single_statement->show_in_lobby)&&$single_statement->show_in_lobby?"checked":""; ?>>
                                            <p><br></p>
                                            <p><br></p>
                                            <p><br><br></p>
                                                <label for="addto_lobby"><span></span></label>
                                            </div>
                                        </div>
                                        
                                    </div>

                                </div> 
                        </section>
                        <?php /**/?>
                        <div class="col-lg-12 form-group d-inline-block statement_submit_btn"><input name="save" id="btnSubmit" class="common_btn" type="button" value="SAVE"></div>
            </div>
            <?php } ?>
            
        </div>
        </section>

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
    disallowedContent :'li{list-style-type}',
    extraPlugins:['autogrow','pastefromgdocs','pastefromword','copyformatting'],
});

    CKEDITOR.instances.content.on('change', function() { 
    var elem= $(".personal-statement"); 

    var sheet_pages=$(elem).find(".sheet_pages");

    var last_sheet=sheet_pages.find(".sheet-container.active");

    var content = CKEDITOR.instances["content"].getData();

  //var content = note_editable.html();
  
  if(last_sheet_data=last_sheet.data()){
    var _attr = sheet_pages.data("attr");
    last_sheet_data.html=content;
    last_sheet_data.attr=_attr;
    savePersonalSheet(last_sheet_data);
  }

});

</script>

@endsection