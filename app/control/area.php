<?php
/**
 * 登录
 *
 * 包括 登录 验证 退出 操作
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');
class areaControl extends Control {
	public function __construct(){
		parent::__construct();
	}

	public function all_areaOp(){
		$model_area = Model('area');
		$area_arr = $model_area->getAreaArrayForJson();
		echoJson(SUCCESS, "获取所有区域信息成功", $area_arr);
	}


}
