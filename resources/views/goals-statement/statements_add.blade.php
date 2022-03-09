@extends('layouts.base') 
@section('content')

<?php
 ?>
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

.goal_name{
  color:#fff;
  font-weight: 800;
  text-transform: initial;
}
</style>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js"></script>

<script src="{{ URL::asset('/ckeditor/ckeditor.js') }}"></script>
<script src="{{ URL::asset('/ckeditor/plugins/statement/plugin.js') }}"></script>


<div id="page-wrapper" class="no-pad">
    <div class="graphs">

        <!-- Desktop Html -->

        <div class="hide-on-mobile">

            <?php if($isDesktop){ ?>
            <div class="landing_habbit bootmodal">
                
                <div class="graphs">
                    <div class="fullheightsection">                       
                        <section class="habit custom-old-style" id="habit-id">
                            <header class="header clearfix">
                                <h1 class="header_h1 header-values">Personal Statement   <small class="goal_name">{{$goal_name}}</small></h1>
                            </header>

                            <div class="template-form" style="margin: 15px 15px;">
                                <input type="hidden" name="id" id="temp_id" value="{{$auto_save_id}}">
                            <div class="statement-section statement-editing-area" id="statement">
                            <div class="statement-heading"><h3> Personal statement </h3></div>
                            
                             <textarea name="statement" id="statement" class="_editor"></textarea>

                            </div>
                            <div class="values-section statement-editing-area" id="values">
                            <div class="values-heading"><h3> Values </h3></div>
                            

                            <textarea name="values" id="values" class="_editor "></textarea>
                           </div>
                        </section>
                        
                        <div class="col-lg-12 form-group d-inline-block statement_submit_btn"> <a href="{{ url()->previous() }}" class="common_btn" >SAVE</a> </div>
                    </div>
                </div>

            </div>
            <?php } ?>
            
        </div>


<!-- Mobile Html -->


</div>

</div>


@endsection 
@section('footer_scripts')


  <script type="text/javascript">
    $(document).ready(function(){

    var editor = CKEDITOR.replaceClass = '_editor';
      CKEDITOR.config.autoGrow_onStartup =  true;
      CKEDITOR.config.enterMode = CKEDITOR.ENTER_P;
      CKEDITOR.config.hidpi=true;
      CKEDITOR.config.disallowedContent = 'li{list-style-type}';
      CKEDITOR.config.extraPlugins =  ['statement','autogrow','liststyle'];      

 });
  </script>

<script>

CKEDITOR.on('instanceReady', function(evt) {
var editor = evt.editor;
//CKEDITOR.instances['status'].focus();
var ckediting = editor.container.$;

$(".cke_bottom").remove();

editor.on('change', function(e) {

  var _that = $(this);

var current_sheet = $(_that[0].container.$).parents(".statement-editing-area").find(".sheet-container.active");

var sheet_container=$(_that[0].container.$).parents(".statement-editing-area").find(".sheet_pages");


savePersonal_sheet(current_sheet, sheet_container);

});

});

</script>

  <script type="text/javascript">

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var savePersonal_sheet = function(sheet, sheet_pages){

  var attribut =  sheet_pages.parents(".statement-editing-area").find("._editor").attr("id");
  console.log("attribut",attribut);
  var content = CKEDITOR.instances[''+attribut+''].getData();
  
  if(last_sheet_data=sheet.data()){
    var _attr = attribut;
    last_sheet_data.html=content;
    last_sheet_data.attr=_attr;
    saveGoalStatemntSheet(last_sheet_data);
  }

};

