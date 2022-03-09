$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


jQuery(document).ready(function() {


    init_save_button();

    addto_lobby();

  statement_key();

  screen_max();
  screen_min();
 
    //init_statement_editable();
});

var statement_key =function(){
        
        $(".statement-panel .plus_sheet button").on('click', function() {

             $.tloader("show","Loading");
          
            var elem=$(this).parents(".statement-panel");
            
            var sheet_pages=$(elem).find(".sheet_pages");
            var sheet_data=prepare_create_sheet(elem);
            
            sheet_pages.append(sheet_data.html);
            var last_sheet=sheet_pages.find(".sheet-container:last");
            
            sheet_pages.find(".sheet-container").removeClass("active").attr("data-is_active",false);
            sheet_pages.find(".sheet-container:last").addClass("active").attr("data-is_active",true);

            var content = CKEDITOR.instances["content"].setData('');
            //$(elem).parents(".habits-container").find(".contenteditable-textarea").html('');
            $(".show_in_lobby").prop("checked",false);
            personal_save_sheet(last_sheet, sheet_pages);
            init_personal_sheet_toolbar();
            if($(this).hasClass("isMobile"))
            {
                apply_Mobileoverflow();
            }
            else
            {
                apply_Personaloverflow();
            }
            
        });
        
        getAllPersonalStatementSheet({},function(data){

           $.tloader("show","Loading");

            $.each(data,function(i,d){

                if(d.meta_attr.attr=='statement'){
                    var elem=$(".statement-panel");
                    var sheet_pages=$(elem).find(".sheet_pages");
                    var sheet_data=render_sheet(d.meta_attr);

                    sheet_pages.append(sheet_data.html); 

                    console.log("d.show_in_lobby",d.show_in_lobby);

                    if(d.is_active == 1 && d.show_in_lobby == 1)
                    {
                      $(".show_in_lobby").prop("checked",true);   
                    }
                    
                        if(d.is_active == 1)
                        {
                          var content = CKEDITOR.instances["content"].setData(d.meta_value);
                            //$("#personal_statement_box").html();
                        }
                }
            });

            $.tloader("hide");
            init_personal_sheet_toolbar();
            if($(".sheet_pages").hasClass("isMobile"))
            {
                apply_Mobileoverflow();
            }
            else
            {
                apply_Personaloverflow();
            }
            
            $(".contenteditable-textarea#personal_statement_box").bind('focus', function() {
             $(".sheet-action").hide();
             apply_Personaloverflow();
            });

            $(".contenteditable-textarea#personal_statement_box").bind('input propertychange', function() {

              var current_sheet=$(this).parents(".habits-container").find(".sheet-container.active");

              var sheet_container=$(this).parents(".habits-container").find(".sheet_pages");
            
              personal_save_sheet(current_sheet, sheet_container);

            });
      });

}

var init_statement_editable = function(){

    $(".statement-value-row").unbind("dblclick").bind("dblclick",function(){
        $this = $(this);
        $this.attr('contenteditable', "true");
        //$this.blur();
        $this.focus();
    });

    $(".statement-value-row").unbind("blur").bind("blur",function(){
        $this = $(this);
        $this.attr('contenteditable', "false");
        //$this.blur();
        //$this.focus();
    });
}

var serialize_statement_values = function(){

    var statement_values=[];

    $("._editor").each(function(){


        var row ={};
        var sheet_id = $(this).data("id");
        row.id=sheet_id;
        var type = $(this).data("type");
        row.meta_type=type;

        if(type == "statement")
        {
            var desc = CKEDITOR.instances['statement_'+sheet_id].getData();
            row.html=desc;
        }else{
            var desc = CKEDITOR.instances['values_'+sheet_id].getData();
            row.html= desc;
        }

        
        row.addto_lobby = $("#addto_lobby_"+row.id).is(":checked")?1:0;
        statement_values.push(row);
    });

    return statement_values;
};

