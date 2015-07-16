/**
 * 浜垮繂缃戝浘绉�姛鑳絁S
 * 
 * 璁捐鎬濊矾锛�
 * 		
 *  鐩稿浜庣數鑴戠綉椤电増鐎戝竷娴佸姛鑳斤紝鎵嬫満鐗堢殑涓嶅悓鍦ㄤ簬鎵嬫満灞忓箷杈冨皬锛屽熀鏈彧鑳藉湪姘村钩鏂瑰悜
 * 	鎺掍袱鍒楋紒鍚屾椂锛岀綉椤电増澶氫娇鐢ㄧ粷瀵瑰畾浣嶆潵璁＄畻鍚勪釜鍥剧墖鍧楃殑闂锛屼娇鐢ㄧ殑鏄痯x涓哄崟浣嶏紒
 *  鍦ㄦ墜鏈虹濡傛灉鑰冭檻鍒版墜鏈哄垎杈ㄧ巼鍏煎鎬х殑闂锛屽垯闇�鐢╡m鍋氬崟浣嶏紝鏄剧劧鍦ㄧ粷瀵瑰畾浣嶆椂浼氭湁楹荤儲锛�
 *  鍥犱负js鑾峰彇鐨勯兘鏄痯x鍗曚綅鐨勫�锛堝綋鐒讹紝appcan椤甸潰鍒濆鍖栨椂宸茬粡璁惧畾浜唒x鍜宔m鐨勬瘮渚嬪�锛屽鏋滀笉瀚岄夯鐑︼紝涔熷彲浠ユ瘡娆″幓杞崲锛夛紒
 *  
 *  鍩轰簬鎵嬫満娴忚鐨勭壒鎬у拰灞�檺锛屽湪姝や娇鐢ㄥ彟澶栦竴绉嶈璁℃�璺涓嬶細
 *  
 *  灏嗗睆骞曞垝鍒嗕负涓ゆ爮锛屽悇鍗�0%瀹斤紝閭ｄ箞鍦ㄨ拷鍔犲浘鐗囧潡鏃跺氨涓嶇敤鑰冭檻缁濆瀹氫綅鐨勯棶棰橈紝鐩存帴鍦ㄥ乏鍙虫瘡涓猟iv閲岄潰杩藉姞鍥剧墖鍧楀嵆鍙紒
 *  鐒跺悗鍦ㄨ拷鍔犲浘鐗囧潡鏃讹紝棣栧厛鑾峰彇宸﹀彸涓や釜div鐨勯珮搴︼紝灏嗘渶杩戜竴涓浘鐗囧潡杩藉姞鍒伴珮搴﹀皬鐨勯偅涓嵆鍙紒
 *  
 * 
 * @author		甯冭。鎵嶅瓙
 * @date		2012-09-20
 * @email		work.jerryliu@gmail.com
 * @qq			394969553
 * @version		v1.0
 * @copyright	copyright 2012-2014	YeeYi.com All Rights Reserved	
 */


/**
 * 
 * 鏈嶅姟鍣ㄨ闂湴鍧�
 */
var apiHost = "/wymenuv2/product/getJson";

var page = 1;

/**
 * base64 鍔犲瘑瀵硅薄鍒濆鍖�
 */
var b64 = new Base64();

/* 质朴长存法  by lifesinger 数字补齐*/
function pad(num, n) {
    var len = num.toString().length;
    while(len < n) {
        num = "0" + num;
        len++;
    }
    return num;
}
/**
 * 缃戠粶璇锋眰鍑芥暟
 * @param {Object} url  璇锋眰鍦板潃
 * @param {Object} callback	  鍥炶皟鍑芥暟
 */
function xmlHttp(url,callback){
	if(url == ''){
		alert('地址不能为空');
	}else{
		$.getJSON(url,callback);
	}
}

/**
 * 鑾峰彇娲诲姩鍒楄〃
 */
