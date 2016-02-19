<?php
class DataSyncTables
{
    
    //基础数据的表，在APP上每次手动同步，
    //同步的时候先读取表名，
    //再选择表同步，或全选后同步。
    ///////////////////////
    //每次同步时都根据表名、APP版本号获取表结构，
    //然后根据企业id获取数据，下载到本地后删除所有
    //原有的基础数据，然后插入新的。    
    public static $baseTableName= array(
        array("name"=>"店铺信息","table"=>"nb_local_company"),
        array("name"=>"用户","table"=>"nb_user"),
        array("name"=>"楼层区域","table"=>"nb_floor"),
        array("name"=>"点单PAD","table"=>"nb_pad"),
        array("name"=>"支付方法","table"=>"nb_payment_method"),
        array("name"=>"打印机","table"=>"nb_printer"),
        array("name"=>"打印方式","table"=>"nb_printer_way"),
        array("name"=>"打印方式明细","table"=>"nb_printer_way_detail"),
        array("name"=>"产品","table"=>"nb_product"),
        array("name"=>"产品分类","table"=>"nb_product_category"),
        array("name"=>"产品图片","table"=>"nb_product_picture"),//只有点单pad需要
        array("name"=>"产品打印方式","table"=>"nb_product_printerway"),
        array("name"=>"套餐","table"=>"nb_product_set"),
        array("name"=>"套餐明细","table"=>"nb_product_set_detail"),
        array("name"=>"产品口味","table"=>"nb_product_taste"),
        array("name"=>"退菜理由","table"=>"nb_retreat"),
        array("name"=>"座位","table"=>"nb_site"),
        array("name"=>"座位人数分类","table"=>"nb_site_persons"),
        array("name"=>"座位类型","table"=>"nb_site_type"),
        array("name"=>"口味","table"=>"nb_taste"),
        array("name"=>"口味分组","table"=>"nb_taste_group"),        
    );
    
    //实时同步数据包括
    //线上订单、支付、本地订单、支付、本地会员及活动信息
    //因为会涉及到库存、人气等全部以sql语句的形式，按照顺序执行
    //sql语句存在文件还是表里，随便，先尝试表里
    //本店的会员和活动只能在本店添加，
    //本店使用，上传到云端供统计。
    //云端的会员不用下载到本地，在云端和本地都能使用
    //和上面一样，以sql的形式体现，并按照顺序执行
    //这些表的结构都要在本地建立
    public static $otherTableNmae=array(
        array("name"=>"本店会员","table"=>"nb_member_card"),
        array("name"=>"本店会员充值","table"=>"nb_member_recharge"),
        array("name"=>"本店活动","table"=>"nb_local_activity"),//这张表云端暂时没有
        array("name"=>"日结","table"=>"nb_close_account"),
        array("name"=>"日结明细","table"=>"nb_close_account_detail"),
        array("name"=>"订单","table"=>"nb_order"),
        array("name"=>"整单折扣","table"=>"nb_order_account_discount"),
        array("name"=>"订单配送地址","table"=>"nb_order_address"),
        array("name"=>"订单反馈","table"=>"nb_order_feedback"),
        array("name"=>"支付","table"=>"nb_order_pay"),
        array("name"=>"下单明细","table"=>"nb_order_product"),
        array("name"=>"订单退菜","table"=>"nb_order_retreat"),
        array("name"=>"订单口味","table"=>"nb_order_taste"),
        array("name"=>"排队人数","table"=>"nb_queue_persons"),
        array("name"=>"交班明细","table"=>"nb_shift_detail"),
        array("name"=>"台操作明细","table"=>"nb_site_no"),        
    );

    
    
    /**
     * 获取所有的基础数据表列表
     */
    public static function getBaseTableList()
    {
        return json_encode($baseTableName);        
    }
    
    /**
     * 获取所有的数据表列表，初始化的时候用
     */
    public static function getAllTableList()
    {
         return json_encode(array_merge($baseTableName,$otherTableName));       
    }
    
