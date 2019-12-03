$(function(){
    var now1;
    var now2;
    $('#table1 .revise').click(function(){
        now1=$(this).parent().parent().find('td').eq(0); 
        now2=$(this).parent().parent().find('td').eq(1); 
        now3=$(this).parent().parent().find('td').eq(2); 
        $('.pop').fadeIn();
        var input= $('#ksname');
        var textarea= $('.pop dd textarea');
        var ksid=$('#ksid');
        input.val(now1.html());
        textarea.val(now2.html());
        ksid.val(now3.html());
        $('.pop .submit').click(function(){
                now1.html(input.val());
                now2.html(textarea.val());
                now3.html(ksid.val());
                $('.pop').fadeOut();
        })
        $('.pop .close').click(function(){
                $('.pop').fadeOut();
        })
    })  
});
   

 
   
    