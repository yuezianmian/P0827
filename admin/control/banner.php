<?php
/**
 * banner管理
 *
 */

defined('InShopNC') or exit('Access Invalid!');

class bannerControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * banner
	 */
	public function bannerOp(){
		$lang	= Language::getLangContent();
		$model_banner = Model('banner');

		//删除
		if (chksubmit()){
			if (!empty($_POST['check_banner_id']) && is_array($_POST['check_banner_id']) ){
			    $result = $model_banner->delBanner(array('banner_id'=>array('in',$_POST['check_banner_id'])));
				if ($result) {
			        $this->log('删除banner'.'[ID:'.implode(',',$_POST['check_banner_id']).']',1);
				    showMessage("删除成功");
				}
			}
		    showMessage($lang['nc_common_del_fail']);
		}

		$banner_list = $model_banner->getBannerList(array(),'*',10);
		Tpl::output('banner_list',$banner_list);
		Tpl::output('page',$model_banner->showpage());
		Tpl::showpage('banner.index');
	}

	/**
	 * banner添加
	 */
	public function banner_addOp(){
		$lang	= Language::getLangContent();
		$model_banner = Model('banner');
		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_FILES['banner_img']['name'], "require"=>"true", "message"=>'banner图片不能为空'),
			array("input"=>$_POST["banner_order"], "require"=>"true", "message"=>'排序不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}
			$upload	= new UploadFile();
			$upload->set('default_dir','img/banner');
			$result = $upload->upfile('banner_img');
			if(!$result){
				showMessage($upload->error);
			}
			$insert_array = array();
			$insert_array['banner_img'] = '/data/upload/img/banner/'.$upload->file_name;
			$insert_array['banner_order'] = intval($_POST['banner_order']);
			$insert_array['create_time'] = time();
			$result = $model_banner->addBanner($insert_array);
			if ($result){
				$url = array(
				array(
				'url'=>'index.php?act=banner&op=banner_add',
				'msg'=>'继续添加Banner',
				),
				array(
				'url'=>'index.php?act=banner&op=banner',
				'msg'=>'返回Banner列表',
				)
				);
				$this->log('添加Banner'.'['.$result.']',1);
				showMessage($lang['nc_common_save_succ'],$url,'html','succ',1,5000);
			}else {
				showMessage($lang['nc_common_save_fail']);
			}
		}
		Tpl::showpage('banner.add');
	}

	/**
	 * 编辑
	 */
	public function banner_editOp(){
		$lang	= Language::getLangContent();

		$model_banner = Model('banner');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["banner_order"], "require"=>"true", "message"=>'排序不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}
			$update_array = array();
			if($_FILES['banner_img']['name']!=''){
				$upload	= new UploadFile();
				$upload->set('default_dir','img/banner');
				$result = $upload->upfile('banner_img');
				if(!$result){
					showMessage($upload->error);
				}
				$update_array['banner_img'] = '/data/upload/img/banner/'.$upload->file_name;
			}
			$update_array['banner_order'] = intval($_POST['banner_order']);
			$result = $model_banner->editBanner($update_array,array('banner_id'=>intval($_POST['banner_id'])));
			if ($result){
				$this->log('编辑Banner'.'['.$_POST['banner_id'].']',1);
				showMessage($lang['nc_common_save_succ'],'index.php?act=banner&op=banner');
			}else {
				showMessage($lang['nc_common_save_fail']);
			}

		}

		$banner_info = $model_banner->getBannerInfo(array('banner_id'=>intval($_GET['banner_id'])));
		if (empty($banner_info)){
			showMessage($lang['illegal_parameter']);
		}
		Tpl::output('banner',$banner_info);
		Tpl::showpage('banner.edit');
	}

	/**
	 * 删除分类
	 */
	public function banner_delOp(){
		$lang	= Language::getLangContent();
		$model_banner = Model('banner');
		if (intval($_GET['banner_id']) > 0){
//			$array = array(intval($_GET['banner_id']));
			$result = $model_banner->delBanner(array('banner_id'=>intval($_GET['banner_id'])));
			if ($result) {
			     $this->log('删除Banner'.'[ID:'.$_GET['banner_id'].']',1);
			     showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?act=banner&op=banner');
	}

	
}