function  getPicList(type,cat,pad){
	var url = '';
	if(parseInt(pad)){
		url = apiHost + '/pad/1';
		page = 1;
		xmlHttp(url,showListPad);
	}else{
		if(type==0){
		  url = apiHost + '/cat/'+cat;
		}else if(type==1){
		   url = apiHost + '/type/'+1;
		}else if(type==2){
		   url = apiHost + '/type/'+2;
		}else if(type==3){
		   url = apiHost + '/type/'+3;
		}else if(type==4){
		   url = apiHost + '/type/'+4;
		}
		page = 1;
		xmlHttp(url,showList);
	}
}

/**
 * 娲诲姩鍒楄〃鍥炶皟鍑芥暟锛屽鐞嗚繑鍥炵殑鏁版嵁锛屾樉绀哄湪鐣岄潰涓�
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
		
		//鍙互浣跨敤鍥剧墖缂撳瓨
		//imgCache('p'+item.tid,thumb);
		
		
		leftHeight = $("#leftPic").height();
		rightHeight = $("#rightPic").height();	
		
		if(leftHeight > rightHeight){
			//濡傛灉鍙充晶楂樺害灏忥紝鍒欒拷鍔犲埌鍙充晶
			var trHead = '<div class="blockRight">';
			var trPic = '<a class="product-pic" lid="'+item.lid+'" href="javascript:;"><img style="width:100%;margin:0;" src="'+thumb+'" id="p'+item.lid+'"></a>';
			var trBuy = ' <div class="pad-productbuy"><div class="inmiddle">'+item.product_name+'</div></div>';
			
            var trTitle = '<div class="pictitle" style="background:rgb(255,255,255);border-top:0px;padding-bottom:0;"><div class="subject" style="float:left"><div class="subject-left"><div class="order-num"></div><div  class="order-num-right"> '+item.order_number+'</div><div class="favorite-num"></div><div class="favorite-num-right"> '+item.favourite_number+'</div></div><div class="author"><div  class="price-down">￥'+item.original_price+'</div><div class="clear"></div></div>';
			var trAddinfo = '';
			if(item.order_id)
				 trAddinfo +='<div class="clear"></div></div><div class="addCart view hasorder" style="float:left" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"></div><div class="clear"></div> </div></div>';
			else
				 trAddinfo +='<div class="clear"></div></div><div class="addCart view" style="float:left" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"></div><div class="clear"></div> </div></div>';
			
                        tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			rightPicObj.append(tr);
		}else{
			//鍙嶄箣锛屽鏋滃彸渚ч珮搴﹀ぇ锛屽垯杩藉姞鍒板乏渚�
			var trHead = '<div class="blockLeft">';
			var trPic = '<a class="product-pic" lid="'+item.lid+'" href="javascript:;"><img style="width:100%;margin:0;" src="'+thumb+'" id="p'+item.lid+'"></a>';
			var trBuy = ' <div class="pad-productbuy"><div class="inmiddle">'+item.product_name+'</div></div>';
			
            var trTitle = '<div class="pictitle" style="background:rgb(255,255,255);border-top:0px;padding-bottom:0;"><div class="subject" style="float:left"><div class="subject-left"><div class="order-num"></div><div  class="order-num-right"> '+item.order_number+'</div><div class="favorite-num"></div><div class="favorite-num-right"> '+item.favourite_number+'</div></div><div class="author"><div  class="price-down">￥'+item.original_price+'</div><div class="clear"></div></div>';
			var trAddinfo = '';
			if(item.order_id)
				 trAddinfo +='<div class="clear"></div></div><div class="addCart view hasorder" style="float:left" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"></div><div class="clear"></div> </div></div>';
			else
				 trAddinfo +='<div class="clear"></div></div><div class="addCart view" style="float:left" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"></div><div class="clear"></div> </div></div>';
			tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			leftPicObj.append(tr);
		}
	}
	
}

function showListPad(items){
	var language = $('input[name="language"]').val();
	var leftPicObj = $("#leftPic");
	var rightPicObj = $("#rightPic");
	
	leftPicObj.html('');
	rightPicObj.html('');
	
	var leftHeight = 0;
	var rightHeight = 0;
	
	for(var i in items){
		var item = items[i];
		var thumb = item.main_picture;
		
		//鍙互浣跨敤鍥剧墖缂撳瓨
		//imgCache('p'+item.tid,thumb);
		if(parseInt(language)){
			item.original_price = parseInt(item.original_price);
		}
		
		leftHeight = $("#leftPic").height();
		rightHeight = $("#rightPic").height();	
		
		if(leftHeight > rightHeight){
			//濡傛灉鍙充晶楂樺害灏忥紝鍒欒拷鍔犲埌鍙充晶
			var trHead = '<div class="blockRight blockCategory" product-id="'+pad(item.lid,10)+'" store="'+item.store_number+'" category="'+pad(item.category_id,10)+'" category-name="'+item.category_name+'">';
			var trPic = '<a class="product-pic" lid="'+item.lid+'" href="javascript:;"><img style="width:100%;margin:0;" src="'+thumb+'" id="p'+item.lid+'"><i class="icon-hover-1 view-product-pic" product-id="'+item.lid+'" ><img src="/wymenuv2/./img/product/icon_search.png" style="width:4.8em;height:4.8em;"/><br>'+language_duotuliulan+'</i><i class="icon-hover-2 addCart" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"><img src="/wymenuv2/./img/product/icon_cart.png" style="width:4.8em;height:4.8em;"/><br>'+language_diancai+'</i>';
				if(parseInt(item.store_number)==0){
					trPic += '<div class="sellOff sellOut">'+" "+'已<br/>售完</div>';
				}else if(parseInt(item.store_number) > 0){
					trPic += '<div class="sellOff">仅剩<br/>'+item.store_number+'份</div>';
				}
				trPic += '</a>';
			var trBuy = ' <div class="pad-productbuy"><div class="inmiddle">'+item.product_name+'</div></div>';
            var trTitle = '<div class="pictitle" style="background:rgb(255,255,255);border-top:0px;padding-bottom:0;"><div class="subject-num"><div>'+language_renqi+item.order_number+'&nbsp;￥'+item.original_price+'</div><div class="clear"></div></div><div class="subject-order"><div class="single-num-circel">0</div><i class="icon-hover-3 delCart" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'">'+language_jiancai+'<img src="/wymenuv2/./img/product/icon_cart_m.png"></i></div>';
			var trAddinfo = '<div class="clear"></div>';
            tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			rightPicObj.append(tr);
		}else{
			//鍙嶄箣锛屽鏋滃彸渚ч珮搴﹀ぇ锛屽垯杩藉姞鍒板乏渚�
			var trHead = '<div class="blockLeft blockCategory" product-id="'+pad(item.lid,10)+'" store="'+item.store_number+'" category="'+pad(item.category_id,10)+'" category-name="'+item.category_name+'">';
			var trPic = '<a class="product-pic" lid="'+item.lid+'" href="javascript:;"><img style="width:100%;margin:0;" src="'+thumb+'" id="p'+item.lid+'"><i class="icon-hover-1 view-product-pic" product-id="'+item.lid+'" ><img src="/wymenuv2/./img/product/icon_search.png" style="width:4.8em;height:4.8em;"/><br>'+language_duotuliulan+'</i><i class="icon-hover-2 addCart" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'"><img src="/wymenuv2/./img/product/icon_cart.png" style="width:4.8em;height:4.8em;"/><br>'+language_diancai+'</i>';
				if(parseInt(item.store_number)==0){
					trPic += '<div class="sellOff sellOut">'+" "+'已<br/>售完</div>';
				}else if(parseInt(item.store_number) > 0){
					trPic += '<div class="sellOff">仅剩<br/>'+item.store_number+'份</div>';
				}
				trPic += '</a>';
			var trBuy = ' <div class="pad-productbuy"><div class="inmiddle">'+item.product_name+'</div></div>';
            var trTitle = '<div class="pictitle" style="background:rgb(255,255,255);border-top:0px;padding-bottom:0;"><div class="subject-num"><div>'+language_renqi+item.order_number+'&nbsp;￥'+item.original_price+'</div><div class="clear"></div></div><div class="subject-order"><div class="single-num-circel">0</div><i class="icon-hover-3 delCart" product-id="'+item.lid+'" type="'+item.type+'" price="'+item.original_price+'">'+language_jiancai+'<img src="/wymenuv2/./img/product/icon_cart_m.png"></i></div>';
			var trAddinfo = '<div class="clear"></div>';
			tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			leftPicObj.append(tr);
		}
	}
	
}


/**
 * 鑾峰彇涓嬩竴椤垫椿鍔�
 */
