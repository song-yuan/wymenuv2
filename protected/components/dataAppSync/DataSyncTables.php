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
    public $baseTableName= array(
    	array("name"=>"收银pos设置","table"=>"nb_pad_setting"),
        array("name"=>"同步失败信息","table"=>"nb_sync_failure"),
        array("name"=>"店铺信息","table"=>"nb_local_company"),
    	array("name"=>"登录界面配置","table"=>"nb_company_setting"),
        array("name"=>"用户","table"=>"nb_user"),
        array("name"=>"楼层区域","table"=>"nb_floor"),
        array("name"=>"点单PAD","table"=>"nb_pad"),
        array("name"=>"支付方法","table"=>"nb_payment_method"),
        array("name"=>"打印机","table"=>"nb_printer"),
        array("name"=>"打印方式","table"=>"nb_printer_way"),
        array("name"=>"打印方式明细","table"=>"nb_printer_way_detail"),
        array("name"=>"产品","table"=>"nb_product"),
        array("name"=>"产品缓存图片","table"=>"nb_product_icache"),
        array("name"=>"产品分类","table"=>"nb_product_category"),
        array("name"=>"产品图片","table"=>"nb_product_picture"),//只有点单pad需要
        array("name"=>"产品打印方式","table"=>"nb_product_printerway"),
        array("name"=>"套餐","table"=>"nb_product_set"),
        array("name"=>"套餐明细","table"=>"nb_product_set_detail"),
    	array("name"=>"产品分组","table"=>"nb_product_group"),
    	array("name"=>"产品分组详情","table"=>"nb_product_group_detail"),
    	array("name"=>"套餐对应分组","table"=>"nb_product_set_group"),
        array("name"=>"产品口味","table"=>"nb_product_taste"),
        array("name"=>"退菜理由","table"=>"nb_retreat"),
        array("name"=>"座位","table"=>"nb_site"),
        array("name"=>"座位人数分类","table"=>"nb_site_persons"),
        array("name"=>"座位类型","table"=>"nb_site_type"),
        array("name"=>"外卖渠道表","table"=>"nb_channel"),
        array("name"=>"送餐员","table"=>"nb_takeaway_member"),
        array("name"=>"口味","table"=>"nb_taste"),
        array("name"=>"口味分组","table"=>"nb_taste_group"), 
    	array("name"=>"产品标签","table"=>"nb_product_label"),
    	array("name"=>"产品标签详情","table"=>"nb_product_label_detail"),
    	array("name"=>"指令表","table"=>"nb_instruction"),
    	array("name"=>"指令详情表","table"=>"nb_instruction_detail"),
    	array("name"=>"产品指令表","table"=>"nb_product_instruction"),
    	array("name"=>"副屏表","table"=>"nb_double_screen"),
    	array("name"=>"副屏详情表","table"=>"nb_double_screen_detail"),
    	array("name"=>"产品BOM表","table"=>"nb_product_bom"),
    	array("name"=>"品项信息表","table"=>"nb_product_material"),
    	array("name"=>"品项库存表","table"=>"nb_product_material_stock"),
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
    public $otherTableName=array(
        array("name"=>"会员等级","table"=>"nb_brand_user_level"),
        array("name"=>"本店会员","table"=>"nb_member_card"),
    	array("name"=>"本店会员积分","table"=>"nb_member_points"),
        array("name"=>"本店会员充值","table"=>"nb_member_recharge"),
        array("name"=>"折扣表","table"=>"nb_discount"),
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
    	array("name"=>"订单优惠","table"=>"nb_order_product_promotion"),
        array("name"=>"排队人数","table"=>"nb_queue_persons"),
        array("name"=>"交班明细","table"=>"nb_shift_detail"),
        array("name"=>"台操作明细","table"=>"nb_site_no"),  
        array("name"=>"普通活动","table"=>"nb_normal_promotion"),
    	array("name"=>"普通活动详情","table"=>"nb_normal_promotion_detail"),
        array("name"=>"满送满减活动","table"=>"nb_full_sent"),
    	array("name"=>"满送满减活动详情","table"=>"nb_full_sent_detail"),
    	array("name"=>"满送满减活动","table"=>"nb_buysent_promotion"),
    	array("name"=>"满送满减活动详情","table"=>"nb_buysent_promotion_detail"),
    );

    
    
    /**
     * 获取所有的基础数据表列表
     */
    public function getBaseTableList()
    {
        return $this->baseTableName;        
    }
    
    /**
     * 获取所有的数据表列表，初始化的时候用
     */
    public function getAllTableList()
    {
         return array_merge($this->baseTableName,$this->otherTableName);       
    }
    /**
     * 获取所有的数据表列表，初始化的时候用
     */
    public function getAllTableName()
    {
    	$allTables = array_merge($this->baseTableName,$this->otherTableName);
    	$allTable = array();
    	foreach ($allTables as $table){
    		array_push($allTable,$table['table']);
    	}
    	return $allTable;
    }
    /**
     * 根据表名获取表结构
     * sync_type 表示同步类型
     * 0 表示打印 1 同步云端 2 会员支付减余额
     * 
     * print_type 0 蓝牙 1 usb
     * 
     */
    public function getTableStructure($tablename)
    {
        $tableStructureAll=array(
        	"nb_pad_setting"=>"CREATE TABLE IF NOT EXISTS nb_pad_setting (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"jobid int(10) NOT NULL DEFAULT '0',".
	        	"pad_code varchar(50) NOT NULL,".
        		"pay_activate varchar(2) NOT NULL DEFAULT '0',".
				"pad_sales_type varchar(2) NOT NULL DEFAULT '0',".
        		"screen_type varchar(2) NOT NULL DEFAULT '0',".
        		"item_count varchar(2) NOT NULL DEFAULT '4',".
				"pad_type varchar(2) NOT NULL,".
				"pad_ip varchar(20) NOT NULL,".
				"pad_fip varchar(20) NOT NULL DEFAULT '0',".
        		"print_type varchar(2) NOT NULL DEFAULT '0',".
				"bt_mac varchar(20) NOT NULL,".
        		"order_period int(3) NOT NULL DEFAULT '3',".
        		"serial_number TINYINT(2) NOT NULL DEFAULT '0',".
				"is_product_free varchar(1) NOT NULL  DEFAULT '0',".
        		"order_number TINYINT(2) NOT NULL  DEFAULT '1',".
        		"payment_mode varchar(1) NOT NULL  DEFAULT '0',".
				"sync_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
				"delete_flag varchar(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
				"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_sync_failure"=>"CREATE TABLE IF NOT EXISTS nb_sync_failure (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"jobid int(10) NOT NULL DEFAULT '0',".
        		"sync_type varchar(2) NOT NULL DEFAULT '0',".
                "sync_url varchar(255) NOT NULL DEFAULT '0',".
				"content text NOT NULL,".
				"delete_flag varchar(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
            "nb_local_company"=>"CREATE TABLE IF NOT EXISTS nb_local_company (".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"token varchar(50) NOT NULL,".
        		"type varchar(2) NOT NULL DEFAULT '0',".
        		"comp_dpid int(10) NOT NULL,".
        		"company_name varchar(50) NOT NULL,".
        		"logo varchar(255) NOT NULL,".
        		"contact_name varchar(20) NOT NULL,".
        		"mobile varchar(20) NOT NULL,".
        		"telephone varchar(20) NOT NULL,".
        		"email varchar(50) NOT NULL,".
        		"address varchar(200) NOT NULL,".
        		"lng varchar(10) NOT NULL,".
        		"lat varchar(10) NOT NULL,".
        		"distance int(10) NOT NULL DEFAULT '5',".
        		"homepage varchar(255) NOT NULL,".
        		"country varchar(255) NOT NULL,".
        		"province varchar(255) NOT NULL,".
        		"city varchar(255) NOT NULL,".
        		"county_area varchar(255) NOT NULL,".
        		"domain varchar(255) NOT NULL,".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"description text NOT NULL DEFAULT '',".
        		"queuememo text NOT NULL DEFAULT '',".
        		"printer_id int(10) NOT NULL,".
                "is_membercard_recharge varchar(2) NOT NULL DEFAULT '0',".
        		"membercard_code varchar(16) NOT NULL,".
        		"membercard_enable_date int(3) NOT NULL DEFAULT '1',".
        		"membercard_points_type varchar(2) NOT NULL DEFAULT '0',".
        		"is2_othersystem char(1) NOT NULL DEFAULT '0',".
				"is2_base_fkxx char(1) NOT NULL DEFAULT '0',".
				"is2_base_tjlb char(1) NOT NULL DEFAULT '0',".
				"is2_base_yhhd char(1) NOT NULL DEFAULT '0',".
				"is2_cloud char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (dpid)".
        		")",
        	"nb_company_setting"=>"CREATE TABLE IF NOT EXISTS nb_company_setting (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"show_name varchar(25) NOT NULL DEFAULT '0',".
        		"logo varchar(255) NOT NULL DEFAULT '0',".
        		"slogan varchar(16) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
            "nb_user"=>"CREATE TABLE IF NOT EXISTS nb_user (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"mobile varchar(20) NOT NULL,".
				"username varchar(50) NOT NULL,".
				"password_hash varchar(60) NOT NULL,".
				"password_reset_token varchar(255) NOT NULL,".
				"staff_no varchar(20) NOT NULL,".
				"email varchar(100) NOT NULL,".
				"auth_key varchar(255) NOT NULL,".
				"role int(10) NOT NULL DEFAULT '0',".
				"status int(10) NOT NULL DEFAULT '0',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_floor"=>"CREATE TABLE IF NOT EXISTS nb_floor (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"name varchar(50) NOT NULL,".
				"manager varchar(20) NOT NULL,".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_pad"=>"CREATE TABLE IF NOT EXISTS nb_pad (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"name varchar(100) NOT NULL ,".
				"printer_id int(10) NOT NULL DEFAULT '0',".
				"server_address varchar(70) NOT NULL DEFAULT '0',".
				"pad_type char(1) NOT NULL DEFAULT '0',".
				"is_bind varchar(1) NOT NULL DEFAULT '0',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_payment_method"=>"CREATE TABLE IF NOT EXISTS nb_payment_method (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"name varchar(50) NOT NULL,".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_printer"=>"CREATE TABLE IF NOT EXISTS nb_printer (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"phs_code varchar(25) NOT NULL DEFAULT '0',".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"name varchar(64) NOT NULL DEFAULT '',".
        		"address varchar(64) NOT NULL DEFAULT '',".
        		"language char(2) NOT NULL DEFAULT '1',".
        		"brand varchar(50) NOT NULL DEFAULT '',".
        		"remark varchar(50) NOT NULL DEFAULT '',".
        		"printer_type varchar(2) NOT NULL DEFAULT '0',".
        		"width_type varchar(2) NOT NULL DEFAULT '1',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_printer_way"=>"CREATE TABLE IF NOT EXISTS nb_printer_way (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"phs_code varchar(25) NOT NULL DEFAULT '0',".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"name varchar(50) NOT NULL,".
				"is_onepaper char(1) NOT NULL DEFAULT '1',".
				"list_no tinyint NOT NULL DEFAULT '1',".
				"memo varchar(100) NOT NULL DEFAULT '',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_printer_way_detail"=>"CREATE TABLE IF NOT EXISTS nb_printer_way_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"print_way_id int(10) NOT NULL DEFAULT '0',".
				"floor_id int(10) NOT NULL DEFAULT '0',".
				"printer_id int(10) NOT NULL DEFAULT '0',".
				"list_no tinyint NOT NULL DEFAULT '1',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",	
            "nb_product" => "CREATE TABLE IF NOT EXISTS nb_product (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "category_id int(10) NOT NULL DEFAULT '0',".
                "phs_code varchar(12) NOT NULL,".
                "chs_code varchar(12) NOT NULL,".
                "product_name varchar(50) NOT NULL,".
                "simple_code varchar(25) NOT NULL DEFAULT '',".
                "main_picture varchar(255) NOT NULL DEFAULT '',".
                "description text NOT NULL DEFAULT '',".
                "rank tinyint NOT NULL DEFAULT '3',".
        		"sort int(4) NOT NULL DEFAULT '50',".
                "spicy tinyint NOT NULL DEFAULT '0',".
                "is_temp_price char(1) NOT NULL DEFAULT '0',".
                "is_member_discount char(1) NOT NULL DEFAULT '0',".
                "is_special char(1) NOT NULL DEFAULT '0',".
                "is_discount char(1) NOT NULL DEFAULT '0',".
                "status char(1) NOT NULL DEFAULT '0',".
                "dabao_fee decimal(10,2) NOT NULL DEFAULT '0.00',".
                "original_price decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"member_price decimal(10,2) NOT NULL DEFAULT '0.00',".
                "product_unit varchar(10) NOT NULL DEFAULT '',".
                "weight_unit varchar(10) NOT NULL DEFAULT '',".
                "is_weight_confirm char(1) NOT NULL DEFAULT '0',".
                "store_number int(10) NOT NULL DEFAULT '-1',".
                "order_number int(10) NOT NULL DEFAULT '0',".
                "favourite_number int(10) NOT NULL DEFAULT '0',".
                "printer_way_id int(10) NOT NULL DEFAULT '0',".
                "is_show char(1) NOT NULL DEFAULT '1',".
        		"is_show_wx varchar(2) NOT NULL DEFAULT '1',".
        		"is_lock varchar(1) NOT NULL DEFAULT '0',".
                "delete_flag char(1) NOT NULL DEFAULT '0',".
                "is_sync varchar(50) NOT NULL DEFAULT '11111',".
                "PRIMARY KEY (lid,dpid)".
                ")",
             "nb_product_icache" => "CREATE TABLE IF NOT EXISTS nb_product_icache (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
				"product_id int(10) NOT NULL DEFAULT '0',".
                "icache_picture varchar(255) NOT NULL DEFAULT '',".
                "is_set varchar(2) NOT NULL DEFAULT '0',".
                "delete_flag char(1) NOT NULL DEFAULT '0',".
                "is_sync varchar(50) NOT NULL DEFAULT '11111',".
                "PRIMARY KEY (lid,dpid)".
                ")",
        	"nb_product_category"=>"CREATE TABLE IF NOT EXISTS nb_product_category (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"pid int(10) NOT NULL DEFAULT '0',".
				"tree varchar(50) NOT NULL,".
				"category_name varchar(50) NOT NULL,".
				"chs_code varchar(12) NOT NULL,".
				"type varchar(3) NOT NULL DEFAULT '0',".
        		"cate_type tinyint NOT NULL DEFAULT '0',".
        		"show_type varchar(2) NOT NULL DEFAULT '1',".
				"main_picture varchar(255) NOT NULL DEFAULT '',".
				"order_num int(4) NOT NULL DEFAULT '0',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_picture"=>"CREATE TABLE IF NOT EXISTS nb_product_picture (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"product_id int(10) NOT NULL DEFAULT '0',".
				"is_set char(1) NOT NULL DEFAULT '0',".
				"pic_path varchar(255) NOT NULL DEFAULT '',".
				"pic_show_order tinyint NOT NULL DEFAULT '1',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_printerway"=>"CREATE TABLE IF NOT EXISTS nb_product_printerway (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"printer_way_id int(10) NOT NULL DEFAULT '0',".
        		"product_id int(10) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_set"=>"CREATE TABLE IF NOT EXISTS nb_product_set (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"category_id int(10) NOT NULL,".
        		"pshs_code varchar(25) NOT NULL DEFAULT '',".
        		"chs_code varchar(12) NOT NULL DEFAULT '',".
        		"set_name varchar(50) NOT NULL,".
        		"source varchar(2) NOT NULL DEFAULT '0',".
				"simple_code varchar(25) NOT NULL,".
				"type varchar(2) NOT NULL DEFAULT '0',".
				"main_picture varchar(255) NOT NULL,".
        		"set_price decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"member_price decimal(10,2) NOT NULL DEFAULT '0.00',".
				"description text NOT NULL DEFAULT '',".
				"rank tinyint(3) NOT NULL DEFAULT '3',".
        		"sort int(4) NOT NULL DEFAULT '50',".
				"is_member_discount char(1) NOT NULL DEFAULT '0',".
				"is_special char(1) NOT NULL DEFAULT '0',".
				"is_discount char(1) NOT NULL DEFAULT '0',".
				"status char(1) NOT NULL DEFAULT '0',".
				"store_number int(10) NOT NULL DEFAULT '-1',".
				"order_number int(10) NOT NULL DEFAULT '0',".
				"favourite_number int(10) NOT NULL DEFAULT '0',".
        		"is_show varchar(2) NOT NULL DEFAULT '1',".
        		"is_show_wx varchar(2) NOT NULL DEFAULT '1',".
        		"is_lock varchar(1) NOT NULL DEFAULT '0',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_set_detail"=>"CREATE TABLE IF NOT EXISTS nb_product_set_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"set_id int(10) NOT NULL DEFAULT '0',".
				"product_id int(10) NOT NULL DEFAULT '0',".
				"price decimal(10,2) NOT NULL DEFAULT '0.00',".
				"group_no tinyint NOT NULL DEFAULT '0',".
				"number tinyint NOT NULL DEFAULT '1',".
				"is_select char(1) NOT NULL DEFAULT '1',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_group"=>"CREATE TABLE IF NOT EXISTS nb_product_group (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"name varchar(50) NOT NULL,".
        		"pg_code varchar(12) NOT NULL DEFAULT '0',".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag varchar(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_group_detail"=>"CREATE TABLE IF NOT EXISTS nb_product_group_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"prod_group_id int(10) NOT NULL DEFAULT '0',".
        		"pg_code varchar(12) NOT NULL DEFAULT '',".
        		"product_id int(10) NOT NULL DEFAULT '0',".
        		"phs_code varchar(12) NOT NULL DEFAULT '',".
        		"price decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"number tinyint NOT NULL DEFAULT '1',".
        		"is_select char(1) NOT NULL DEFAULT '1',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_set_group"=>"CREATE TABLE IF NOT EXISTS nb_product_set_group (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"group_no tinyint NOT NULL DEFAULT '0',".
        		"set_id int(10) NOT NULL DEFAULT '0',".
        		"prod_group_id int(10) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_taste"=>"CREATE TABLE IF NOT EXISTS nb_product_taste (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"taste_group_id int(10) NOT NULL DEFAULT '0',".
				"product_id int(10) NOT NULL DEFAULT '0',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_retreat"=>"CREATE TABLE IF NOT EXISTS nb_retreat (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"type varchar(2) NOT NULL DEFAULT '1',".
        		"sole_code varchar(15) NOT NULL DEFAULT '',".
        		"name varchar(50) NOT NULL,".
				"tip varchar(50) NOT NULL DEFAULT '',".
        		"source varchar(2) NOT NULL DEFAULT '0',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_site"=>"CREATE TABLE IF NOT EXISTS nb_site (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"serial varchar(50) NOT NULL,".
				"type_id int(10) NOT NULL DEFAULT '0',".
				"splid int(10) NOT NULL DEFAULT '0',".
				"site_level varchar(20) DEFAULT NULL,".
				"site_channel_lid int(10) NOT NULL DEFAULT '0',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"has_minimum_consumption char(1) NOT NULL DEFAULT '0',".
				"minimum_consumption_type char(1) NOT NULL DEFAULT '0',".
				"minimum_consumption decimal(10,2) NOT NULL DEFAULT '0.00',".
				"number tinyint(3) NOT NULL DEFAULT '0',".
				"period float NOT NULL DEFAULT '0',".
				"overtime float NOT NULL DEFAULT '0',".
				"buffer float NOT NULL DEFAULT '0',".
				"overtime_fee decimal(10,2) NOT NULL DEFAULT '0.00',".
				"floor_id int(10) NOT NULL DEFAULT '0',".
				"status char(1) NOT NULL DEFAULT '0',".
				"qrcode varchar(255) DEFAULT NULL DEFAULT '',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_site_persons"=>"CREATE TABLE IF NOT EXISTS nb_site_persons (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"min_persons tinyint(3) NOT NULL DEFAULT '1',".
        		"max_persons tinyint(3) NOT NULL DEFAULT '4',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_site_type"=>"CREATE TABLE IF NOT EXISTS nb_site_type (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"name varchar(45) NOT NULL,".
				"simplecode varchar(3) NOT NULL DEFAULT 'A',".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_channel"=>"CREATE TABLE IF NOT EXISTS nb_channel (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"channel_type varchar(2) NOT NULL DEFAULT '0',".
        		"channel_name varchar(50) NOT NULL,".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_takeaway_member"=>"CREATE TABLE IF NOT EXISTS nb_takeaway_member (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"type varchar(2) NOT NULL DEFAULT '0',".
        		"member_name varchar(255) NOT NULL DEFAULT '',".
        		"phone_number varchar(11) NOT NULL DEFAULT '',".
        		"cardId varchar(25) NOT NULL DEFAULT '',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_taste"=>"CREATE TABLE IF NOT EXISTS nb_taste (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"taste_group_id int(10) NOT NULL DEFAULT '0',".
        		"name varchar(50) NOT NULL,".
        		"allflae char(1) NOT NULL DEFAULT '0',".
        		"price decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"is_selected varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_taste_group"=>"CREATE TABLE IF NOT EXISTS nb_taste_group (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"name varchar(50) NOT NULL,".
        		"tghs_code varchar(12) NOT NULL DEFAULT '',".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"allflae char(1) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_label"=>"CREATE TABLE IF NOT EXISTS nb_product_label (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"product_id int(10) NOT NULL,".
        		"is_print_date varchar(2) NOT NULL DEFAULT '1',".
        		"is_print_bar varchar(2) NOT NULL DEFAULT '1',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_label_detail"=>"CREATE TABLE IF NOT EXISTS nb_product_label_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"product_label_id int(10) NOT NULL,".
        		"font_size varchar(2) NOT NULL DEFAULT '1',".
        		"content varchar(255) NOT NULL DEFAULT '1',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_brand_user_level"=>"CREATE TABLE IF NOT EXISTS nb_brand_user_level (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"level_name varchar(50) NOT NULL,".
        		"level_type varchar(1) NOT NULL DEFAULT '0',".
        		"level_discount varchar(8) NOT NULL DEFAULT '1',".
        		"birthday_discount varchar(8) NOT NULL DEFAULT '1',".
        		"min_charge_money int(10) NOT NULL DEFAULT '0',".
        		"min_total_points int(10) NOT NULL DEFAULT '0',".
        		"max_total_points int(10) NOT NULL DEFAULT '0',".
        		"'card_cost' int(10) NOT NULL DEFAULT '0',".
        		"'enable_date' TIMESTAMP NOT NULL DEFAULT (datetime('now', 'localtime')),".
        		"'style_id' int(10) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",	
        	"nb_member_card"=>"CREATE TABLE IF NOT EXISTS nb_member_card (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"selfcode varchar(10) DEFAULT NULL ,".
				"rfid varchar(10) DEFAULT NULL UNIQUE,".
				"level_id int(10) NOT NULL DEFAULT '0',".
				"name varchar(20) NOT NULL DEFAULT '',".
				"mobile varchar(20) DEFAULT NULL ,".
				"email varchar(100) NOT NULL DEFAULT '',".
				"haspassword varchar(1) NOT NULL DEFAULT '0',".
				"password_hash varchar(60) NOT NULL DEFAULT '',".
        		"birthday varchar(16) NOT NULL DEFAULT '01.01',".
				"sex varchar(1) NOT NULL DEFAULT 'm' ,".
				"ages varchar(20) NOT NULL DEFAULT '18-25' ,".
				"all_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"all_points int(10) NOT NULL DEFAULT '0',".
				"card_status varchar(1) NOT NULL DEFAULT '0' ,".
        		"enable_date TIMESTAMP NOT NULL DEFAULT (datetime('now', 'localtime')),".
				"delete_flag char(1) NOT NULL DEFAULT '0',".
				"is_sync varchar(50) NOT NULL DEFAULT '11111' ,".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_member_points"=>"CREATE TABLE IF NOT EXISTS nb_member_points (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"card_type tinyint(2) NOT NULL DEFAULT '0',".
        		"card_id varchar(10) NOT NULL DEFAULT '0',".
        		"point_resource tinyint(2) NOT NULL DEFAULT '0',".
        		"resource_id int(10) NOT NULL DEFAULT '0',".
        		"points int(10) NOT NULL DEFAULT '0',".
        		"remain_points	int(10) NOT NULL DEFAULT '0',".
        		"end_time TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_member_recharge"=>"CREATE TABLE IF NOT EXISTS nb_member_recharge (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"admin_id int(10)  DEFAULT 'NULL',".
        		"member_card_id int(10) NOT NULL DEFAULT '0',".
        		"type tinyint NOT NULL DEFAULT '0',".
        		"reality_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"give_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
            "nb_discount"=>"CREATE TABLE IF NOT EXISTS nb_discount (".
        		"lid int(10) NOT NULL,".
                "dpid int(10) NOT NULL,".
                "create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "discount_name varchar(50) NOT NULL,".
                "discount_abstract varchar(255) NOT NULL DEFAULT '',".
                "discount_num decimal(5,2) NOT NULL DEFAULT '0.00',".
                "discount_type varchar(2) NOT NULL DEFAULT '0',".
                "is_available varchar(2) NOT NULL DEFAULT '0',".
                "delete_flag varchar(1) NOT NULL DEFAULT '0',".
                "is_sync varchar(50) NOT NULL DEFAULT '11111',".
                "PRIMARY KEY (lid,dpid)".
                ")",
        	"nb_close_account"=>"CREATE TABLE IF NOT EXISTS nb_close_account (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"user_id int(10) NOT NULL DEFAULT '0',".
        		"begin_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"end_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"close_day date NOT NULL DEFAULT '0000-00-00',".
        		"all_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_close_account_detail"=>"CREATE TABLE IF NOT EXISTS nb_close_account_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"close_account_id int(10) NOT NULL DEFAULT '',".
        		"paytype varchar(1) NOT NULL DEFAULT '0',".
        		"payment_method_id int(10) NOT NULL DEFAULT '0',".
        		"all_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_order"=>"CREATE TABLE IF NOT EXISTS nb_order (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"user_id int(10) DEFAULT NULL DEFAULT '0',".
        		"account_no varchar(20) NOT NULL DEFAULT '0',".
        		"classes int(10) NOT NULL DEFAULT '0',".
        		"username varchar(50) NOT NULL ,".
        		"site_id int(10) NOT NULL DEFAULT '0',".
        		"is_temp varchar(1) NOT NULL DEFAULT '0' ,".
        		"number tinyint NOT NULL DEFAULT '0',".
        		"order_status varchar(1) NOT NULL DEFAULT '1',".
        		"order_type tinyint NOT NULL DEFAULT '0',".
        		"takeout_typeid int(10) NOT NULL DEFAULT '0',".
        		"takeout_status varchar(1) NOT NULL DEFAULT '0',".
        		"appointment_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"lock_status varchar(1) NOT NULL DEFAULT '0',".
        		"should_total decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"reality_total decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"callno varchar(10) NOT NULL DEFAULT '0',".
        		"paytype varchar(1) NOT NULL DEFAULT '0',".
        		"payment_method_id int(10) NOT NULL DEFAULT '0',".
        		"pay_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"remark text NOT NULL DEFAULT '全款支付',".
        		"taste_memo varchar(50) NOT NULL DEFAULT '0',".
        		"cupon_branduser_lid int(10) NOT NULL DEFAULT '0',".
        		"cupon_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_order_account_discount"=>"CREATE TABLE IF NOT EXISTS nb_order_account_discount (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"order_id int(10) NOT NULL DEFAULT '0',".
        		"account_no varchar(20) NOT NULL ,".
        		"discount_title varchar(255) NOT NULL DEFAULT '0',".
        		"discount_type varchar(2) NOT NULL ,".
        		"discount_id int(10) NOT NULL DEFAULT '0',".
        		"discount_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"delete_flag varchar(2) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_order_address"=>"CREATE TABLE IF NOT EXISTS nb_order_address (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"order_lid int(11) NOT NULL ,".
        		"consignee varchar(30) DEFAULT NULL ,".
        		"province varchar(30) DEFAULT NULL,".
        		"city varchar(30) DEFAULT NULL ,".
        		"area varchar(30) DEFAULT NULL ,".
        		"street varchar(30) DEFAULT NULL ,".
        		"postcode varchar(30) DEFAULT NULL ,".
        		"mobile varchar(30) DEFAULT NULL ,".
        		"tel varchar(30) DEFAULT NULL ,".
        		"delete_flag tinyint NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) DEFAULT '11111' ,".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_order_feedback"=>"CREATE TABLE IF NOT EXISTS nb_order_feedback (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"site_id int(10) NOT NULL DEFAULT '0',".
        		"is_temp char(1) NOT NULL DEFAULT '0',".
        		"is_deal char(1) NOT NULL DEFAULT '0',".
        		"feedback_id int(10) NOT NULL DEFAULT '0',".
        		"order_id int(10) NOT NULL DEFAULT '0',".
        		"is_order char(1) NOT NULL DEFAULT '0',".
        		"feedback_memo varchar(50) NOT NULL,".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_order_pay"=>" CREATE TABLE IF NOT EXISTS nb_order_pay (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"order_id int(10) NOT NULL DEFAULT '0',".
        		"account_no varchar(20) NOT NULL,".
        		"pay_amount decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"paytype varchar(2) NOT NULL DEFAULT '0',".
        		"payment_method_id int(10) NOT NULL DEFAULT '0',".
        		"paytype_id int(10) NOT NULL DEFAULT '0',".
        		"remark varchar(50) NOT NULL DEFAULT '全款支付',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_order_product"=>"CREATE TABLE IF NOT EXISTS nb_order_product (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"order_id int(10) NOT NULL DEFAULT '0',".
        		"set_id int(10) NOT NULL DEFAULT '0',".
        		"private_promotion_lid int(10) NOT NULL DEFAULT '0',".
        		"main_id int(10) NOT NULL DEFAULT '0',".
        		"product_id int(10) NOT NULL DEFAULT '0',".
        		"product_name varchar(255) NOT NULL,".
        		"product_pic varchar(255) NOT NULL,".
        		"product_type varchar(2) NOT NULL DEFAULT '0',".
        		"is_retreat char(1) NOT NULL DEFAULT '0',".
        		"original_price decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"price decimal(10,4) NOT NULL DEFAULT '0.00',".
        		"offprice varchar(10) NOT NULL DEFAULT '100%',".
        		"amount float NOT NULL DEFAULT '1',".
        		"zhiamount float NOT NULL DEFAULT '0',".
        		"is_waiting char(1) NOT NULL DEFAULT '0',".
        		"weight decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"taste_memo text NOT NULL,".
        		"is_giving char(1) NOT NULL DEFAULT '0',".
        		"is_print varchar(1) NOT NULL DEFAULT '0',".
        		"product_status varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"product_order_status char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_order_retreat"=>"CREATE TABLE IF NOT EXISTS nb_order_retreat (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"retreat_id int(10) NOT NULL DEFAULT '0',".
        		"order_detail_id int(10) NOT NULL DEFAULT '0',".
        		"retreat_memo varchar(50) NOT NULL ,".
        		"username varchar(50) NOT NULL ,".
        		"retreat_amount int(10) NOT NULL DEFAULT '1',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_order_taste"=>"CREATE TABLE IF NOT EXISTS nb_order_taste (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"taste_id int(10) NOT NULL DEFAULT '0',".
        		"order_id int(10) NOT NULL DEFAULT '0',".
        		"is_order char(1) NOT NULL DEFAULT '0',".
        		"taste_name varchar(255) NOT NULL DEFAULT '',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_order_product_promotion"=>"CREATE TABLE IF NOT EXISTS nb_order_product_promotion (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"order_id int(10) NOT NULL DEFAULT '0',".
        		"order_product_id int(10) NOT NULL DEFAULT '0',".
        		"account_no varchar(20) NOT NULL,".
        		"promotion_title varchar(255) NOT NULL DEFAULT '',".
        		"promotion_type varchar(2) NOT NULL DEFAULT '0',".
        		"promotion_id int(10) NOT NULL DEFAULT '0',".
        		"promotion_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"can_cupon varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_queue_persons"=>"CREATE TABLE IF NOT EXISTS nb_queue_persons (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"stlid int(10) NOT NULL DEFAULT '0',".
        		"splid int(10) NOT NULL DEFAULT '0',".
        		"queue_no varchar(20) NOT NULL DEFAULT 'A001',".
        		"mobile_no varchar(30) NOT NULL ,".
        		"weixin_openid varchar(50) NOT NULL ,".
        		"status char(1) NOT NULL DEFAULT '0',".
        		"slid int(10) NOT NULL DEFAULT '0' ,".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_shift_detail"=>"CREATE TABLE IF NOT EXISTS nb_shift_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"userid int(10) NOT NULL DEFAULT '0',".
        		"begin_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"end_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
         		"order_num decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"order_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"member_charge decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"member_consume decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"cash_total decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"union_total decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"weixin_total decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"zhifubao_total decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"other_total decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
            "nb_site_no"=>"CREATE TABLE IF NOT EXISTS nb_site_no (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
                "update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"site_id int(10) NOT NULL DEFAULT '0',".
        		"is_temp char(1) NOT NULL DEFAULT '0',".
        		"status char(1) NOT NULL DEFAULT '0',".
        		"account_no varchar(255) NOT NULL ,".
        		"t_account_no varchar(255) NOT NULL ,".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"waiter_id int(10) NOT NULL DEFAULT '0',".
        		"number tinyint(3) NOT NULL DEFAULT '0',".
        		"code varchar(10) NOT NULL,".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
          	"nb_normal_promotion" => "CREATE TABLE IF NOT EXISTS nb_normal_promotion (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"normal_code varchar(12) NOT NULL DEFAULT '0',".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"promotion_title varchar(50) NOT NULL,".
        		"main_picture varchar(255) NOT NULL,".
        		"promotion_abstract varchar(255) NOT NULL,".
        		"promotion_memo text NOT NULL,".
        		"promotion_type varchar(2) NOT NULL,".
        		"can_cupon varchar(2) NOT NULL DEFAULT '0',".
        		"begin_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"end_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"weekday varchar(32) NOT NULL DEFAULT '',".
        		"day_begin varchar(8) NOT NULL DEFAULT '00:00',".
        		"day_end varchar(8) NOT NULL DEFAULT '00:00',".
        		"to_group varchar(2) NOT NULL DEFAULT '0',".
        		"group_id int(10) NOT NULL DEFAULT '0',".
        		"order_num int(4) NOT NULL DEFAULT '0',".
        		"is_available varchar(16) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_normal_promotion_detail" => "CREATE TABLE IF NOT EXISTS nb_normal_promotion_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"normal_promotion_id int(10) NOT NULL DEFAULT '0',".
        		"normal_code_pa varchar(12) NOT NULL DEFAULT '0',".
        		"product_id int(10) NOT NULL DEFAULT '0',".
        		"pro_code varchar(12) NOT NULL DEFAULT '0',".
        		"is_set varchar(2) NOT NULL,".
        		"is_discount varchar(2) NOT NULL,".
        		"promotion_money decimal(10,2) NOT NULL,".
        		"promotion_discount decimal(10,2) NOT NULL,".
        		"order_num int(4) NOT NULL DEFAULT '0',".
        		"is_show varchar(2) NOT NULL DEFAULT '2',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
          	"nb_full_sent" => "CREATE TABLE IF NOT EXISTS nb_full_sent (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"sole_code varchar(20) NOT NULL DEFAULT '0',".
        		"title varchar(64) NOT NULL,".
        		"infor varchar(255) NOT NULL,".
        		"begin_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"end_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"full_type varchar(2) NOT NULL DEFAULT '0',".
        	    "full_cost decimal(10,2) NOT NULL DEFAULT '0.00',".
                "extra_cost decimal(10,2) NOT NULL DEFAULT '0.00',".
                "sent_number int(3) NOT NULL DEFAULT '1',".
        		"is_available varchar(16) NOT NULL DEFAULT '0',".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_full_sent_detail" => "CREATE TABLE IF NOT EXISTS nb_full_sent_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"full_sent_id int(10) NOT NULL DEFAULT '0',".
        		"product_id int(10) NOT NULL DEFAULT '0',".
        		"phs_code varchar(15) NOT NULL DEFAULT '0',".
        		"is_discount varchar(2) NOT NULL DEFAULT '0',".
        		"promotion_money decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"promotion_discount decimal(10,2) NOT NULL DEFAULT '1.00',".
        		"number int(3) NOT NULL DEFAULT '1',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_buysent_promotion" => "CREATE TABLE IF NOT EXISTS nb_buysent_promotion (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"sole_code varchar(20) NOT NULL,". 
        		"promotion_title varchar(50) NOT NULL,".
        		"main_picture varchar(255) NOT NULL,".
        		"promotion_abstract varchar(255) NOT NULL,".
        		"promotion_memo text DEFAULT NULL,".
        		"promotion_type varchar(2) NOT NULL DEFAULT '0',".
        		"can_cupon varchar(2) NOT NULL DEFAULT '0',".
        		"begin_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"end_time timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',".
        		"weekday varchar(32) NOT NULL DEFAULT '0',".
        		"day_begin varchar(8) NOT NULL DEFAULT '00:00',".
        		"day_end varchar(8) NOT NULL DEFAULT '00:00',".
        		"to_group varchar(2) NOT NULL DEFAULT '0',".
        		"group_id int(10) NOT NULL DEFAULT '0',".
        		"order_num tinyint(4) NOT NULL DEFAULT '0',".
        		"is_available varchar(16) NOT NULL DEFAULT '0',".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag varchar(2) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_buysent_promotion_detail" => "CREATE TABLE IF NOT EXISTS nb_buysent_promotion_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"sole_code varchar(20) NOT NULL,".
        		"buysent_pro_id int(10) NOT NULL,".
        		"fa_sole_code varchar(20) NOT NULL,".
        		"is_set varchar(2) NOT NULL DEFAULT '0',".
        		"product_id int(10) NOT NULL DEFAULT '0',".
        		"phs_code varchar(15) NOT NULL DEFAULT '0',".
        		"s_product_id int(10) NOT NULL DEFAULT '0',".
        		"s_phs_code varchar(15) NOT NULL DEFAULT '0',".
        		"buy_num int(3) NOT NULL DEFAULT '0',".
        		"sent_num int(3) NOT NULL DEFAULT '0',".
        		"limit_num int(3) NOT NULL DEFAULT '0',".
        		"group_no varchar(15) NOT NULL DEFAULT '0',".
        		"is_available varchar(2) NOT NULL DEFAULT '1',".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag varchar(2) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_instruction" => "CREATE TABLE IF NOT EXISTS nb_instruction (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"phs_code varchar(16) NOT NULL,".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"instruct_name varchar(16) NOT NULL,".
        		"sort varchar(3) NOT NULL DEFAULT '050',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_instruction_detail" => "CREATE TABLE IF NOT EXISTS nb_instruction_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"instruction_id int(10) NOT NULL,".
        		"number varchar(4) NOT NULL DEFAULT '00',".
        		"instruct_name varchar(16) NOT NULL,".
        		"time varchar(2) NOT NULL DEFAULT '0',".
        		"instruct varchar(64) NOT NULL,".
        		"sort varchar(3) NOT NULL DEFAULT '050',".
        		"is_waiting varchar(2) NOT NULL DEFAULT '0',".
        		"is_enquire varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_instruction" => "CREATE TABLE IF NOT EXISTS nb_product_instruction (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"instruction_id int(10) NOT NULL,".
        		"product_id varchar(32) NOT NULL,".
        		"is_taste varchar(2) NOT NULL,".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_double_screen" => "CREATE TABLE IF NOT EXISTS nb_double_screen (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"phs_code varchar(25) NOT NULL,".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"title varchar(32) NOT NULL,".
        		"type varchar(2) NOT NULL DEFAULT '0',".
        		"description varchar(32) DEFAULT NULL,".
        		"is_able varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_double_screen_detail" => "CREATE TABLE IF NOT EXISTS nb_double_screen_detail (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"double_screen_id int(10) NOT NULL,".
        		"type varchar(2) NOT NULL DEFAULT '0',".
        		"url varchar(255) DEFAULT NULL,".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_material_category"=>"CREATE TABLE IF NOT EXISTS nb_material_category (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"pid int(10) NOT NULL DEFAULT '0',".
        		"tree varchar(50) NOT NULL,".
        		"category_name varchar(50) NOT NULL,".
        		"mchs_code varchar(12) NOT NULL,".
        		"main_picture varchar(255) NOT NULL DEFAULT '',".
        		"order_num int(4) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_bom" => "CREATE TABLE IF NOT EXISTS nb_product_bom (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"product_id int(10) NOT NULL,".
        		"taste_id int(10) NOT NULL DEFAULT '0',".
        		"material_id int(10) NOT NULL DEFAULT '0',".
        		"number decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"sales_unit_id int(10) NOT NULL,".
        		"mphs_code varchar(12) DEFAULT NULL,".
        		"phs_code varchar(12) DEFAULT NULL,".
        		"mushs_code varchar(12) DEFAULT NULL,".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_material" => "CREATE TABLE IF NOT EXISTS nb_product_material (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"category_id int(10) NOT NULL,".
        		"material_name varchar(255) DEFAULT NULL,".
        		"material_identifier varchar(255) DEFAULT NULL,".
        		"material_private_identifier varchar(255) DEFAULT NULL,".
        		"stock_unit_id int(10) NOT NULL DEFAULT '0',".
        		"sales_unit_id int(10) NOT NULL DEFAULT '0',".
        		"mchs_code varchar(12) DEFAULT NULL,".
        		"mphs_code varchar(12) DEFAULT NULL,".
        		"mulhs_code varchar(12) DEFAULT NULL,".
        		"mushs_code varchar(12) DEFAULT NULL,".
        		"source varchar(2) NOT NULL DEFAULT '0',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        	"nb_product_material_stock" => "CREATE TABLE IF NOT EXISTS nb_product_material_stock (".
        		"lid int(10) NOT NULL,".
        		"dpid int(10) NOT NULL,".
        		"create_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"update_at TIMESTAMP NOT NULL default (datetime('now', 'localtime')),".
        		"material_id int(10) NOT NULL,".
        		"mphs_code varchar(12) DEFAULT NULL,".
        		"stock_day int(4) NOT NULL DEFAULT '0',".
        		"batch_stock decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"stock decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"free_stock decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"stock_cost decimal(10,2) NOT NULL DEFAULT '0.00',".
        		"delete_flag char(1) NOT NULL DEFAULT '0',".
        		"is_sync varchar(50) NOT NULL DEFAULT '11111',".
        		"PRIMARY KEY (lid,dpid)".
        		")",
        );
        
        return $tableStructureAll[$tablename];
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
        $insertSample= "INSERT INTO nb_product (lid, dpid, create_at, update_at, category_id, product_name,"
            . " simple_code, main_picture, description, rank, spicy, is_temp_price, is_member_discount,"
            . " is_special, is_discount, status, original_price, product_unit, weight_unit,"
            . " is_weight_confirm, store_number, order_number, favourite_number, printer_way_id, is_show,"
            . " delete_flag, is_sync) "
            . "select 0000000001, 0000000007, '2015-02-05 22:00:00', '2015-06-29 08:17:09', 0000000002, '红烧肉',"
            . " 'HSR', '/wymenuv2/uploads/test/01.jpg', '傲娇是凉快', 3, 0, '0', '0', '0', '0', '0', '2123.00', '份', '份',"
            . " '0', 4, 11234, 10433, 0000000001, '1', '1', '11111' union all select 0000000001, 0000000008, '2015-02-05 22:00:00',"
            . " '2015-06-29 08:17:09', 0000000002, '红烧肉', 'HSR', '/wymenuv2/uploads/test/01.jpg', '傲娇是凉快', 3, 0, '0', '0',"
            . " '0', '0', '0', '2123.00', '份', '份', '0', 4, 11234, 10433, 0000000001, '1', '1', '11111'";
    }
}