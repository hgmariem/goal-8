
$(document).ready(function () {
    $(document).ajaxComplete(function () {
        $('.landing_habbit input').iCheck('destroy');
        $('.landing_habbit input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green',
            handle: 'checkbox'
        });
    });
    $('.landing_habbit input').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green',
        handle: 'checkbox'
    });
    $('[data-toggle="popover"]').popover({
        content: '<div class="settings-window">\
		 <div class="settings-window__btn-group">\
		   <button class="settings-window__btn m-sett-left">HABIT</button>\
		   <button class="settings-window__btn m-sett-mid m-sett-active">Task</button>\
		   <button class="settings-window__btn m-sett-right">Character</button>\
		 </div>\
		 <div class="settings-window__sett">\
		   <div class="settings-window__sett-elem">\
		     <div class="sett-elem__name p-left">expiration date</div>\
		     <div class="sett-elem__set p-right">\
		       <time datetime="2015-07-23" class="set-inf">23 June, 2015</time>\
		       <button class="calendar m-set-calendar-btn"></button>\
		     </div>\
		   </div>\
		   <div class="settings-window__sett-elem">\
		       <div class="sett-elem__name p-left">schedule type</div>\
		       <div class="sett-elem__set p-right">\
		         <div class="set-inf">7 Days/Week</div>\
		         <button class="btn-arrow-orange btn-arrow-orange-close m-set-arrow-btn"></button>\
		       </div>\
		   </div>\
		   <div class="settings-window__sett-elem">\
		       <div class="sett-elem__name p-left">show in lobby</div>\
		       <div class="sett-elem__set p-right">\
		         <button class="btn-yes-no m-set-yes-no-btn btn-yes"></button>\
		       </div>\
		   </div>\
		   <div class="settings-window__sett-elem">\
		       <div class="sett-elem__name p-left">goals in active</div>\
		       <div class="sett-elem__set p-right">\
		         <button class="btn-yes-no m-set-yes-no-btn btn-no"></button>\
		       </div>\
		   </div>\
		 </div>\
		  </div>'
    })

    cheklogin();
});



var block = document.querySelector('.templates');
var btnClose = document.querySelector('.btn-star-close');
var btnOpen = document.querySelector('.btn-star');
var className = 'templates-show';
var classNameClose = 'template-close';

if (btnOpen != null)
    btnOpen.addEventListener('click', function (e) {
        if (!block.classList.contains(className)) {
            block.classList.add(className);
            btnOpen.classList.add(classNameClose);
            btnClose.classList.add(className);
            document.getElementById('temp-all').classList.add('padding-right-350');
        }
    });

if (btnClose != null)
    btnClose.addEventListener('click', function (e) {
        if (block.classList.contains(className)) {
            block.classList.remove(className);
            btnOpen.classList.remove(classNameClose);
            btnClose.classList.remove(className);
            document.getElementById('temp-all').classList.remove('padding-right-350');
        }
    });

function cheklogin(){
    var feedback;

    if(feedback && feedback.readyState !=4)
    {
        feedback.abort();
    }

    feedback = $.ajax({
        type: "GET",
        url: site_url+"/cheklogin",
        async: false,
        success:function(result){
            if (result.status==0) {
                window.location.href = '/logout';
            }
            setTimeout(function(){ cheklogin();}, 10000);
            
        }
    });


    
}
 