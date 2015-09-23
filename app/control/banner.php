<?php
/**
 * banner
.*/

defined('InShopNC') or exit('Access Invalid!');
class bannerControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function banner_listOp(){
		$model_banner = Model('banner');
		$condition	= array();
		$banner_list = $model_banner->getBannerList($condition, '*', null);
		echoJson(SUCCESS, '获取banner列表成功', $banner_list, $this->token);
	}



}