var init_save_button = function(){

    $(".statement_submit_btn .common_btn").unbind("touchstart click").bind("touchstart click",function(){

        var sheet_data = {};
        
    var _sheet_data =$(".personal-statement .sheet-container.active").data();

        var sheet_id = (_sheet_data.sheet_id)?_sheet_data.sheet_id:(Math.floor(100000000 + Math.random() * 900000000));

        var sheet_name = (_sheet_data.sheet_name)?_sheet_data.sheet_name:generate_sheet_name();//;
        
        var show_in_lobby = $(".show_in_lobby").prop('checked');

        var html = CKEDITOR.instances["content"].getData();

        var personal_statement_id = $("#single_statement_id").val();

        sheet_data.id= personal_statement_id;
        sheet_data.is_active= true;
        sheet_data.sheet_id= sheet_id;
        sheet_data.sheet_number= 0;
        sheet_data.sheet_name= sheet_name;
        sheet_data.html= html;
        sheet_data.attr= "statement";
        sheet_data.show_in_lobby = show_in_lobby?1:0;

        sheet_data.extras = serialize_statement_values();

        var xhr = $.ajax({
            url: site_url+"/statement-values/save_statement",
            data:sheet_data,
            method:"POST",
            success: function(response) {
                
                if(response.status==1){
                    $("#single_statement_id").val(response.data.id);
                }

                $.notify("Personal Statement Added successfully", {
                        type:'success',
                        z_index: 999999999,
                });
            }
        });

    });
};


var addto_lobby = function(){

    $('.show_in_lobby').on('change', function(event){

        console.log("Reached here..........");
        
        var show_in_lobby= ($(this).prop('checked'))?1:0;
        addto_lobby_request(this,show_in_lobby);
    });

};

var addto_lobby_request = function(elem, show_in_lobby){

    var sheet_data = {};
    sheet_data.id= $(elem).data("value");
    sheet_data.show_in_lobby=show_in_lobby;
    var xhr = $.ajax({
        url: site_url+"/statement-values/addto_lobby",
        data:sheet_data,
        method:"POST",
        success: function(response) {
            
        }
    });

};

