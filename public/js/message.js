$(document).ready(function() {
	$('#light-box').resizable({ iframeFix: true },{ handles: " e, s, w, se, sw" });
	$("#close-frame-btn").bind('touchstart click',function(event) {
		var c = $('#light-box').find('.modal-body');
		c.empty();
		$('#light-box').modal('hide');
		event.preventDefault();
		return false;
	});
	$("div.post-holder").on('touchstart click', 'a.comment-show', function() {
		var b = $(this)
		var c = (b.next('.comment-block')).find('.comment-send');
		if (c.css("display") == "none"){
			c.show();
			c.find('textarea').focus();
		}
		else{
			c.hide();
		}
		event.preventDefault();
		return false;
    });
    $("div.post-holder").on('touchstart click', 'a.popup-message-link', function() {
    	var _this = $(this);
		var url = _this.data('url');
		var b = $("#light-box");
		b.css({width:'90%',height:'90%'});
		var c = b.find('.modal-body');
		var w = b.width()+'px';
		var h = b.height()+'px';
		c.empty();
		var iframe=$('<iframe src="'+url+'" width="'+w+'" height="'+h+'"></iframe>');
		var mask= $('<div id="mask"></div>');
		c.append(iframe);
		c.append(mask);
		b.modal();
	});
	$("div.post-holder").on('touchstart click', 'a.image-message-link', function() {
		var _this = $(this);
		var url = _this.data('url');
		_this.attr('href',url);
	});
	$("div.post-holder").on('touchstart click', '.lightbox, .lightboxOverlay, .lb-close', function(event) {
		$("a.image-message-link").removeAttr('href');
	});
	$(document).keyup(function(e) {
	    if (e.which == 27) {
			$("a.image-message-link").removeAttr('href');
		}
	});
	$('#light-box').on('hide', function (e) {
		var b = $("#light-box");
		var c = b.find('.modal-body');
		c.empty();
	});
	$('#light-box').resizable({
		resize: function(event, ui) {
			$("#myfr").css({ "height": ui.size.height,"width":ui.size.width});
			$("#mask").css({ "height": ui.size.height,"width":ui.size.width});

		},
		start: function(event, ui) {
           $("#mask").css("display","block");
        },
        stop: function(event, ui) {
           $("#mask").css("display","none");
        }
	});

});
