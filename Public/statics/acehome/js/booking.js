$(function(){

    //日历插件
    laydate.render({
      elem: '.datepicker1' //指定元素
    });
    //日历插件
    laydate.render({
      elem: '.datepicker2' //指定元素
    });
    var arrDate=[days(0),days(1),days(7),getCurrentWeek(),getCurrentMonth()];

    setCondition($('.yyTime'),arrDate);
    textInputFocus($('.booking-top dl').eq(0).find('input'),'',null,null,null);
    textInputFocus($('.booking-top dl').eq(1).find('input'),'',null,null,null);
    $('#form1 ul input:not(:last)').click(function(){
        sPopup($(this).index('#form1 ul input'));
       
    })

    //隔行变色
    function changeColor(){
        $('#table1 tr:even').css('backgroundColor','transparent')
        $('#table1 tr:odd').css('backgroundColor','#fff');
    }

    changeColor();

    creatDelpop();

    $('#form1 .search-btn').click(function(){
        table1();
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
         
    })
 
});

    //搜索下拉框
    function searchList(){
        var searchInput=$('.searchText .t');
        var suggestions=$('#suggestions');
        // 输入搜索内容，显示下拉框
        searchInput.on('keyup', function() {
                // 输入的内容
                var text = $(this).val()
                // 智能提示数据jsonp的请求地址
                var _url = 'https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=' + text + '&json=1&p=3&sid=20144_1467_19033_20515_18240_17949_20388_20456_18133_17001_15202_11615&req=2&csor=2&pwd=s&cb=jQuery110207612423721154653_1467355506619&_=1467355506623'

                // ajax根据输入的内容，获取jsonp数据,并渲染到提示下拉框
                $.ajax({
                    url: _url,
                    type: 'GET',
                    dataType: 'jsonp', //指定服务器返回的数据类型
                    jsonpCallback: 'jQuery110207612423721154653_1467355506619',
                    success: function (data) {
                        var data = data.s
                        var lis = data.reduce(function (result, item) {
                            return result += '<li>' + item + '</li>'
                        }, '')
                        suggestions.html(lis)
                        suggestions.show();
                    }
                })
        })

        // 点击其他区域 下拉框隐藏
        $(document).click(function() {
            suggestions.hide()
        })

        //为suggestionWrap，绑定事件委托，点击suggestionWrap下的li,输入到搜索框
        suggestions.on('click', 'li', function() {
            var text = $(this).html()
            searchInput.val(text);
        })


    }
   
    