var render_sheet =  function(sheet_row){
        
    var sheet_class=sheet_row.is_default?"sheet-container default_sheet":"sheet-container";
    
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

var prepare_create_sheet = function(elem){
 
  var total_sheets=$(elem).find(".sheet-container").length;

  var is_default=(!total_sheets)?true:false;

  var id=(is_default)?"default":(Math.floor(100000000 + Math.random() * 900000000));

  var sheet_number=$(elem).find(".sheet-container").length+1;
  
  var sheet_name=generate_sheet_name();
  
  $(elem).find(".sheet-container").removeClass("active");

  var sheet_row = {};
  sheet_row.sheet_id=id;
  sheet_row.sheet_number=sheet_number;
  sheet_row.sheet_name=sheet_name;
  sheet_row.is_default=is_default;
  sheet_row.is_active=is_default;

  return render_sheet(sheet_row);

};

var generate_sheet_name = function(){

  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; //January is 0!

  var yyyy = today.getFullYear();
  if(dd<10){
      dd='0'+dd;
  } 
  if(mm<10){
      mm='0'+mm;
  } 
  var today = dd+'.'+mm+'.'+yyyy;
  return today;
};

var apply_overflow = function(){

    $(".values-panel .sheet_pages,.statement-panel .sheet_pages").each(function(i,e){

      var sheet_panel = $(e);
     
      var length = sheet_panel.find(".sheet-container").length;
      var sheet_action = $(e).find(".sheet-action:visible");

      if(sheet_action.length){
        sheet_panel.css("overflow-y", "");
        sheet_panel.css("overflow-x", "");
      }else if(length > 5){
        sheet_panel.css("overflow-y", "hidden");
        sheet_panel.css("overflow-x", "scroll");
      }


    });
}


var apply_scroll = function(){

    
      var length = $(".statement-panel").find(".sheet-container").length;
       if(length > 5){
        //$(".statement-panel").css("overflow-y", "hidden");
        $(".statement-panel").css("overflow-x", "scroll");
      }
}


var apply_Mobileoverflow = function(force){

    console.log("overflow applied...");

    console.log("force:"+force);

    if(force){
      var sheet_panel = $(".sheets-panel .sheet_pages");
      sheet_panel.css("overflow-y", "");
      sheet_panel.css("overflow-x", "");
    }else{

      $(".sheets-panel .sheet_pages").each(function(i,e){

        var sheet_panel = $(e);
        var length = sheet_panel.find(".sheet-container").length;
        var sheet_action = $(e).find(".sheet-action:visible");
        if(sheet_action.length){
          sheet_panel.css("overflow-y", "");
          sheet_panel.css("overflow-x", "");
        }else if(length > 3){
          sheet_panel.css("overflow-y", "hidden");
          sheet_panel.css("overflow-x", "scroll");
        }

      });
    }
}

var apply_Personaloverflow = function(force){

    console.log("overflow applied...");

    console.log("force:"+force);

    if(force){
      var sheet_panel = $(".sheets-panel .sheet_pages");
      sheet_panel.css("overflow-y", "");
      sheet_panel.css("overflow-x", "");
    }else{

      $(".sheets-panel .sheet_pages").each(function(i,e){

        var sheet_panel = $(e);
        var length = sheet_panel.find(".sheet-container").length;
        var sheet_action = $(e).find(".sheet-action:visible");
        
        if(sheet_action.length){
          sheet_panel.css("overflow-y", "");
          sheet_panel.css("overflow-x", "");
        }else if(length > 15){
          sheet_panel.css("overflow-y", "hidden");
          sheet_panel.css("overflow-x", "scroll");
        }

      });
    }
}


var savePersonalSheet = function(sheet_data){

  if(savexhr && savexhr.readyState != 4){
      savexhr.abort();
  }

  savexhr = $.ajax({
     
      url: site_url+"/statement-values/save-personal-statement-values",

      data:sheet_data,
      
      method:"POST",
      
      success: function(response) {
        
      }
  });

}

var initPersonal_sheet_toolbar = function(){

        $(".statement-editing-area .sheet-name").unbind("click").click(function(){
            $(this).parent().parent().find(".sheet-container").removeClass("active");
            $(this).parent().parent().find(".sheet-container").attr("data-is_active",false);
            $(this).parent().addClass("active");
            $(this).parent().attr("data-is_active",true);

            $(".sheet-action").hide();

            var attr = $(this).parents(".sheet_pages").data("attr");

            var sheet_data = $(this).parent().data();
            
            sheet_data.attr=attr;

            //var note_editable=$(this).parents(".statement-editing-area").find(".contenteditable-textarea");
            //var note_editable=$(this).parents(".habits-container").find("textarea");

            getGoalSingleSheet(sheet_data, function(data){
                
                if(data){

                  var content = CKEDITOR.instances["content"].setData(data.meta_value);

                    //note_editable.html(data.meta_value);
                }

                if(data.show_in_lobby){
                  $(".show_in_lobby").prop("checked",true); 
                }else{
                  $(".show_in_lobby").prop("checked",false);
                }

            });

            apply_overflow();
            $.tloader("hide");
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
          var attr = sheet.parents(".sheet_pages").data("attr");

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

            var note_editable=last_sheet.parents(".statement-editing-area").find(".contenteditable-textarea");
            
            getGoalSingleSheet(sheet_data, function(data){

                if(data){

                  var content = CKEDITOR.instances["content"].setData(data.meta_value);

                  if(data.show_in_lobby){
                  $(".show_in_lobby").prop("checked",true); 
                  }else{
                    $(".show_in_lobby").prop("checked",false);
                  }

                  //note_editable.html(data.meta_value);
                }

            });
          }

          apply_overflow();
          $.tloader("hide");

        });


        $(".statement-editing-area .duplicatesheet").unbind("click").click(function(){

          var sheet = $(this).parents(".sheet-container:first");

          var sheet_data = sheet.data();

          sheet_data.attr = sheet.parent().data("attr");

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
          
          sheet_data.attr = sheet.parent().data("attr");

          /*var note_editable=sheet.parents(".note-editing-area").find(".note-editable");

          var content = note_editable.html();
          
          sheet_data.html = content;*/
          
          $(".sheet-action").hide();
           apply_overflow();

           renameGoalstatementSheet(sheet_data);

        });

};


