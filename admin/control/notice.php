<?php
/**
 * 系统公告管理
 *
 */

defined('InShopNC') or exit('Access Invalid!');

class noticeControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * notice
	 */
	public function noticeOp(){
		$lang	= Language::getLangContent();
		$model_notice = Model('notice');

		//删除
		if (chksubmit()){
			if (!empty($_POST['check_notice_id']) && is_array($_POST['check_notice_id']) ){
				$result = $model_notice->delNotice(array('notice_id'=>array('in',$_POST['check_notice_id'])));
				if ($result) {
					$this->log('删除notice'.'[ID:'.implode(',',$_POST['check_notice_id']).']',1);
					showMessage("删除成功");
				}
			}
			showMessage($lang['nc_common_del_fail']);
		}

		$notice_list = $model_notice->getNoticeList(array(),'*',10);
		Tpl::output('notice_list',$notice_list);
		Tpl::output('page',$model_notice->showpage());
		Tpl::showpage('notice.index');
	}

	/**
	 * notice添加
	 */
	public function notice_addOp(){
		$lang	= Language::getLangContent();
		$model_notice = Model('notice');
		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_FILES['notice_img']['name'], "require"=>"true", "message"=>'公告图不能为空'),
				array("input"=>$_POST["notice_abstract"], "require"=>"true", "message"=>'概要不能为空'),
				array("input"=>$_POST["notice_title"], "require"=>"true", "message"=>'标题不能为空'),
				array("input"=>$_POST["notice_content"], "require"=>"true", "message"=>'内容不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}
			$upload	= new UploadFile();
			$upload->set('default_dir','img/notice');
			$result = $upload->upfile('notice_img');
			if(!$result){
				showMessage($upload->error);
			}
			$insert_array = array();
			$insert_array['notice_img'] = '/data/upload/img/notice/'.$upload->file_name;
			$insert_array['notice_title'] = $_POST['notice_title'];
			$insert_array['notice_abstract'] = $_POST['notice_abstract'];
			$insert_array['notice_content'] = $_POST['notice_content'];
			$insert_array['create_time'] = time();
			$result = $model_notice->addNotice($insert_array);
			if ($result){
				$url = array(
					array(
						'url'=>'index.php?act=notice&op=notice_add',
						'msg'=>'继续添加',
					),
					array(
						'url'=>'index.php?act=notice&op=notice',
						'msg'=>'返回列表',
					)
				);
				$this->log('添加Notice'.'['.$result.']',1);
				showMessage($lang['nc_common_save_succ'],$url,'html','succ',1,5000);
			}else {
				showMessage($lang['nc_common_save_fail']);
			}
		}
		Tpl::showpage('notice.add');
	}

	/**
	 * 编辑
	 */
	public function notice_editOp(){
		$lang	= Language::getLangContent();

		$model_notice = Model('notice');

		if (chksubmit()){
			//验证
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["notice_abstract"], "require"=>"true", "message"=>'概要不能为空'),
				array("input"=>$_POST["notice_title"], "require"=>"true", "message"=>'标题不能为空'),
				array("input"=>$_POST["notice_content"], "require"=>"true", "message"=>'内容不能为空'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}
			$update_array = array();
			if($_FILES['notice_img']['name']!=''){
				$upload	= new UploadFile();
				$upload->set('default_dir','img/notice');
				$result = $upload->upfile('notice_img');
				if(!$result){
					showMessage($upload->error);
				}
				$update_array['notice_img'] = '/data/upload/img/notice/'.$upload->file_name;
			}
			$update_array['notice_title'] = $_POST['notice_title'];
			$update_array['notice_abstract'] = $_POST['notice_abstract'];
			$update_array['notice_content'] = $_POST['notice_content'];
			$result = $model_notice->editNotice($update_array,array('notice_id'=>intval($_POST['notice_id'])));
			if ($result){
				$this->log('编辑系统公告'.'['.$_POST['notice_id'].']',1);
				showMessage($lang['nc_common_save_succ'],'index.php?act=notice&op=notice');
			}else {
				showMessage($lang['nc_common_save_fail']);
			}

		}

		$notice_info = $model_notice->getNoticeInfo(array('notice_id'=>intval($_GET['notice_id'])));
		if (empty($notice_info)){
			showMessage($lang['illegal_parameter']);
		}
		Tpl::output('notice',$notice_info);
		Tpl::showpage('notice.edit');
	}

	/**
	 * 删除分类
	 */
	public function notice_delOp(){
		$lang	= Language::getLangContent();
		$model_notice = Model('notice');
		if (intval($_GET['notice_id']) > 0){
//			$array = array(intval($_GET['notice_id']));
			$result = $model_notice->delNotice(array('notice_id'=>intval($_GET['notice_id'])));
			if ($result) {
				$this->log('删除Notice'.'[ID:'.$_GET['notice_id'].']',1);
				showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?act=notice&op=notice');
	}


}
