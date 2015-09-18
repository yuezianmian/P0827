<?php
/**
 * 签到
 *
.*/

defined('InShopNC') or exit('Access Invalid!');
class signControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function sign_listOp(){
		$member_id = $this->member_info['member_id'];
		$month = $_GET['month'];
		if(empty($month)){
			echoJson(FAILED, "查询签到记录的月份参数不合法");
		}
		$model_sign = Model('sign');
		$condition	= array();
		$condition["month"]	= $month;
		$condition['member_id'] = $member_id;
		$sign_list = $model_sign->getSignList($condition, '*', null);
		echoJson(SUCCESS, '获取会员的签到列表成功', $sign_list, $this->token);
	}

	public function add_signOp(){
		$member_id = $this->member_info['member_id'];
		$model_sign = Model('sign');
		$condition	= array();
		$condition["day"]	= date('Y-m-d', time());
		$condition['member_id'] = $member_id;
		$sign_info = $model_sign->getSignInfo($condition);
		if(!empty($sign_info)){
			echoJson(10, '今日已签到，一天只能签到一次');
		}
		$insert_array = array();
		$insert_array['member_id']	= $member_id;
		$insert_array['member_mobile']	= $this->member_info['member_mobile'];
		$insert_array['sign_time']	= time();
		$sign_id = $model_sign->addSign($insert_array);
		if($sign_id){
			$sign_points = C("points.sign");
			//添加积分记录
			$points_model = Model('points');
			$points_model->savePointsLog('sign',array('pl_memberid'=>$member_id,'pl_membermobile'=>$this->member_info['member_mobile'],'pl_points'=>$sign_points),true);
			echoJson(SUCCESS, '签到成功，获得'.$sign_points.'积分', array('sign_points'=>$sign_points), $this->token);
		}else{
			echoJson(FAILED	, '签到失败');
		}

	}



}
