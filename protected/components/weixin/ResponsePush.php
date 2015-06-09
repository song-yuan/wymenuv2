<?php
/**
 * ResponsePush.php
 * 微信被动回复响应消息
 * 1.回复文本消息 text
 * 2.回复图片消息 image
 * 3.回复语言消息 voice
 * 4.回复视频消息 video
 * 5.回复音乐消息 music
 * 6.回复图文消息 news
 */
 
class ResponsePush {
	
	/**
	 * 回复文本消息
	 * @param String $fromUserName 发送方
	 * @param String $toUserName 接收方
	 * @param String $content 文本内容
	 */
	public static function text($fromUserName, $toUserName, $content) {
		return sprintf(PushTemplates::TEXT, $fromUserName, $toUserName, time(),  $content);
	}
		
	/**
	 * 返回图文模板 - 多条图文，故采用方法，直接返回格式化后的字符串
	 * @param String $fromUserName 发送方
	 * @param String $toUserName 接收方
	 * @param Array $arr 一维度数组 或 二维数组，代表一个图文消息里多个条目
	 * @param Integer $brandId 品牌主键
	 * 注意：
	 * 数组$arr可以是关联数组，且存在键 title, description, pic_url, url
	 * 也可以是索引数组，则0-3对应上述相应的值，不可错乱
	 * 
	 * picUrl限制图片链接的域名需要与开发者填写的基本资料中的Url一致
	 */
	public static function news($fromUserName, $toUserName, $arr, $brandId) {
		if(ArrayUtil::depth($arr) == 1) {	// 一维数组，图文一条条目
			if(isset($v['title']))
				return sprintf(PushTemplates::NEWS, $fromUserName, $toUserName, time(),   $arr['title'], $arr['description'], Brand::fullPicUrl($arr['pic_url'], $brandId), URLOauth::redirect($brandId, $arr['url']));
			else
				return sprintf(PushTemplates::NEWS, $fromUserName, $toUserName, time(),   $arr[0], $arr[1], Brand::fullPicUrl($arr[2], $brandId), URLOauth::redirect($brandId, $arr[3]));
		}else {	// 二维数组，图文多条条目
			$item = "<item>
				 <Title><![CDATA[%s]]></Title> 
				 <Description><![CDATA[%s]]></Description>
				 <PicUrl><![CDATA[%s]]></PicUrl>
				 <Url><![CDATA[%s]]></Url>
				 </item>";
			$itemStr = '';
			foreach($arr as $v) {
				if(isset($v['title'])) 
					$itemStr .= sprintf($item, $v['title'], $v['description'], Brand::fullPicUrl($v['pic_url'], $brandId), URLOauth::redirect($brandId, $v['url']));
				else
					$itemStr .= sprintf($item, $v[0], $v[1], Brand::fullPicUrl($v[2], $brandId), URLOauth::redirect($brandId, $v[3]));
			}
			// 注意，不能在此处 $tbl . $itemStr ."</Articles>..."，然后再sprintf因此URLOauth中的网址包含了转义字符%s等，造成sprintf参数太少的错误
			$tbl =  "<xml>
					 <ToUserName><![CDATA[%s]]></ToUserName>
					 <FromUserName><![CDATA[%s]]></FromUserName>
					 <CreateTime>%s</CreateTime>
					 <MsgType><![CDATA[news]]></MsgType>
					 <ArticleCount>".count($arr)."</ArticleCount>
					 <Articles>
						 ";
			$frontPortion = sprintf($tbl, $fromUserName, $toUserName, time());
			return $frontPortion.$itemStr."
					 </Articles>
					 <FuncFlag>1</FuncFlag>
					 </xml>";
		}
	}
	
}
 
?>