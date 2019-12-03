
/* common.js
 * version:1.1.703
 * last update time:2016/03/10
 */

$(function(){
	$('.login').hover(function(){
		$('.login').addClass('active');
	},function(){
		$('.login').removeClass('active');
	})
	$('.login .out').click(function(){
		$('.login a').hide();
		$('.login .out').hide();
		$('.login .enter').css('display','block');
	})
});

function creatDelpop(){
    var $delPop=$('<div class="delPop"><div class="delPop-mask"></div><div class="delCon"><span class="close"></span><p>您确实要把该信息放入回收站吗？</p><div class="btns"><a href="#" class="cancel">取消</a><a href="#" class="ok">确定</a></div></div></div>');
    $('body').append($delPop);
}

/* * NAME：input[type="text"]聚焦
 * inputElement(string),滚动盒子(element,id,'.class')
 * defText(string),默认默认提示文字
 * defStyle(string),默认样式
 * focusStyle(string),聚焦样式
 * emptyStyle(string),离开为空时的提示样式
 */
function textInputFocus(inputElement,defText,defStyle,focusStyle,emptyStyle){
	$(inputElement).val(defText).addClass(defStyle);
	$(inputElement).focus(function(){
		if($(this).val() == defText){
			$(this).val('');
		};
		$(this).addClass(focusStyle);
		if($(this).hasClass(defStyle)){
			$(this).removeClass(defStyle);
		}else if($(this).hasClass(emptyStyle)){
			$(this).removeClass(emptyStyle);
		};
	});
	$(inputElement).blur(function(){
		if($(this).val() == '' || $(this).val() == defText){
			$(this).val(defText).removeClass(focusStyle).addClass(emptyStyle);
		}else{
			$(this).removeClass(focusStyle);
		};
	});
}

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




