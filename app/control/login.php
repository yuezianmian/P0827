<?php
/**
 * 登录
 *
 * 包括 登录 验证 退出 操作
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');
class LoginControl extends Control {

	public function registOp(){
		//TODO 校验短信验证码
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
			array("input"=>$_POST["member_mobile"],		"require"=>"true", "message"=>"手机号不能为空"),
			array("input"=>$_POST["member_passwd"],		"require"=>"true", "message"=>"密码不能为空"),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
			echoJson(FAILED, $error);
		}
		$model_member	= Model('member');
		$register_info = array();
		$register_info['member_mobile'] = $_POST['member_mobile'];
		$register_info['member_passwd'] = $_POST['member_passwd'];
		$register_info['parent_code'] = $_POST['parent_code'];
		$insert_id = $model_member->register($register_info);
		if($insert_id) {
			$token = encrypt(serialize(array('mobile'=>$_POST['member_mobile'], 'id'=>$insert_id,'type'=>MEMBER_TYPE_STORE)),MD5_KEY);
			echoJson(SUCCESS, "注册成功", array('member_id'=>$insert_id), $token);
		} else {
			echoJson(FAILED, "注册失败");
		}
	}

	public function loginOp(){
		$array	= array();
		$array['member_mobile']	= $_POST['member_mobile'];
		$array['member_passwd']	= md5(trim($_POST['member_passwd']));
		$model_member = Model('member');
		$member_info = $model_member->getMemberInfo($array);
		if(is_array($member_info) and !empty($member_info)) {
			$token = encrypt(serialize(array('mobile'=>$member_info['member_mobile'], 'id'=>$member_info['member_id'],'type'=>$member_info['member_type'])),MD5_KEY);
			echoJson(SUCCESS, "登录成功", $member_info, $token);
		}else{
			echoJson(FAILED, "登录失败");
		}
	}

	public function checkMobileOp(){
		if (empty($_GET['member_mobile'])){
			echoJson(FAILED, "手机号为空");
		}
		$model_member = Model('member');
		$check_member_mobile = $model_member->getMemberInfo(array('member_mobile'=>$_GET['member_mobile']));
		if(is_array($check_member_mobile) and count($check_member_mobile) > 0) {
			echoJson(SUCCESS, "该手机号已存在", array('isExist'=>"1"));
		}else{
			echoJson(SUCCESS, "该手机号不存在", array('isExist'=>"0"));
		}
	}


}