function  getMorePic(type,cat){
	page = page + 1;
	var url = '';
	if(type){
		url = apiHost + '&cat='+cat+'&page='+page;
	}else{
		url = apiHost + '&rec='+1+'&page='+page;
	}
 
	$("#nextpage").text("点击获取下一页");
	xmlHttp(url,showMoreList);
}

/**
 * 涓嬩竴椤垫椿鍔ㄥ垪琛ㄥ洖璋冨嚱鏁�
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
			//濡傛灉鍙充晶楂樺害灏忥紝鍒欒拷鍔犲埌鍙充晶
			var trHead = '<div class="blockRight">';
			var trPic = '<a href="/wymenuv2/product/productInfo/id/'+item.lid+'"><img style="min-height:'+item.picHeight+'em" src="'+thumb+'" id="p'+item.lid+'"></a>';
			var trBuy = ' <div class="pad-productbuy"><div class="inmiddle"><a class="numminus" href="javascript:;" product-id="'+item.lid+'" origin_price="'+item.origin_price+'" price="'+item.price+'">-</a>'+
						' <input type="text" class="num" name="product_num" maxlength="8" value="0"/><a class="numplus" href="javascript:;" product-id="'+item.lid+'" origin_price="'+item.origin_price+'" price="'+item.price+'">+</a></div></div>';
			var trTitle = '<div class="pictitle"><div class="subject">'+item.product_name+'</div>';
			var trAddinfo = '<div class="addinfo"><div class="author" style="color:red;">'+item.price+'</div><div class="view"><strike>'+item.origin_price+'</strike></div> </div></div></div>';
			tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			rightPicObj.append(tr);
		}else{
			//鍙嶄箣锛屽鏋滃彸渚ч珮搴﹀ぇ锛屽垯杩藉姞鍒板乏渚�
			var trHead = '<div class="blockLeft">';
			var trPic = '<a href="/wymenuv2/product/productInfo/id/'+item.lid+'"><img style="min-height:'+item.picHeight+'em" src="'+thumb+'" id="p'+item.lid+'"></a>';
			var trBuy = ' <div class="pad-productbuy"><div class="inmiddle"><a class="numminus" href="javascript:;" product-id="'+item.lid+'" origin_price="'+item.origin_price+'" price="'+item.price+'">-</a>'+
			' <input type="text" class="num" name="product_num" maxlength="8" value="0"/><a class="numplus" href="javascript:;" product-id="'+item.lid+'" origin_price="'+item.origin_price+'" price="'+item.price+'">+</a></div></div>';
			var trTitle = '<div class="pictitle"><div class="subject">'+item.product_name+'</div>';
			var trAddinfo = '<div class="addinfo"><div class="author" style="color:red;">'+item.price+'</div><div class="view"><strike>'+item.origin_price+'</strike></div> </div></div></div>';
			tr = trHead + trBuy + trPic + trTitle + trAddinfo;
			leftPicObj.append(tr);
		}
	}
	$("#nextpage").text("点击获取下一页");
}