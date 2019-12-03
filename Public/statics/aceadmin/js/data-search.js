$(function(){
    var arrDate=[days(0),days(1),days(7),getCurrentWeek(),getCurrentMonth()];
    var arrAge=[[18,24],[25,29],[30,39],[40,49],[50,100]];

    setCondition($('.yyTime'),arrDate);
    setCondition($('.tjTime'),arrDate);
    setCondition($('.ageRange'),arrAge);

    

    $('#form1 ul input').click(function(){
        sPopup($(this).index('#form1 ul input'));
    })

    //隔行变色
    function changeColor(){
        $('.data-search table tr:even').addClass('grey');
        $('.data-search table tr:odd').removeClass('grey');
    }
    changeColor();
    $('#form1 .search-btn').click(function(){
        table1();
         //父选框的全选和取消全选
            var selectall=$('#table1 input').eq(0);
            var allCheckBtn=$('#table1 input[name="checkBtn"]'); 
             //当改变全选值时
                selectall.change(function(){
                    //如果全选被选中，则选中所有子选项;否则取消选中子选项
                    if($(this).is(":checked")){
                        allCheckBtn.prop("checked",true);
                    }else{
                        allCheckBtn.removeAttr("checked",false);
                    }
                });
                 
                //当改变子选项时，需要判断子选项是否全部被选中，如果全部被选中，那么全选被选中；否则全选不被选中
                allCheckBtn.change(function(){
                     if($('#table1 input[name="checkBtn"]:checked').length==allCheckBtn.length){
                         selectall.prop("checked",true);
                     }else{
                       selectall.removeAttr("checked",false);
                     }
                });

            $('#table1 .del').click(function(){
                $(this).parent().parent().remove();
                changeColor();
            })
         
    })

  
});

  // 获取当前和昨天，最近7天
    function days(count) {
      var time1 = new Date();
      var time2 = new Date();
      if (count === 1) {
        time1.setTime(time1.getTime() - (24 * 60 * 60 * 1000))
      } else {
        if (count >= 0) {
          time1.setTime(time1.getTime())
        } else {
          if (count === -2) {
            time1.setTime(time1.getTime() + (24 * 60 * 60 * 1000) * 2)
          } else {
            time1.setTime(time1.getTime() + (24 * 60 * 60 * 1000))
          }
        }
      }
      time2.setTime(time2.getTime() - (24 * 60 * 60 * 1000 * count));//之前的7天或者30天
      return [formatTime(time2), formatTime(time1)]
    }

    //获取本周
    function getCurrentWeek(){ 

        var currentDate=new Date(); 
        //返回date是一周中的某一天  
        var week=currentDate.getDay(); 
        //返回date是一个月中的某一天  
        var month=currentDate.getDate(); 
        //一天的毫秒数  
        var millisecond=1000*60*60*24; 
        //减去的天数  
        var minusDay=week!=0?week-1:6; 
        //alert(minusDay);  
        //本周 周一  
        var monday=new Date(currentDate.getTime()-(minusDay*millisecond)); 
        //本周 周日  
        var sunday=new Date(monday.getTime()+(6*millisecond)); 
        return [formatTime(monday),formatTime(sunday)]; 
    }; 

    //获得本月的起止时间
     
    function  getCurrentMonth(){ 
            //获取当前时间  
            var currentDate=new Date(); 
            //获得当前月份0-11  
            var currentMonth=currentDate.getMonth(); 
            //获得当前年份4位年  
            var currentYear=currentDate.getFullYear(); 
            //求出本月第一天  
            var firstDay=new Date(currentYear,currentMonth,1); 
            //当为12月的时候年份需要加1  
            //月份需要更新为0 也就是下一年的第一个月  
            if(currentMonth==11){ 
                currentYear++; 
                currentMonth=0;//就为  
            }else{ 
                //否则只是月份增加,以便求的下一月的第一天  
                currentMonth++; 
            }    
            //一天的毫秒数  
            var millisecond=1000*60*60*24; 
            //下月的第一天  
            var nextMonthDayOne=new Date(currentYear,currentMonth,1); 
            //求出上月的最后一天  
            var lastDay=new Date(nextMonthDayOne.getTime()-millisecond); 
            //返回  
            return [formatTime(firstDay),formatTime(lastDay)]; 
    }; 

    //时间转换
    function formatTime(num){
        var date = new Date(num);
        Y = date.getFullYear();
        M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1);
        D = date.getDate()<10?'0'+date.getDate():date.getDate();
        return Y+'-'+M+'-'+D;
    }

    function setCondition(obj,arr){
        obj.find('b').click(function(){
            obj.find('input').eq(0).val(arr[$(this).index()][0]);
            obj.find('input').eq(1).val(arr[$(this).index()][1]);   
        })
    }


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


   
    