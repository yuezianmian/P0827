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
		$model_class = Model('product');

		//删除
		if (chksubmit()){
			if (!empty($_POST['check_product_id']) && is_array($_POST['check_product_id']) ){
			    $result = $model_class->delProduct(array('product_id'=>array('in',$_POST['check_product_id'])));
				if ($result) {
			        $this->log('删除产品'.'[ID:'.implode(',',$_POST['check_product_id']).']',1);
				    showMessage("删除成功");
				}
			}
		    showMessage($lang['nc_common_del_fail']);
		}

		$product_list = $model_class->getProductList(array(),20);
		Tpl::output('product_list',$product_list);
		Tpl::output('page',$model_class->showpage());
		Tpl::showpage('product.index');
	}

	/**
	 * 商品分类添加
	 */
	public function product_addOp(){
		$lang	= Language::getLangContent();
		$model_class = Model('product');
		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["sc_name"], "require"=>"true", "message"=>$lang['product_name_no_null']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['sc_name'] = $_POST['sc_name'];
				$insert_array['sc_bail'] = intval($_POST['sc_bail']);
				$insert_array['sc_sort'] = intval($_POST['sc_sort']);
				$result = $model_class->addProduct($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=product&op=product_add',
					'msg'=>$lang['continue_add_product'],
					),
					array(
					'url'=>'index.php?act=product&op=product',
					'msg'=>$lang['back_product_list'],
					)
					);
					$this->log(L('nc_add,product').'['.$_POST['sc_name'].']',1);
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

		$model_class = Model('product');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["sc_name"], "require"=>"true", "message"=>$lang['product_name_no_null']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['sc_name'] = $_POST['sc_name'];
				$update_array['sc_bail'] = intval($_POST['sc_bail']);
				$update_array['sc_sort'] = intval($_POST['sc_sort']);
				$result = $model_class->editProduct($update_array,array('sc_id'=>intval($_POST['sc_id'])));
				if ($result){
					$this->log(L('nc_edit,product').'['.$_POST['sc_name'].']',1);
					showMessage($lang['nc_common_save_succ'],'index.php?act=product&op=product');
				}else {
					showMessage($lang['nc_common_save_fail']);
				}
			}
		}

		$class_array = $model_class->getProductInfo(array('sc_id'=>intval($_GET['sc_id'])));
		if (empty($class_array)){
			showMessage($lang['illegal_parameter']);
		}

		Tpl::output('class_array',$class_array);
		Tpl::showpage('product.edit');
	}

	/**
	 * 删除分类
	 */
	public function product_delOp(){
		$lang	= Language::getLangContent();
		$model_class = Model('product');
		if (intval($_GET['sc_id']) > 0){
			$array = array(intval($_GET['sc_id']));
			$result = $model_class->delProduct(array('sc_id'=>intval($_GET['sc_id'])));
			if ($result) {
			     $this->log(L('nc_del,product').'[ID:'.$_GET['sc_id'].']',1);
			     showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?act=product&op=product');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
	    $model_class = Model('product');
	    $update_array = array();
		switch ($_GET['branch']){
			//分类：验证是否有重复的名称
			case 'product_name':
			    $condition = array();
				$condition['sc_name'] = $_GET['value'];
				$condition['sc_id'] = array('sc_id'=>array('neq',intval($_GET['sc_id'])));
				$class_list = $model_class->getProductList($condition);
				if (empty($class_list)){
					$update_array['sc_name'] = $_GET['value'];
					$update = $model_class->editProduct($update_array,array('sc_id'=>intval($_GET['id'])));
					$return = $update ? 'true' : 'false';
				} else {
					$return = 'false';
				}
				break;
			//分类： 排序 显示 设置
			case 'product_sort':
				$model_class = Model('product');
				$update_array['sc_sort'] = intval($_GET['value']);
				$result = $model_class->editProduct($update_array,array('sc_id'=>intval($_GET['id'])));
				$return = $result ? 'true' : 'false';
				break;
			//分类：添加、修改操作中 检测类别名称是否有重复
			case 'check_class_name':
				$condition['sc_name'] = $_GET['sc_name'];
				$condition['sc_id'] = array('sc_id'=>array('neq',intval($_GET['sc_id'])));
				$class_list = $model_class->getProductList($condition);
				$return = empty($class_list) ? 'true' : 'false';
				break;
		}
		exit($return);
	}
}
