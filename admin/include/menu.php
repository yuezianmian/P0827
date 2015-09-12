<?php
/**
 * 菜单
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');
/**
 * top 数组是顶部菜单 ，left数组是左侧菜单
 * left数组中'args'=>'welcome,dashboard,dashboard',三个分别为op,act,nav，权限依据act来判断
 */
$arr = array(
		'top' => array(
			0 => array(
				'args' 	=> 'dashboard',
				'text' 	=> $lang['nc_console']),
		),
		'left' =>array(
			0 => array(
				'nav' => 'dashboard',
				'text' => $lang['nc_console'],
				'list' => array(
					array('args'=>'admin,admin,dashboard',			       'text'=>$lang['nc_limit_manage']),
					array('args'=>'member,member,dashboard',				'text'=>'会员管理'),
					array('args'=>'product,product,dashboard',				'text'=>'产品中心地址'),
				)
			),
		),
);
return $arr;
