$(function(){
		$('.channel input').click(function(){
			if($(this).is(':checked')){
                $(this).parent().addClass('active');
            }else{
            	$(this).parent().removeClass('active');
            }
		})
		$('.PopupCon input').click(function(){
			$('.PopupCon input').parent().parent().removeClass('active');
			if($(this).is(':checked')){
                $(this).parent().parent().addClass('active');
            }
		})
		$('#choice').click(function(){
			$('.footer').fadeIn();
		})

		$('.PopupCon .ok').click(function(){
			$('#choice').find('span').html($('.PopupCon input:checked').parent().find('span').text()).addClass('shxing');
			$('.footer').fadeOut();
		})
		$('.PopupCon .cancel').click(function(){
			$('.footer').fadeOut();
		})		 

})
