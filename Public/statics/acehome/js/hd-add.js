$(function(){

	$('.hd-add label input').click(function(){
    	$(this).parent().parent().find('label').removeClass('active');
		if($(this).is(':checked')){
            $(this).parent().addClass('active');
        }else{
        	$(this).parent().removeClass('active');
        }
	})
	
	$("#up").change(function(e) {

		var readFile = new FileReader();
		var mfile = $("#up")[0].files[0]; 
		if(mfile){
			readFile.readAsDataURL(mfile);
			readFile.onload = function() {
				$("#ImgPr").attr("src", this.result);
			}
		}else{
			return false;
		}
    });
})