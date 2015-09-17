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

	public function point_logOp(){
		$member_id = $this->member_info['member_id'];
		$page_size = $_POST['page_size'] ? $_POST['page_size'] : 10;
		$page_index = $_POST['page_index'] ? $_POST['page_index'] : 1;
		$start = $page_size * ($page_index - 1);
		$model_points = Model('points');
		$condition	= array();
		$condition['limit'] = $start.','.$page_size;
		$condition['pl_memberid'] = $member_id;
		$points_log_list = $model_points->getPointsLogList($condition);
		//获取总数
		$condition	= array();
		$condition['pl_memberid'] = $member_id;
		$points_log_amount = $model_points->getPointsLogList($condition,'','count(1) as amount');
		$amount = $points_log_amount[0]['amount'];
		$total_page = ($amount%$page_size==0)?intval($amount/$page_size):(intval($amount/$page_size)+1);
		$return_data = array();
		$return_data['member_points'] = $this->member_info['member_points'];
		$return_data['total_points'] = $this->member_info['total_points'];
		$return_data['points_log_list'] = $points_log_list;
		$return_data['total_page'] = $total_page;
		$return_data['amount'] = $amount;
		echoJson(SUCCESS, '获取货源的积分记录列表成功', $return_data, $this->token);
	}

}
