 $.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

$(function(){


    $(document).on('ifChanged', "ul.habit-list input.habits__checkbox", function(e) {
			var _this = $(this);
            add_log(e,_this);
	});

    init_assignment_sortable();
    
    init_default_assignment_sortable();

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
              
              init_assignment_sortable();
            var notify = $.notify(response.msg);

            } else {
            
            var notify = $.notify('There was an error. Please refresh the page and try again! Error code: '+response.msg);
                //alert('There was an error. Please refresh the page and try again! Error code: ' + response.msg);
            }

             $.tloader("hide");

        }, "json");

        return false;
    });

});



var renderList = function(assignment){
    var html ='';
    html+='<tr class="goal-row" id="item-'+assignment.id+'">'
         html+='<td class="arrows-holder" width="50">'
            html+='<a class="movedown down-55 btnDown arrow-up" index="55" aid="'+assignment.id+'" href="javascript:void(0);">'
            html+='<i class="lnr lnr-move"></i>'
            html+='</a>'
            html+='</td>'
        html+='<td class="goal"><a class="" href="'+site_url+'/assignment/edit/'+assignment.id+'">'+assignment.name+'</a></td>'
        html+='<td align="right" class="assignment-list-action">'
            html+='<a class="" href="'+site_url+'/assignment/edit/'+assignment.id+'">'
            html+='<button type="button" class="btn btn-info">'
            html+='<i class="fa fa-pencil"></i>'
            html+='</button>'
            html+='</a>'
            html+='<a class="remove-link" href="javascript:;" aid="'+assignment.id+'">'
            html+='<button type="button" class="btn btn-danger">'
            html+='<i class="fa fa-minus"></i>'
            html+='</button>'
            html+='</a>'
        html+='</td>'
    html+='</tr>';

    return html;
}

var remove_item = function(){

    $("#goal-list").unbind('click').on('click', '.remove-link', function () {
                        
        if (!confirm('Are you sure you want to delete this item?')){
            return false;
        }

        $.tloader("show","Please wait...");

        var _this = $(this);
        var aid = _this.attr('aid');

        $.post('/assignment/delete', {id: aid}, function (response) {
            
            if (response.status == 1)
            {
                var parent = _this.parents('tr');

                $(parent).fadeOut('slow');
                 $.tloader("hide");
                 var notify = $.notify(response.msg);

            }else{
                var notify = $.notify(response.msg);
                $.tloader("hide");
            }
        }, "json");
    });
}

var init_assignment_sortable=function(){

    var assignment_list=$('#goal-list').sortable({
        handle: '.arrows-holder>a',
        items: '>tr',
        listType: 'table',
        opacity: .6,
        stop: function(event, ui) {
            $.tloader("show","Hang On...");
            var data = assignment_list.sortable("serialize");
			//console.log(data);
            $.post(site_url+"/assignment/list_order", {data:data}, function (response) {
               
                if (response.status == 0)
                {

                }

                $.tloader("hide");
            });
			/**/
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
    assignment_list.disableSelection();
};

var init_default_assignment_sortable=function(){

    var assignment_list=$('#default-goal-list').sortable({
        handle: '.arrows-holder>a',
        items: '>tr',
        listType: 'table',
        opacity: .6,
        stop: function(event, ui) {
            $.tloader("show","Hang On...");
            var data = assignment_list.sortable("serialize");
            //console.log(data);
            $.post(site_url+"/assignment/default/list_order", {data:data}, function (response) {
               
                if (response.status == 0)
                {

                }

                $.tloader("hide");
            });
            /**/
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
    assignment_list.disableSelection();
};