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
				array("input"=>$_POST["pg_name"], "require"=>"true", "message"=>'商品名称不能为空'),
				array("input"=>$_POST["pg_points"], "require"=>"true", "message"=>'兑换积分不能为空'),
				array("input"=>$_POST["pg_stock"], "require"=>"true", "message"=>'库存不能为空'),
				array("input"=>$_POST["pg_state"], "require"=>"true", "message"=>'状态不能为空'),
				array("input"=>$_POST["pg_desc"], "require"=>"true", "message"=>'描述不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}
			$update_array = array();
			$update_array['pg_name'] = $_POST['pg_name'];
			$update_array['pg_points'] = $_POST['pg_points'];
			$update_array['pg_stock'] = $_POST['pg_stock'];
			$update_array['pg_state'] = $_POST['pg_state'];
			$update_array['pg_desc'] = $_POST['pg_desc'];
			$result = $model_points_order->editPointsOrder($update_array,array('pg_id'=>intval($_POST['pg_id'])));
			if ($result){
				$this->log('编辑积分商品'.'['.$_POST['pg_id'].']',1);
				showMessage($lang['nc_common_save_succ'],'index.php?act=points_order&op=points_order');
			}else {
				showMessage($lang['nc_common_save_fail']);
			}

		}

		$points_order_info = $model_points_order->getPointsOrderInfo(array('pg_id'=>intval($_GET['pg_id'])));
		if (empty($points_order_info)){
			showMessage($lang['illegal_parameter']);
		}
		Tpl::output('points_order',$points_order_info);
		Tpl::showpage('points_order.edit');
	}




}
