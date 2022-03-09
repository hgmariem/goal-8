@extends('layouts.base') 

@section('content')
<div id="page-wrapper" class="no-pad">
    <div class="graphs">
            <div class="<?php echo ($isMobile)?"hide-on-desktop":"hide-on-mobile"; ?>">
                <section class="lobby-goals-rows col-lg-12 no-pad">
                    <?php 
                        if($isMobile){?>
                            @include('mobile_header')
                    <?php } ?>
                </section>
            </div>
            <section class="trophy-room col-lg-12 no-pad" id="trophy-holder">
                <div class="yellow-head"><h1>Trophy Room</h1></div>
                <ul>
                <?php foreach($trophies as $year=>$monthly_trophy){
                    ?>
                    <li class="year-trophy">
                        <h4>Year <?php echo $year; ?></h4>
                        <ul>
                            <?php foreach($monthly_trophy as $month=>$month_data){?>
                            <li class="month-trophy">
                                <h4><?php echo  date("M",strtotime($year."-".$month."-01")); ?></h4>
                                <ul>
                                    <?php foreach($month_data as $data){?>
                                    <li>
                                        <div class="day-trophy"><?php echo  date("M d",strtotime($data->trophy_date)); ?></div>
                                        <div class="detail-trophy"><?php echo $data->name;?></div>
                                        <div class="trophy-action">
                                            <a class="edit-link btn btn-info" gid="<?php echo $data->id?>" gname="{{$data->name}}" gdate="<?php echo date("Y-m-d",strtotime($data->trophy_date));?>" id="edit-<?php echo $data->id?>"><i class="fa fa-pencil"></i></a>
                                            <a class="remove-link btn btn-danger" gid="<?php echo $data->id?>" id="remove-<?php echo $data->id?>" href="javascript:void(0)"><i class="fa fa-minus"></i></a>
                                        </div>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li> 
                            <?php }?>                           
                        </ul>
                    </li>
                    <?php }?>
                </ul>

                
            </section>
            <div id="light-box" class="modal hide fade lightbox-pop-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <a href="" id="close-btn"></a>
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">&nbsp;&nbsp;</div>
                    <div class="modal-body"></div>
                    <div class="modal-footer"></div>
                  </div>
                </div>
            </div>
    </div>
</div>
@endsection @section('footer_scripts')

<!--<script src="./js/bootstrap.js"></script>-->
<script src="{{ URL::asset('/lib/picker.v2015.js') }}"></script>
<script src="{{ URL::asset('/lib/picker.date.v2015.js') }}"></script>
<script src="{{ URL::asset('/js/add-new.js') }}"></script>
<script src="{{ URL::asset('/js/info.js') }}"></script>
<script src="{{ URL::asset('/js/jquery.autosize.js') }}"></script>

<script type="text/javascript">
    // unblock when ajax activity stops
    $(document).ajaxStop($.unblockUI);

    function deleteTask() {
        if (!confirm('Are you sure you want to delete this item?'))
            return false;

        $.blockUI({
            message: '<img src="{{URL::asset('/img/ajax-loader.gif')}}" />',
            css: {backgroundColor: 'transparent', border: 0}
        });

        var _this = $(this);
        var gid = _this.attr('gid');

        $.post('{{URL::asset('/trophy/delete')}}', {id: gid}, function (response) {
            if (response.status == 1)
            {
                location.reload();
                /*$('#trophy-holder').html(response.html);
                $("ul.goal-list").on('click', 'a.remove-link', deleteTask);
                $('.edit-link').on('click', function (event) {
                    console.log("clicked");
                    popupUpdate(this, 0, event.target);
                });*/

            }else{
               alert(data.msg); 
            }
            
        }, "json");
    }

    $(".trophy-room").on('click', 'a.remove-link', deleteTask);

    $('.edit-link').on('click', function (event) {
        console.log("clicked");
        popupUpdate(this, 0, event.target);
    });
    
    $(".year-trophy").on('click',function(){
        
        var selector=$(this).data("selector");
        $('[class*=" '+selector+'"]').slideToggle();
    });
    
    /*$(".month-trophy").on('click',function(){
        var selector=$(this).data("selector");
        console.log(selector);
        $('[class*=" '+selector+'"]').slideToggle();
    });*/
    
    
    function popupUpdate(target, isMain, triggerObj) {

        var b = $("#light-box");
        var h = b.find('.modal-header');
        var c = b.find('.modal-body');
        var f = b.find('.modal-footer');
        
        h.empty();
        c.empty();
        f.empty();

        var _this = $(target);
        var gid = _this.attr('gid');
        var gname = _this.attr('gname');
        var gdate = _this.attr('gdate');
        

        var input = '<form class="form-horizontal">'+
    '<div class="form-group">'+
        '<label class="control-label col-sm-2" for="gname">Name</label>'+
        '<div class="col-sm-10">'+
            '<input type="text" class="form-control" id="gname" value="'+ gname +'" />'+
        '</div>'+
    '</div>'+
    '<div class="form-group">'+
    '<label class="control-label col-sm-2" for="gdate">Date</label>'+
        '<div class="col-sm-10">'+
            '<input type="text" class="form-control" id="gdate" value="' + gdate + '" />'+
        '</div>'+
    '</div>'+
'</form>';

        h.html('<h4 style="text-align:center;">Edit task</h4>');
        c.html(input);
        f.html('<p style="text-align:center;"><a class="btn btn-success btnPopupReactive" href="javascript:void(0)" style="margin-right:20px;">UPDATE</a> <a class="btn btn-warning btnPopupNotReactive" href="javascript:void(0)">CANCEL</a></p>');

        $('#gdate').attr('value', gdate);
        $('#gdate').datepicker({ dateFormat: 'yy-mm-dd' });

        $('#light-box .btnPopupReactive').on('click', function () {
            b.modal('hide');

            console.log($('input#gname').val());

            var updatedName = $('input#gname').val();
            var updatedDate = $('input#gdate').val();

            var url = '{{URL::asset('/trophy/edit')}}';

            console.log(url);

            $.blockUI({
                message: '<img src="./img/ajax-loader.gif" />',
                css: {backgroundColor: 'transparent', border: 0}
            });

            var gid = _this.attr('gid');

            $.post(url, {id: _this.attr('gid'), name: updatedName, date: updatedDate}, function (data) {
                if (data.status == 1)
                {
                    location.reload();
                } 
                else
                {
                    alert(data.msg);
                }
            }, "json");
        });

        $('#light-box .btnPopupNotReactive').unbind('click').bind('click', function () {
            b.modal('hide');
        });
        //alert(2);
        b.modal();
    }

    
</script>
@endsection