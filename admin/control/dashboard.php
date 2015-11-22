<?php
/**
 * 控制台
 *
 *
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');

class dashboardControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('dashboard');
	}
	/**
	 * 欢迎页面
	 */
	public function welcomeOp(){
		/**
		 * 管理员信息
		 */
		$model_admin = Model('admin');
		$tmp = $this->getAdminInfo();
		$condition['admin_id'] = $tmp['id'];
		$admin_info = $model_admin->infoAdmin($condition);
		$admin_info['admin_login_time'] = date('Y-m-d H:i:s',($admin_info['admin_login_time'] == '' ? time() : $admin_info['admin_login_time']));
		/**
		 * 系统信息
		 */
//		$version = C('version');
//		$setup_date = C('setup_date');
//		$statistics['os'] = PHP_OS;
//		$statistics['web_server'] = $_SERVER['SERVER_SOFTWARE'];
//		$statistics['php_version'] = PHP_VERSION;
//		$statistics['sql_version'] = Db::getServerInfo();
//		$statistics['shop_version'] = $version;
//		$statistics['setup_date'] = substr($setup_date,0,10);
//
//        // shopnc c extension
//        try {
//            $r = new ReflectionExtension('shopnc');
//            $statistics['php_version'] .= ' / ' . $r->getVersion();
//        } catch (ReflectionException $ex) {
//        }

//		Tpl::output('statistics',$statistics);
		Tpl::output('admin_info',$admin_info);
		Tpl::showpage('welcome');
	}

	/**
	 * 关于我们
	 */
	public function aboutusOp(){

		Tpl::showpage('aboutus');
	}

	/**
	 * 统计
	 */
	public function statisticsOp(){
        $statistics = array();
        // 本周开始时间点
        $tmp_time = mktime(0,0,0,date('m'),date('d'),date('Y'))-(date('w')==0?7:date('w')-1)*24*60*60;
        /**
         * 会员
         */
        $model_member = Model('member');
        // 会员总数
        $statistics['member'] = $model_member->getMemberCount(array());
        // 新增会员数
        $statistics['week_add_agent'] = $model_member->getMemberCount(array('create_time' => array('egt', $tmp_time), 'member_type' => '1'));
        // 预存款提现
        $statistics['week_add_shop'] = $model_member->getMemberCount(array('create_time' => array('egt', $tmp_time), 'member_type' => '2'));

        /**
         * 提现
         */
        $model_extract_cash = Model('extract_cash');
        // 总次数
        $statistics['extract_cash'] = $model_extract_cash->counExtractCash(array());
        // 已提现次数
        $statistics['extract_cash_amount'] = $model_extract_cash->counExtractCash(array('cash_state' => 2));
        // 申请中次数
        $statistics['to_extract_cash_amount'] = $model_extract_cash->counExtractCash(array('cash_state' => 1));

        /**
         * 积分兑换订单
         */
        $model_points_order = Model('points_order');
        // 积分兑换订单总数
        $statistics['points_order'] = $model_points_order->countPointsOrder(array());
        // 积分兑换完成订单数
        $statistics['points_order_amount'] = $model_points_order->countPointsOrder(array('point_orderstate' => 2));
        // 未兑换订单数
        $statistics['to_points_order_amount'] = $model_points_order->countPointsOrder(array('point_orderstate' => 1));

        /**
         * 二维码扫描
         */
        $model_qrcode_record = Model('qrcode_record');
        // 总数
        $statistics['qrcode_scan_amount'] = $model_qrcode_record->countQrcodeRecord(array());
        // 本周新增
        $statistics['qrcode_scan_weeken_add'] = $model_qrcode_record->countQrcodeRecord(array('create_time' => array('egt', $tmp_time)));
        /**
         * 反馈
         */
        $model_feedback = Model('feedback');
        // 总数
        $statistics['feedback_amount'] = $model_feedback->countFeedback(array());
        // 本周新增
        $statistics['feedback_weeken_add'] = $model_feedback->countFeedback(array('create_time' => array('egt', $tmp_time)));

        echo json_encode($statistics);
		exit;
	}
}