var init_personal_sheet_toolbar = function(){

        $(".personal-statement .sheet-name").unbind("click").click(function(){

           $.tloader("show","Loading");
           
           if($(this).parents(".sheet_pages").hasClass("isMobile"))
            {
                apply_Mobileoverflow();
            }
            else
            {
                apply_Personaloverflow();
            }
            
            
            $(this).parent().parent().find(".sheet-container").removeClass("active");

            $(this).parent().addClass("active");

            $(".sheet-action").hide();

            //sheet_pages
            var attr = $(this).parents(".sheet_pages").data("attr");

            var sheet_data = $(this).parent().data();
            
            sheet_data.attr=attr;

            var note_editable=$(this).parents(".habits-container").find(".contenteditable-textarea");

            getPersonalStatementSheet(sheet_data, function(data){
                
                if(data){

                    var content = CKEDITOR.instances["content"].setData(data.meta_value);

                  if(data.show_in_lobby){
                  $(".show_in_lobby").prop("checked",true); 
                  }else{
                    $(".show_in_lobby").prop("checked",false);
                  }
                    
                     $.tloader("hide");
                }

            });

        });

        $(".action-trigger").unbind("click").click(function(e){
            e.stopPropagation();
            $(this).parent().parent().parent().find(".sheet-container").removeClass("active");

            $(this).parent().parent().addClass("active");
           
            $(".sheet-action").hide();

            $(this).parents(".sheet-container:first").find(".sheet-action").show();
            
            var checkMob = $(this).parents(".sheet_pages").hasClass("isMobile");

            if(checkMob)
            {
                apply_Mobileoverflow();
            }
            else
            {
                apply_Personaloverflow();
            }
            
        });


        $(".personal-statement .deletsheet").unbind("click").click(function(){

          var checkMob  = $(this).parents(".sheet_pages").hasClass("isMobile");
          var sheet_pages = $(this).parents(".sheet_pages").find(".sheet-container");
          if(sheet_pages.length==1){
            alert("Sorry, You can not delete this sheet.");
            //pop_up_error("Sorry, You can not delete this sheet.");
            return false;
          }

          var sheet = $(this).parents(".sheet-container:first");
          
          var attr = sheet.parents(".sheet_pages").data("attr");

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

          deletePersonalSheet(sheet_data);

          //var attr = last_sheet.parents(".sheet_pages").data("attr");

          if(last_sheet){
            var sheet_data = last_sheet.data();

            sheet_data.attr=attr;

            var note_editable=last_sheet.parents(".habits-container").find(".contenteditable-textarea");

            getPersonalStatementSheet(sheet_data, function(data){

                if(data){

                  var content = CKEDITOR.instances["content"].setData(data.meta_value);
                  //note_editable.html(data.meta_value);

                  if(data.show_in_lobby){
                  $(".show_in_lobby").prop("checked",true); 
                  }else{
                    $(".show_in_lobby").prop("checked",false);
                  }

                }

            });
          }
          if(checkMob)
          {
            apply_Mobileoverflow();
          }
          else
          {
            apply_Personaloverflow(true);
          }

        });


        $(".personal-statement .duplicatesheet").unbind("click").click(function(){

          var sheet = $(this).parents(".sheet-container:first");
          var checkMob  = $(this).parents(".sheet_pages").hasClass("isMobile");

          var sheet_data = sheet.data();

          sheet_data.attr = sheet.parent().data("attr");

          duplicatePersonalSheet(sheet_data,function(data){
            
            var value = JSON.parse(data.meta_attr);
            
            value.is_active=0;

            var sheet_data = render_sheet(value);
            
            sheet.parent().prepend(sheet_data.html);
            
            //$(sheet_data.html).insertBefore( sheet );  
            
            $(".sheet-action").hide();
            
            if(checkMob)
              {
                apply_Mobileoverflow();
              }
              else
              {
                apply_Personaloverflow(true);
              }

            init_personal_sheet_toolbar();

          });

        });

        $(".personal-statement .renamesheet").unbind("click").click(function(){

          var sheet = $(this).parents(".sheet-container:first");
          var checkMob  = $(this).parents(".sheet_pages").hasClass("isMobile");
          
          var current_name = sheet.find("span.sheetname").html();
          
          var sheetname = prompt("Sheet Name:", current_name);

          if(sheetname){
            sheet.find("span.sheetname").html(sheetname);
            sheet.data("sheet_name",sheetname);
          }

          var sheet_data = sheet.data();
          
          sheet_data.attr = sheet.parent().data("attr");

          /*var note_editable=sheet.parents(".note-editing-area").find(".note-editable");

          var content = note_editable.html();
          
          sheet_data.html = content;*/
          
          $(".sheet-action").hide();
           if(checkMob)
              {
                apply_Mobileoverflow();
              }
              else
              {
                apply_Personaloverflow(true);
              }
           renamePersonalSheet(sheet_data);

        });

};


var personal_save_sheet = function(sheet, sheet_pages){

  var note_editable=sheet_pages.parents(".habits-container").find(".contenteditable-textarea");

  var content = CKEDITOR.instances["content"].getData();

  //var content = note_editable.html();
  
  if(last_sheet_data=sheet.data()){
    var _attr = sheet_pages.data("attr");
    last_sheet_data.html=content;
    last_sheet_data.attr=_attr;
    savePersonalSheet(last_sheet_data);
  }

};


var savePersonal_sheet = function(sheet, sheet_pages){

  var note_editable=sheet_pages.parents(".statement-editing-area").find(".contenteditable-textarea");

  var content = note_editable.html();
  
  if(last_sheet_data=sheet.data()){
    var _attr = sheet_pages.data("attr");
    last_sheet_data.html=content;
    last_sheet_data.attr=_attr;
    saveGoalStatemntSheet(last_sheet_data);
  }

};

