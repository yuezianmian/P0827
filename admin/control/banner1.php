<?php
/**
 * banner管理
 *
 */

defined('InShopNC') or exit('Access Invalid!');

class banner1Control extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * banner1
	 */
	public function banner1Op(){
		$lang	= Language::getLangContent();
		$model_banner1 = Model('banner1');



		$banner1_list = $model_banner1->getBannerList(array('banner1_id' => 1),'*',10);
		Tpl::output('banner1_list',$banner1_list);
		Tpl::output('page',$model_banner1->showpage());
		Tpl::showpage('banner1.index');
	}



	/**
	 * 编辑
	 */
	public function banner1_editOp(){
		$lang	= Language::getLangContent();

		$model_banner1 = Model('banner1');

		if (chksubmit()){
			//验证

			$update_array = array();
			if($_FILES['banner1_img']['name']!=''){
				$upload	= new UploadFile();
				$upload->set('default_dir','img/banner1');
				$result = $upload->upfile('banner1_img');
				if(!$result){
					showMessage($upload->error);
				}
				$update_array['banner1_img'] = '/data/upload/img/banner1/'.$upload->file_name;
			}
			$result = $model_banner1->editBanner($update_array,array('banner1_id'=>intval($_POST['banner1_id'])));
			if ($result){
				$this->log('编辑Banner1'.'['.$_POST['banner1_id'].']',1);
				showMessage($lang['nc_common_save_succ'],'index.php?act=banner1&op=banner1');
			}else {
				showMessage($lang['nc_common_save_fail']);
			}

		}

		$banner1_info = $model_banner1->getBannerInfo(array('banner1_id'=>intval($_GET['banner1_id'])));
		if (empty($banner1_info)){
			showMessage($lang['illegal_parameter']);
		}
		Tpl::output('banner1',$banner1_info);
		Tpl::showpage('banner1.edit');
	}



}
