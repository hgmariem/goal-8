@extends('layouts.base')
@section('page_head_css_scripts')
<script>
    $(document).ready(function () {
        $('.opendropdown').click(function () {
            $(this).next('.dropdown-menu').toggle();
        });
    })
</script>
@endsection
@section('content')
<div id="page-wrapper" class="no-pad">
    <div class="graphs">
        <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
            <?php 
                if($isMobile){?>
                    @include('mobile_header')
            <?php } ?>

            <section class="task-reminders-wrapper">  
                <section class="lobby-goals-rows no-pad task tasks" id="task-id">
                    <div class="tasks-container">
                         @include('goals.partials.tasks',['tasks'=>$tasks])
                    </div>
                </section>
            </section>

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

@endsection
@section('footer_scripts')
<style type="text/css">
    .halfheightsec{
        height: 100% !important;
        overflow: hidden !important;
    }

    .header{
        position: fixed;
        width: 95%;
        z-index: 2;
    }
    .task-list{
        margin-top: 50px;
    }
    .mainchild .task-list{
         margin-top: 0px;
    }
</style>
<script src="{{ URL::asset('js/dash.js') }}"></script>
<script type="text/javascript">
     var task_resize =  function(type)
 {
  var desktopTaskData = [];
    var li_type = "";
    var ul_type = "";
    if(type == "list")
    {
      ul_type = ".task-list-list";
      li_type = ".task-desk-list";
        $(""+ul_type+" "+li_type+"").each(function( index ) {
          var task_desk_title = $(this).find(".gd-heading .task-link-title").attr("title");
              desktopTaskData.push(task_desk_title);
        });
        
    }
    else if(type == "leaf")
    {
      ul_type = ".task-list-leaf";
      li_type = ".task-desk-leaf";
        $(""+ul_type+" "+li_type+"").each(function( index ) {
          var task_desk_title = $(this).find(".gd-heading .task-link-title").attr("title");
              desktopTaskData.push(task_desk_title);
        });
        
    }
    else
    {
      ul_type = ".task-list-tree";
      li_type = ".task-desk-tree";
        $(""+ul_type+" "+li_type+"").each(function( index ) {
          var task_desk_title = $(this).find(".gd-heading .task-link-title").attr("title");
              desktopTaskData.push(task_desk_title);
        });
    }

    $(window).resize(function() 
        { 
          var width = $(window).width();
          if(width < 775)
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              
              var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 5);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 10);
              }

              if((length > 5 && checkArrow) || length > 10)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }

                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
        });
          }
          else if(width < 825 && width > 775)
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;

             // var subStr = task_desk_title.substring(0, 5);
              var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 5);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 20);
              }

              if((length > 15 && checkArrow) || length > 20)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }

                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
        });
          }
          else if(width < 890 && width > 825)
          {
            $(ul_type+" "+li_type).each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 8);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 5);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 30);
              }

              if((length > 5 && checkArrow) || length > 30)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
          });
          }
          else if(width < 1100 && width > 890)
          {
              $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 15);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 10);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 40);
              }
              if((length > 10 && checkArrow) || length > 60)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });
              
          }
          else if(width < 1270 && width > 1100)
          {
          
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 20);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 20);

              }
              else
              {
                var subStr = task_desk_title.substring(0, 50);
              }

              if((length > 20 && checkArrow) || length > 60)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });     
          }
          else if(width < 1410 && width > 1270)
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 30);

              var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 40);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 70);
              }
              if(length > 90)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });
            
          }
          else if(width < 1490 && width > 1410)
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 40);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 50);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 90);
              }
              if(length > 90)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });
          }
          else
          {
            $(""+ul_type+" "+li_type+"").each(function(key) {
              var task_desk_title = desktopTaskData[key];
              var length = task_desk_title.length;
              //var subStr = task_desk_title.substring(0, 40);
               var checkArrow =  $(this).children().find("span").children().find("a").hasClass("show-task-link");
              if(li_type == ".task-desk-tree" && checkArrow)
              {
                var subStr = task_desk_title.substring(0, 50);
              }
              else
              {
                var subStr = task_desk_title.substring(0, 90);
              }
              if(length > 90)
              {
                subStr  = subStr+"...";
              }
              else
              {
                subStr  = subStr;
              }
                $(this).find(".gd-heading .task-link-title").html("");
                $(this).find(".gd-heading .task-link-title").html(subStr);
            });
          }

    }).resize();
  }
</script>
@endsection