<?php
/**
 * PushTemplates.php
 * 微信推送消息模板
 */
 
class PushTemplates {
	
	// 文本 采用常量方式
	const TEXT = 	"<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>0</FuncFlag>
					</xml>";

	// 图文 - 单条图文
	const NEWS = 	"<xml>
					 <ToUserName><![CDATA[%s]]></ToUserName>
					 <FromUserName><![CDATA[%s]]></FromUserName>
					 <CreateTime>%s</CreateTime>
					 <MsgType><![CDATA[news]]></MsgType>
					 <ArticleCount>1</ArticleCount>
					 <Articles>
						 <item>
							 <Title><![CDATA[%s]]></Title> 
							 <Description><![CDATA[%s]]></Description>
							 <PicUrl><![CDATA[%s]]></PicUrl>
							 <Url><![CDATA[%s]]></Url>
						 </item>
					 </Articles>
					 <FuncFlag>1</FuncFlag>
					 </xml>";
	
}
 
 
?>