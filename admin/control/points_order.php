<?php
/**
 * 积分兑换订单管理
 *
 */

defined('InShopNC') or exit('Access Invalid!');

class points_orderControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * points_order
	 */
	public function points_orderOp(){
		$lang	= Language::getLangContent();
		$model_points_order = Model('points_order');
		if ($_GET['search_field_value'] != '') {
			switch ($_GET['search_field_name']){
				case 'point_ordersn':
					$condition['point_ordersn'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				case 'member_mobile_true':
					$condition['point_buyermobiletrue'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
			}
		}
		if($_GET['search_state']){
			$condition['point_orderstate'] = intval($_GET['search_state']);
		}

		$points_order_list = $model_points_order->getPointsOrderList($condition,'*',10);

		Tpl::output('search_state',$_GET['search_state']);
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('points_order_list',$points_order_list);
		Tpl::output('page',$model_points_order->showpage());
		Tpl::showpage('points_order.index');
	}


	/**
	 * 编辑
	 */
	public function points_order_editOp(){
		$lang	= Language::getLangContent();

		$model_points_order = Model('points_order');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["point_orderdesc"], "require"=>"true", "message"=>'商品详细信息不能为空'),
				array("input"=>$_POST["point_orderid"], "require"=>"true", "message"=>'订单id不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}
			$update_array = array();
			$update_array['point_orderdesc'] = $_POST['point_orderdesc'];
			$update_array['point_orderstate'] = 2;
			$update_array['point_finishedtime'] = time();
			$result = $model_points_order->editPointsOrder($update_array,array('point_orderid'=>intval($_POST['point_orderid'])));
			if ($result){
				$this->log('编辑积分商品'.'['.$_POST['pg_id'].']',1);
				showMessage('核销成功','index.php?act=points_order&op=points_order');
			}else {
				showMessage('核销失败');
			}

		}

		$points_order_info = $model_points_order->getPointsOrderInfo(array('point_orderid'=>intval($_GET['point_orderid'])));
		if (empty($points_order_info)){
			showMessage($lang['illegal_parameter']);
		}
		Tpl::output('points_order',$points_order_info);
		Tpl::showpage('points_order.edit');
	}

	/**
	 * 导出列表
	 */
	public function export_points_orderOp(){
		$model_points_order = Model('points_order');
		$condition_arr = array();
		if ($_GET['search_field_value'] != '') {
			switch ($_GET['search_field_name']){
				case 'point_ordersn':
					$condition['point_ordersn'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				case 'member_mobile_true':
					$condition['point_buyermobiletrue'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
			}
		}
		if($_GET['search_state']){
			$condition['point_orderstate'] = intval($_GET['search_state']);
		}
		$points_order_list = $model_points_order->getPointsOrderList($condition,'*');


		$this->createExcel($points_order_list);
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'商品名称');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'兑换数量');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'积分');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'下单时间');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'收货手机号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'收货地址');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'完成时间');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'状态');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'商品详细信息');
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>$v['point_ordersn']);
			$tmp[] = array('data'=>$v['pg_name']);
			$tmp[] = array('data'=>$v['pg_number']);
			$tmp[] = array('data'=>$v['point_allpoint']);
			$tmp[] = array('data'=>date('Y-m-d H:i',$v['point_addtime']));
			$tmp[] = array('data'=>$v['receiver_name']);
			$tmp[] = array('data'=>$v['receiver_mobile']);
			$tmp[] = array('data'=>$v['address']);
			$tmp[] = array('data'=>date('Y-m-d H:i',$v['point_finishedtime']));
			$tmp[] = array('data'=>($v['point_orderstate']==1?'待完成' : '已完成'));
			$tmp[] = array('data'=>$v['point_orderdesc']);
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset('积分兑换订单列表',CHARSET));
		$excel_obj->generateXML($excel_obj->charset('积分兑换订单列表',CHARSET).'-'.date('Y-m-d-H',time()));
	}




}
