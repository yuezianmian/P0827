<?php
/**
 * 登录
 *
 * 包括 登录 验证 退出 操作
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');
class memberControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function submit_shopOp(){
		$member_id = $_POST['member_id'];
		$update_info = array(
			'shop_name'=> $_POST['shop_name'],
			'shop_img'=> $_POST['shop_img'],
			'area_name'=> $_POST['area_name'],
			'area_id'=> $_POST['area_id'],
			'shop_address'=> $_POST['address'],
			'member_state'=> 1
		);
		$model_member = Model('member');
		$return = $model_member->editMember(array('member_id'=>$member_id),$update_info);
		if($return){
			echoJson(SUCCESS, "提交成功", array(), $this->token);
		}else{
			echoJson(FAILED, "提交失败", array(), $this->token);
		}
	}



}
