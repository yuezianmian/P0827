<?php
/**
 * 用户反馈
 *
.*/

defined('InShopNC') or exit('Access Invalid!');
class feedbackControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}


	public function add_feedbackOp(){
		$cash_points = $_POST['cash_points'];
		if(empty($cash_points)){
			echoJson(FAILED, 'cash_points不能为空');
		}
		if($cash_points < 200){
			echoJson(10, '提现的积分不能小于200');
		}
		if($cash_points > $this->member_info['member_points']){
			echoJson(11, '提现的积分大于会员可用积分');
		}
		$member_id = $this->member_info['member_id'];
		$points_model = Model('points');
		$result = $points_model->savePointsLog('feedback',array('pl_memberid'=>$member_id,'pl_membermobile'=>$this->member_info['member_mobile'],'pl_points'=>-$cash_points),true);
		if(!$result){
			echoJson(FAILED, '提现申请失败');
		}
		$feedback_model = Model('feedback');
		$insert_arr	= array();
		$insert_arr['member_id'] = $member_id;
		$insert_arr['member_mobile'] = $this->member_info['member_mobile'];
		$insert_arr['cash_points'] = $cash_points;
		$insert_arr['cash_state'] = 1;
		$insert_arr['create_time'] = time();
		$cash_id = $feedback_model->addExtractCash($insert_arr);
		if($cash_id){
			echoJson(SUCCESS, '提现申请成功', array('cash_id'=>$cash_id), $this->token);
		}else{
			echoJson(FAILED, '提现申请失败');
		}
	}



}
