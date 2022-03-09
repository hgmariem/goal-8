/**
 * Copyright (c) 2014-2018, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * Basic sample plugin inserting current date and time into the CKEditor editing area.
 *
 * Created out of the CKEditor Plugin SDK:
 * http://docs.ckeditor.com/ckeditor4/docs/#!/guide/plugin_sdk_intro
 */

// Register the plugin within the editor.
CKEDITOR.plugins.add( 'statement', {


	// Register the icons. They must match command names.
	icons: 'statement',

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

	  // This method will be called when editor is initialized by $('..').summernote();
      // You can create elements for plugin
        this.initialize = function() {
          var _attr = editor.element.$.name;
          

          $html_panel="<div>"+
          "<div class='plus_sheet'><button type='button'>+</button></div>"+
          "<div class='sheet_pages' data-attr='"+_attr+"'></div>"+
          "</div>";

          this.$panel = $('<div class="sheets-panel">'+$html_panel+'</div>').css({
            //position: 'relative',
            // width: '100%',
            //background: 'red'
          });


          let _that = this;

          setTimeout(function(){
          var editable_area =  editor.container.$;
          /*console.log("editable_area",_that.$panel);
         */
          var editable = $(editable_area).attr("id");
          
          $(_that.$panel[0].outerHTML).appendTo($("#"+editable+""));
           statement_values(); 

          var sheet=$("#"+editable+"").find(".sheet_pages");

           getGoalstatementSheet(_attr,sheet);

           initPersonal_sheet_toolbar();
            apply_overflow();

          },0);   

          var sheet=$(this.$panel).find(".sheet_pages");      

        };

       this.initialize();
      // This methods will be called when editor is destroyed by $('..').summernote('destroy');
      // You should remove elements on `initialize`.
      this.destroy = function() {
        this.$panel.remove();
        this.$panel = null;
      };

function statement_values(sheet){

  
        $("#statement .plus_sheet button").unbind('touchstart click').bind('touchstart click', function() {

            var elem=$(this).parents(".sheets-panel");

            var sheet_pages=$(elem).find(".sheet_pages");
            var sheet_data=prepare_create_sheet(elem);
            sheet_pages.append(sheet_data.html);
            var last_sheet=sheet_pages.find(".sheet-container:last");
          
            sheet_pages.find(".sheet-container").removeClass("active").attr("data-is_active",false);
            sheet_pages.find(".sheet-container:last").addClass("active").attr("data-is_active",true);

            
            var attribut =  $(elem).parents(".statement-editing-area").find("._editor").attr("id");
            var content = CKEDITOR.instances[''+attribut+''].setData('');
            
            savePersonal_sheet(last_sheet, sheet_pages);
            initPersonal_sheet_toolbar();
            apply_overflow();
        });


        $("#values .plus_sheet button").unbind('touchstart click').bind('touchstart click', function() {

            
            var elem=$(this).parents(".sheets-panel");
            var sheet_pages=$(elem).find(".sheet_pages");
            var sheet_data=prepare_create_sheet(elem);
            
            sheet_pages.append(sheet_data.html);
            var last_sheet=sheet_pages.find(".sheet-container:last");
            //last_sheet.addClass("active").attr("data-is_active",true);

            sheet_pages.find(".sheet-container").removeClass("active").attr("data-is_active",false);
            sheet_pages.find(".sheet-container:last").addClass("active").attr("data-is_active",true);

            var attribut =  $(elem).parents(".statement-editing-area").find("._editor").attr("id");
            var content = CKEDITOR.instances[''+attribut+''].setData('');
            last_sheet.content = content;
            last_sheet.attr = attribut;

            savePersonal_sheet(last_sheet, sheet_pages);
            initPersonal_sheet_toolbar();
            apply_overflow();
        });
           

        $(".contenteditable-textarea#statement, .contenteditable-textarea#values").bind('focus', function() {
             $(".sheet-action").hide();
             apply_overflow();
        });

        $(".statement-editing-area#statement, .statement-editing-area#values").bind('input propertychange', function() {

          var current_sheet=$(this).parents(".statement-editing-area").find(".sheet-container.active");

          var sheet_container=$(this).parents(".statement-editing-area").find(".sheet_pages");

          savePersonal_sheet(current_sheet, sheet_container);

        });

    
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

var apply_Personaloverflow = function(force){

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

	}
});