var savexhr;
var deleteGoalstatementSheet = function(sheet_data){
  
  var auto_save_id = $("#temp_id").val();
    
    sheet_data.auto_save_id=auto_save_id;


    var xhr = $.ajax({
        url: site_url+"/statement-values/attr/delete_sheet",
        data:sheet_data,
        method:"POST",
        success: function(response) {

        }
    });

},
saveGoalStatemntSheet = function(sheet_data){
  
  var auto_save_id = $("#temp_id").val();

  sheet_data.auto_save_id=auto_save_id;

  if(savexhr && savexhr.readyState != 4){
      savexhr.abort();
  }

  savexhr = $.ajax({
     
      url: site_url+"/statement-values/save-statement-values",

      data:sheet_data,
      
      method:"POST",
      
      success: function(response) {
          //do something
      }
  });

},
renameGoalstatementSheet = function(sheet_data){

  var auto_save_id = $("#temp_id").val();

  sheet_data.auto_save_id=auto_save_id;

  if(savexhr && savexhr.readyState != 4){
      savexhr.abort();
  }

  savexhr = $.ajax({
     
      url: site_url+"/statement-values/attr/rename_sheet",

      data:sheet_data,
      
      method:"POST",
      
      success: function(response) {
          //do something
      }
  });
},
getGoalSingleSheet = function(sheet_data, callback){
   
    var auto_save_id = $("#temp_id").val();

    sheet_data.auto_save_id=auto_save_id;


    var xhr = $.ajax({
        url: site_url+"/statement-values/attr/get_sheet",
        data:sheet_data,
        method:"POST",
        success: function(response) {
          
          if(response.status==1){
            callback(response.data);
          }

        }
    });
},
duplicateGoalstatementSheet = function(sheet_data, callback){

    var auto_save_id = $("#temp_id").val();

    sheet_data.auto_save_id=auto_save_id;
   
    var xhr = $.ajax({
        url: site_url+"/statement-values/attr/duplicate_sheet",
        data:sheet_data,
        method:"POST",
        success: function(response) {
          
          if(response.status==1){
            callback(response.data);
          }

        }
    });

},
render_sheet =  function(sheet_row){
        
    var sheet_class=(sheet_row.is_default)?"sheet-container default_sheet":"sheet-container";
    
    if(sheet_row.is_active=="true"){
      sheet_class+=" active";
    }
    
    var html="<div class='"+sheet_class+"' data-is_active='"+sheet_row.is_active+"' data-sheet_id='"+sheet_row.sheet_id+"' data-sheet_number='"+sheet_row.sheet_number+"' data-sheet_name='"+sheet_row.sheet_name+"'>"+
      "<span class='sheet-name'><span class='sheetname'>"+sheet_row.sheet_name+"</span> <span class='action-trigger'></span></span>"+
        "<ul class='sheet-action' data-sheet_id='"+sheet_row.sheet_id+"'>";
        html+="<li class='deletsheet'>Delete</li>";
        html+="<li class='duplicatesheet'>Duplicate</li>"+
          "<li class='renamesheet'>Rename</li>"+
        "</ul>"+
      "</div>";

    var sheet_data={};
      
    sheet_data.html=html;
      
    sheet_data.sheet={id:sheet_row.sheet_id, sheet_number:sheet_row.sheet_number, sheet_name:sheet_row.sheet_name, is_active:sheet_row.is_active};

    return sheet_data;

};

