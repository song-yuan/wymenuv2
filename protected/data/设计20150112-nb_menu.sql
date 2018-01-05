-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 10, 2015 at 10:17 PM
-- Server version: 5.5.37-log
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nb_wymenu`
--

-- --------------------------------------------------------

--
-- Table structure for table `nb_company`
--

CREATE TABLE IF NOT EXISTS `nb_company` (
  `dpid` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `token` varchar(50) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `contact_name` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `homepage` varchar(255) NOT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `printer_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `is2_othersystem` char(1) NOT NULL DEFAULT '0' COMMENT '0不和第三方系统对接，所有基础数据自己录入，1和第三方系统对接，基础数据从第三方获取',
  `is2_cloud` char(1) NOT NULL DEFAULT '0' COMMENT '1定期和云端数据同步，0暂时不和云端数据同步',
  PRIMARY KEY (`dpid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='唯一在云端维护的表' AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `nb_local_company` (
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `token` varchar(50) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `contact_name` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `homepage` varchar(255) NOT NULL,
  `delete_flage` char(1) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `printer_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `is2_othersystem` char(1) NOT NULL DEFAULT '0' COMMENT '0不和第三方系统对接，所有基础数据自己录入，1和第三方系统对接，基础数据从第三方获取',
  `is2_cloud` char(1) NOT NULL DEFAULT '0' COMMENT '1定期和云端数据同步，0暂时不和云端数据同步',
  PRIMARY KEY (`dpid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='唯一在云端维护的表' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `nb_company`
--

INSERT INTO `nb_local_company` (`dpid`, `create_at`, `update_at`, `token`, `company_name`, `logo`, `contact_name`, `mobile`, `telephone`, `email`, `address`, `homepage`, `delete_flage`, `description`, `printer_id`, `is2_othersystem`, `is2_cloud`) VALUES
(0000000001, '2015-01-10 13:56:39', '2015-01-10 13:56:39', '1213124323wsdw', '', '', '', '', '', '', '', '', '0', '', 0000000000, '0', '0');

CREATE TABLE IF NOT EXISTS `nb_company_wifi` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `wifi_name` varchar(50) NOT NULL,
  `macid` varchar(50) NOT NULL,
  `max_number` tinyint(3) NOT NULL DEFAULT '0',
  `current_number` tinyint(3) NOT NULL DEFAULT '0',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_user` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `mobile` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(60) NOT NULL,
  `password_reset_token` varchar(255) NOT NULL,
  `staff_no` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `auth_key` varchar(255) NOT NULL,
  `role` int(10) NOT NULL DEFAULT '0',
  `status` int(10) NOT NULL DEFAULT '0',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_floor` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `name` varchar(50) NOT NULL,
  `manager` varchar(20) NOT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='楼层区域表';

CREATE TABLE IF NOT EXISTS `nb_printer` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `name` varchar(50) NOT NULL,
  `ip_address` varchar(64) NOT NULL,
  `com_name` varchar(10) NOT NULL COMMENT '串口',
  `baud_rate` int(10) NOT NULL DEFAULT '0' COMMENT '波特率',
  `brand` varchar(50) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `printer_type` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_printer_way` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `name` varchar(50) NOT NULL,
  `memo` varchar(100) NOT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_printer_way_detail` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `print_way_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印方式id',
  `floor_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '楼层id',
  `printer_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印机id',
  `list_no` tinyint(3) NOT NULL DEFAULT '0',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_site_type` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `name` varchar(45) NOT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_site` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `serial` varchar(50) NOT NULL,
  `type_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印机id',
  `site_level` varchar(20) NOT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  `has_minimum_consumption` char(1) NOT NULL DEFAULT '0',
  `minimum_consumption_type` char(1) NOT NULL DEFAULT '0',
  `minimum_consumption` decimal(10,2) NOT NULL DEFAULT '0.00',
  `number` tinyint(3) NOT NULL DEFAULT '0',
  `period` float NOT NULL DEFAULT '0',
  `overtime` float NOT NULL DEFAULT '0',
  `buffer` float NOT NULL DEFAULT '0',
  `overtime_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `floor_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印机id',
  `status` char(1) NOT NULL DEFAULT '0' COMMENT '状态：0已开台，1未开台，2被并台，3被换台，4被撤台，5被结单',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_site_no` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `site_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印机id',
  `is_temp` char(1) NOT NULL DEFAULT '0' COMMENT 'site_id临时台还是固定台：0固定，1临时',
  `status` char(1) NOT NULL DEFAULT '0' COMMENT '状态：0已开台，1未开台，2被并台，3被换台，4被撤台，5被结单',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  `waiter_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印机id',
  `number` tinyint(3) NOT NULL DEFAULT '0',
  `code` varchar(10) NOT NULL,
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_taste` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `name` varchar(50) NOT NULL,
  `allflae` char(1) NOT NULL DEFAULT '0' COMMENT '1整单口味，0不是',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_retreat` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `name` varchar(50) NOT NULL,
  `tip` varchar(50) NOT NULL COMMENT '如：理由name是有异物，这里tip提示输入物品名称',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_feedback` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `name` varchar(50) NOT NULL,
  `tip` varchar(50) NOT NULL,
  `allflag` char(1) NOT NULL DEFAULT '0' COMMENT '1整单反馈，0不是',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_b_login` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `user_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印机id',
  `out_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_c_login` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `c_user_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印机id',
  `site_no_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印机id',
  `c_mac` varchar(20) NOT NULL,
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product_category` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `pid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印机id',
  `tree` varchar(50) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `category_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '种类id',
  `product_name` varchar(50) NOT NULL,
  `simple_code` varchar(25) NOT NULL,
  `main_picture` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rank` tinyint(3) NOT NULL DEFAULT '3' COMMENT '产品星级，商家自己从1-5评星',
  `is_temp_price` char(1) NOT NULL DEFAULT '0' COMMENT '是否时价',
  `is_member_discount` char(1) NOT NULL DEFAULT '0' COMMENT '是否参与会员折扣',
  `is_special` char(1) NOT NULL DEFAULT '0' COMMENT '是否特价菜',
  `is_discount` char(1) NOT NULL DEFAULT '0' COMMENT '是否参与优惠活动',
  `status` char(1) NOT NULL DEFAULT '0' COMMENT '0正常，1沽清',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `product_unit` varchar(10) NOT NULL COMMENT '默认单位',
  `weight_unit` varchar(10) NOT NULL COMMENT '重量单位',
  `is_weight_confirm` char(1) NOT NULL DEFAULT '0' COMMENT '是否需要确认重量',
  `order_number` int(10) NOT NULL DEFAULT '0' COMMENT '总下单次数',
  `favourite_number` int(10) NOT NULL DEFAULT '0' COMMENT '总点赞次数',
  `printer_way_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '打印方案id',
  `is_show` char(1) NOT NULL DEFAULT '1' COMMENT '是否在正常分类显示，单在活动、套餐等中总显示，1显示，0不显示',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product_taste` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `taste_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `product_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product_set` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `set_name` varchar(50) NOT NULL,
  `simple_code` varchar(25) NOT NULL,
  `main_picture` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rank` tinyint(3) NOT NULL DEFAULT '3' COMMENT '产品星级，商家自己从1-5评星',
  `is_member_discount` char(1) NOT NULL DEFAULT '0' COMMENT '是否参与会员折扣',
  `is_special` char(1) NOT NULL DEFAULT '0' COMMENT '是否特价菜',
  `is_discount` char(1) NOT NULL DEFAULT '0' COMMENT '是否参与优惠活动',
  `status` char(1) NOT NULL DEFAULT '0' COMMENT '0正常，1沽清',
  `order_number` int(10) NOT NULL DEFAULT '0' COMMENT '总下单次数',
  `favourite_number` int(10) NOT NULL DEFAULT '0' COMMENT '总点赞次数',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product_set_detail` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `set_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `product_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '菜品在套餐中的价格，可能是打折过的，总价根据这个价格累加计算而得，但是这个明细价格前台不显示，前台只显示套餐总价，所以可以随便设定，比如把第一个固定菜品价格设定成菜单总价，其他设定成0也可以。当套餐中有可选项且价格不一致时，可选项变化套餐总价也变化',
  `group_no` tinyint(3) NOT NULL DEFAULT '0' COMMENT '一个套餐中有多组产品，如：主食一组、饮料一组，一般一组就一个，也有一组中有多个可供客户选择的。',
  `number` tinyint(3) NOT NULL DEFAULT '0' COMMENT '套餐中默认数量',
  `is_select` char(1) NOT NULL DEFAULT '0' COMMENT '同一组中有多个选择时，那个产品时默认选中的这个字段为1，否则为0',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product_tempprice` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `product_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '时价',
  `order_number` int(10) NOT NULL DEFAULT '0' COMMENT '时价期间的点单率，和菜品总的点单率重复统计',
  `favourite_number` int(10) NOT NULL DEFAULT '0' COMMENT '时价期间的点赞率，和菜品总的点赞率重复统计',
  `begin_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product_special` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `product_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `is_set` char(1) NOT NULL DEFAULT '0' COMMENT '0上面的product_id是单品，1product_id是套餐',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '特价菜的价格',
  `order_number` int(10) NOT NULL DEFAULT '0' COMMENT '特价菜期间的点单数量，不能大于下面的all_count，和菜品总点单数量重复统计',
  `all_count` int(10) NOT NULL DEFAULT '0' COMMENT '特价菜的总数量，点单数不能超过这个数量，0代表不限数量',
  `favourite_number` int(10) NOT NULL DEFAULT '0' COMMENT '特价菜期间的点赞数量',
  `begin_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product_discount` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `product_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `is_set` char(1) NOT NULL DEFAULT '0' COMMENT '0上面的product_id是单品，1product_id是套餐',
  `price_discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠价格或折扣比例，视下面的字段而定',
  `is_discount` char(1) NOT NULL DEFAULT '0' COMMENT '0优惠价，1折扣',
  `order_number` int(10) NOT NULL DEFAULT '0' COMMENT '优惠期间的点单数量，不大于all_count',
  `favourite_number` int(10) NOT NULL DEFAULT '0' COMMENT '优惠期间的点赞数量',
  `all_count` int(10) NOT NULL DEFAULT '0' COMMENT '0代表不限数量',
  `reason` varchar(50) NOT NULL,
  `begin_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product_out` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `product_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `is_set` char(1) NOT NULL DEFAULT '0' COMMENT '0上面的product_id是单品，1product_id是套餐',
  `reason` varchar(50) NOT NULL,
  `pass_flag` char(1) NOT NULL DEFAULT '0' COMMENT '1沽清，0结束沽清',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_product_picture` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `product_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `is_set` char(1) NOT NULL DEFAULT '0' COMMENT '0上面的product_id是单品，1product_id是套餐',
  `pic_path` varchar(255) NOT NULL,
  `pic_show_order` tinyint(3) NOT NULL DEFAULT '1',
  `delete_flag` char(1) NOT NULL DEFAULT '0' COMMENT '1删除，0未删除',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_order` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `site_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '台子的编号，前台session存储site_no_id,根据site_no_id在site_no表中找到site_id和is_temp,然后找到对应订单。换台时被换对应该表中site_id和is_temp要更新；并台时被并的人数要加到并到的台；撤台改变状态就行。',
  `is_temp` char(1) NOT NULL DEFAULT '0' COMMENT '0固定台 1临时台',
  `number` tinyint(3) NOT NULL DEFAULT '0' COMMENT '人数，和开台中的人数保持一致',
  `order_status` char(1) NOT NULL DEFAULT '0' COMMENT '0未结单，1结单，2被并台，3被撤台，4被换台',
  `pay_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reality_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `remark` varchar(50) NOT NULL,
  `taste_memo` varchar(50) NOT NULL,
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_order_product` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `order_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `set_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '0000000000表示下面的product是单品，否则是套餐内的产品',
  `product_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `is_retreat` char(1) NOT NULL DEFAULT '0' COMMENT '0非退菜，1退菜',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '下单时价格',
  `amount` tinyint(3) NOT NULL DEFAULT '0' COMMENT '下单数量',
  `zhiamount` tinyint(3) NOT NULL DEFAULT '0' COMMENT '下单只数',
  `is_waiting` char(1) NOT NULL DEFAULT '0' COMMENT '0不等叫，1等叫，2已上菜',
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `taste_memo` varchar(50) NOT NULL,
  `retreat_memo` varchar(50) NOT NULL,
  `is_giving` char(1) NOT NULL DEFAULT '0' COMMENT '0非赠送，1赠送',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_order_taste` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `taste_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `order_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `is_order` char(1) NOT NULL DEFAULT '0' COMMENT '1是全单口味，order_id就是订单lid，0不是全单，对应订单明细lid',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_order_retreat` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `retreat_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `order_detail_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `retreat_memo` varchar(50) NOT NULL COMMENT '如有异物时，输入：头发',
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_order_feedback` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `feedback_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `order_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `is_order` char(1) NOT NULL DEFAULT '0' COMMENT '1是全单反馈，order_id就是订单lid，0不是全单，对应订单明细lid',
  `feedback_memo` varchar(50) NOT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_payment_method` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `name` varchar(50) NOT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_online_pay` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `order_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `c_user_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `c_mac` varchar(20) NOT NULL,
  `pay_type` char(1) NOT NULL DEFAULT '0' COMMENT '1是支付宝，2微信，3银联',
  `all_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `memo` varchar(50) NOT NULL,
  `status` char(1) NOT NULL DEFAULT '0' COMMENT '1支付成功，0失败',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_close_account` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `user_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `begin_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `close_day` date NOT NULL DEFAULT '0000-00-00' COMMENT '结算日，这段时间的结算属于这个结算日的',
  `all_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_close_account_detail` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `close_account_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `payment_method_id` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `all_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nb_data_sync` (
  `lid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '自身id，统一dpid下递增',
  `dpid` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT '店铺id',
  `create_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `sync_result` char(1) NOT NULL DEFAULT '0' COMMENT '1是成功，0失败',
  `is_interface` char(1) NOT NULL DEFAULT '0' COMMENT '1是和第三方点单系统接口基础数据同步，0和云端所有数据同步',
  PRIMARY KEY (`lid`,`dpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET GLOBAL log_bin_trust_function_creators = 1;

delimiter $$
CREATE FUNCTION `fristPinyin`(P_NAME VARCHAR(255)) RETURNS varchar(255) CHARSET utf8
BEGIN
    DECLARE V_RETURN VARCHAR(255);
    SET V_RETURN = ELT(INTERVAL(CONV(HEX(left(CONVERT(P_NAME USING gbk),1)),16,10), 
        0xB0A1,0xB0C5,0xB2C1,0xB4EE,0xB6EA,0xB7A2,0xB8C1,0xB9FE,0xBBF7, 
        0xBFA6,0xC0AC,0xC2E8,0xC4C3,0xC5B6,0xC5BE,0xC6DA,0xC8BB,
        0xC8F6,0xCBFA,0xCDDA,0xCEF4,0xD1B9,0xD4D1),    
    'A','B','C','D','E','F','G','H','J','K','L','M','N','O','P','Q','R','S','T','W','X','Y','Z');
    RETURN V_RETURN;
END$$
delimiter ;

delimiter $$
CREATE FUNCTION `pinyin`(P_NAME VARCHAR(255)) RETURNS varchar(255) CHARSET utf8
BEGIN
    DECLARE V_COMPARE VARCHAR(255);
    DECLARE V_RETURN VARCHAR(255);
    DECLARE I INT;
    SET I = 1;
    SET V_RETURN = '';
    while I < LENGTH(P_NAME) do
        SET V_COMPARE = SUBSTR(P_NAME, I, 1);
        IF (V_COMPARE != '') THEN
            #SET V_RETURN = CONCAT(V_RETURN, ',', V_COMPARE);
            SET V_RETURN = CONCAT(V_RETURN, fristPinyin(V_COMPARE));
            #SET V_RETURN = fristPinyin(V_COMPARE);
        END IF;
        SET I = I + 1;
    end while;
    IF (ISNULL(V_RETURN) or V_RETURN = '') THEN
        SET V_RETURN = P_NAME;
    END IF;
    RETURN V_RETURN;
END$$
delimiter ;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
