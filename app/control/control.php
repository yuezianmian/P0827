<?php
/**
 * 系统后台公共方法
 *
 * 包括系统后台父类
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');
class Control{
	protected $token = array();   // 会员信息
	protected function __construct(){
		//校验请求是否合法
		if(!$this->checkIsLegalRequest()){
			echoJson(ILLEGAL_REQUEST, "非法的请求");
		}
		//刷新token
		$this->refreshToken();
		/**
		 * 验证用户是否登录
		 * $admin_info 管理员资料 name id
		 */
		$this->admin_info = $this->checkToken();
		if ($this->admin_info['id'] != 1){
			// 验证权限
			$this->checkPermission();
		}
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
			$user = unserialize(decrypt($_GET['token'], MD5_KEY));
			if (!key_exists('gid',(array)$user) || !isset($user['sp']) || (empty($user['name']) || empty($user['id']))){
				@header('Location: index.php?act=login&op=login');exit;
			}
			return $user;
		}
	}

	/**
	 * 系统后台 会员登录后 将会员验证内容写入对应cookie中
	 *
	 * @param string $name 用户名
	 * @param int $id 用户ID
	 * @return bool 布尔类型的返回结果
	 */
	protected final function systemSetKey($user){
		setNcCookie('sys_key',encrypt(serialize($user),MD5_KEY),3600,'',null);
	}
}


/********************************** 会员control父类 **********************************************/

class BaseMemberControl extends Control {
	protected $member_info = array();   // 会员信息
	public function __construct(){
		parent::__construct();

		//会员验证
		$this->checkLogin();

		//获得会员信息
		$this->member_info = $this->getMemberInfo(true);



	}

	/**
	 * 验证会员是否登录
	 *
	 */
	protected function checkLogin(){
		//取得cookie内容，解密，和系统匹配
		$user = unserialize(decrypt(cookie('sys_key'),MD5_KEY));
		if (!key_exists('gid',(array)$user) || !isset($user['sp']) || (empty($user['name']) || empty($user['id']))){
			@header('Location: index.php?act=login&op=login');exit;
		}else {
			$this->systemSetKey($user);
		}
		return $user;
	}

	/**
	 * 输出会员等级
	 * @param bool $is_return 是否返回会员信息，返回为true，输出会员信息为false
	 */
	protected function getMemberInfo(){
		$member_info = array();
		//会员详情
		if($_SESSION['member_id']) {
			$model_member = Model('member');
			$member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
		}
		if ($is_return == true){//返回会员信息
			return $member_info;
		} else {//输出会员信息
			Tpl::output('member_info',$member_info);
		}
	}
}
