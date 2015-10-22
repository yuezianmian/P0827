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
		$member_points = $this->member_info['member_points'];
		if(empty($_POST['points'])){
			echoJson(10, '兑换商品所需积分不能为空');
		}
		if($_POST['points'] > $member_points){
			echoJson(11, '您的积分不足，无法兑换');
		}
		if(empty($_POST['pg_id']) || empty($_POST['pg_name'])){
			echoJson(12, '兑换的商品id和商品名称不能为空');
		}
		if(empty($_POST['pg_number'])){
			echoJson(13, '兑换商品的数量不能为空');
		}
		$member_id = $this->member_info['member_id'];
		$member_mobile = $this->member_info['member_mobile'];
		$member_mobile_true = $this->member_info['member_mobile_true'];
		$points_order_model = Model('points_order');
		$insert_arr	= array();
		$insert_arr['member_id'] = $member_id;
		$insert_arr['member_mobile'] = $member_mobile;
		$insert_arr['member_mobile_true'] = $member_mobile_true;
		$insert_arr['point_allpoint'] = $_POST['points'];
		$insert_arr['pg_number'] = $_POST['pg_number'];
		$insert_arr['pg_id'] = $_POST['pg_id'];
		$insert_arr['pg_name'] = $_POST['pg_name'];
		$points_order_id = $points_order_model->createPointsOrder($insert_arr);
		if(!$points_order_id){
			echoJson(FAILED, '提交积分兑换订单失败');
		}
		//减积分
		$points_model = Model('points');
		$result = $points_model->savePointsLog('pointorder',array('pl_memberid'=>$member_id,'pl_membermobile'=>$member_mobile,'pl_points'=>-intval($_POST['points'])),true);

		$model_points_good = Model('points_good');
		$update_array = array();
		$update_array['pg_stock'] = array('exp', 'pg_stock-'.$_POST['pg_number']);
		$result = $model_points_good->editPointsGood($update_array,array('pg_id'=>intval($_POST['pg_id'])));

		echoJson(SUCCESS, '提交积分兑换订单成功', array('points_order_id'=>$points_order_id), $this->token);
	}



}
