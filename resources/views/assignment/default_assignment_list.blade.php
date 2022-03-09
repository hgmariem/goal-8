@extends('layouts.base') @section('content')
<div id="page-wrapper" class="no-pad">
    <div class="graphs">
        <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>"">
            <section class="lobby-goals-rows col-lg-12 no-pad">
            <?php 
                if($isMobile){?>
                    @include('mobile_header')
            <?php } ?>
            </section>
        </div>
            <section class="lobby-goals-rows col-lg-12 no-pad">
                <table class="table-responsive" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr class="yellow-head">
                            <th colspan="4" class="heading">
                                <div class="dropdown gls">
                                    <button class="btn btn-primary dropdown-toggleXX opendropdown" type="button" data-toggle="dropdown">Default Assignments <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="habit-title-name" href="{{URL('assignment/default/add')}}">Add Default Assignment</a> </li>
                                    </ul>
                                </div>
                            </th>
                        </tr>
					</thead>
                        <!--Row Goal-->
                        <tbody id="default-goal-list">
						<!--Row Goal-->

                        @foreach ($assignments as $row)


                        <tr class="goal-row" id="item-{{$row->id}}">
                            <td class="arrows-holder" width="50">
                                <a class="movedown down-55 btnDown arrow-up" index="55" aid="{{$row->id}}" href="javascript:void(0);"><i class='lnr lnr-move'></i></a>
                                <?php /*<a class="movedown down-55 btnDown arrow-up" index="55" aid="{{$row->id}}" href="javascript:void(0);"><i class='lnr lnr-chevron-up'></i></a>*/?>
                            </td>
                            <td class="goal"><a class="" href="{{URL('assignment/default/edit/'.$row->id)}}">{{$row->name}}</a></td>
                            <td align="right" class="assignment-list-action">
                                <a class="" href="{{URL('assignment/default/edit/'.$row->id)}}"><button type='button' class='btn btn-info'><i class='fa fa-pencil'></i></button></a>
                                <a class="remove-link" href="javascript:;" aid="{{$row->id}}"><button type="button" class="btn btn-danger"><i class="fa fa-minus"></i></button></a>
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
<script src="{{ URL::asset('js/assignment.js') }}"></script>
<style type="text/css">
    .lobby-goals-rows table tr:nth-child(2) > td.arrows-holder > a.arrow-up{
        display: block;
        
    }
    .lobby-goals-rows table .goal-row td.arrows-holder a.arrow-up{
       top: 17px; 
    }
</style>
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
                            $('#default-goal-list tr:eq(0)').after(template);
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

                    $("#default-goal-list").on('click', '.remove-link', function () {
                        
                        if (!confirm('Are you sure you want to delete this item?')){
                            return false;
                        }

                        $.tloader("show","Please wait...");

                        var _this = $(this);
                        var aid = _this.attr('aid');

                        $.post('/assignment/default/delete', {id: aid}, function (response) {
                            
                            if (response.status == 1)
                            {
                                var parent = _this.parents('tr');

                                $(parent).fadeOut('slow');
                                 $.tloader("hide");
                            }else{
								alert(response.msg);
								$.tloader("hide");
							}
                        }, "json");
                    });

                    /*
                    */
</script>
@endsection