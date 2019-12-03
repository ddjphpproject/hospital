$(function(){
    var arrDate=[days(0),days(1),days(7),getCurrentWeek(),getCurrentMonth()];

    setCondition($('.yyTime'),arrDate);
    textInputFocus($('#form1 li').eq(0).find('input'),'',null,null,null);
    textInputFocus($('#form1 li').eq(1).find('input'),'',null,null,null);

    //日历插件
    laydate.render({
      elem: '.datepicker1' //指定元素
    });
    laydate.render({
      elem: '.datepicker2' //指定元素
    });
    
    //隔行变色
    function changeColor(){
        $('#table1 tr:even').css('backgroundColor','transparent')
        $('#table1 tr:odd').css('backgroundColor','#fff');
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

    