<?php
/**
 * 积分商品管理
 *
 */

defined('InShopNC') or exit('Access Invalid!');

class points_goodControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * points_good
	 */
	public function points_goodOp(){
		$lang	= Language::getLangContent();
		$model_points_good = Model('points_good');

		//删除
		if (chksubmit()){
			if (!empty($_POST['check_pg_id']) && is_array($_POST['check_pg_id']) ){
				$result = $model_points_good->delPointsGood(array('pg_id'=>array('in',$_POST['check_pg_id'])));
				if ($result) {
					$this->log('删除points_good'.'[ID:'.implode(',',$_POST['check_pg_id']).']',1);
					showMessage("删除成功");
				}
			}
			showMessage($lang['nc_common_del_fail']);
		}

		$points_good_list = $model_points_good->getPointsGoodList(array(),'*',10);
		Tpl::output('points_good_list',$points_good_list);
		Tpl::output('page',$model_points_good->showpage());
		Tpl::showpage('points_good.index');
	}

	/**
	 * points_good添加
	 */
	public function points_good_addOp(){
		$lang	= Language::getLangContent();
		$model_points_good = Model('points_good');
		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["pg_name"], "require"=>"true", "message"=>'商品名称不能为空'),
				array("input"=>$_FILES['pg_img']['name'], "require"=>"true", "message"=>'商品图片不能为空'),
				array("input"=>$_POST["pg_points"], "require"=>"true", "message"=>'兑换积分不能为空'),
				array("input"=>$_POST["pg_stock"], "require"=>"true", "message"=>'库存不能为空'),
				array("input"=>$_POST["pg_state"], "require"=>"true", "message"=>'状态不能为空'),
				array("input"=>$_POST["pg_desc"], "require"=>"true", "message"=>'描述不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}
			$upload	= new UploadFile();
			$upload->set('default_dir','img/points_good');
			$result = $upload->upfile('pg_img');
			if(!$result){
				showMessage($upload->error);
			}
			$insert_array = array();
			$insert_array['pg_name'] = $_POST["pg_name"];
			$insert_array['pg_img'] = '/data/upload/img/points_good/'.$upload->file_name;
			$insert_array['pg_points'] = $_POST['pg_points'];
			$insert_array['pg_stock'] = $_POST['pg_stock'];
			$insert_array['pg_state'] = $_POST['pg_state'];
			$insert_array['pg_desc'] = $_POST['pg_desc'];
			$insert_array['create_time'] = time();
			$result = $model_points_good->addPointsGood($insert_array);
			if ($result){
				$url = array(
					array(
						'url'=>'index.php?act=points_good&op=points_good_add',
						'msg'=>'继续添加',
					),
					array(
						'url'=>'index.php?act=points_good&op=points_good',
						'msg'=>'返回列表',
					)
				);
				$this->log('添加积分商品'.'['.$result.']',1);
				showMessage($lang['nc_common_save_succ'],$url,'html','succ',1,5000);
			}else {
				showMessage($lang['nc_common_save_fail']);
			}
		}
		Tpl::showpage('points_good.add');
	}

	/**
	 * 编辑
	 */
	public function points_good_editOp(){
		$lang	= Language::getLangContent();

		$model_points_good = Model('points_good');

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
			if($_FILES['pg_img']['name']!=''){
				$upload	= new UploadFile();
				$upload->set('default_dir','img/points_good');
				$result = $upload->upfile('pg_img');
				if(!$result){
					showMessage($upload->error);
				}
				$update_array['pg_img'] = '/data/upload/img/points_good/'.$upload->file_name;
			}
			$update_array['pg_name'] = $_POST['pg_name'];
			$update_array['pg_points'] = $_POST['pg_points'];
			$update_array['pg_stock'] = $_POST['pg_stock'];
			$update_array['pg_state'] = $_POST['pg_state'];
			$update_array['pg_desc'] = $_POST['pg_desc'];
			$result = $model_points_good->editPointsGood($update_array,array('pg_id'=>intval($_POST['pg_id'])));
			if ($result){
				$this->log('编辑积分商品'.'['.$_POST['pg_id'].']',1);
				showMessage($lang['nc_common_save_succ'],'index.php?act=points_good&op=points_good');
			}else {
				showMessage($lang['nc_common_save_fail']);
			}

		}

		$points_good_info = $model_points_good->getPointsGoodInfo(array('pg_id'=>intval($_GET['pg_id'])));
		if (empty($points_good_info)){
			showMessage($lang['illegal_parameter']);
		}
		Tpl::output('points_good',$points_good_info);
		Tpl::showpage('points_good.edit');
	}

	/**
	 * 删除分类
	 */
	public function points_good_delOp(){
		$lang	= Language::getLangContent();
		$model_points_good = Model('points_good');
		if (intval($_GET['pg_id']) > 0){
//			$array = array(intval($_GET['points_good_id']));
			$result = $model_points_good->delPointsGood(array('pg_id'=>intval($_GET['pg_id'])));
			if ($result) {
				$this->log('删除PointsGood'.'[ID:'.$_GET['pg_id'].']',1);
				showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?act=points_good&op=points_good');
	}


}
