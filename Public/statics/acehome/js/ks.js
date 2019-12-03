$(function(){
    
    $('.ks table tr:odd').css('backgroundColor','#fff');
    var now;
    $('.ks table .revise').click(function(){
        now=$(this).parent().parent().find('td').eq(0); 
        $('.pop').fadeIn();
        var input= $('.pop dd input');
        input.val(now.html());
        $('.pop .submit').click(function(){
                now.html(input.val());
                $('.pop').fadeOut();
        })
        $('.pop .close').click(function(){
                $('.pop').fadeOut();
        })
    })

    //隔行变色
    function changeColor(){
        $('.ks table tr:even').css('backgroundColor','transparent')
        $('.ks table tr:odd').css('backgroundColor','#fff');
    }
    changeColor();
    creatDelpop();
    var now;
    $('#table1 .del').click(function(){
        now=$(this).parent().parent();
        $('.delPop').fadeIn();
        $('.delPop .ok').click(function(){
            now.remove();
            $('.delPop').fadeOut();
            changeColor();
        })
        $('.delPop .cancel').click(function(){           
            $('.delPop').fadeOut();
        })  
        $('.delPop .close').click(function(){           
            $('.delPop').fadeOut();
        })     
        
    })
});

   
    