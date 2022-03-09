@extends('layouts.base') @section('page_head_css_scripts')

<script src="{{ URL::asset('/ckeditor/ckeditor.js') }}"></script>


<script>
    $(document).ready(function() {
        $('.opendropdown').click(function() {
            $(this).next('.dropdown-menu').toggle();
        });
       
        function autosize(){
          var el = textarea;
          setTimeout(function(){
            var ht = parseInt(el.scrollHeight)+30;
            el.style.cssText = 'height:' + ht+'px';
            el.preventDefault();
          },0);
        } 
    });
</script>
<style type="text/css">
    .white-button {
        background: #fff;
        line-height: initial;
        padding: 0rem 4rem;
        border-radius: 50px;
        margin: 10px 100px 0px 0px;
        font-weight: 700;
    }
    body{
        background: #f2f2f2;
    }
    .goal-create-details{
        box-shadow: none;
        background: transparent;
    }
    .red-title{
    font-weight: 700;
    color: #e02e12;
    font-size: 20px;
    margin-bottom: 20px;
    }
    .add-button{
        margin-bottom: 20px;
    }
    .add-button a{
        font-style: italic;
        font-weight: 500;
    }
    .add-button a span{
        width: 30px;
        display: inline-block;
        height: 30px;
        text-align: center;
        line-height: 27px;
        border: 1px dashed;
        font-weight: 500;
        margin-right: 10px;
    }
    .small-fields{
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .small-fields span{
        display: inline-block;
        margin-right: 5px;
    }
    .small-fields span:nth-child(1){
        width: 50px;
    }
    .small-fields span:nth-child(2){
        width: 70px;
    }
    .small-fields span:nth-child(3){
        width: 70px;
    }
    .status span{
        text-transform: uppercase;
        font-weight: 400;
    }
    .status span:nth-child(2){
        font-style: italic;
    }
    .form-group label{
        font-weight: 600;
        color: #989696;
    }
    .messages-history{
        border-radius: 20px;
        background: #fff;
        padding: 2rem;
        border: 3px solid #e02e12;
        margin: 45px;
        max-height: 80vh;
        overflow: auto;
    }
    .messages-history h5{
        color: #e02e12;
        margin-bottom: 1.5rem;
        font-size: 15px;
    }
    .messages-history ul{
        list-style: none;
    }
    .messages-history ul li{
        margin-bottom: 1.2rem;
        font-size: 13px;
    }
    @media (max-width:767px) {
        .messages-history{
            margin: 0px 0px 25px 0px;
            max-height: none;
            overflow: visible;
        }
    }
</style>
@endsection @section('content')
<div id="page-wrapper" class="no-pad">
    <div class="graphs">
        <div class="hide-on-desktop">
            <section class="lobby-goals-rows col-lg-12 no-pad">
                <div class="mobile-header">
                    <a href="#" class="trigger"><i class="fa fa-bars"></i></a>
                    <a href="#" class="mailbox"><!--<i class="badge-danger">3</i>--></a>
                    <div class="text-center"><a href="#" class="logo-mobile"><img src="{{URL('images/logo.png')}}" alt=""></a></div>
                </div>
            </section>
        </div>
        
            <section class="lobby-goals-rows col-lg-12 no-pad">

                <section class="goasl">

                    <div class="col-md-7XX no-padXX" id="add-form">

                        <table class="table-responsive" cellpadding="0" cellspacing="0">
                            <tbody id="goal-list">
                                <tr class="yellow-head">
                                    <th colspan="4" class="heading">
                                        <div class="dropdown gls">
                                            <a class="float-right white-button show-button" href="javascript:void(0);">Older</a>
                                            <button class="btn btn-primary dropdown-toggle opendropdown" type="button" data-toggle="dropdown">Calender </button>
                                        </div>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="massage">
                        </div>
                       

                        {!! Form::open(array("id"=>'addWorkshop')) !!}

                        <div class="goal-create-details">
                            @include('errors/validation')
                            <div class="row">
                                <!--right box-->
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 col-lg-push-8 col-md-push-8 col-sm-push-8 older-workshop show">
                                    <div class="messages-history">
                                        <h5>Sent messages</h5>
                                        <ul>
                                        @if(!empty($old_data))
                                        @foreach($old_data as $old)
                                            <li>{{$old->title}}</li>
                                            @endforeach
                                            @endif
                                          </ul>
                                    </div>
                                </div>
                                <!--right box end-->
                                <!--left form-->
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-pull-4 col-md-pull-4 col-sm-pull-4 latest-workshop">
                                    <h3 class="red-title">Manage workshops</h3>
                                    <div class="add-button">
                                        <a href="javascript:void(0);" class="add-more"><span>+</span> New workshop message</a>
                                    </div>
                                    
                                    <?php $i=0; ?>
                                   @if(isset($workshop) && !$workshop->isEmpty())
                                    @foreach($workshop as $w)
                                        <?php $i++; ?>
                                    <div class="form-group workshop_s" data-i="{{$i}}">
                                    <input type="hidden" id="workshop-id" name="id[{{$i}}]" value="{{$w->id}}">
                                        <label>Workshop message</label>
                                       
                                        <input placeholder="Workshop's title" class="workshopinput form-control" type="text" value="{{$w->title}}" name="title[{{$i}}]" id="name">
                                        
                                        <div class="small-fields">
                                            <span>
                                            <?php
                                             
                                             $day = ($w->day != "")?$w->day:""; ?>
                                                <label>Day</label>
                                                <input type="text" class="form-control day" name="day[{{$i}}]" value="{{$day}}">
                                            </span>
                                            <span>
                                            <?php 
                                            $month = ($w->month != "")?$w->month:""; ?>
                                                <label>Month</label>
                                                <input type="text" class="form-control month" name="month[{{$i}}]" value="{{$month}}">
                                            </span>
                                            <span>
                                            <?php 
                                            
                                            $year = ($w->year != "")?$w->year:""; ?>
                                                <label>Year</label>
                                                <input type="text" class="form-control year" name="year[{{$i}}]" value="{{$year}}">
                                            </span>
                                            <span data-id="{{$w->id}}" class="remove"> <i class="fa fa-times-circle"></i></span>
                                        </div>
                                        <?php
                                         $current_date = date("Y-m-d");
                                         $workshop_date = date("Y-m-d",strtotime($w->date));
                                         if($year == "" || $month == "" || $day == ""){
                                            $status = "Information missing";
                                            $class = "text-danger";
                                        }else if($current_date > $workshop_date){
                                            $status = "Sent";
                                            $class = "text-success";
                                        }else{
                                            $status = "Ready";
                                            $class = "text-success";
                                        } ?>
                                        <label class="status"><span>Status:</span> <span class="{{$class}}">{{$status}}</span></label>
                                    </div>
                                    @endforeach
                                   
                                    @else
                                    <?php $i++; ?>
                                    <div class="form-group workshop_s" data-i="{{$i}}">
                                    <input type="hidden" id="workshop-id" name="id[{{$i}}]" value="">
                                        <label>Workshop message</label>
                                        <input placeholder="Workshop's title" class="workshopinput form-control" type="text" value="" name="title[{{$i}}]" id="name">
                                        <div class="small-fields">
                                            <span>
                                                <label>Day</label>
                                                <input type="text" class="form-control day" name="day[{{$i}}]">
                                            </span>
                                            <span>
                                                <label>Month</label>
                                                <input type="text" class="form-control month" name="month[{{$i}}]">
                                            </span>
                                            <span>
                                                <label>Year</label>
                                                <input type="text" class="form-control year" name="year[{{$i}}]">
                                            </span>
                                            <span data-id="" class="remove"> <i class="fa fa-times-circle"></i></span>
                                        </div>
                                        <label class="status"><span>Status:</span> <span class="text-danger">Information missing</span></label>
                                    </div>
                                    @endif

                                </div>
                                <!--left form- end -->
                            </div>

                                <input id="btnSubmit" class="submit" type="button" value="SUBMIT">

                           


                    </div>
                    {!! Form::close() !!}

                </section>


                <div id="light_box" class="modal hide fade lightbox-pop-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-body"></div>
                </div>
                <style>
                    .autosave-notify1{
                        float:right;
                    }
                </style>
            </section>

            <div id="light-box" class="modal hide fade lightbox-pop-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-body"></div>
            </div>
    </div>
</div>

@endsection @section('footer_scripts')

<script>

removeWorkshop = function(){
    $(".remove").unbind("click").bind("click",function(){
           var len = $(".workshop_s").length;
           console.log("Len Is Here...",len);
           addMore();
           if(len > 1){
           var id =  $(this).attr("data-id");
           if(id == ""){
            $(this).parents(".workshop_s:first").remove();
           }else{

             var div_data = $(this).parents(".workshop_s:first");

                $.ajax({
                    url:"/workshop/delete/"+id,
                    method:"get",
                    success:function(res){
                        var msg = '';
                        if(res.status){
                        msg += '<div class="alert alert-success alert-block">';
                        msg +='<button type="button" class="close" data-dismiss="alert">×</button>';   
                        msg +='<strong>'+res.msg+'</strong>';
                        msg +='</div>';
                        $(".massage").html(msg);
                        div_data.remove();

                        }else{
                        msg += '<div class="alert alert-danger alert-block">';
                        msg +='<button type="button" class="close" data-dismiss="alert">×</button>';   
                        msg +='<strong>'+res.msg+'</strong>';
                        msg +='</div>';
                        $(".massage").html(msg);
                        }
                    }
                    });
            }
           }else{
            alert("Sorry You Can't remove workshop.");
            return false;
           }

        });
}

var inputDay = function(){
    $(".day").on("input propertychange",function(){
        var day = $(this).val();
        if(day > 31){
          $(this).val('');
          alert("Day can't more than 31.");
          return false;  
        }
        })
}


var inputMonth = function(){
    $(".month").on("input propertychange",function(){
        var month = $(this).val();
        if(month >= 12){
          $(this).val('');
          alert("Month can't more than 12.");
          return false;  
        }
        })
}


var showOlder = function(){
	$(".show-button").unbind("click").bind("click",function(){
			$(this).removeClass("show-button");
			$(this).addClass("hide-button");
			$(".older-workshop").removeClass("hide");
			$(".older-workshop").addClass("show");
			$(".latest-workshop").removeClass("col-lg-12 col-md-12 col-sm-12 col-xs-12 col-lg-pull-0 col-md-pull-0 col-sm-pull-0");
			$(".latest-workshop").addClass("col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-pull-4 col-md-pull-4 col-sm-pull-4");

			hideOlder();

		});
}

var hideOlder = function(){
	$(".hide-button").unbind("click").bind("click",function(){
			$(this).removeClass("hide-button");
			$(this).addClass("show-button");
			$(".older-workshop").removeClass("show");
			$(".older-workshop").addClass("hide");
			$(".latest-workshop").removeClass("col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-pull-4 col-md-pull-4 col-sm-pull-4");
			$(".latest-workshop").addClass("col-lg-12 col-md-12 col-sm-12 col-xs-12 col-lg-pull-0 col-md-pull-0 col-sm-pull-0");

			showOlder();
		});
}

var addMore = function(){
    $(".add-more").unbind("click").bind("click",function(){
         var i = $(".workshop_s:last").attr("data-i");
         var i = parseInt(i)+1;
         var html = '';
         html += '<div class="form-group workshop_s" data-i="'+i+'">';
         html += '<input type="hidden" id="workshop-id" name="id['+i+']" value="">';
         html += '<label>Workshop message</label>';
         html += '<input placeholder="Workshops title" class="workshopinput form-control" type="text" value="" name="title['+i+']" id="name">';
         html += '<div class="small-fields">';
         html += ' <span>';
         html += '<label>Day</label>';
         html += '<input type="text" class="form-control day" name="day['+i+']">';
         html += '</span>';
         html += '<span>';
         html += '<label>Month</label>';
         html += '<input type="text" class="form-control month" name="month['+i+']">';
         html += '</span>';
         html += '<span>';
         html += '<label>Year</label>';
         html += '<input type="text" class="form-control year" name="year['+i+']">';
         html += '</span>';
         html += '<span data-id="" class="remove"> <i class="fa fa-times-circle"></i></span>';
         html += '</div>';
         html += '<label class="status"><span>Status:</span> <span class="text-danger">Information missing</span></label>';
         html += '</div>';

         var len = $(".workshop_s").length;
         if(len <= 10){
               $(".workshop_s:last").after(html);
            }else{
                alert("You Can't add more 10 workshop.");
                return false;
            }

            removeWorkshop();

        });
}
$(document).ready(function(){
    addMore();
    showOlder();
    hideOlder();
    inputDay();
    inputMonth();
    removeWorkshop();
    $("#btnSubmit").unbind("click").bind("click",function(){
          submit_form();
        });

        
    
});

var submit_form = function(){
        var xhr;
      if(xhr && xhr.readyState != 4){
                  xhr.abort();
              }
     var form_data =  $("#addWorkshop").serialize();
     xhr =  $.ajax({
            url:"{{url('/workshop/create')}}",
            method:"post",
            data:form_data,
            success:function(data){
                if(data.status){
                   location.reload(true); 
                }
                
                 }
            })
    }

</script>

@endsection