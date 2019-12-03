//ES5  兼容低版本系统
if (!Array.prototype.forEach) {
    Array.prototype.forEach = function(callback, thisArg) {
        var T, k;
        if (this == null) {
            throw new TypeError(" this is null or not defined");
        }
        var O = Object(this);
        var len = O.length >>> 0; // Hack to convert O.length to a UInt32
        if ({}.toString.call(callback) != "[object Function]") {
            throw new TypeError(callback + " is not a function");
        }
        if (thisArg) {
            T = thisArg;
        }
        k = 0;
        while (k < len) {
            var kValue;
            if (k in O) {
                kValue = O[k];
                callback.call(T, kValue, k, O);
            }
            k++;
        }
    };
}
if (!Array.prototype.filter){
  Array.prototype.filter = function(fun /*, thisArg */)
  {
    "use strict";

    if (this === void 0 || this === null)
      throw new TypeError();

    var t = Object(this);
    var len = t.length >>> 0;
    if (typeof fun !== "function")
      throw new TypeError();

    var res = [];
    var thisArg = arguments.length >= 2 ? arguments[1] : void 0;
    for (var i = 0; i < len; i++)
    {
      if (i in t)
      {
        var val = t[i];

        // NOTE: Technically this should Object.defineProperty at
        //       the next index, as push can be affected by
        //       properties on Object.prototype and Array.prototype.
        //       But that method's new, and collisions should be
        //       rare, so use the more-compatible alternative.
        if (fun.call(thisArg, val, i, t))
          res.push(val);
      }
    }

    return res;
  };
}
if (!Array.prototype.some){
  Array.prototype.some = function(fun /*, thisArg */)
  {
    'use strict';

    if (this === void 0 || this === null)
      throw new TypeError();

    var t = Object(this);
    var len = t.length >>> 0;
    if (typeof fun !== 'function')
      throw new TypeError();

    var thisArg = arguments.length >= 2 ? arguments[1] : void 0;
    for (var i = 0; i < len; i++)
    {
      if (i in t && fun.call(thisArg, t[i], i, t))
        return true;
    }

    return false;
  };
}

