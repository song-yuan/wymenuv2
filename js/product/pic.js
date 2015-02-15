/**
 * 亿忆网图秀功能JS
 * 
 * 设计思路：
 * 		
 *  相对于电脑网页版瀑布流功能，手机版的不同在于手机屏幕较小，基本只能在水平方向
 * 	排两列！同时，网页版多使用绝对定位来计算各个图片块的问题，使用的是px为单位！
 *  在手机端如果考虑到手机分辨率兼容性的问题，则需要用em做单位，显然在绝对定位时会有麻烦，
 *  因为js获取的都是px单位的值（当然，appcan页面初始化时已经设定了px和em的比例值，如果不嫌麻烦，也可以每次去转换）！
 *  
 *  基于手机浏览的特性和局限，在此使用另外一种设计思路如下：
 *  
 *  将屏幕划分为两栏，各占50%宽，那么在追加图片块时就不用考虑绝对定位的问题，直接在左右每个div里面追加图片块即可！
 *  然后在追加图片块时，首先获取左右两个div的高度，将最近一个图片块追加到高度小的那个即可！
 *  
 * 
 * @author		布衣才子
 * @date		2012-09-20
 * @email		work.jerryliu@gmail.com
 * @qq			394969553
 * @version		v1.0
 * @copyright	copyright 2012-2014	YeeYi.com All Rights Reserved	
 */


/**
 * 
 * 服务器访问地址
 */
var apiHost = "/wymenuv2/product/getJson";

var page = 1;

/**
 * base64 加密对象初始化
 */
var b64 = new Base64();

/**
 * 网络请求函数
 * @param {Object} url  请求地址
 * @param {Object} callback	  回调函数
 */
function xmlHttp(url,callback){
	if(url == ''){
		alert('请求地址不能为空！');
	}else{
		$.getJSON(url,callback);
	}
}

/**
 * 获取活动列表
 */
function  getPicList(type,cat){
	var url = '';
	if(type){
	  url = apiHost + '/cat/'+cat;
	}else{
	   url = apiHost + '/rec/'+1;
	}
	page = 1;
	xmlHttp(url,showList);
}

/**
 * 活动列表回调函数，处理返回的数据，显示在界面上
 * @param {Object} items
 */
function showList(items){
	var leftPicObj = $("#leftPic");
	var rightPicObj = $("#rightPic");
	
	leftPicObj.html('');
	rightPicObj.html('');
	
	var leftHeight = 0;
	var rightHeight = 0;
	
	for(var i in items){
		var item = items[i];
		var thumb = item.main_picture;
		
		//可以使用图片缓存
		//imgCache('p'+item.tid,thumb);
		
		
		leftHeight = $("#leftPic").height();
		rightHeight = $("#rightPic").height();	
		
		if(leftHeight > rightHeight){
			//如果右侧高度小，则追加到右侧
			var trHead = '<div class="blockRight">';
			var trPic = '<a href="javascript:;"><img style="min-height:'+item.picHeight+'em;width:100%;margin:0;" src="'+thumb+'" id="p'+item.lid+'"></a>';
			var trBuy = ' <div class="productbuy"><div class="inmiddle">'+item.product_name+'</div></div>';
			var trTitle = '<div class="pictitle" style="background:rgb(255,255,255);border-top:0px;padding-bottom:0;"><div class="subject"><div class="subject-left"><div class="order-num"></div><div class="order-num-right"> '+item.order_number+'</div></div><div id="favorite" class="subject-right" product-id="'+item.lid+'"><div class="favorite-num"></div><div class="favorite-num-right"> '+item.favourite_number+'</div></div><div class="clear"></div></div>';
			var trAddinfo = '<div class="addinfo" style="padding:0"><div class="author" ><div  class="price-down">￥'+item.original_price+'</div></div>';
				if(item.order_id)
					 trAddinfo +='<div id="addCart" class="view hasorder" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"></div> <div class="clear"></div></div></div></div>';
				else
					 trAddinfo +='<div id="addCart" class="view" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"></div> <div class="clear"></div></div></div></div>';
			tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			rightPicObj.append(tr);
		}else{
			//反之，如果右侧高度大，则追加到左侧
			var trHead = '<div class="blockLeft">';
			var trPic = '<a href="javascript:;"><img style="min-height:'+item.picHeight+'em;width:100%;margin:0;" src="'+thumb+'" id="p'+item.lid+'"></a>';
			var trBuy = ' <div class="productbuy"><div class="inmiddle">'+item.product_name+'</div></div>';
			var trTitle = '<div class="pictitle" style="background:rgb(255,255,255);border-top:0px;padding-bottom:0;"><div class="subject" style="float:left"><div class="subject-left"><div class="order-num"></div><div  class="order-num-right"> '+item.order_number+'</div></div><div id="favorite" class="subject-right" product-id="'+item.lid+'"><div class="favorite-num"></div><div class="favorite-num-right"> '+item.favourite_number+'</div></div><div class="author"><div  class="price-down">￥'+item.original_price+'</div><div class="clear"></div></div>';
			var trAddinfo = '';
			if(item.order_id)
				 trAddinfo +='<div class="clear"></div></div><div id="addCart" class="view hasorder" style="float:left" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"></div> </div></div>';
			else
				 trAddinfo +='<div class="clear"></div></div><div id="addCart" class="view" style="float:left" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"></div> </div></div>';
			tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			leftPicObj.append(tr);
		}
	}
	
}



