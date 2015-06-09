<?php
/**
 * ArrayUtil.php
 * 数组工具类，用以补充一些PHP暂时还没提供的功能函数
 */

class ArrayUtil {
	
	/**
	 * 计算数组最大维度
	 */
	public static function depth($arr) {
	    $depthArr = array(0); 
	    function recursionDepth($arr, &$depthArr, $depth=0){ 
	        if(is_array($arr)) { 
	            array_push($depthArr, ++$depth);
	            foreach($arr as $v){ 
	                recursionDepth($v,$depthArr, $depth);
	            } 
	        }
	    }
	    recursionDepth($arr, $depthArr);
	    return max($depthArr);
	} 
	
	/**
	 * 对数组的每个成员递归调用函数 
	 */
	public static function mapRecursive($func, $arr) {
    	$resArr = array();
	    foreach ($arr as $k => $v) {
	    	//注意：此处不能直接func($v)，低版本的不支持 Menu::filter() 类静态访问，因此用call_user_func
	        $resArr[$k] = is_array($v) ? self::mapRecursive($func, $v) : call_user_func($func, $v);
	    }
	    return $resArr;
	}
	
	/**
	 * 判断$subset是不是$set的子集
	 */
	public static function isSubset(Array $subset, Array $set) {
		return array_diff($subset, $set) ? false : true;
	}
	
	/**
	 * 判断$subset是不是$set的非空子集
	 */
	public static function isNonEmptySubset(Array $subset, Array $set) {
		if(!$subset)
			return false;
		return array_diff($subset, $set) ? false : true;
	}
	
	/**
	 * 相同二组键值组成数组，下面是多文件上传时的数据格式
	 * array (
     * 		'name' => 
     *				array (
     *					0 => 'tangyan2.jpg',
     *					1 => 'tangyan3.jpg',
  	 *				),
  	 *		'type' => 
  	 *				array (
     *					0 => 'image/jpeg',
     *					1 => 'image/jpeg',
  	 *				),
  	 *		'tmp_name' => 
  	 *				array (
     *					0 => 'D:\\wamp\\tmp\\phpF2F1.tmp',
     *					1 => 'D:\\wamp\\tmp\\phpF2F2.tmp',
  	 *				),
  	 *		'error' => 
  	 *				array (
     *					0 => 0,
     *					1 => 0,
  	 *				),
  	 *		'size' => 
  	 *				array (
     *					0 => 22134,
     *					1 => 21843,
  	 *				),
	 *	)
	 */
	public static function separator($multipleFile) {
		$arr = array();
		foreach($multipleFile as $k=>$v) {
			foreach($v as $h=>$i) {
				$arr[$h][$k] = $i;
			}
		}
		return $arr;
	}
	
}
 
 
?>