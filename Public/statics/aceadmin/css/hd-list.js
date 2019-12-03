$(function(){

    textInputFocus('.hd-list .search .t','请输入搜索关键字',null,null,null);
    searchList();


    //隔行变色
    function changeColor(){
        $('.hd-list table tr:even').addClass('grey');
        $('.hd-list table tr:odd').removeClass('grey');
    }
    $('.hd-list table .del').click(function(){
        $(this).parent().parent().remove();
        changeColor();
    })
    changeColor();
    
});


//搜索下拉框
    function searchList(){
        var searchInput=$('.hd-list .search .t');
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
   