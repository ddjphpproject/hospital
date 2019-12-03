$(function(){
	$('.hd-ts .hdCon .time').find('span').html(getTime());
    $('.hd-ts .open').click(function(){
   	$(this).parent().parent().toggleClass('active');
   })

})


function getTime(){
     var myDate=new Date();
     var year=myDate.getFullYear();
     var month=myDate.getMonth()+1;
     var date=myDate.getDate();
     var h=myDate.getHours();
     var m=myDate.getMinutes();
     var time=year+'-'+(month<10?"0"+month:month)+'-'+(date<10?"0"+date:date)+' '+(h<10?"0"+h:h)+':'+(m<10?"0"+m:m);
     return time;
 }