var apply_overflow = function(){

    $("#values .sheet_pages,#statement .sheet_pages").each(function(i,e){

      var sheet_panel = $(e);
     
      var length = sheet_panel.find(".sheet-container").length;
      var sheet_action = $(e).find(".sheet-action:visible");

      if(sheet_action.length){
        sheet_panel.css("overflow-y", "");
        sheet_panel.css("overflow-x", "");
      }else if(length > 12){
        sheet_panel.css("overflow-y", "hidden");
        sheet_panel.css("overflow-x", "scroll");
      }


    });
};
var initPersonal_sheet_toolbar = function(){

        $(".statement-editing-area .sheet-name").unbind("click").click(function(){
            $(this).parent().parent().find(".sheet-container").removeClass("active");
            $(this).parent().parent().find(".sheet-container").attr("data-is_active",false);
            $(this).parent().addClass("active");
            $(this).parent().attr("data-is_active",true);

            $(".sheet-action").hide();

            var attr = $(this).parents(".statement-editing-area").attr("id");

            var sheet_data = $(this).parent().data();
            
            sheet_data.attr=attr;

            var attribut=$(this).parents(".statement-editing-area").find("._editor").attr("id");
            //var note_editable=$(this).parents(".habits-container").find("textarea");

            getGoalSingleSheet(sheet_data, function(data){
                    console.log(data);
                if(data){
                    var content = CKEDITOR.instances[''+attribut+''].setData(data.meta_value);
                }else{
                    var content = CKEDITOR.instances[''+attribut+''].setData('');
                }

            });

            apply_overflow();
            
        });

        $(".statement-editing-area .action-trigger").unbind("click").click(function(e){

            e.stopPropagation();
            
            $(this).parent().parent().find(".sheet-container").removeClass("active");

            $(this).parent().addClass("active");

            $(".sheet-action").hide();

            $(this).parents(".sheet-container:first").find(".sheet-action").show();
            //apply_scroll();
            apply_overflow();

        });


        $(".statement-editing-area .deletsheet").unbind("click").click(function(){

          var sheet_pages = $(this).parents(".sheet_pages").find(".sheet-container");
          
          if(sheet_pages.length==1){
            alert("Sorry, You can not delete this sheet.");
            return false;
          }

          var sheet = $(this).parents(".sheet-container:first"); 
          var attr = sheet.parents(".statement-editing-area").attr("id");

          var last_sheet = sheet.next(".sheet-container");
          
          if(last_sheet.length){
           
            last_sheet.addClass("active");
          }else if(!last_sheet.length){
            var last_sheet = sheet.prev(".sheet-container");
            last_sheet.addClass("active");
          }
          
          sheet.remove();

          var sheet_data = sheet.data();
          
          sheet_data.attr = attr;

          deleteGoalstatementSheet(sheet_data);

          //var attr = last_sheet.parents(".sheet_pages").data("attr");

          if(last_sheet){
            var sheet_data = last_sheet.data();

            sheet_data.attr=attr;

            //var note_editable=last_sheet.parents(".statement-editing-area").find(".contenteditable-textarea");

            var attribut = last_sheet.parents(".statement-editing-area").find("._editor").attr("id");
            
            getGoalSingleSheet(sheet_data, function(data){

                if(data){
                     var content = CKEDITOR.instances[''+attribut+''].setData(data.meta_value);
                  //note_editable.html(data.meta_value);
                }else{
                    var content = CKEDITOR.instances[''+attribut+''].setData('');
                }

            });
          }

          apply_overflow();
          

        });


        $(".statement-editing-area .duplicatesheet").unbind("click").click(function(){

          var sheet = $(this).parents(".sheet-container:first");

          var sheet_data = sheet.data();

          sheet_data.attr = sheet.parents(".statement-editing-area").attr("id");

          duplicateGoalstatementSheet(sheet_data,function(data){
            
            var value = JSON.parse(data.meta_attr);
            
            value.is_active=0;

            var sheet_data = render_sheet(value);
            
            sheet.parent().prepend(sheet_data.html);
            
            //$(sheet_data.html).insertBefore( sheet );  
            
            $(".sheet-action").hide();
            
            apply_overflow();

            initPersonal_sheet_toolbar();

          });

        });



        $(".statement-editing-area .renamesheet").unbind("click").click(function(){

          var sheet = $(this).parents(".sheet-container:first");
          
          var current_name = sheet.find("span.sheetname").html();
          
          var sheetname = prompt("Sheet Name:", current_name);

          if(sheetname){
            sheet.find("span.sheetname").html(sheetname);
            sheet.data("sheet_name",sheetname);
          }

          var sheet_data = sheet.data();
          
          sheet_data.attr = sheet.parents(".statement-editing-area").attr("id");

          /*var note_editable=sheet.parents(".note-editing-area").find(".note-editable");

          var content = note_editable.html();
          
          sheet_data.html = content;*/
          
          $(".sheet-action").hide();
           apply_overflow();

           renameGoalstatementSheet(sheet_data);

        });

};


getGoalstatementSheet = function(attr,sheet_pages){
    
    var auto_save_id = $("#temp_id").val();

    
    var xhr = $.ajax({
        url: site_url+"/statement-values/get-statements-values/byattr",
        data: {auto_save_id:auto_save_id,attr:attr},
        method: "GET",
        success: function(response) {
          
          if(response.status==1){
            $.each(response.data,function(i,d){
                console.log("d is here",d);
               // if(d.meta_attr.attr=='statement'){

                    var sheet_data=render_sheet(d.meta_attr);

                    sheet_pages.append(sheet_data.html); 
                        
                        if(d.meta_attr.is_active == "true")
                        {
                            var attribut =  $(sheet_pages).parents(".statement-editing-area").find("._editor").attr("id");

                            var content = CKEDITOR.instances[''+attribut+''].setData(d.meta_value);
                            
                        }

                // }else{

                //     var sheet_data=render_sheet(d.meta_attr);
                //     sheet_pages.append(sheet_data.html); 

                //     if(d.meta_attr.is_active == "true")
                //         {
                //             var attribut =  $(sheet_pages).parents(".statement-editing-area").find("._editor").attr("id");
                //             var content = CKEDITOR.instances[''+attribut+''].setData(d.meta_value);
                //         }

                // }
            });

            initPersonal_sheet_toolbar();
            apply_overflow();
          }
        }
    });
}
  </script>



@endsection