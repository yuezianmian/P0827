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
		if(empty($this->member_info['member_truename'])){
			echoJson(14, '请先完善您的姓名信息');
		}
		if(empty($this->member_info['address_area_id']) || empty($this->member_info['address_area_name']) || empty($this->member_info['address_detail']) || empty($this->member_info['address_postcode'])){
			echoJson(15, '请先完善您的收货地址信息');
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

	public function points_order_listOp(){
		$page_size = $_POST['page_size'] ? $_POST['page_size'] : 10;
		$page_index = $_POST['page_index'] ? $_POST['page_index'] : 1;
		$start = $page_size * ($page_index - 1);
		$points_order_model = Model('points_order');
		$limit =  $start.','.$page_size;
		$condition	= array();
		$condition['point_buyerid'] = $this->member_info['member_id']; //上架状态
		$points_order_list = $points_order_model->getPointsOrderList($condition,'point_orderid,point_ordersn,pg_id,pg_name,pg_number,point_allpoint,point_addtime,point_finishedtime,point_orderstate,point_orderdesc','',$limit);
		$points_order_amount = $points_order_model->countPointsOrder($condition);
		$total_page = ($points_order_amount%$page_size==0)?intval($points_order_amount/$page_size):(intval($points_order_amount/$page_size)+1);
		$return_data = array();
		$return_data['points_order_list'] = $points_order_list;
		$return_data['total_page'] = $total_page;
		$return_data['amount'] = $points_order_amount;
		echoJson(SUCCESS, '获取用户订单成功', $return_data, $this->token);
	}



}
