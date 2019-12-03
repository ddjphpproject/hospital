$(function () {


    //排序
    $('.sortBlock span:not(:last)').click(function () {
        if ($(this).attr('class') == 'active') {
            $(this).find($('em.active')).removeClass('active').siblings().addClass('active');
        } else {
            $(this).addClass('active').siblings().removeClass('active').find('em').removeClass('active');
            $(this).find('em').eq(1).addClass('active');
        }

    })
    //筛选
    var arr1 = ['专家', '地区', '状态', '科室']
    $('.barTabs li').click(function () {

        if ($(this).attr('class') == 'active') {
            $('.filterMask').hide();
            $('.tabBox .tabCon').hide();
            $(this).removeClass('active');
        } else {
            $('.filterMask').show();
            $('.tabBox .tabCon').eq($(this).index()).show().siblings().hide();
            $(this).addClass('active').siblings().removeClass('active');
        }

    })
    $('.tabCon li').click(function () {
        $(this).toggleClass('status');
    })

    $('.tabBlock .btns').each(function (i) {
        $('.tabBlock .btns').eq(i).attr('data-index', i);
    })
    //重置
    $('.tabBlock .btns .reset').click(function () {
        $(this).parent().parent().find('li').removeClass('active');
        $(this).parent().parent().find('.areaCon span').removeClass('active');
    })
    //确定
    $('.tabBlock .btns .ok').click(function () {
        var nowIndex = $(this).parent().attr('data-index');
        var selectLi = $(this).parent().parent().find('li.active');
        var selectSpan = $(this).parent().parent().find('.areaBox span.active');
        var nowLi = $('.barTabs li').eq(nowIndex);
        if (nowIndex == 1) {
            fn(selectSpan);
        } else {
            fn(selectLi);
        }

        $(this).parent().parent().hide();
        $('.filterMask').hide();

        function fn(obj) {
            if (obj.length > 0) {
                var str = '';
                obj.each(function (i) {
                    if (i == obj.length - 1) {
                        str += obj.eq(i).text();
                    } else {
                        str = str + obj.eq(i).text() + ',';
                    }
                })
                nowLi.addClass('active1').find('span').html(str + '<i class="bgImg">');
            } else {
                nowLi.removeClass().find('span').html(arr1[nowIndex] + '<i class="bgImg">');
            }
        }


    })

    $('.areaTab span').click(function () {

        var nowCon = $('.areaBox .areaCon').eq($(this).index());
        nowCon.show().siblings().hide();
        $('.areaBox span').removeClass('active');
        $(this).addClass('province').siblings().removeClass('province');
    })
    //全选
    $('.areaBox .areaCon').each(function () {
        select($(this));
    })

    function select(nowCon) {

        //父选框的全选和取消全选
        var selectall = nowCon.find('span').eq(0);
        var allCheckBtn = nowCon.find('span:gt(0)');
        //当改变全选值时
        selectall.click(function () {
            $(this).toggleClass('active');
            //如果全选被选中，则选中所有子选项;否则取消选中子选项
            if ($(this).attr('class') == 'active') {
                allCheckBtn.addClass('active');
            } else {
                allCheckBtn.removeClass('active');
            }
        });

        //当改变子选项时，需要判断子选项是否全部被选中，如果全部被选中，那么全选被选中；否则全选不被选中
        allCheckBtn.click(function () {
            $(this).toggleClass('active');
            if (nowCon.find('span.active').length == allCheckBtn.length) {
                selectall.addClass('active');
            } else {
                selectall.removeClass('active');
            }
        });
    }

    //筛选侧边栏
    $('#screen').click(function (event) {
        $('.sideBox').addClass('open');
        $('.sideMask').css('display', 'block');
        event.stopPropagation();

    })
    $('.sideMask').click(function () {
        $('.sideBox').removeClass('open');
        $('.sideMask').fadeOut();
        clearStyle();
    })

    $('.sideCon span').click(function () {
        $(this).toggleClass('active');
        var em = $(this).parent().parent().find('em')
        var text = em.text();
        var nowText = $(this).text();
        if ($(this).attr('class') == 'active') {
            if (text.length == 0) {
                text += nowText;
            } else {
                text += ',' + nowText;
            }

        } else {
            var index = text.indexOf(nowText);
            if (text.charAt(index - 1) == ',') {
                nowText = ',' + nowText;
            } else if (text.charAt(index + nowText.length) == ',') {
                nowText = nowText + ',';
            }
            text = text.replace(nowText, '');

        }

        em.html(text);

    })

    $('.sideBox .reset').click(function () {
        clearStyle();
    })
    $('.sideCon i').click(function () {
        var dl = $(this).parent().parent();
        if (dl.find('dd span').length > 4) {
            if (dl.attr('class') == 'open') {
                dl.removeClass('open');
            } else {
                dl.addClass('open');
            }
        }


    })

    //清除样式
    function clearStyle() {
        $('.sideCon span').removeClass('active');
        $('.sideCon input').val('');
        $('.sideCon em').html('');
        $('.sideCon dl').removeClass('open')
    }

    //点击选择
    $('#choice').click(function () {
        if ($(this).attr('class') == 'cancel') {
            $('.footer').fadeOut(100);
            $('.f2Box table i').removeClass('active').css('display', 'none');
            $(this).removeClass('cancel').html('选择');
            $('.footer-btn a').eq(0).removeClass('all').html('全选')

        } else {
            $('.footer').fadeIn(100);
            $('.f2Box table i').css('display', 'inline-block');
            $(this).addClass('cancel').html('取消');
        }

    })

    //$('.f2Box table i').click(function () {
    //    $(this).toggleClass('active');
    //    $(this).parent().toggleClass('name');
    //})

    $(".f2Box table ").on("click",'i',function(){
        $(this).toggleClass('active');
            $(this).parent().toggleClass('name');
    });


    $('.footer-btn a').eq(0).click(function () {
        if ($(this).attr('class') == 'all') {
            $('.f2Box table i').removeClass('active');
            $(this).removeClass('all').html('全选')
        } else {
            $('.f2Box table i').addClass('active');
            $(this).addClass('all').html('全不选')
        }

    })
})