$(function () {
    Vue.component('demo-grid', {
        template: '#grid-template',
        props: {
            data: Array,
            columns: Array,
            filterKey: String
        },
        data: function () {
            var sortOrders = {}
            this.columns.forEach(function (key) {
                sortOrders[key] = 1
            })
            return {
                sortKey: '',
                sortOrders: sortOrders
            }
        },
        // watch: {
        //     data: function(v){
        //         console.log("-----u------",v)
        //     }
        // },
        computed: {
            filteredData: function () {
                var sortKey = this.sortKey
                var filterKey = this.filterKey && this.filterKey.toLowerCase()
                var order = this.sortOrders[sortKey] || 1
                var data = this.data
                // console.log("11111111",data)
                if (filterKey) {
                    data = data.filter(function (row) {
                        return Object.keys(row).some(function (key) {
                            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                        })
                    })
                }
                if (sortKey) {
                    data = data.slice().sort(function (a, b) {
                        a = a[sortKey]
                        b = b[sortKey]
                        return (a === b ? 0 : a > b ? 1 : -1) * order
                    })
                }
                // console.log("22222222",data)
                return data
            },
        },
        filters: {
            capitalize: function (str) {
                return str.charAt(0).toUpperCase() + str.slice(1)
            }
        },
        methods: {
            sortBy: function (key, index) {
                if(index==0) return;
                this.sortKey = key
                this.sortOrders[key] = this.sortOrders[key] * -1
            },
            quantity: function(key){
                var total = 0;
                this.data.forEach((t, i) => {
                    if(t.hasOwnProperty(key)) total+=parseInt(t[key]);
                })
                return total
            },
            outHtml: function(tab){
                var tabs=parseInt(tab);
                var str;

                switch (tabs) {
                    case 1:
                        str = '<img width="20" src="/mtpl/Public/images/icon-yes.png">';
                        break;
                    case 0:
                        str = '<img width="20" src="/mtpl/Public/images/icon-no.png">';
                        break;
                    default:
                        str = tab
                }
                return str;
            }
        },
    });

    Vue.filter( 'replaceYuYueKey' , function(tab) {
        var str;
            switch (tab) {
            case 'ks':
                str = '科室';
                break;
            case 'name':
                str = '患者';
                break;
            case 'phone':
                str = '电话';
                break;
            case 'subscribe':
                str = '预约';
                break;
            case 'diagnosis':
                str = '到诊';
                break;
            case 'consumption':
                str = '消费';
                break;
            case 'hospital':
                str = '住院';
                break;
            default:
        }
        return str;
    })
    if($('#page-viewdata').length) {
        var viewData = new Vue({
            el: '#page-viewdata',
            data: {
                scrollDisable: false,
                searchQuery: '',
                gridColumns: ['ks', 'subscribe', 'diagnosis', 'consumption', 'hospital'],
                gridData: [
                  
                ]
            },
            directives:{
                scroll: {
                    bind: function(el, binding){
                        var self = this;
                        window.addEventListener('scroll', function() {
                            if(document.body.scrollTop + window.innerHeight >= el.clientHeight + 112) {
                                console.log('load data');
                                var loadMore = binding.value;
                                if(!self.scrollDisable) loadMore();
                            }
                        })
                    }
                }
            },
        //     watch: {
        //     gridData: function(v){
        //         console.log("-----u------",v)
        //     }
        // },
            methods: {
                //查询方法
                query: function(){
                    // return;
                    var types=document.getElementById("querytype").value;
                    var star=document.getElementById("star-data").value;
                    var end=document.getElementById("end-data").value;
                    $.ajax({
                        url: "/index.php/Admin/View/index",
                        type: "POST",
                        // dataType: "jsonp",
                        data: {
                                querytype:types,
                                star:star,
                                end:end,
                        },

                        }).done(function(data) {
                            // console.log('loadingEnd')
                        console.log("success");
                        // alert(this);
                        // var json = JSON.parse(data);
                        // alert(data);
                        var jsons=eval ("(" + data + ")");
                        viewData.gridData  = jsons;
                        console.log(viewData.gridData);
                    }).fail(function() {
                        console.log("error");
                    })
                },
                //滚动加载更多
                loadMore: function(){
                    // this.scrollDisable = true;
                    // //在这里处理数据，追加到gridData
                    // var newAry = [];
                    // for(var i = 0; i < 10; i++) {
                    //   newAry.push({ name: '中老年病科'+i, subscribe: 40, diagnosis: 15, consume: 12, inhospital: 12})
                    // }
                    // //end
                    // this.gridData = this.gridData.concat(newAry);
                    // this.scrollDisable = false;
                }
            }
        });
    }
  if($('#page-subscribe').length) {
        var subscribe = new Vue({
            el: '#page-subscribe',
            data: {
                searchQuery: '',
                gridColumns: ['name', 'phone', 'diagnosis', 'consumption', 'hospital'],
                gridData: [

                ]
            },
            methods: {
                //查询方法
                query: function(){
                    // return;
                    var types=document.getElementById("name").value;
                    var star=document.getElementById("star-data").value;
                    var end=document.getElementById("end-data").value;
                    $.ajax({
                        url: "/index.php/Admin/View/show",
                        type: "POST",
                        // dataType: "jsonp",
                        data: {
                                querytype:types,
                                star:star,
                                end:end,
                        },

                        }).done(function(data) {
                            // console.log('loadingEnd')
                        console.log("success");
                        // alert(data);
                        // alert(this);
                        // var json = JSON.parse(data);
                        // alert(data);
                        var jsons=eval ("(" + data + ")");
                        // alert(jsons);
                        subscribe.gridData  = jsons;
                        console.log(subscribe.gridData);
                    }).fail(function() {
                        console.log("error");
                    })
                },
                //滚动加载更多
                loadMore: function(){
                    // this.scrollDisable = true;
                    // //在这里处理数据，追加到gridData
                    // var newAry = [];
                    // for(var i = 0; i < 10; i++) {
                    //   newAry.push({ name: '中老年病科'+i, subscribe: 40, diagnosis: 15, consume: 12, inhospital: 12})
                    // }
                    // //end
                    // this.gridData = this.gridData.concat(newAry);
                    // this.scrollDisable = false;
                }
            }
            //患者 电话 到诊 消费 住院
        })
    }

    // var typeCat = { name1: true, phone1: true }
    // function appendFormItem (obj, text, name){
    //     if(!typeCat[name]) return;
    //     var parent = obj.closest('div');
    //     var clone = parent.clone(true)
    //     clone.find('.tit').text(text).end().find('.icon-add').remove()
    //     parent.after(clone)
    //     typeCat[name1] = false;

    // }
    $('.js-add-name').on('click', function(){
        $('.js-add-name').css("display","none");
        $('.dis1').css("display","block");
    })
    $('.js-add-phone').on('click', function(){
        $('.js-add-phone').css("display","none");
        $('.dis').css("display","block");
    })
    $('.js-show-menu').on('touchstart', function(){
        var menu = $(this).closest('.header').find('.navi');
        if(menu.is(':hidden')) {
            menu.show()
        }else{
            menu.hide()
        }
    })
    $(".end-data")


})

