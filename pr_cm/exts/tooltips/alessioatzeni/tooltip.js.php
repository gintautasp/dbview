
	$('.masterTooltip').hover(function(){
		// Hover over code
		var title = $(this).attr('title');
		title.replace ( '&quot;', '"' );
		title.replace (  '&lt;', '<' );
		title.replace ( '&gt;', '>' );
		// alert ( title );
		$(this).data('tipText', title).removeAttr('title');
		$('<pre class="tooltip"></pre>')
		.text(title)
		.appendTo('body')
		.fadeIn('slow');
	}, function() {
		// Hover out code
		$(this).attr('title', $(this).data('tipText'));
		$('.tooltip').remove();
	}).mousemove(function(e) {
		var mousex = e.pageX + 20; //Get X coordinates
		var mousey = e.pageY + 10; //Get Y coordinates
		$('.tooltip')
		.css({ top: mousey, left: mousex })
	});
