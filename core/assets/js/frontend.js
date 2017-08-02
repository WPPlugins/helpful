jQuery( document ).ready( function( $ ) {
	
	$('.helpful-pro').on( 'click', function( event ) {
		
		event.preventDefault();
		
		var post_id = jQuery(this).data('id'),
			user = jQuery(this).data('user'),
			pro = jQuery(this).data('pro'),
			contra = jQuery(this).data('contra');			
		
		$.ajax({
			url : helpful.ajax_url,
			type : 'post',
			data : {
				action : 'helpfull_ajax_callback',
				post_id : post_id,
				user : user,
				pro : pro,
				contra : contra
			},
			success : function( response ) {
				$('.helpful').html( response );
			}
		});
		
		return false;
		
	});
	
	$('.helpful-con').on( 'click', function( event ) {
		
		event.preventDefault();
		
		var post_id = jQuery(this).data('id'),
			user = jQuery(this).data('user'),
			pro = jQuery(this).data('pro'),
			contra = jQuery(this).data('contra');
		
		$.ajax({
			url : helpful.ajax_url,
			type : 'post',
			data : {
				action : 'helpfull_ajax_callback',
				post_id : post_id,
				user : user,
				pro : pro,
				contra : contra
			},
			success : function( response ) {
				$('.helpful').html( response );
			}
		});
		
		return false;
		
	});
	
});