var savexhr;

var addSheet = function(sheet_data){
  if(sheet_data.attr=='status'){

    var container = $(editor[0]).parent();
    
    var prefill_status = $("#prefill_status").html();
    
    container.find(".note-editable").html(prefill_status);
    
  }

},
deleteGoalstatementSheet = function(sheet_data){
  
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

deletePersonalSheet = function(sheet_data){
  
    var xhr = $.ajax({
        url: site_url+"/statement-values/attr/delete_personal-sheet",
        data:sheet_data,
        method:"POST",
        success: function(response) {

        }
    });
    },
savePersonalSheet = function(sheet_data){
 
  if(savexhr && savexhr.readyState != 4){
      savexhr.abort();
  }

  savexhr = $.ajax({
     
      url: site_url+"/statement-values/save-personal-statement-values",

      data:sheet_data,
      
      method:"POST",
      
      success: function(response) {
         $.tloader("hide");
          //do something
      }
  });

},
renamePersonalSheet = function(sheet_data){

  if(savexhr && savexhr.readyState != 4){
      savexhr.abort();
  }

  savexhr = $.ajax({
     
      url: site_url+"/statement-values/attr/personal-rename_sheet",

      data:sheet_data,
      
      method:"POST",
      
      success: function(response) {
          //do something
      }
  });
},
getPersonalStatementSheet = function(sheet_data, callback){
      
    var xhr = $.ajax({
        url: site_url+"/personal-statement-values/attr/get_sheet",
        data:sheet_data,
        method:"POST",
        success: function(response) {
         
          if(response.status==1){
            callback(response.data);
          }

        }
    });
},
duplicatePersonalSheet = function(sheet_data, callback){

    var xhr = $.ajax({
        url: site_url+"/statement-values/attr/duplicate_personal-sheet",
        data:sheet_data,
        method:"POST",
        success: function(response) {
          
          if(response.status==1){
            callback(response.data);
          }

        }
    });

},

getAllPersonalStatementSheet = function(sheet_data, callback){
    
    $.tloader("show","Loading...");

    var xhr = $.ajax({
        url: site_url+"/statement-values/get-personal-statements-values",
        data: sheet_data,
        method: "GET",
        success: function(response) {
          
          if(response.status==1){
            callback(response.data);
          }
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

getGoalstatementSheet = function(sheet_data, callback){
    
    var auto_save_id = $("#temp_id").val();

    sheet_data.auto_save_id=auto_save_id;

    $.tloader("show","Loading...");

    var xhr = $.ajax({
        url: site_url+"/statement-values/get-statements-values",
        data: sheet_data,
        method: "GET",
        success: function(response) {
          
          if(response.status==1){
            callback(response.data);
          }
        }
    });
}

var set_scroll = function(note_editable){

        var position = $("#"+note_editable+"-container").offset().top;
        var div_height = $("#"+note_editable+"-container").height();
        
        var scroll = $(".goal-create-details").scrollTop();
       
        if(parseInt(position) > parseInt(scroll) && parseInt(position) > 45)
        {
            position = position+scroll-75;
           $(".goal-create-details").animate({ scrollTop:position}, 1000); 
        }
        else if(position < scroll && position > 45 && scroll > position)
        {
            new_position = position+scroll-75;
           $(".goal-create-details").animate({ scrollTop:new_position}, 1000); 
        }
        else if(position < scroll && position < 0 && scroll > 0)
        {
               new_position = scroll+position-75; 
               $(".goal-create-details").animate({ scrollTop:new_position}, 1000);   
        }
        else
        {
          $(".goal-create-details").animate({ scrollTop:scroll}, 1000);  
        }
      
};



var screen_max = function(){
    /*$(document).on('touchstart click', ".statement_maximizer", function(event) {
        event.preventDefault();
        //var view_type=$("div.task__list-type>button:disabled").attr("gid");
        //console.log(view_type);
        var url = site_url+'/statement-values/statement-view';
        window.location.href = url;
    });*/
}

var screen_min = function(){
    /*$(document).on('touchstart click', ".statement_minimizer", function(event) {
        event.preventDefault();
        //var view_type=$("div.task__list-type>button:disabled").attr("gid");
        //console.log(view_type);
        var url = site_url+'/statement-values/';
        window.location.href = url;
    });*/
}





                


