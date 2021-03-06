<?php
/**
 * 登录
 *
 * 包括 登录 验证 退出 操作
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');
class loginControl extends Control {
	public function __construct(){
		parent::__construct();
	}

	public function registOp(){
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
			array("input"=>$_POST["member_mobile"],		"require"=>"true", "message"=>"手机号不能为空"),
			array("input"=>$_POST["member_passwd"],		"require"=>"true", "message"=>"密码不能为空"),
			array("input"=>$_POST["verify_code"],		    "require"=>"true", "message"=>"短信验证码不能为空"),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
			echoJson(FAILED, $error);
		}
		//校验短信验证码
		if(empty($_GET['token'])){
			echoJson(10,"短信验证码不正确");
		}
		$data = unserialize(decrypt($_GET['token'], MD5_KEY));
		if (empty($data) || empty($data['code'] || empty($data['time']))){
			echoJson(10,"短信验证码不正确");
		}
		if(time()-$data['time'] > VERIFY_CODE_TIMEOUT){
			echoJson(11,"短信验证码超时，重新获取短信验证码");
		}
		if($data['code'] != $_POST["verify_code"]){
			echoJson(10,"短信验证码不正确");
		}

		$model_member	= Model('member');
		$register_info = array();
		$register_info['member_mobile'] = $_POST['member_mobile']; //用户名暂时使用手机号
		$register_info['member_mobile_true'] = $_POST['member_mobile']; //这个才是手机号
		$register_info['member_passwd'] = $_POST['member_passwd'];
		$register_info['parent_code'] = $_POST['parent_code'];
		$register_info['recomm_member_id'] = $_POST['recomm_member_id'];
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
		$array['member_mobile_true']	= $_POST['member_mobile']; //手机号
		$array['member_passwd']	= md5(trim($_POST['member_passwd']));
		$model_member = Model('member');
		$member_info = $model_member->getMemberInfo($array);
		if(is_array($member_info) and !empty($member_info)) {
			$token = encrypt(serialize(array('mobile'=>$member_info['member_mobile'], 'id'=>$member_info['member_id'],'type'=>$member_info['member_type'])),MD5_KEY);
			echoJson(SUCCESS, "登录成功", $member_info, $token);
		}else{
			echoJson(10, "手机号或密码不正确");
		}
	}

	public function check_mobileOp(){
		if (empty($_GET['member_mobile'])){
			echoJson(FAILED, "手机号为空");
		}
		$model_member = Model('member');
		$check_member_mobile = $model_member->getMemberInfo(array('member_mobile_true'=>$_GET['member_mobile']));
		if(is_array($check_member_mobile) and count($check_member_mobile) > 0) {
			echoJson(SUCCESS, "该手机号已存在", array('isExist'=>"1"));
		}else{
			echoJson(SUCCESS, "该手机号不存在", array('isExist'=>"0"));
		}
	}

	public function check_parentcodeOp(){
		if (empty($_GET['parent_code'])){
			echoJson(FAILED, "代理商编号为空");
		}
		$model_member = Model('member');
		$check_parent_code = $model_member->getMemberInfo(array('parent_code'=>$_GET['parent_code']));
		if(is_array($check_parent_code) and count($check_parent_code) > 0) {
			echoJson(SUCCESS, "存在该代理商", array('isExist'=>"1"));
		}else{
			echoJson(SUCCESS, "不存在该代理商，请确认代理商编码", array('isExist'=>"0"));
		}
	}

	/**
	 * 注册 - 发送验证码
	 */
	public function regist_sendcodeOp() {
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
			array("input"=>$_GET["member_mobile"], "require"=>"true", 'validator'=>'mobile',"message"=>'请正确填写手机号码'),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
			echoJson(FAILED, "请正确填写手机号码");
		}
		$model_member = Model('member');
		$check_member_mobile = $model_member->getMemberInfo(array('member_mobile_true'=>$_GET['member_mobile']));
		if(is_array($check_member_mobile) and count($check_member_mobile) > 0) {
			echoJson(FAILED, "该手机号已被使用，请更换其它手机号");
		}

		$verify_code = rand(100,999).rand(100,999);

		$param = array();
		$param['verify_code'] = $verify_code;
		$message = ncReplaceText( C('sms.temp_code'),$param);
		$sms = new Sms();
		$result = $sms->send($_GET["member_mobile"],$message);

		if ($result['code'] == 2) {
			$token = encrypt(serialize(array('code'=>$verify_code, 'time'=>time())),MD5_KEY);
			echoJson(SUCCESS, "发送短信验证码成功", array(), $token);
		} else {
			$error = "send sms error,code:".$result['code'].",msg:".$result['msg'];
			Log::record($error,Log::ERR);
			echoJson(FAILED, "发送短信验证码失败");
		}
	}


	/**
	 * 找回密码 - 发送验证码
	 */
	public function findpassword_sendcodeOp() {
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
			array("input"=>$_GET["member_mobile"], "require"=>"true", 'validator'=>'mobile',"message"=>'请填写正确手机号码'),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
			echoJson(FAILED, "请填写正确手机号码");
		}
		$model_member = Model('member');
		$check_member_mobile = $model_member->getMemberInfo(array('member_mobile_true'=>$_GET['member_mobile']));
		if(empty($check_member_mobile)) {
			echoJson(10, "不存在该手机号的用户");
		}

		$verify_code = rand(100,999).rand(100,999);

		$param = array();
		$param['verify_code'] = $verify_code;
		$message = ncReplaceText( C('sms.temp_code'),$param);
		$sms = new Sms();
		$result = $sms->send($_GET["member_mobile"],$message);

		if ($result['code'] == 2) {
			$token = encrypt(serialize(array('code'=>$verify_code,'mobile'=>$_GET["member_mobile"], 'time'=>time())),MD5_KEY);
			echoJson(SUCCESS, "发送短信验证码成功", array(), $token);
		} else {
			$error = "send sms error,code:".$result['code'].",msg:".$result['msg'];
			Log::record($error,Log::ERR);
			echoJson(FAILED, "发送短信验证码失败");
		}
	}

	public function findpassowrd_checkmobileOp(){
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
			array("input"=>$_POST["member_mobile"],		"require"=>"true", "message"=>"手机号不能为空"),
			array("input"=>$_POST["verify_code"],		    "require"=>"true", "message"=>"短信验证码不能为空"),
			array("input"=>$_GET["token"],		    "require"=>"true", "message"=>"token不能为空"),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
			echoJson(FAILED, $error);
		}
		$data = unserialize(decrypt($_GET['token'], MD5_KEY));
		if (empty($data) || empty($data['code'] || empty($data['time']))){
			echoJson(10,"短信验证码不正确");
		}
		if(time()-$data['time'] > VERIFY_CODE_TIMEOUT){
			echoJson(11,"短信验证码超时，重新获取短信验证码");
		}
		if($data['code'] != $_POST["verify_code"]){
			echoJson(10,"短信验证码不正确");
		}
		if($data['mobile'] != $_POST["member_mobile"]){
			echoJson(12,"发送验证码的手机与提交的手机号不匹配");
		}

		$token = encrypt(serialize(array('mobile'=>$_POST['member_mobile'])),MD5_KEY);
		echoJson(SUCCESS, "手机号校验成功", array(), $token);
	}

	public function findpassowrd_editpasswordOp(){
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
			array("input"=>$_POST["member_mobile"],		"require"=>"true", "message"=>"手机号不能为空"),
			array("input"=>$_POST["member_passwd"],		    "require"=>"true", "message"=>"密码不能为空"),
			array("input"=>$_GET["token"],		    "require"=>"true", "message"=>"token不能为空"),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
			echoJson(FAILED, $error);
		}
		$data = unserialize(decrypt($_GET['token'], MD5_KEY));
		if (empty($data) || empty($data['mobile'] || $data['mobile'] != $_POST["member_mobile"])){
			echoJson(10,"请先校验手机号");
		}

		$model_member = Model('member');
		$result = $model_member->editMember(array('member_mobile_true'=>$_POST['member_mobile']),array('member_passwd'=>md5($_POST["member_passwd"])));
		if($result){
			echoJson(SUCCESS, "修改成功");
		}else{
			echoJson(FAILED, "修改失败");
		}

	}

}
