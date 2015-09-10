<?php
/**
 * 会员管理
 *
 *
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');

class memberControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
	}

	/**
	 * 会员管理
	 */
	public function memberOp(){
		$lang	= Language::getLangContent();
		//会员级别
		$model_member = Model('member');
		$member_grade = $model_member->getMemberGradeArr();
		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'member_mobile':
    				$condition['member_mobile'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'member_truename':
    				$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				case 'member_code':
					$condition['member_code'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				case 'parent_code':
					$condition['parent_code'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
    		}
		}
		if($_GET['search_state']){
			$condition['member_state'] = intval($_GET['search_state']);
		}
		if($_GET['search_type']){
			$condition['member_type'] = intval($_GET['search_type']);
		}

		//排序
		$order = 'member_id desc';
		$member_list = $model_member->getMemberList($condition, '*', 10, $order);
		//整理会员信息
		if (is_array($member_list)){
			foreach ($member_list as $k=> $v){
				$member_list[$k]['create_time'] = $v['create_time']?date('Y-m-d H:i:s',$v['create_time']):'';
			}
		}
		Tpl::output('search_state',$_GET['search_state']);
		Tpl::output('search_type',$_GET['search_type']);
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('member_list',$member_list);
		Tpl::output('page',$model_member->showpage());
		Tpl::showpage('member.index');
	}

	/**
	 * 会员修改
	 */
	public function member_editOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["member_email"], "require"=>"true", 'validator'=>'Email', "message"=>$lang['member_edit_valid_email']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['member_id']			= intval($_POST['member_id']);
				if (!empty($_POST['member_passwd'])){
					$update_array['member_passwd'] = md5($_POST['member_passwd']);
				}
				$update_array['member_email']		= $_POST['member_email'];
				$update_array['member_truename']	= $_POST['member_truename'];
				$update_array['member_sex'] 		= $_POST['member_sex'];
				$update_array['member_qq'] 			= $_POST['member_qq'];
				$update_array['member_ww']			= $_POST['member_ww'];
				$update_array['inform_allow'] 		= $_POST['inform_allow'];
				$update_array['is_buy'] 			= $_POST['isbuy'];
				$update_array['is_allowtalk'] 		= $_POST['allowtalk'];
				$update_array['member_state'] 		= $_POST['memberstate'];
				if (!empty($_POST['member_avatar'])){
					$update_array['member_avatar'] = $_POST['member_avatar'];
				}
				$result = $model_member->editMember(array('member_id'=>intval($_POST['member_id'])),$update_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_edit_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_edit&member_id='.intval($_POST['member_id']),
					'msg'=>$lang['member_edit_again'],
					),
					);
					$this->log(L('nc_edit,member_index_name').'[ID:'.$_POST['member_id'].']',1);
					showMessage($lang['member_edit_succ'],$url);
				}else {
					showMessage($lang['member_edit_fail']);
				}
			}
		}
		$condition['member_id'] = intval($_GET['member_id']);
		$member_array = $model_member->getMemberInfo($condition);

		Tpl::output('member_array',$member_array);
		Tpl::showpage('member.edit');
	}

	/**
	 * 新增会员
	 */
	public function member_addOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			    array("input"=>$_POST["member_mobile"], "require"=>"true", "message"=>'手机号不能为空'),
			    array("input"=>$_POST["member_passwd"], "require"=>"true", "message"=>'密码不能为空'),
			    array("input"=>$_POST["member_truename"], "require"=>"true", "message"=>'姓名不能为空'),
			    array("input"=>$_POST["member_code"], "require"=>"true", "message"=>'代理商编号不能为空')
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['member_mobile']	= trim($_POST['member_mobile']);
				$insert_array['member_passwd']	= trim($_POST['member_passwd']);
				$insert_array['member_truename']= trim($_POST['member_truename']);
				$insert_array['member_code'] 	= trim($_POST['member_code']);
				$insert_array['area_id'] 		= trim($_POST['area_id']);
				$insert_array['area_name']		= trim($_POST['area_name']);
				$insert_array['member_state']	= MEMBER_STATE_NORMAL;
				$insert_array['member_type']	= MEMBER_TYPE_AGENT;

				$result = $model_member->addMember($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_add_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_add',
					'msg'=>'继续添加代理商',
					),
					);
					$this->log(L('nc_add,member_index_name').'[	'.$_POST['member_mobile'].']',1);
					showMessage($lang['member_add_succ'],$url);
				}else {
					showMessage($lang['member_add_fail']);
				}
			}
		}
		$model_area = Model('area');
		$area_arr = $model_area->getAreaArrayForJson();
		//generator member code
		$model_key_generator = Model('key_generator');
		$member_code = $model_key_generator->generatorNextValue('member_code');
		Tpl::output('member_code',$member_code);
		Tpl::output('area_json',json_encode($area_arr));
		Tpl::showpage('member.add');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证会员手机号是否重复
			 */
			case 'check_member_mobile':
				$model_member = Model('member');
				$condition['member_mobile']	= $_GET['member_mobile'];
				$condition['member_id']	= array('neq',intval($_GET['member_id']));
				$list = $model_member->getMemberInfo($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
				/**
			 * 验证代理商编码是否重复
			 */
			case 'check_member_code':
				$model_member = Model('member');
				$condition['member_code'] = $_GET['member_code'];
				$condition['member_id'] = array('neq',intval($_GET['member_id']));
				$list = $model_member->getMemberInfo($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}

}
