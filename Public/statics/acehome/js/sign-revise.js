


$(function(){
		 $('.sign-revise label input').click(function(){
	    	$(this).parent().parent().find('label').removeClass('active');
			if($(this).is(':checked')){
	            $(this).parent().addClass('active');
	        }else{
	        	$(this).parent().removeClass('active');
	        }
		})


		$('#area').click(function(){
			$('.footer').fadeIn();
		})

		$('.PopupCon .close').click(function(){
			clear();
		})

		var aSpan=$('.addressTab span');
		var addressCons=$('.addressBox .addressCon');
	   //加索引值
	   addressCons.each(function(i){
	   		$(this).attr('data-index',i);
	   })
	   //点击span 切换
	   aSpan.click(function(){
	   	 $(this).addClass('active').siblings().removeClass('active');
	   	  addressCons.eq($(this).index()).fadeIn().siblings().fadeOut();
	   })
	   //点击li事件
	   $('.addressCon li').click(function(){
	   	  $(this).parent().find('li').removeClass('active');
	   	  $(this).addClass('active');
	   	  var index=Number($(this).parent().parent().attr('data-index'));
	   	  aSpan.eq(index).html($(this).text()).removeClass('active');
	   	  aSpan.eq(index+1).html('请选择')
	   	  aSpan.eq(index+1).addClass('active');
	   	  addressCons.eq(index).hide();
	   	  addressCons.eq(index+1).show();
	   	  if(index==aSpan.length-1){
	   	  	var str='';
	   	  	aSpan.each(function(){
	   	  		str+=$(this).html();
	   	  	})
	   	  	$('#area').html(str).addClass('active');
	   	  	clear();
	   	  }

	   })

	   //关闭清除样式
	   	 function  clear(){
	   	 	$('.footer').hide();
	   	 	aSpan.removeClass('active').html('');
	   	 	addressCons.hide();
	   	 	$('.addressBox .addressCon li').removeClass('active');
	   	 	aSpan.eq(0).addClass('active').html('请选择');
	   	 	addressCons.eq(0).show();
	   	 }
})