    /**
     * 根据表名获取表结构
     */
    public static function getTableStructure($tablename)
    {
        $tableStructureAll=array(
            "nb_local_company"=>"",
            "nb_user"=>"",
            "nb_product" => " CREATE TABLE 'nb_product'('lid' int(10) NOT NULL,"
                    . " 'dpid' int(10) NOT NULL,"
                    . "'create_at' TIMESTAMP NOT NULL default (datetime('now', 'localtime')) ,".
                    "  'update_at' TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                    "  'category_id' int(10) NOT NULL DEFAULT '0000000000',".
                    "  'product_name' varchar(50) NOT NULL,".
                    "  'simple_code' varchar(25) NOT NULL,".
                    "  'main_picture' varchar(255) NOT NULL,".
                    "  'description' text NOT NULL,".
                    "  'rank' tinyint NOT NULL DEFAULT '3',".
                    "  'spicy' tinyint NOT NULL DEFAULT '0',".
                    "  'is_temp_price' char(1) NOT NULL DEFAULT '0',".
                    "  'is_member_discount' char(1) NOT NULL DEFAULT '0',".
                    "  'is_special' char(1) NOT NULL DEFAULT '0',".
                    "  'is_discount' char(1) NOT NULL DEFAULT '0',".
                    "  'status' char(1) NOT NULL DEFAULT '0',".
                    "  'original_price' decimal(10,2) NOT NULL DEFAULT '0.00',".
                    "  'product_unit' varchar(10) NOT NULL,".
                    "  'weight_unit' varchar(10) NOT NULL,".
                    "  'is_weight_confirm' char(1) NOT NULL DEFAULT '0',".
                    "  'store_number' int(10) NOT NULL DEFAULT '-1',".
                    "  'order_number' int(10) NOT NULL DEFAULT '0',".
                    "  'favourite_number' int(10) NOT NULL DEFAULT '0',".
                    "  'printer_way_id' int(10) NOT NULL DEFAULT '0000000000',".
                    "  'is_show' char(1) NOT NULL DEFAULT '1',".
                    "  'delete_flag' char(1) NOT NULL DEFAULT '0',".
                    " 'is_sync' varchar(50) NOT NULL DEFAULT '11111',".
                    " PRIMARY KEY ('lid','dpid')".
                   ");",
            "nb_site_no"=>"",
        );
        
        return json_encode($tableStructureAll[$tablename]);
    }
    
    /**
     * 获取需要到本地执行的sql，每次仅限100条
     * @param type $dpid 店铺id 数值
     * @param type $tablename 表名
     * @param type $beginRecord 读取数据开始位置，第一次从1，第二次从101
     * @param type $fieldlist 字段列表，防止以后app和云端表结构差异引起的数据同步错误
     * field1-field2-field3...
     */
    public static function getBaseData100($dpid,$tablename,$beginRecord,$fieldlist)
    {
        $insertSample= "INSERT INTO `nb_product` (`lid`, `dpid`, `create_at`, `update_at`, `category_id`, `product_name`,"
            . " `simple_code`, `main_picture`, `description`, `rank`, `spicy`, `is_temp_price`, `is_member_discount`,"
            . " `is_special`, `is_discount`, `status`, `original_price`, `product_unit`, `weight_unit`,"
            . " `is_weight_confirm`, `store_number`, `order_number`, `favourite_number`, `printer_way_id`, `is_show`,"
            . " `delete_flag`, `is_sync`) "
            . "select 0000000001, 0000000007, '2015-02-05 22:00:00', '2015-06-29 08:17:09', 0000000002, '红烧肉',"
            . " 'HSR', '/wymenuv2/uploads/test/01.jpg', '傲娇是凉快', 3, 0, '0', '0', '0', '0', '0', '2123.00', '份', '份',"
            . " '0', 4, 11234, 10433, 0000000001, '1', '1', '11111' union all select 0000000001, 0000000008, '2015-02-05 22:00:00',"
            . " '2015-06-29 08:17:09', 0000000002, '红烧肉', 'HSR', '/wymenuv2/uploads/test/01.jpg', '傲娇是凉快', 3, 0, '0', '0',"
            . " '0', '0', '0', '2123.00', '份', '份', '0', 4, 11234, 10433, 0000000001, '1', '1', '11111';";
    }
}