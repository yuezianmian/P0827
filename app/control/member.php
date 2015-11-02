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
		$member_id = $this->member_info['member_id'];
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
	
	public function shoplist_withOrderNum_byagentOp(){
		$member_code = $this->member_info['member_code'];
		$member_state = $_POST["member_state"];
		if(empty($member_code)){
			echoJson(FAILED, "当前会员不是代理商", '');
		}
		$model_member = Model('member');
		$condition = array();
		$parent_code = $member_code;
		$member_array = $model_member->getShopListWithOrderAmount($parent_code, date('Ym', time()));
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
				if($check_member_info['recomm_member_id']){//如果当前会员存在推荐会员，则需要赠送积分
					$points_model = Model('points');
					$recommend_user_points = C("points.recommend_user"); //推荐人获得积分值
					$recommended_user_points = C("points.recommended_user");//被推荐人获得积分值
					$points_model->savePointsLog('recommed_regist',array('pl_memberid'=>$_POST["member_id"],'pl_membermobile'=>$check_member_info['member_mobile'],'pl_points'=>$recommended_user_points),true);
					$recomm_member_info = $model_member->getMemberInfo(array('member_id'=>$check_member_info['recomm_member_id']));
					$points_model->savePointsLog('recomm_regist',array('pl_memberid'=>$recomm_member_info["member_id"],'pl_membermobile'=>$recomm_member_info['member_mobile'],'pl_points'=>$recommend_user_points),true);
				}
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

	public function point_log_listOp(){
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

	/**
	 * 会员积分排行榜
	 */
	public function member_point_rankingOp(){
		$page_size = $_POST['page_size'] ? $_POST['page_size'] : 5;
		$page_index = $_POST['page_index'] ? $_POST['page_index'] : 1;
		$start = $page_size * ($page_index - 1);
		$model_member = Model('member');
		$limit = $start.','.$page_size;
		$condition	= array();
		$condition['member_state'] = array('neq',MEMBER_STATE_NOPASS);
		$member_list = $model_member->getMemberList($condition,"member_avatar,member_mobile,total_points",0,"total_points desc",$limit);
		//获取总数
		$member_amount = $model_member->getMemberCount($condition);
		$total_page = ($member_amount%$page_size==0)?intval($member_amount/$page_size):(intval($member_amount/$page_size)+1);
		$return_data = array();
		$return_data['member_list'] = $member_list;
		$return_data['total_page'] = $total_page;
		$return_data['amount'] = $member_amount;
		echoJson(SUCCESS, '获取会员积分排行成功', $return_data, $this->token);
	}

	/**
	 * 编辑会员信息
	 */
	public function editOp(){
		$update_array = array();
		if($_POST['member_truename']){
			$update_array['member_truename'] = $_POST['member_truename'];
		}
		if($_POST['member_mobile_true']){
			$update_array['member_mobile_true'] = $_POST['member_mobile_true'];
		}
		if($_POST['member_avatar']){
			$update_array['member_avatar'] = $_POST['member_avatar'];
		}
		if($_POST['member_sex']){
			$update_array['member_sex'] = $_POST['member_sex'];
		}
		if($_POST['member_birthday']){
			$update_array['member_birthday'] = $_POST['member_birthday'];
		}
		if($_POST['alipay_number']){
			$update_array['alipay_number'] = $_POST['alipay_number'];
		}
		if($_POST['bank_number'] && $_POST['bank_username'] && $_POST['bank_name'] && $_POST['bank_branch']){
			$update_array['bank_number'] = $_POST['bank_number'];
			$update_array['bank_username'] = $_POST['bank_username'];
			$update_array['bank_name'] = $_POST['bank_name'];
			$update_array['bank_branch'] = $_POST['bank_branch'];
		}
		if($_POST['address_area_id'] && $_POST['address_area_name'] && $_POST['address_detail'] && $_POST['address_postcode']){
			$update_array['address_area_id'] = $_POST['address_area_id'];
			$update_array['address_area_name'] = $_POST['address_area_name'];
			$update_array['address_detail'] = $_POST['address_detail'];
			$update_array['address_postcode'] = $_POST['address_postcode'];
		}
		if($_POST['shop_name']){
			$update_array['shop_name'] = $_POST['shop_name'];
		}
		if($_POST['shop_img']){
			$update_array['shop_img'] = $_POST['shop_img'];
		}
		if($_POST['area_name']){
			$update_array['area_name'] = $_POST['area_name'];
		}
		if($_POST['shop_address']){
			$update_array['shop_address'] = $_POST['shop_address'];
		}
		if(count($update_array) > 0){
			$model_member = Model('member');
			$result = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),$update_array);
			if($result){
				echoJson(SUCCESS, '更新会员信息成功', array(), $this->token);
			}else{
				echoJson(FAILED, '更新会员信息失败');
			}
		}
		echoJson(FAILED, '更新会员信息失败', array(), $this->token);
	}

	public function getMemberInfoOp(){
		echoJson(SUCCESS, '获取app首页相关会员信息成功', $this->member_info, $this->token);
	}

	/**
	 * app首页用到的一些会员信息
	 */
	public function homepage_infoOp(){
		$return = array();
		$member_id = $this->member_info['member_id'];
		$model_member = Model('member');
		$model_qrcode_record = Model('qrcode_record');
		$condition	= array();
		$condition['create_time'] = array('egt',strtotime(date('Y-m-01 00:00:00')));
		$qrcodeRecord_month_count = $model_qrcode_record->countQrcodeRecord($condition);
		$model_points = Model('points');
//		$condition	= array();
//		$condition['pl_addtime'] = array('egt',strtotime(date('Y-m-01 00:00:00')));
//		$condition['pl_points'] = array('gt',0);
		$condition_str	= " and `points_log`.pl_memberid = ".$member_id;
		$condition_str	= " and `points_log`.pl_addtime >= ".strtotime(date('Y-m-01 00:00:00'));
		$condition_str	.= " and `points_log`.pl_points > 0 ";
		$month_points_sum = $model_points->countPoints($condition_str);
		$return = array();
		$return['month_points_sum'] = $month_points_sum;
		$return['qrcodeRecord_month_count'] = $qrcodeRecord_month_count;
		$return['member_id'] = $member_id;
		$return['member_mobile_true'] = $this->member_info['member_mobile_true'];;
		$return['total_points'] = $this->member_info['total_points'];
		$return['member_points'] = $this->member_info['member_points'];
		$return['member_avatar'] = $this->member_info['member_avatar'];
		$return['shop_name'] = $this->member_info['shop_name'];
		$return['member_state'] = $this->member_info['member_state'];
		$return['member_type'] = $this->member_info['member_type'];
		echoJson(SUCCESS, '获取app首页相关会员信息成功', $return, $this->token);
	}

	/**
	 * 获取积分规则配置信息
	 */
	public function getPointsConfigOp(){
		$return = array();
		$return['sign_points'] =  C("points.sign");
		$return['recommend_member_points'] =  C("points.recommend_user");
		$return['recommended_member_points'] =  C("points.recommended_user");
		echoJson(SUCCESS, '获取积分规则配置信息成功', $return, $this->token);
	}

}
