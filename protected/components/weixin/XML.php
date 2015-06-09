<?php
/**
 * XML.php
 * XML处理类
 * 默认微信POST过来的都是UTF-8格式的数据
 */
 
class XML {
	
	/**
	 * 本方法主要处理对方POST过来的XML数据信息
	 * simplexml_load_string 返回的是SimpleXMLElement对象, 通过->访问的类属性依然是SimpleXMLElement，因此通过json_encode和json_decode方式转换成数组
	 * simplexml_load_string函数自身带有trim功能，键名不存在数字类型或字符串数字类型的， 键值被转换成数组。
	 */
	public static function getPostArr() {
		$xmlStr = file_get_contents('php://input');
		if(!$xmlStr)
			throw new Exception('无POST XML类型的数据');
		return self::convertToArr($xmlStr);
	}
	
	/**
	 * 本方法对xml格式的字符串进行处理
	 * 注意：需要经过array_walk把一些空数组转换成字符串，如： <name></name> ['name']= array(); 变成['name']=''
	 */
	public static function convertToArr($xmlStr) {
		return self::emptyArrToString(json_decode(json_encode(simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA)), true));
	} 
	
	/**
	 * 把数组元素中的值为空数组的转为空字符串
	 */
	public static function emptyArrToString($arr) {
		function emptyArrToString(&$v, $k) {
			$v =  $v == array() ? '':$v;
		}
		array_walk($arr, 'emptyArrToString');
		return $arr;
	}
	
	/**
	 * 生成DOMElement树
	 * array(
	 *		'tagName'=>'Shanghai',			// 标签名称
	 *		'nodeValue'=>'',				// 标签值，可选
	 *		'attributeArr'=>array(),		// 属性值， 可选
	 *		'subElementArr'=>array(			// 子节点，跟父类数据结构一样，可选
	 *			array(
	 *				'tagName'=>'Hongkou',
	 *				'nodeValue'=>'Hongkou nodeValue',
	 *				'attributeArr'=>array('lng'=>'100', 'lat'=>'300'),
	 *				'subElementArr'=>array(),
	 *			),
	 *			array(
	 *				'tagName'=>'Xuhui',
	 *				'nodeValue'=>'Xuhui nodeValue',
	 *				'attributeArr'=>array('lng'=>'200', 'lat'=>'400'),
	 *				'subElementArr'=>array(),
	 *			),
	 *		),
	 *	);
	 * @param DOMDocument $DOMDocument 用于生成中间环节的DOMElement，DOMText，DOMAttr等，直接new DOMElement会出现不可写的报错
	 * @param Array $arr
	 */
	public static function DOMElement(DOMDocument $DOMDocument, $arr) {
		$DOMElement = $DOMDocument->createElement($arr['tagName']);
		$DOMDocument->appendChild($DOMElement);
		if(!empty($arr['nodeValue'])) {			// 如果存在节点文本，则添加该文本
			$textNode = $DOMDocument->createTextNode($arr['nodeValue']);
			$DOMElement->appendChild($textNode);
		}
		if(!empty($arr['attributeArr'])) {		// 如果存在属性，则循环为当前标签添加属性
			foreach($arr['attributeArr'] as $k=>$v) {
				$attribute = $DOMDocument->createAttribute($k);
				$attribute->appendChild($DOMDocument->createTextNode($v));;
				$DOMElement->appendChild($attribute);
			}
		}
		if(!empty($arr['subElementArr'])) {		// 如果存在子节点，则循环追加子节点
			foreach($arr['subElementArr'] as $subElement) {
				$DOMElement->appendChild(self::DOMElement($DOMDocument, $subElement));
			}
		}
		return $DOMElement;
	}
	
	/**
	 * 生成xml
	 * @param Array $arr 格式参加@link DOMElement
	 */
	public static function DOMDocument(Array $arr) {
		$DOMDocument = new DOMDocument('1.0', 'utf-8');
		$DOMDocument->appendChild(self::DOMElement($DOMDocument, $arr));
		return $DOMDocument;
	}
	
	/**
	 * 生成xml字符串
	 * @param Array $arr 格式参加@link DOMElement
	 */
	public static function saveXML(Array $arr) {
		return self::DOMDocument($arr)->saveXML();
	}
	/**
	 * 	作用：array转xml
	 */
	public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.="</xml>";
        return $xml; 
    }
	
}
 
?>