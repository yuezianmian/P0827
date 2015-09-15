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

	public function shoplist_byagentOp(){
		$member_code = $this->member_info['member_code'];
		$member_state = $_POST["member_state"];
		if(empty($member_code)){
			echoJson(FAILED, "当前会员不是代理商", '');
		}
		$model_member = Model('member');
		$condition = array();
		$condition['parent_code'] = $member_code;
		if(!is_null($member_state) && $member_state != ''){
			$condition['member_state'] = $member_state;
		}
		$member_array = $model_member->getMemberList($condition);
		echoJson(SUCCESS, '获取当前代理的下属店面列表成功', $member_array, $this->token);
	}

	/**
	 * 会员审核通过
	 */
	public function checkOp(){
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
			array("input"=>$_POST["type"],		"require"=>"true", "message"=>"审核类型不能为空"),
			array("input"=>$_POST["member_id"],		"require"=>"true", "message"=>"审核会员id不能为空"),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
			echoJson(FAILED, $error);
		}
		$model_member = Model('member');
		$check_member_info = $model_member->getMemberInfo(array('member_id'=>$_POST["member_id"]));
		if($check_member_info['parent_code'] != $this->member_info['member_code']){ //被审核的会员非当前会员的下属会员
			echoJson(FAILED, '被审核会员不属于当前代理商');
		}
		if($check_member_info['member_state'] == 0){
			echoJson(FAILED, '该会员还未提交店面信息，无法审核');
		}
		if($check_member_info['member_state'] == 2 || $check_member_info['member_state'] == 3){
			echoJson(FAILED, '该会员已审核，不能重复审核');
		}
		if($_POST["type"] == 'pass'){
			$result = $model_member->editMember(array('member_id'=>intval($_POST['member_id'])),array('member_state'=>2));
			if ($result){
				echoJson(SUCCESS, '审核成功', array(), $this->token);
			}else{
				echoJson(FAILED, '审核失败');
			}
		}else if($_POST["type"] == 'nopass'){
			$result = $model_member->editMember(array('member_id'=>intval($_POST['member_id'])),array('member_state'=>3));
			if ($result){
				echoJson(SUCCESS, '审核成功', array(), $this->token);
			}else{
				echoJson(FAILED, '审核失败');
			}
		}
	}



}
