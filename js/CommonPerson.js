/// <reference path="jquery-1.8.0.min.js" />


/*页面加载loading样式
*/
//.loadingPage_bg { background: none repeat scroll 0 0 #fff; display: block; height: 100%; left: 0; /*:rgba(0,0,0,0.5);*/ opacity: 0.1; filter: alpha(opacity=50); position: absolute; top: 0; width: 100%; z-index: 110; }
//#loadingPage { display: block; font-weight: bold; font-size: 12px; color: #595959; height: 28px; left: 50%; line-height: 27px; margin-left: -74px; margin-top: -14px; padding: 10px 10px 10px 50px; position: absolute; text-align: left; top: 50%; width: 148px; z-index: 111; background: url(img/loading.gif) no-repeat scroll 12px center #FFFFFF; border: 2px solid #86A5AD; }


var CommonPerson = {};
CommonPerson.Base = {};
CommonPerson.Base.LoadingPic = {
    operation: {
        timeTest: null,                     //延时器
        loadingCount: 0,                    //计数器 当同时被多次调用的时候 记录次数
        loadingImgUrl: "jiazai/index.html",  //默认load图地址
        loadingImgHeight: 24,               //Loading图的高
        loadingImgWidth: 24                 //Loading图的宽
    },

    //显示全屏Loading图
    FullScreenShow: function (msg) {
        if (msg === undefined) {
            msg = ".";
        }

        if ($("#div_loadingImg").length == 0) {
            $("body").append("<div id='div_loadingImg'></div>");
        }
        if (this.operation.loadingCount < 1) {
            this.operation.timeTest = setTimeout(function () {
                $("#div_loadingImg").append("<div id='loadingPage_bg' class='loadingPage_bg1'></div><div id='loadingPage'><span class='spinner-loader'>Loading&#8230;</span></div>");
                $("#loadingPage_bg").height($(top.window.document).height()).width($(top.window.document).width());
            }, 100);
        }
        this.operation.loadingCount += 1;
    },

    //隐藏全屏Loading图
    FullScreenHide: function () {
        this.operation.loadingCount -= 1;
        if (this.operation.loadingCount <= 0) {
            clearTimeout(this.operation.timeTest);
            $("#div_loadingImg").empty();
            $("#div_loadingImg").remove();
            this.operation.loadingCount = 0;
        }
    },

}