/**
 * 获取下一页活动
 */
function  getMorePic(type,cat){
	page = page + 1;
	var url = '';
	if(type){
		url = apiHost + '&cat='+cat+'&page='+page;
	}else{
		url = apiHost + '&rec='+1+'&page='+page;
	}
 
	$("#nextpage").text("数据加载中……");
	xmlHttp(url,showMoreList);
}

/**
 * 下一页活动列表回调函数
 * @param {Object} items
 */
function showMoreList(items){
	var leftPicObj = $("#leftPic");
	var rightPicObj = $("#rightPic");
	j = 0;
	var leftHeight = 0;
	var rightHeight = 0;
	for(var i in items){
		var item = items[i];
		var thumb = item.main_picture;
		//imgCache('p'+item.tid,thumb);
		var leftHeight = $("#leftPic").height();
		var rightHeight = $("#rightPic").height();	
		if(leftHeight > rightHeight){
			//如果右侧高度小，则追加到右侧
			var trHead = '<div class="blockRight">';
			var trPic = '<a href="/wymenuv2/product/productInfo/id/'+item.lid+'"><img style="min-height:'+item.picHeight+'em" src="'+thumb+'" id="p'+item.lid+'"></a>';
			var trBuy = ' <div class="productbuy"><div class="inmiddle"><a class="numminus" href="javascript:;" product-id="'+item.lid+'" origin_price="'+item.origin_price+'" price="'+item.price+'">-</a>'+
						' <input type="text" class="num" name="product_num" maxlength="8" value="0"/><a class="numplus" href="javascript:;" product-id="'+item.lid+'" origin_price="'+item.origin_price+'" price="'+item.price+'">+</a></div></div>';
			var trTitle = '<div class="pictitle"><div class="subject">'+item.product_name+'</div>';
			var trAddinfo = '<div class="addinfo"><div class="author" style="color:red;">价格: ￥'+item.price+'</div><div class="view">原价: ￥<strike>'+item.origin_price+'</strike></div> </div></div></div>';
			tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			rightPicObj.append(tr);
		}else{
			//反之，如果右侧高度大，则追加到左侧
			var trHead = '<div class="blockLeft">';
			var trPic = '<a href="/wymenuv2/product/productInfo/id/'+item.lid+'"><img style="min-height:'+item.picHeight+'em" src="'+thumb+'" id="p'+item.lid+'"></a>';
			var trBuy = ' <div class="productbuy"><div class="inmiddle"><a class="numminus" href="javascript:;" product-id="'+item.lid+'" origin_price="'+item.origin_price+'" price="'+item.price+'">-</a>'+
			' <input type="text" class="num" name="product_num" maxlength="8" value="0"/><a class="numplus" href="javascript:;" product-id="'+item.lid+'" origin_price="'+item.origin_price+'" price="'+item.price+'">+</a></div></div>';
			var trTitle = '<div class="pictitle"><div class="subject">'+item.product_name+'</div>';
			var trAddinfo = '<div class="addinfo"><div class="author" style="color:red;">价格: ￥'+item.price+'</div><div class="view">原价: ￥<strike>'+item.origin_price+'</strike></div> </div></div></div>';
			tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			leftPicObj.append(tr);
		}
	}
	$("#nextpage").text("查看下8条");
}