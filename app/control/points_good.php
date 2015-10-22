<?php
/**
 *
 *
.*/

defined('InShopNC') or exit('Access Invalid!');
class points_goodControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function points_good_listOp(){
		$page_size = $_POST['page_size'] ? $_POST['page_size'] : 10;
		$page_index = $_POST['page_index'] ? $_POST['page_index'] : 1;
		$start = $page_size * ($page_index - 1);
		$model_points_good = Model('points_good');
		$limit =  $start.','.$page_size;
		$condition	= array();
		$condition['pg_state'] = 1; //上架状态
		$points_good_list = $model_points_good->getPointsGoodList($condition,'pg_id,pg_name,pg_points,pg_stock,create_time','',$limit);
		$points_good_amount = $model_points_good->countPointsGoodList($condition);
		$total_page = ($points_good_amount%$page_size==0)?intval($points_good_amount/$page_size):(intval($points_good_amount/$page_size)+1);
		$return_data = array();
		$return_data['points_good_list'] = $points_good_list;
		$return_data['total_page'] = $total_page;
		$return_data['amount'] = $points_good_amount;
		echoJson(SUCCESS, '获取积分商品成功', $return_data, $this->token);
	}

	public function points_good_infoOp(){
		$condition	= array();
		$condition['pg_id'] = $_POST['pg_id'];
		$model_points_good = Model('points_good');
		$points_good_info = $model_points_good->getPointsGoodInfo($condition);
		echoJson(SUCCESS, '获取积分商品详细信息成功', $points_good_info, $this->token);
	}


}
