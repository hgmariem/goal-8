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
CKEDITOR.plugins.add( 'timestamp', {

	// Register the icons. They must match command names.
	icons: 'timestamp',

	// The plugin initialization logic goes inside this method.
	init: function( editor ) {

    /*console.log("timestamp", editor);
*/
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
           init_sheet();
           var sheet=$("#"+editable+"").find(".sheet_pages");
            
           load_sheets(sheet);
           set_editables_height();
           if(sheet.find(".sheet-container").length > 5){
           	
            apply_overflow();
          }

          },0);

          //this.$panel.appendTo(editable_area);
          
          //this.init_sheet();

          //console.log();
          var sheet=$(this.$panel).find(".sheet_pages");

          //load_sheets(sheet);

          

        };

       this.initialize();
      // This methods will be called when editor is destroyed by $('..').summernote('destroy');
      // You should remove elements on `initialize`.
      this.destroy = function() {
        this.$panel.remove();
        this.$panel = null;
      };


      var savexhr;
      var base_url = "http://www.keyhabits.ete/";

      var init_sheet = function(){

          $(".goal-create-details .plus_sheet").unbind("click").click(function(){
          
             var that = $(this);

              var first_goal_txt = $('.first-text').val();
              if (typeof first_goal_txt == 'undefined' || first_goal_txt == '') {
                pop_up_error("You didn't enter anything in input box, Please enter and then continue.");
                return false;
              }

              var sheet_pages = that.next();
              console.log("details here",sheet_pages);
              init_create_sheet(sheet_pages, true);

              apply_overflow();

              set_editables_height(function(done){
                
                if(done){

                  var sheet_pages = that.next();

                  var note_editable=sheet_pages.parents(".cke").find(".cke_contents");

                  //console.log($(note_editable).offset().top);

                  //console.log($(note_editable).data("position"));

                 // scroll_to_editor(note_editable);
                }

              });

          });
      };

      var set_editables_height = function(callback){

          var total_height=0;

          $(".cke_contents").each(function(){

            total_height+=$(this).height()+20;
            $(this).attr("data-position", total_height);
          });

          if(typeof callback === "function"){
            callback(true);
          }
      };

      var scroll_to_editor = function(editing_area){

          //console.log("height: " + $(".fullhalfheightsec")[0].scrollHeight);

          //console.log($(editing_area));
          
          //console.log($(editing_area).data("position"));

          var top_position = $(editing_area).data("position");

          //console.log($(editing_area).scrollTop());

          var extras =  $(".cke_contents").length*40;

          var scrollTop = extras+top_position;

          //$(".fullhalfheightsec").scrollTop(top_position);
          
          $(".fullhalfheightsec").animate({ scrollTop: scrollTop }, "slow");

      };

      var init_create_sheet = function(sheet_pages, init_save){

          /*

          var last_sheet=sheet_pages.find(".sheet-container:last");
          
          if(last_sheet && init_save){

            var note_editable=sheet_pages.parents(".cke").find(".cke_contents");

            save_sheet(last_sheet, sheet_pages);

            note_editable.html("");
          }
          
         */

         var attribut = sheet_pages.parents("header:first").find("._editor").attr("id");
           if(attribut == 'status')
           {
              $preContent = CKEDITOR.instances[''+attribut+''].getData();
              var orginal = $preContent;
              console.log("orginal",orginal);
             
              var output = orginal.replace(/style=".*?"/g, '');
              var content = CKEDITOR.instances[''+attribut+''].setData(output);

           }else{
                var content = CKEDITOR.instances[''+attribut+''].setData('');
           } 

          //var note_editable=sheet_pages.parents(".cke").find(".cke_contents");
          //console.log(sheet_data.html);
   
          //note_editable.html("");

          var sheet_data=prepare_create_sheet(sheet_pages);
          
          var _attr = sheet_pages.data("attr");

          sheet_data.attr=_attr;   

          sheet_pages.prepend(sheet_data.html);

          init_sheet_toolbar();

          addSheet(sheet_data);
          set_scroll(sheet_data.attr);
          var last_sheet=sheet_pages.find(".sheet-container:first");
          
          last_sheet.addClass("active");

          
          save_sheet(last_sheet, sheet_pages);
      };

      var save_sheet = function(sheet, sheet_pages){

          
          var attribut = sheet_pages.parents("header:first").find("._editor").attr("id");
         
          var content = CKEDITOR.instances[''+attribut+''].getData();
         
          //var note_editable=sheet_pages.parents(".cke").find(".cke_contents");

          //var content = note_editable.html();
          
          if(last_sheet_data=sheet.data()){
           
            var _attr = sheet_pages.data("attr");
            
            last_sheet_data.html=content;
            
            last_sheet_data.attr=_attr;
            
            saveSheet(last_sheet_data);

          }
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

      var load_sheets = function(sheet_pages){

            //console.log(sheet_pages);

            var datas= sheet_pages.parents("header:first").find("._editor").data("attrs");
            
            if(datas){

              
              $.each(datas,function(key,value){
                
                var is_default = value.sheet_id=='default'?true:false;
                
                var sheet_row = {};
                sheet_row.sheet_id=value.sheet_id;
                sheet_row.sheet_number=value.sheet_number;
                sheet_row.sheet_name=value.sheet_name;
                sheet_row.is_default=is_default;
                sheet_row.is_active=value.is_active;

                sheet_pages.attr("data-attr",value.attr);

                var sheet_data = render_sheet(sheet_row);
                
                sheet_pages.prepend(sheet_data.html);
              });
              
              init_sheet_toolbar();

            }else{
              init_create_sheet(sheet_pages);

            }

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

      var render_sheet =  function(sheet_row){
        
        var sheet_class=sheet_row.is_default?"sheet-container default_sheet":"sheet-container";
        
        if(sheet_row.is_active){
          sheet_class+=" active";
        }/*else if(sheet_row.is_default){
          sheet_class+=" active";
        }*/

        //console.log(sheet_class);

        var html="<div class='"+sheet_class+"' data-is_active='"+sheet_row.is_active+"' data-sheet_id='"+sheet_row.sheet_id+"' data-sheet_number='"+sheet_row.sheet_number+"' data-sheet_name='"+sheet_row.sheet_name+"'>"+
          "<span class='sheet-name'><span class='sheetname'>"+sheet_row.sheet_name+"</span> <span class='action-trigger'></span></span>"+
            "<ul class='sheet-action' data-sheet_id='"+sheet_row.sheet_id+"'>";

            //if(!sheet_row.is_default){
              html+="<li class='deletsheet'>Delete</li>";
            //}

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

          //var sheet=$(this.$panel[0]).find(".sheet_pages");
          
          $(".goal-create-details .sheet_pages").each(function(i,e){

             

              var sheet_panel = $(e).parents(".sheets-panel:first");
             
              var length = $(e).find(".sheet-container").length;
              
              var sheet_action = $(e).find(".sheet-action:visible");

              if(sheet_action.length){
                $(sheet_panel).css("overflow-y", "");
                $(sheet_panel).css("overflow-x", "");
                $(sheet_panel).removeClass("new-sheet-panel");
              }else if(length > 5){
                $(sheet_panel).css("overflow-y", "hidden");
                $(sheet_panel).css("overflow-x", "scroll");
                $(sheet_panel).addClass("new-sheet-panel");
              }

          });

          //console.log();

          
      };


      var  addSheet =  function(sheet_data){

        console.log("sheet data",sheet_data);

      // console.log("sheet added..");
      if(sheet_data.attr=='status'){

      console.log("welcome",sheet_data.attr);
      var container = $(editor[0]).parent();


      var content = CKEDITOR.instances['status'].getData();
      
      //var prefill_status = $("#prefill_status").html();

      CKEDITOR.instances['status'].setData(content);

      //container.find(".note-editable").html(prefill_status);
      //console.log(editor[0]);
      }

      };

      var init_sheet_toolbar = function(){

        $(".goal-create-details .sheet-name").unbind("click").click(function(){

      
            $(this).parent().parent().find(".sheet-container").removeClass("active");

            $(this).parent().addClass("active");

            //$(".sheet-action").hide();

            //sheet_pages
            var attr = $(this).parents(".sheet_pages").data("attr");

            var sheet_data = $(this).parent().data();
            
            sheet_data.attr=attr;

            var attribut = $(this).parents("header:first").find("._editor").attr("id");
            
            //var note_editable = .children().find(".cke_editable");
           // var note_editable = $("#"+note_edit+"").children().find(".cke_contents");
          
            set_scroll(attr);
            getSheet(sheet_data, function(data){
                var content = CKEDITOR.instances[''+attribut+''].setData(data.meta_value);
                //CKEDITOR.instances['attribut'].insertHtml(data.meta_value)
                //note_editable.html(data.meta_value);

                set_editables_height();
            });

            apply_overflow();
        });

        $(".goal-create-details .action-trigger").unbind("click").click(function(e){

            e.stopPropagation();

            $(".sheet-action").hide();

            $(this).parents(".sheet-container:first").find(".sheet-action").show();
            apply_overflow();

        });


        $(".goal-create-details .deletsheet").unbind("click").click(function(){

          var sheet_pages = $(this).parents(".sheet_pages").find(".sheet-container");
          
          if(sheet_pages.length==1){
            pop_up_error("Sorry, You can not delete this sheet.");
            return false;
          }

          //console.log(sheet_pages.length);


          var sheet = $(this).parents(".sheet-container:first");
          
          var attr = sheet.parents(".sheet_pages").data("attr");

          //console.log(sheet);
          
          var last_sheet = sheet.next(".sheet-container");
          
          if(last_sheet.length){
            //console.log("next found.");
            last_sheet.addClass("active");
          }else if(!last_sheet.length){
            //console.log("prev found.");
            var last_sheet = sheet.prev(".sheet-container");
            last_sheet.addClass("active");
          }
          /*if(last_sheet){
            last_sheet.addClass("active");
          }*/
          
          sheet.remove();

          var sheet_data = sheet.data();
          
          sheet_data.attr = attr;

          deleteSheet(sheet_data);

          //var attr = last_sheet.parents(".sheet_pages").data("attr");

          if(last_sheet){
            var sheet_data = last_sheet.data();

            sheet_data.attr=attr;


            var attribut = last_sheet.parents("header:first").find("._editor").attr("id");
            
          
            //var note_editable=last_sheet.parents(".cke").find(".cke_contents");

            getSheet(sheet_data, function(data){

              var content = CKEDITOR.instances[''+attribut+''].setData(data.meta_value);

              //note_editable.html(data.meta_value);

              set_editables_height();
            });
          }

          apply_overflow();

        });


        $(".goal-create-details .duplicatesheet").unbind("click").click(function(){

          var sheet = $(this).parents(".sheet-container:first");

          //console.log(sheet);

          var sheet_data = sheet.data();

          sheet_data.attr = sheet.parent().data("attr");

          //console.log(sheet_data);

          duplicateSheet(sheet_data,function(data){
            
            var value = JSON.parse(data.meta_attr);
            
            value.is_active=0;

            var sheet_data = render_sheet(value);
            
            sheet.parent().prepend(sheet_data.html);
            
            //$(sheet_data.html).insertBefore( sheet );  
            
            $(".sheet-action").hide();
            
            apply_overflow();

            init_sheet_toolbar();

          });

        });



        $(".goal-create-details .renamesheet").unbind("click").click(function(){

          var sheet = $(this).parents(".sheet-container:first");
          
          var current_name = sheet.find("span.sheetname").html();
          
          var sheetname = prompt("Sheet Name:", current_name);

          if(sheetname){
            sheet.find("span.sheetname").html(sheetname);
            sheet.data("sheet_name",sheetname);
          }

          var sheet_data = sheet.data();
          
          sheet_data.attr = sheet.parent().data("attr");

          /*var note_editable=sheet.parents(".cke").find(".cke_contents");

          var content = note_editable.html();
          
          sheet_data.html = content;*/
          
          $(".sheet-action").hide();
           apply_overflow();

           renameSheet(sheet_data);

        });

      };

	}
});