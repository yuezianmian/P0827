<?php
/**
 * 提现
 *
.*/

defined('InShopNC') or exit('Access Invalid!');
class extract_cashControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function cash_listOp(){
		$member_id = $this->member_info['member_id'];
		$page_size = $_POST['page_size'] ? $_POST['page_size'] : 10;
		$page_index = $_POST['page_index'] ? $_POST['page_index'] : 1;
		$cash_state = $_POST['cash_state'];
		$start = $page_size * ($page_index - 1);
		$model_extract_cash = Model('extract_cash');
		$condition	= array();
		$limit =  $start.','.$page_size;
		$condition['member_id'] = $member_id;
		if($cash_state){
			$condition['cash_state'] = $cash_state;
		}
		$extract_cash_list = $model_extract_cash->getExtractCashList($condition,'*','', $limit);
		//获取总数
		$condition	= array();
		$condition['to_member_id'] = $member_id;
		if($cash_state){
			$condition['cash_state'] = $cash_state;
		}
		$extract_cash_amount = $model_extract_cash->counExtractCash($condition);
		$total_page = ($extract_cash_amount%$page_size==0)?intval($extract_cash_amount/$page_size):(intval($extract_cash_amount/$page_size)+1);
		$return_data = array();
		$return_data['extract_cash_list'] = $extract_cash_list;
		$return_data['total_page'] = $total_page;
		$return_data['amount'] = $extract_cash_amount;
		echoJson(SUCCESS, '获取会员的提现列表成功', $return_data, $this->token);
	}

	public function apply_cashOp(){
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
		$result = $points_model->savePointsLog('extract_cash',array('pl_memberid'=>$member_id,'pl_membermobile'=>$this->member_info['member_mobile'],'pl_points'=>-$cash_points),true);
		if(!$result){
			echoJson(FAILED, '提现申请失败');
		}
		$extract_cash_model = Model('extract_cash');
		$insert_arr	= array();
		$insert_arr['member_id'] = $member_id;
		$insert_arr['member_mobile'] = $this->member_info['member_mobile'];
		$insert_arr['cash_points'] = $cash_points;
		$insert_arr['cash_state'] = 1;
		$insert_arr['create_time'] = time();
		$cash_id = $extract_cash_model->addExtractCash($insert_arr);
		if($cash_id){
			echoJson(SUCCESS, '提现申请成功', array('cash_id'=>$cash_id), $this->token);
		}else{
			echoJson(FAILED, '提现申请失败');
		}
	}



}
