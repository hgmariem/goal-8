@extends('layouts.base')
@section('page_head_css_scripts')
<script>
    $(document).ready(function () {
        $('.opendropdown').click(function () {
            $(this).next('.dropdown-menu').toggle();
        });
    })
</script>
<style type="text/css">
    .alert{
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1040;
    }
</style>
@endsection
@section('content')
<div id="page-wrapper" class="no-pad">
    <div class="graphs">
        <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
            <?php 
                if($isMobile){?>
                    @include('mobile_header')
            <?php } ?>
                    <!--<div class="hide-on-mobile">-->
                     @if(Session::has('success'))
                    <div class="alert alert-danger alert-dismissible" role="alert" id="success-btn">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    {{Session::get('success')}}
                    </div>
                    @endif
                    <section class="lobby-goals-rows col-lg-12 no-pad">
                        <table class="table-responsive" cellpadding="0" cellspacing="0">
                            <tbody id="new-goal-options">
                                <tr class="yellow-head">
                                    <th colspan="4" class="heading"> <div class="dropdown gls">
                                            <button class="btn btn-primary dropdown-toggleXX opendropdown" type="button" data-toggle="dropdown">Goal <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a class="habit-title-name" href="{{URL('add/')}}">Add Goal</a> </li>
                                                
                                                <?php if(is_admin()){?>
                    							     <li><a class="habit-title-name" href="{{URL('goals/default/list')}}">Default Goal</a> </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <a href="#" class="title-g-t open "><i class="fa fa-star"></i></a> 
                                    </th>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table-responsive goal-list" cellpadding="0" cellspacing="0">
                            <tbody id="goal-list">
                                <!--Row Goal--> 
                                <!--Row Goal-->       
                                    @foreach($goals as $row)
                                    <tr class="goal-row" id="item_{{$row->id}}"> 
                                        <td class="arrows-holder" width="50">
                                            <a class="movedown down-55 btnDown arrow-up" index="0" gid="{{$row->id}}" href="javascript:void(0);">
                                                <i class='lnr lnr-move'></i>
                                            </a>
                                        </td>
                                        <td class="goal"><a href="{{URL('edit/'.$row->id)}}">{{$row->name}}</a></td>        
                                        <td  align="right" class="goal-list-action">
                                            <a class="" href="{{URL('edit/'.$row->id)}}">
                                                <button type='button' class='btn btn-info'><i class='fa fa-pencil'></i></button>
                                            </a>
                                            <a class="remove-link" gid="{{$row->id}}" data-autosaveid="{{$row->auto_save_id}}" href="javascript:void(0);">
                                                <button type="button" class="btn btn-danger"><i class="fa fa-minus"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                    				@endforeach
                                                                
                                                            <!--Row Goal Ends--> 
                                <!--Row Goal-->

                            </tbody>
                        </table>
                    </section>
                    <section class="goals-tamplates">
                        <a href="javascript:;" class="title-g-t close">Default Goals <i class="fa fa-close"></i></a>
                        <table id="goal-list-demo1" class="table-responsive" cellpadding="0" cellspacing="0" >    
                            @foreach($default_goals as $row)
                        		<tr>
                                    <td width="300">
                                        <?php $default_edit='goals/default/edit/'.$row->id?>
                                        <a href="{{URL($default_edit)}}">{{$row->name}}</a>
                                    </td>
                        			<td>
                                        <a class="_addtomylist" href="{{URL('goals/default/addtomylist/'.$row->id)}}">
                                            <button type='button' class='btn btn-info'>Add</button>
                                        </a>  
                                    </td>
                                </tr>
                        	@endforeach
                        </table>
                    </section>

        <!-- </div> -->

        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
<style type="text/css">
    tr.dragged {
    position: absolute;
    top: 0;
    opacity: 0.5;
    z-index: 2000;
    width: auto !important;
}

.goals-tamplates .title-g-t{
    position: fixed;
    background: #2e2e2e;
}
.goals-tamplates table{
    margin-top: 55px;
}
</style>
<script type="text/javascript">

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function  () {
  
    var list_sortable = function(){

      var goal_list=$('table.goal-list tbody#goal-list').sortable({
          handle: '.arrows-holder>a',
          items: '>tr',
          stop: function ($item, container, _super) {
            $.tloader("show","Hang On...");
            var data = goal_list.sortable("serialize");

            $.post("{{url('goals/list_order')}}", {data:data}, function (response) {
                if (response.status == 0)
                {

                }
            $.tloader("hide");
            });
          },
          placeholder: {
                element: function(currentItem) {
                    return $("<tr><td colspan='3'></td></tr>")[0];
                },
                update: function(container, p) {
                    return;
                }
            }
        });

        goal_list.disableSelection();
    }

    list_sortable();

    var remove_item = function(){
        $(".remove-link").on('click', function () {
            if (!confirm('Are you sure you want to delete this item?'))
                return false;

            $.blockUI({
                message: '<img src="./img/ajax-loader.gif" />',
                css: {backgroundColor: 'transparent', border: 0}
            });

            var _this = $(this);
            var gid = _this.attr('gid');
            var autosaveid = _this.data('autosaveid');
            //console.log(autosaveid);
            $.post("{{URL('/goals/delete')}}", {auto_save_id: autosaveid}, function (response) {
                if (response.status == 1)
                {
                    var parent = _this.parents('tr');
                    $(parent).fadeOut('slow');
                  var notify = $.notify(response.msg);  
                } else {
                    //alert('There was an error. Please refresh the page and try again! Error code: ' + response.error);
                    var notify = $.notify('There was an error. Please refresh the page and try again! Error code: ' + response.msg);
                }
            }, "json");
        });
    }

    remove_item();

     $("._addtomylist").on('click', function (e) {
        
        $.tloader("show","Hang On...");
        e.preventDefault();

        var link = $(this).attr("href");

        $.get(link, function (response) {
            if (response.status == 1)
            {
              
              var html=renderList(response.data);
              
              $("#goal-list").prepend(html);

              remove_item();
              
              list_sortable();
            var notify = $.notify(response.msg);

            } else {
            
            var notify = $.notify('There was an error. Please refresh the page and try again! Error code: ' +response.msg);
                //alert('There was an error. Please refresh the page and try again! Error code: ' + response.msg);

            }

             $.tloader("hide");

        }, "json");

        return false;
     });

});


