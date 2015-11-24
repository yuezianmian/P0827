<?php
/**
 * 载入权限
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc.
 */
defined('InShopNC') or exit('Access Invalid!');
$_limit =  array(
	array('name'=>'控制台', 'child'=>array(
		array('name'=>'管理员设置', 'op'=>null, 'act'=>'admin'),
		array('name'=>'会员管理', 'op'=>null, 'act'=>'member'),
		array('name'=>'产品管理', 'op'=>null, 'act'=>'product'),
		array('name'=>'生成二维码', 'op'=>null, 'act'=>'generate_code'),
		array('name'=>'扫描记录', 'op'=>null, 'act'=>'qrcode_record'),
		array('name'=>'积分明细', 'op'=>null, 'act'=>'points'),
		array('name'=>'banner管理', 'op'=>null, 'act'=>'banner'),
		array('name'=>'banner1管理', 'op'=>null, 'act'=>'banner1'),
	    array('name'=>'提现记录', 'op'=>null, 'act'=>'extract_cash'),
	    array('name'=>'反馈记录', 'op'=>null, 'act'=>'feedback'),
	    array('name'=>'系统公告', 'op'=>null, 'act'=>'notice'),
	    array('name'=>'积分商品', 'op'=>null, 'act'=>'points_good'),
	    array('name'=>'积分兑换订单', 'op'=>null, 'act'=>'points_order'),
		)),

);

return $_limit;
