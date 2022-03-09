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
    textarea{  
      /*overflow:hidden;
      padding:10px;
      width:100%;
      font-size:14px;
      margin:0px auto;
      display:block;*/
    }
    
    /*textarea{height: 800px;}*/
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
                                            <!--        <a class="habit-title-name" href="/list">Goal Management</a> | <a class="habit-title-name" style="color:#fff;" href="/assignment/list">Assignments</a> <a class="habit-title-name" style="float:right;margin-right:10px;" href="/defaultAssignment/list"><i class="fa fa-folder-open"></i> Default Assignment</a>-->
                                            <button class="btn btn-primary dropdown-toggle opendropdown" type="button" data-toggle="dropdown">Assignment </button>

                                        </div>
                                        <!--<a href="#" class="title-g-t open hide-on-mobile"><i class="fa fa-star"></i></a>-->
                                    </th>
                                </tr>
                            </tbody>
                        </table>

                        {!! Form::open(array("id"=>'addAssignment')) !!}


                        <div class="goal-create-details">
                            @include('errors/validation')
                            <!--Section-->
                            <div class="section">
                                <input type="hidden" name="id" id="assignmentId" value="">
                                <header>
                                    <h4>Assignment</h4>
                                    <input placeholder="Assignment's title" class="assignmentinput form-control" type="text" value="" name="name" id="name"> </header>
                            </div>
                            <!--//Section Ends-->
                            <!--Section-->
                            <div class="section">
                                <header>
                                    <h4>Content</h4>
                                    <textarea name="content" id="content"></textarea>

                                    <div class="autosave-notify1"></div>
                                </header>


                                <input id="checkChanged" type="hidden" value="" name="modified">
                                <input id="btnSubmit" class="submit" type="button" value="SUBMIT"> </div>

                            <!--//Section Ends-->
                        </div>
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
CKEDITOR.replace( 'content',{
    removePlugins: 'magicline,elementspath',
    disallowedContent :'li{list-style-type}',
    extraPlugins:['autogrow','pastefromgdocs','pastefromword','copyformatting'],
});
</script>

<script type="text/javascript">
    jQuery(window).on("load resize", function() {
        var emSize = parseFloat($("body").css("font-size"));
        var wh = $(window).height();
        var gh = wh - emSize * 5 + 2;
        var th = gh - emSize * 17;
  //      $('.goal').css({'height': gh + 'px', 'overflow-y': 'scroll'});
       $('textarea').autosize();
      //  $('textarea').css({'min-height': th + 'px', 'padding-bottom': '20px'});
    });
</script>
<script type="text/javascript">
    var xhr;
    var http_referer = document.referrer;
    function popupNotSaved(url) {
        var b = $("#light-box");
        var c = b.find('.modal-body');
        c.empty();
        var input = '<h4 style="text-align:center;">You did not save change. Do you want to save?</h4><p style="text-align:center;"><a class="btn btn-success btnPopupSave" href="javascript:void(0)" style="margin-right:20px;">YES</a> <a class="btn btn-warning btnPopupNo" href="javascript:void(0)">NO</a></p>';
        c.append(input);

        $('#light-box .btnPopupSave').on('click', function() {
            b.modal('hide');
            window.onbeforeunload = function() {
            }
            saveAssignment(url);
        });

        $('#light-box .btnPopupNo').on('click', function() {
            window.location.href = url;
        });

        b.modal();
    }

    function saveAssignment(redirect_url, autosave)
    {
       
        var name = $("input[name='name']").val();
        var content = CKEDITOR.instances["content"].getData();
        var id  = $("#assignmentId").val();
               
        if (!name)
        {
            alert("Please enter assignment's title!");
            $("input[name='name']").focus();
            return false;
        }

        var data = {
            name: name,
            content: content,
            id:id,
        };
        
        if(!autosave){
            $.blockUI({
                message: '<img src="/img/ajax-loader.gif" />',
                css: {backgroundColor: 'transparent', border: 0}
            });
        }

        var url = '/assignment/add';

        $.post(url, data, function(response) {
            if (response.error == 0)
            {
                $("#assignmentId").val(response.id);
                $('#checkChanged').val('');
                if(redirect_url){
                    //window.location.reload(history.back());
                    window.location.href = http_referer;
                }
            }
        }, "json");
    }
    /*
    function windowloadaleart(action) {
        window.onbeforeunload = function() {
            // Add your code here
            var url = $('ul.nav li a').attr('href');
            setTimeout(function() {
                if (action != '1') {
                    popupNotSaved(url);
                }
            }, 2000);
            return false;
        }
    }*/

    $(document).ready(function() {
        $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
        /*
        // unblock when ajax activity stops
        $(document).ajaxStop($.unblockUI);

        $('div.add-form').on('change', 'input', function() {
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
        });

        $('div.add-form').on('change', 'textarea', function() {
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
        });

        $('ul.nav li a').bind('touchstart click', function() {
            var url = $(this).attr('href');
            if ($('#checkChanged').val())
            {
                popupNotSaved(url);
                return false;
            }
        });

        $('div.habit-title > a.habit-title-name').bind('touchstart click', function() {
            var url = $(this).attr('href');
            if ($('#checkChanged').val())
            {
                popupNotSaved(url);
                return false;
            }
        });

        $('.sticky-left-side .logo-icon a').bind('touchstart click', function() {
            var url = $(this).attr('href');
            if ($('#checkChanged').val())
            {
                popupNotSaved(url);
                return false;
            }
        });

        $('input,textarea').on('keypress', function(e) {
            $('#checkChanged').val(1);
            var action = 1;
            windowloadaleart(action);
            []
        });

        $('#btnSubmit').on("click", function() {

            //saveAssignment('/assignment/list');
        });
        */

   function autoSaveAssignment(redirect_url, autosave)
    {
       
        var name = $("input[name='name']").val();
        var content = CKEDITOR.instances["content"].getData();
        var id  = $("#assignmentId").val();

        var data = {
            name: name,
            content: content,
            id:id,
        };
        
        if(!autosave){
            $.blockUI({
                message: '<img src="/img/ajax-loader.gif" />',
                css: {backgroundColor: 'transparent', border: 0}
            });
        }
        if(xhr && xhr.readyState != 4){
                  xhr.abort();
              }

        var url = '/assignment/create-update';
          xhr =  $.ajax({
                    url : url,
                    method : "post",
                    data : data,
                    success : function(response)
                    {
                       if (response.status == 1)
                        {
                            $("#assignmentId").val(response.gid);
                            if(redirect_url){
                                //window.location.reload(history.back());
                                window.location.href = http_referer;
                            }
                        }
                        else
                        {
                            $("#assignmentId").val('');
                            if(redirect_url){
                                //window.location.reload(history.back());
                                window.location.href = http_referer;
                            }  
                        }
                    }
                });
          
            }


        (function(){
              // do some stuff
              //console.log("called....");
              autoSaveAssignment(false, true);
              setTimeout(arguments.callee, 6000);
          })();


          $('#btnSubmit').on("click", function() {

            autoSaveAssignment('/assignment/list', true);
        });

    });
</script>

@endsection