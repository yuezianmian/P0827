<?php
/**
 * 登录
 *
 * 包括 登录 验证 退出 操作
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');
class productControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function productcenter_urlOp(){
		$model_config = Model('config');
		$product_center_info = $model_config->getConfigInfo(array('type'=>'product_center_url'));
		if (empty($product_center_info)){
			echoJson(FAILED, "获取产品中心地址失败", array(), $this->token);
		}else{
			echoJson(SUCCESS, "获取产品中心地址成功", array('product_center_url'=>$product_center_info['value']), $this->token);
		}
	}



}
