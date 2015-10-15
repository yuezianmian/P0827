<?php
/**
 * 系统后台公共方法
 *
 * 包括系统后台父类
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');
class Control{
	protected $token;   // 会员信息
	protected function __construct(){
		//校验请求是否合法
//		if(!$this->checkIsLegalRequest()){
//			echoJson(ILLEGAL_REQUEST, "非法的请求");
//		}
		//刷新token
		$this->refreshToken();

		//转码  防止GBK下用ajax调用时传汉字数据出现乱码
		if (($_GET['branch']!='' || $_GET['op']=='ajax') && strtoupper(CHARSET) == 'GBK'){
			$_GET = Language::getGBK($_GET);
		}
	}

	/**
	 * 校验请求是否合法
	 *
	 * @param
	 * @return bool 布尔类型的返回结果
	 */
	protected final function checkIsLegalRequest(){
		$isLegal = false;
		if(!empty($_GET['sign']) && !empty($_GET['timestamp']) && (time() - $_GET['timestamp']) < 5*60*1000){
			if(md5($_GET['timestamp'].MD5_KEY) == $_GET['sign']){
				$isLegal = true;
			}
		}
		return $isLegal;
	}

	/**
	 * 刷新token值
	 *
	 * @param
	 * @return string 的返回结果
	 */
	protected final function refreshToken(){
		if(!empty($_GET['token'])){
			//取得token内容，解密，和系统匹配
			$user = unserialize(decrypt($_GET['token'], MD5_KEY, APP_SESSION_TIMEOUT));
			if (!empty($user) && !empty($user['name'] && !empty($user['id']))){
				$this -> token = $this->generateToken($user);
			}
		}
	}

	/**
	 * 生成token
	 *
	 * @param
	 * @return string 的返回结果
	 */
	protected final function generateToken($user){
		$this -> token = encrypt(serialize($user),MD5_KEY);
	}
}


/********************************** 会员control父类 **********************************************/

class BaseMemberControl extends Control {
	protected $member_info = array();   // 会员信息
	public function __construct(){
		parent::__construct();

		//会员验证
		$user = $this->checkLogin();
		$this->member_info = $this->getMemberInfo($user['id']);
		if($this->member_info['member_state'] == MEMBER_STATE_NOPASS){
			echoJson(MEMBER_INVALID, "会员状态已失效");
		}
		//测试使用
		$this->member_info = $this->getMemberInfo(1);

	}

	/**
	 * 验证会员是否登录
	 *
	 */
	protected function checkLogin(){
		if(empty($_GET['token'])){
			echoJson(NOT_LOGIN, "token值为空");
		}
		//取得token内容，解密，和系统匹配
		$user = unserialize(decrypt($_GET['token'], MD5_KEY, APP_SESSION_TIMEOUT));
		if (empty($user) || empty($user['mobile'] || empty($user['id']) || empty($user['type']))){
			echoJson(NOT_LOGIN, "token值不正确");
		}

		return $user;
	}

	/**
	 * 后去会员信息
	 * @param bool $is_return 是否返回会员信息，返回为true，输出会员信息为false
	 */
	protected function getMemberInfo($member_id){
		$member_info = array();
		$model_member = Model('member');
		$member_info = $model_member->getMemberInfoByID($member_id);
		return $member_info;
	}
}