var renderList = function(goal){
    var html ='';
    html+='<tr class="goal-row" id="item_'+goal.id+'">' 
        html+='<td class="arrows-holder" width="50">'
            html+='<a class="movedown down-55 btnDown arrow-up" index="0" gid="'+goal.id+'" href="javascript:void(0);">'
                html+='<i class="lnr lnr-move"></i>'
            html+='</a>'
        html+='</td>'
        html+='<td class="goal"><a href="{{URL("edit")}}/'+goal.id+'">'+goal.name+'</a></td>'      
        html+='<td align="right" class="goal-list-action">'
            html+='<a class="" href="{{URL("edit")}}/'+goal.id+'">'
                html+='<button type="button" class="btn btn-info"><i class="fa fa-pencil"></i></button>'
            html+='</a>'
            html+='<a class="remove-link" gid="'+goal.id+'" data-autosaveid="'+goal.auto_save_id+'" href="javascript:void(0);">'
                html+='<button type="button" class="btn btn-danger"><i class="fa fa-minus"></i></button>'
            html+='</a>'
        html+='</td>'
    html+='</tr>';
    return html;
}

// unblock when ajax activity stops

$(document).ajaxStop($.unblockUI);
/*
$("#goal-list-demo1").on('click', '.btnAddDefaultGoal', function () {


    $.blockUI({
        message: '<img src="./img/ajax-loader.gif" />',
        css: {backgroundColor: 'transparent', border: 0}
    });

    var _this = $(this);
    var aid = _this.attr('aid');
  
    $.post('./defaultGoal/addtomylist/', {ids: aid}, function (response) {
        if (response.error == 0)
        {
            alert(response.message);
            var data=response.data;
            var template='<tr class="goal-row" id="goal-'+data.id+'" style="cursor: default;">'+
            '<td class="arrows-holder" width="50">'+

                '<a class="moveup up-0 btnUp arrow-down" index="0" gid="'+data.id+'" href="javascript:void(0);"><i class="lnr lnr-chevron-down"></i></a>'+
                
            '</td>'+
            '<td class="goal"><a href="'+data.url+'">'+data.name+'</a></td>'+         
            '<td align="right" class="goal-list-action">'+
                '<a class="" href="'+data.url+'"><button type="button" class="btn btn-info"><i class="fa fa-pencil"></i></button></a>'+                  
                '<a class=" remove-link" gid="'+data.id+'" data-autosaveid="'+data.auto_save_id+'" href="javascript:;"><button type="button" class="btn btn-danger"><i class="fa fa-minus"></i></button></a>'+
            '</td>'+
            '</tr>';
            $('#goal-list tr:eq(0)').after(template);
                        //location.reload();
                        check_hover();
                    }
                }, "json");
});
*/

 


                    /*$("#goal-list")
                            .on('click', '.remove-link', function () {
                                if (!confirm('Are you sure you want to delete this item?'))
                                    return false;

                                $.blockUI({
                                    message: '<img src="./img/ajax-loader.gif" />',
                                    css: {backgroundColor: 'transparent', border: 0}
                                });

                                var _this = $(this);
                                var gid = _this.attr('gid');
                                var autosaveid = _this.data('autosaveid');
                                console.log(autosaveid);
                                $.post("{{URL('/goals/delete')}}", {auto_save_id: autosaveid}, function (response) {
                                    if (response.status == 1)
                                    {
                                        var parent = _this.parents('tr');
                                        $(parent).fadeOut('slow');

                                    } else {
                                        alert('There was an error. Please refresh the page and try again! Error code: ' + response.error);
                                    }
                                }, "json");
                            })
                            .on('click', 'a.btnUp', function () {
                                $.blockUI({
                                    message: '<img src="./img/ajax-loader.gif" />',
                                    css: {backgroundColor: 'transparent', border: 0}
                                });

                                var _this = $(this);
                                var index = _this.attr('index');
                                var downid = _this.attr('gid');
                                var nextindex = parseInt(index) + 1;
                                var upid = $('.up-' + nextindex).attr('gid');

                                $.post('./goal/swap', {downid: downid, upid: upid, type: 'list'}, function (response) {
                                    if (response.error == 0)
                                    {
                                        $('#goal-list').html(response.html);
                                        check_hover();
                                    } else {
                                        alert('There was an error. Please refresh the page and try again! Error code: ' + response.error);
                                    }
                                }, "json");
                            })
                            .on('click', 'a.btnDown', function () {
                                $.blockUI({
                                    message: '<img src="./img/ajax-loader.gif" />',
                                    css: {backgroundColor: 'transparent', border: 0}
                                });

                                var _this = $(this);
                                var index = _this.attr('index');
                                var upid = _this.attr('gid');
                                var previndex = parseInt(index) - 1;
                                var downid = $('.down-' + previndex).attr('gid');

                                $.post('./goal/swap', {downid: downid, upid: upid, type: 'list'}, function (response) {
                                    if (response.error == 0)
                                    {
                                        $('#goal-list').html(response.html);
                                        check_hover();
                                    }
                                }, "json");
                            });
                        */
                    
                    /** DND START **/

                    /* is_drag để kiểm tra chỉ drag goal hay ko */
                   
                   /* var is_drag = 0;
                    var drag_id = '';
                    jQuery(document).bind("ready", function () {
                        check_hover();
                    });

                    
                    function dragStart(ev)
                    {
                        var data_send = ev.target.id;
                        drag_id = ev.target.id;
                        is_drag = 1;
                        ev.dataTransfer.setData("Text", data_send);
                        // console.log(drag_id);
                    }
                    function allowDrop(ev)
                    {
                        ev.preventDefault();
                    }
                    function drop_above(ev)
                    {
                        if (is_drag == 1) {
                            var id = ev.dataTransfer.getData("Text");
                            drag_id = id;
                            var p = $(ev.target);
                            while (!p.is("tr"))
                                p = p.parent();
                            drop_id = p.attr('id');
                            //console.log('Drag:' + drag_id, 'Drop: ' + drop_id);
                            if (drop_id != drag_id) {
                                $('#' + drop_id).before($('#' + drag_id));

                                // begin ajax call
                                $.blockUI({
                                    message: '<img src="./img/ajax-loader.gif" />',
                                    css: {backgroundColor: 'transparent', border: 0}
                                });

                                $.post('./goal/dragndrop', {dragid: drag_id, dropid: drop_id}, function (response) {
                                    alert(response);
                    if (response.error == 0)
                    {
                                        $('#goal-list').html(response.html);
                        check_hover();
                    }
                }, "json");
                            }

                            $('#goal-list tr').css('border-top', 'none');
                            $('#goal-list tr').css('padding-top', '0px');
                            is_drag = 0;
                            drag_id = '';
                        }
                        ev.preventDefault();
                    }
                    function drag_hover(a) {
                        a.on('dragenter dragover', function () {
                            hover_drop_id = a.attr('id');
                            // console.log(hover_drop_id);
                            if (drag_id != hover_drop_id && $('#' + drag_id).next().attr('id') != hover_drop_id) {
                                a.css('border-top', 'solid 1px #fff');
                            }
                        });
                        a.on('dragleave dragend', function () {
                            $('#goal-list tr').css('border-top', 'none');
                            $('#goal-list tr').css('padding-top', '0px');
                        });
                    }
                    function check_hover() {
                        $('#goal-list tr').each(function () {
                            var a = $(this);
                            drag_hover(a);
                            a.hover(function () {
                                $(this).css("cursor", "move");
                            }, function () {
                                $(this).css("cursor", "default");
                            });
                        });
                    }*/

                    $( "#success-btn" ).fadeIn( 300 ).delay( 1500 ).fadeOut( 400 );

</script>
@endsection