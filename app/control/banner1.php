<?php
/**
 * banner
.*/

defined('InShopNC') or exit('Access Invalid!');
class banner1Control extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function banner1_infoOp(){
		$model_banner1 = Model('banner1');
		$condition	= array();
		$condition['banner1_id'] =1;
		$banner1_info = $model_banner1->getBannerInfo($condition);
		echoJson(SUCCESS, '获取banner1信息成功', $banner1_info, $this->token);
	}



}
