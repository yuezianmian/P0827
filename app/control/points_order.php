<?php
/**
 * 积分兑换订单
 *
.*/

defined('InShopNC') or exit('Access Invalid!');
class points_orderControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}


	public function add_points_orderOp(){
		$points_order_content = $_POST['points_order_content'];
		$points_order_img = $_POST['points_order_img'];
		if(empty($points_order_content)){
			echoJson(FAILED, '反馈内容不能为空');
		}
		if(empty($points_order_img)){
			echoJson(FAILED, '反馈的图片不能为空');
		}
		$member_id = $this->member_info['member_id'];
		$member_mobile = $this->member_info['member_mobile'];
		$points_order_model = Model('points_order');
		$insert_arr	= array();
		$insert_arr['member_id'] = $member_id;
		$insert_arr['member_mobile'] = $member_mobile;
		$insert_arr['points_order_content'] = $points_order_content;
		$insert_arr['points_order_img'] = $points_order_img;
		$insert_arr['create_time'] = time();
		$points_order_id = $points_order_model->addPointsOrder($insert_arr);
		if($points_order_id){
			echoJson(SUCCESS, '提交积分兑换订单成功', array('points_order_id'=>$points_order_id), $this->token);
		}else{
			echoJson(FAILED, '提交积分兑换订单失败');
		}
	}



}
