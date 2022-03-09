(function(factory) {
  /* global define */
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else if (typeof module === 'object' && module.exports) {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(window.jQuery);
  }
}(function($) {
  // Extends plugins for adding hello.
  //  - plugin is external module for customizing.
  $.extend($.summernote.plugins, {
    /**
     * @param {Object} context - context object has status of editor.
     */
    'sheet': function(context) {

      var self = this;

      
      // ui has renders to build ui elements.
      //  - you can create a button with `ui.button`
      var ui = $.summernote.ui;

      var $editor = context.layoutInfo.editor;
      var options = context.options;
      var lang = options.langInfo;

      // This events will be attached when editor is initialized.
      this.events = {
        // This will be called after modules are initialized.
        'summernote.init': function(we, e) {
          //console.log('summernote initialized', we, e);
          //console.log("init called...");
        },
        // This will be called when user releases a key on editable.
        'summernote.keyup': function(we, e) {
          
          $(".sheet-action").hide();

          apply_overflow();
          
          //console.log("keyup is called....");
          
          set_editables_height();
          

          /*
          console.log("keyup is called....");

          console.log(e);

          var current_sheet=$(e.target).next(".sheets-panel").find(".sheet-container.active");

          var sheet_container=$(e.target).next(".sheets-panel").find(".sheet_pages");
          
          save_sheet(current_sheet, sheet_container);*/

        },
        'summernote.enter': function(we, e){
          
          $(".sheet-action").hide();

          apply_overflow();

          set_editables_height();
          

          //console.log("enter is called....");
        },
        'summernote.focus': function(we, e) {
          
          $(".sheet-action").hide();

          apply_overflow();
          
          //console.log("focus is called....");
        },
        'summernote.paste': function(we, e){

          var thisNote = $(this);
          
          var updatePastedText = function(someNote){
                var original = someNote.summernote('code');
                var cleaned = CleanPastedHTML(original);
                someNote.summernote('code', cleaned);
                //someNote.summernote('').summernote(cleaned);
            };
            setTimeout(function () {
                //this kinda sucks, but if you don't do a setTimeout, 
                //the function is called before the text is really pasted.
                updatePastedText(thisNote);
            }, 10);
          //console.log("paste is called....");

          /*var current_sheet=$(e.target).next(".sheets-panel").find(".sheet-container.active");

          var sheet_container=$(e.target).next(".sheets-panel").find(".sheet_pages");
          
          save_sheet(current_sheet, sheet_container);*/

          set_editables_height();
          
        },
    'summernote.change': function(e, content){

          //console.log("change is called....");

          //console.log(we);

          //console.log(e);

          var current_sheet=$(e.target).parent().find(".sheets-panel").find(".sheet-container.active");

          var sheet_container=$(e.target).parent().find(".sheets-panel").find(".sheet_pages");
          
          save_sheet(current_sheet, sheet_container);
          
          set_editables_height();
          

        }

      };

      // This method will be called when editor is initialized by $('..').summernote();
      // You can create elements for plugin
      this.initialize = function() {

        //console.log("initialize called...");

        var _attr = $editor.prev("textarea:first").attr("name");

        $html_panel="<div>"+
        "<div class='plus_sheet'><button type='button'>+</button></div>"+
        "<div class='sheet_pages' data-attr='"+_attr+"'></div>"+
        "</div>";

        this.$panel = $('<div class="sheets-panel">'+$html_panel+'</div>').css({
          //position: 'relative',
          // width: '100%',
          //background: 'red'
        });

        //console.log($editor);

        var editable_area=$editor.find(".note-editing-area");

        this.$panel.appendTo(editable_area);
        
        this.init_sheet();

        //console.log();
        var sheet=$(this.$panel[0]).find(".sheet_pages");

        load_sheets(sheet);

        if(sheet.find(".sheet-container").length > 5){
          apply_overflow();
        }

      };

      // This methods will be called when editor is destroyed by $('..').summernote('destroy');
      // You should remove elements on `initialize`.
      this.destroy = function() {
        this.$panel.remove();
        this.$panel = null;
      };

      this.init_sheet = function(){

          $(".goal-create-details .plus_sheet").unbind("click").click(function(){
             
             var that = $(this);

              var first_goal_txt = $('.first-text').val();
              if (typeof first_goal_txt == 'undefined' || first_goal_txt == '') {
                pop_up_error("You didn't enter anything in input box, Please enter and then continue.");
                return false;
              }

              var sheet_pages = that.next();
              
              init_create_sheet(sheet_pages, true);

              apply_overflow();

              set_editables_height(function(done){
                
                if(done){

                  var sheet_pages = that.next();

                  var note_editable=sheet_pages.parents(".note-editing-area").find(".note-editable");

                  //console.log($(note_editable).offset().top);

                  //console.log($(note_editable).data("position"));

                 // scroll_to_editor(note_editable);
                }

              });

              

          });
      };


      var set_editables_height = function(callback){

          var total_height=0;

          $(".note-editable").each(function(){

            total_height+=$(this).height()+40;
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

          var extras =  $(".note-editable").length*40;

          var scrollTop = extras+top_position;

          //$(".fullhalfheightsec").scrollTop(top_position);
          
          $(".fullhalfheightsec").animate({ scrollTop: scrollTop }, "slow");

      };

      var init_create_sheet = function(sheet_pages, init_save){

          /*

          var last_sheet=sheet_pages.find(".sheet-container:last");
          
          if(last_sheet && init_save){

            var note_editable=sheet_pages.parents(".note-editing-area").find(".note-editable");

            save_sheet(last_sheet, sheet_pages);

            note_editable.html("");
          }
          
         */

          var note_editable=sheet_pages.parents(".note-editing-area").find(".note-editable");

          note_editable.html("");

          var sheet_data=prepare_create_sheet(sheet_pages);
          
          var _attr = sheet_pages.data("attr");

          sheet_data.attr=_attr;   

          sheet_pages.prepend(sheet_data.html);

          init_sheet_toolbar();

          options.sheet.addSheet(sheet_data);
          set_scroll(sheet_data.attr);
          var last_sheet=sheet_pages.find(".sheet-container:first");
          
          last_sheet.addClass("active");

          save_sheet(last_sheet, sheet_pages);
      };

      var save_sheet = function(sheet, sheet_pages){

          //console.log("save called...");

          var note_editable=sheet_pages.parents(".note-editing-area").find(".note-editable");

          var content = note_editable.html();
          
          if(last_sheet_data=sheet.data()){
            var _attr = sheet_pages.data("attr");
            last_sheet_data.html=content;
            last_sheet_data.attr=_attr;
            options.sheet.saveSheet(last_sheet_data);

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

                var sheet_data = render_sheet(sheet_row);
                
                sheet_pages.prepend(sheet_data.html);
              });
              
              init_sheet_toolbar();

            }else{

              init_create_sheet(sheet_pages);

            }

      };


      var CleanPastedHTML = function(data)
      {

        var pastedData = data;

        /*if (/<!--StartFragment-->([^]*)<!--EndFragment-->/.test(pastedData)) {*/
         //console.log(pastedData);
        var result = pastedData.replace(/<p class="MsoNormal">([\s\S]*?)<\/p>/g, "<p>$1</p>\n");
        // Fix titles
        result = result.replace(/<p class="MsoTitle">([\s\S]*?)<\/p>/g, "## $1");
        var $desc = $('<div>' + result + '</div>');
        $desc.contents().each(function() {
          if (this.nodeType == 8) { // nodeType 8 = comments
            $(this).remove();
          }
        });

          var firstItems = $desc.find('p').filter(function() {
          return /MsoList.*?First/g.test(this.className);
        });

        var lists = [];

        firstItems.each(function() {
          lists.push($(this).nextUntil('.MsoListParagraphCxSpLast').addBack().next().addBack());
        });

        // Add lists with one item
        lists.push($desc.find(".MsoListParagraph"));

        // Change between ordered and un-ordered lists
        if (lists.length != 0) {
          lists.forEach(function(list) {
            if (list.length > 0) {
              if (/[\s\S]*?(Symbol|Wingdings)[\s\S]*?/.test(list.html()))
                var unordered = true;
              else if (/[^0-9]/.test(list.text()[0]))
                var unordered = true;

              list.each(function() {
                if (/[\s\S]*?level[2-9][\s\S]*/.test(this.outerHTML))
                  var nested = true;

                var $this = $(this);
                if (unordered)
                  var newText = $this.text().replace(/[^0-9]([\s\S]*)/, "$1");
                else {
                  var newText = $this.text().replace(/[0-9]*?[0-9](\.|\))([\s\S]*)/, "$2");
                }

                $this.html(newText);
                if (nested) {
                  if (unordered)
                    $this.wrapInner('<ul><li>');
                  else
                    $this.wrapInner('<ol><li>');
                }

              });
              list.wrapInner('<li>');
              if (unordered)
                list.wrapAll('<ul>');
              else
                list.wrapAll('<ol>');
              // Filter to make sure that we don't unwrap nested lists
              list.find('li').filter(function() {
                return this.parentNode.tagName == 'P'
              }).unwrap();
            }
          });
        }
        out = $desc.html();
       // regex = /<li[^>]&nbsp;/g;
        out = out.replace(/&nbsp;/g, '');
        //out = out.replace(/<\/?span[^>]*>/g,"");
        var tS = new RegExp('<(/)*(meta|link|\\?xml:|st1:|o:|font)(.*?)>', 'gi');
        out = out.replace(tS, '');
        out = out.replace(/<p[^>]<p[\/^>]>/g, '');
        out = out.replace(/<li><p[^.*>]*>/g,'<li>');
        out = out.replace(/<\/p><\/li>/g,'</li>');
        //console.log(out);
        /*var badAttributes = ['start'];
     for (var i=0; i< badAttributes.length; i++) {
       var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
       out = out.replace(attributeStripper, '');
     }*/
     return out;



      }

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


      var init_sheet_toolbar = function(){

        $(".goal-create-details .sheet-name").unbind("click").click(function(){

            $(this).parent().parent().find(".sheet-container").removeClass("active");

            $(this).parent().addClass("active");

            $(".sheet-action").hide();

            //sheet_pages
            var attr = $(this).parents(".sheet_pages").data("attr");

            var sheet_data = $(this).parent().data();
            
            sheet_data.attr=attr;

            var note_editable=$(this).parents(".note-editing-area").find(".note-editable");
            set_scroll(attr);
            options.sheet.getSheet(sheet_data, function(data){

                note_editable.html(data.meta_value);

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

          options.sheet.deleteSheet(sheet_data);

          
          //console.log(last_sheet);


          //var attr = last_sheet.parents(".sheet_pages").data("attr");

          if(last_sheet){
            var sheet_data = last_sheet.data();

            sheet_data.attr=attr;

            var note_editable=last_sheet.parents(".note-editing-area").find(".note-editable");

            options.sheet.getSheet(sheet_data, function(data){

              note_editable.html(data.meta_value);

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

          options.sheet.duplicateSheet(sheet_data,function(data){
            
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

          /*var note_editable=sheet.parents(".note-editing-area").find(".note-editable");

          var content = note_editable.html();
          
          sheet_data.html = content;*/
          
          $(".sheet-action").hide();
           apply_overflow();

           options.sheet.renameSheet(sheet_data);

        });

      };

    }
  });
}));
