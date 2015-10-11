<?php
/**
 * lottery
.*/

defined('InShopNC') or exit('Access Invalid!');
class lotteryControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function awards_listOp(){
		$model_lottery = Model('lottery');
		$activity_id = 1;
		$awards_list = $model_lottery->getAwardsList($activity_id);
		echoJson(SUCCESS, '获取奖项列表成功', $awards_list, $this->token);
	}



}
