<?php
/**
 * 店铺分类管理
 *
 *
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');

class productControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 店铺分类
	 */
	public function productOp(){
		$lang	= Language::getLangContent();
		$model_product = Model('product');

		//删除
		if (chksubmit()){
			if (!empty($_POST['check_product_id']) && is_array($_POST['check_product_id']) ){
			    $result = $model_product->delProduct(array('product_id'=>array('in',$_POST['check_product_id'])));
				if ($result) {
			        $this->log('删除产品'.'[ID:'.implode(',',$_POST['check_product_id']).']',1);
				    showMessage("删除成功");
				}
			}
		    showMessage($lang['nc_common_del_fail']);
		}

		$product_list = $model_product->getProductList(array(),20);
		Tpl::output('product_list',$product_list);
		Tpl::output('page',$model_product->showpage());
		Tpl::showpage('product.index');
	}

	/**
	 * 商品分类添加
	 */
	public function product_addOp(){
		$lang	= Language::getLangContent();
		$model_product = Model('product');
		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["product_name"], "require"=>"true", "message"=>'产品名称不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['product_name'] = $_POST['product_name'];
				$insert_array['agent_points'] = intval($_POST['agent_points']);
				$insert_array['shop_points'] = intval($_POST['shop_points']);
				$insert_array['agent_scan_points'] = intval($_POST['agent_scan_points']);
				$insert_array['create_time'] = time();
				$result = $model_product->addProduct($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=product&op=product_add',
					'msg'=>'继续添加产品',
					),
					array(
					'url'=>'index.php?act=product&op=product',
					'msg'=>'返回产品列表',
					)
					);
					$this->log('添加产品'.'['.$_POST['product_name'].']',1);
					showMessage($lang['nc_common_save_succ'],$url,'html','succ',1,5000);
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}
		Tpl::showpage('product.add');
	}

	/**
	 * 编辑
	 */
	public function product_editOp(){
		$lang	= Language::getLangContent();

		$model_product = Model('product');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["product_name"], "require"=>"true", "message"=>'产品名称不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['product_name'] = $_POST['product_name'];
				$update_array['agent_points'] = intval($_POST['agent_points']);
				$update_array['shop_points'] = intval($_POST['shop_points']);
				$update_array['agent_scan_points'] = intval($_POST['agent_scan_points']);
				$result = $model_product->editProduct($update_array,array('product_id'=>intval($_POST['product_id'])));
				if ($result){
					$this->log('编辑产品'.'['.$_POST['product_name'].']',1);
					showMessage($lang['nc_common_save_succ'],'index.php?act=product&op=product');
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		$product_info = $model_product->getProductInfo(array('product_id'=>intval($_GET['product_id'])));
		if (empty($product_info)){
			showMessage($lang['illegal_parameter']);
		}

		Tpl::output('product_info',$product_info);
		Tpl::showpage('product.edit');
	}

	/**
	 * 删除分类
	 */
	public function product_delOp(){
		$lang	= Language::getLangContent();
		$model_product = Model('product');
		if (intval($_GET['product_id']) > 0){
//			$array = array(intval($_GET['product_id']));
			$result = $model_product->delProduct(array('product_id'=>intval($_GET['product_id'])));
			if ($result) {
			     $this->log('删除产品'.'[ID:'.$_GET['sc_id'].']',1);
			     showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?act=product&op=product');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		$model_product = Model('product');
		switch ($_GET['branch']){
			//分类：验证是否有重复的名称
			case 'check_product_name':
			    $condition = array();
				$condition['product_name'] = trim($_GET['product_name']);
				$condition['product_id'] = array('neq',intval($_GET['product_id']));
				$product_list = $model_product->getProductList($condition);
				if (empty($product_list)){
					echo 'true';exit;
				} else {
					echo 'false';exit;
				}
				break;

		}
	}

	/**
	 * 产品中心地址
	 */
	public function productcenterOp(){
		$lang	= Language::getLangContent();

		$model_config = Model('config');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["product_center_url"], "require"=>"true", "message"=>'产品中心地址不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['value'] = trim($_POST['product_center_url']);
				$result = $model_config->editConfig($update_array,array('type'=>'product_center_url'));
				if ($result){
					$this->log('编辑产品中心地址'.'['.$_POST['product_center_url'].']',1);
					showMessage($lang['nc_common_save_succ'],'index.php?act=product&op=productcenter');
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		$product_center_info = $model_config->getConfigInfo(array('type'=>'product_center_url'));
		if (empty($product_center_info)){
			showMessage($lang['illegal_parameter']);
		}

		Tpl::output('product_center_info',$product_center_info);
		Tpl::showpage('product_center.edit');
	}
}
