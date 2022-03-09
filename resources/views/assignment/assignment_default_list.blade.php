@extends('layouts.base') @section('content')
<div id="page-wrapper" class="no-pad">
    <div class="graphs">
            <section class="lobby-goals-rows col-lg-12 no-pad">
                <table class="table-responsive" cellpadding="0" cellspacing="0">
                    <tbody id="goal-list">
                        <tr class="yellow-head">
                            <th colspan="4" class="heading">
                                <div class="dropdown gls">
                                    <button class="btn btn-primary dropdown-toggleXX opendropdown" type="button" data-toggle="dropdown">Assignments <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="habit-title-name" href="{{URL('assignment/add')}}">Add Assignment</a> </li>
                                        <?php if(is_admin()){?>
                                            <li><a class="habit-title-name" href="{{URL('assignment/default/add')}}">default Assignment</a> </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <a href="#" class="title-g-t open /*hide-on-mobile*/"><i class="fa fa-star"></i></a>
                            </th>
                        </tr>
                        <!--Row Goal-->
                        <!--Row Goal-->
                        @foreach ($var as $row)
                        <tr class="goal-row" draggable='true' ondragstart='dragStart(event)' ondrop='drop_above(event)' ondragover='allowDrop(event)' id="assignment-78">
                            <td class="arrows-holder" width="50">

                                <a class="moveup up-55 btnUp arrow-down" index="55" aid="78" href="javascript:void(0);"><i class='lnr lnr-chevron-down'></i></a>
                                <a class="movedown down-55 btnDown arrow-up" index="55" aid="78" href="javascript:void(0);"><i class='lnr lnr-chevron-up'></i></a>
                            </td>
                            <td class="goal"><a class="" href="{{URL('assignment/default_edit/'.$row->id)}}">{{$row->name}}</a></td>
                            <td align="right" class="assignment-list-action">
                                <a class="" href="{{URL('assignment/default_edit/'.$row->id)}}"><button type='button' class='btn btn-info'><i class='fa fa-pencil'></i></button></a> <a class=" remove-link" href="{{URL('assignment/default/delete/'.$row->id)}}"><button type="button" class="btn btn-danger"><i class="fa fa-minus"></i></button></a>
                            </td>
                        </tr>
                        @endforeach
                        <!--Row Goal Ends-->
                        <!--Row Goal-->
                    </tbody>
                </table>
            </section>
        </div>
    </div>


@endsection @section('footer_scripts')

<script>
    $(document).ready(function () {
        $('.opendropdown').click(function () {
            $(this).next('.dropdown-menu').toggle();
        });
    })
</script>
<script type="text/javascript">
    // unblock when ajax activity stops
                    $(document).ajaxStop($.unblockUI);

                    $("#goal-list-demo1").on('click', '.btnAddDefault', function () {

                        $.blockUI({
                            message: '<img src="{{ URL::asset('/img/ajax-loader.gif') }}" />',
                            css: {backgroundColor: 'transparent', border: 0}
                        });

                        var _this = $(this);
                        var aid = _this.attr('aid');

                        $.post('./defaultAssignment/addtomylist/', {ids: aid}, function (response) {
                            if (response.error == 0)
                            {
                                alert(response.message);
                                var data=response.data;
                                var template='<tr class="goal-row" draggable="true" ondragstart="dragStart(event)" ondrop="drop_above(event)" ondragover="allowDrop(event)" id="assignment-'+data.id+'" style="cursor: default;">'+
                    '<td class="arrows-holder" width="50">'+

                        '<a class="moveup up-0 btnUp arrow-down" index="0" aid="'+data.id+'" href="javascript:void(0);"><i class="lnr lnr-chevron-down"></i></a>'+
                        
                    '</td>'+
                    '<td class="goal"> <a href="'+data.url+'">'+data.name+'</a></td>'+        
                    '<td align="right" class="assignment-list-action">'+
                        '<a class="" href="'+data.url+'"><button type="button" class="btn btn-info"><i class="fa fa-pencil"></i></button></a>'+ 
                        '<a class=" remove-link" aid="'+data.id+'" href="javascript:;"><button type="button" class="btn btn-danger"><i class="fa fa-minus"></i></button></a>'+
                    '</td>'+
                '</tr>';
                            $('#goal-list tr:eq(0)').after(template);
                                //location.reload();
                            check_hover();
                                //location.reload();
                            }
                        }, "json");
                    });

                    $("#goal-list-demo1").on('click', '.btnAddDefaultGoal', function () {


                        $.blockUI({
                            message: '<img src="./img/ajax-loader.gif" />',
                            css: {backgroundColor: 'transparent', border: 0}
                        });

                        var _this = $(this);
                        var aid = _this.attr('aid');
                        //alert(aid);


                        $.post('./defaultGoal/addtomylist/', {ids: aid}, function (response) {
                            if (response.error == 0)
                            {
                                alert(response.message);
                            }
                        }, "json");
                    });

                    $("#goal-list").on('click', '.remove-link', function () {
                        if (!confirm('Are you sure you want to delete this item?'))
                            return false;

                        $.blockUI({
                            message: '<img src="./img/ajax-loader.gif" />',
                            css: {backgroundColor: 'transparent', border: 0}
                        });

                        var _this = $(this);
                        var aid = _this.attr('aid');

                        $.post('./assignment/delete/', {id: aid}, function (response) {
                            if (response.error == 0)
                            {
                                var parent = _this.parents('tr');

                                $(parent).fadeOut('slow'
//                    function(){
//                        parent.remove();
//                        $('ul.goal-list').html(response.html);
//                        check_hover();
//                    }

                                        );
                            }
                        }, "json");
                    });


                    $("#goal-list").on('click', 'a.btnDown', function () {
                        $.blockUI({
                            message: '<img src="./img/ajax-loader.gif" />',
                            css: {backgroundColor: 'transparent', border: 0}
                        });

                        var _this = $(this);
                        var index = _this.attr('index');
                        var upid = _this.attr('aid');
                        var previndex = parseInt(index) - 1;
                        var downid = $('.down-' + previndex).attr('aid');

                        $.post('./assignment/swap', {downid: downid, upid: upid}, function (response) {
                            if (response.error == 0)
                            {

                                $('#goal-list').html(response.html);
                                check_hover();
                            }
                        }, "json");
                    });

                    $("#goal-list").on('click', 'a.btnUp', function () {
                        $.blockUI({
                            message: '<img src="./img/ajax-loader.gif" />',
                            css: {backgroundColor: 'transparent', border: 0}
                        });

                        var _this = $(this);
                        var index = _this.attr('index');
                        var downid = _this.attr('aid');
                        var nextindex = parseInt(index) + 1;
                        var upid = $('.up-' + nextindex).attr('aid');

                        $.post('./assignment/swap', {downid: downid, upid: upid}, function (response) {
                            if (response.error == 0)
                            {
                                $('#goal-list').html(response.html);
                                check_hover();
                            }
                        }, "json");
                    });

                    /** DND START **/

                    /* is_drag để kiểm tra chỉ drag goal hay ko */
                    var is_drag = 0;
                    var drag_id = '';
                    jQuery(document).bind("ready", function () {
                        check_hover();
                    });

                    /*---DRAG AND DROP --*/
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

                                $.post('./assignment/dragndrop', {dragid: drag_id, dropid: drop_id}, function (response) {
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
                    }
</script>
@endsection