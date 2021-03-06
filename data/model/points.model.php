<?php
/**
 * 积分及积分日志管理
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc.
 */
defined('InShopNC') or exit('Access Invalid!');

class pointsModel {
	/**
	 * 操作积分
	 * @author ShopNC Develop Team
	 * @param  string $stage 操作阶段 regist(注册),login(登录),comments(评论),order(下单),system(系统),other(其他),pointorder(积分礼品兑换),app(同步积分兑换)
	 * @param  array $insertarr 该数组可能包含信息 array('pl_memberid'=>'会员编号','pl_membername'=>'会员名称','pl_adminid'=>'管理员编号','pl_adminname'=>'管理员名称','pl_points'=>'积分','pl_desc'=>'描述','orderprice'=>'订单金额','order_sn'=>'订单编号','order_id'=>'订单序号','point_ordersn'=>'积分兑换订单编号');
	 * @param  bool $if_repeat 是否可以重复记录的信息,true可以重复记录，false不可以重复记录，默认为true
	 * @return bool
	 */
	function savePointsLog($stage,$insertarr,$if_repeat = true){
		if (!$insertarr['pl_memberid']){
			return false;
		}
		//记录原因文字
		switch ($stage){
			case 'regist':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '注册会员';
				}
				$insertarr['pl_points'] = intval(C('points_reg'));
				break;
			case 'recomm_regist':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '推荐会员注册';
				}
//				$insertarr['pl_points'] = intval(C('points_reg'));
				break;
			case 'recommed_regist':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '被推荐注册';
				}
//				$insertarr['pl_points'] = intval(C('points_reg'));
				break;
			case 'login':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '会员登录';
				}
				$insertarr['pl_points'] = intval(C('points_login'));
				break;
			case 'scan_qrcode': //店面扫描商品增加店面积分
					$message_content = "你的积分于". date('Y-m-d H:i:s')." 有变化，描述：".$insertarr['pl_desc']."，产品名称: ".$insertarr['product_name']."，积分变化 ：".$insertarr['pl_points'];
				break;
			case 'shop_scan_qrcode': //店面扫描商品增加店面所属代理商积分
				$message_content = "你的积分于". date('Y-m-d H:i:s')." 有变化，描述：".$insertarr['pl_desc']."，扫描店面:".$insertarr['shop_name']."，产品名称: ".$insertarr['product_name']."，积分变化 ：".$insertarr['pl_points'];
				break;
			case 'pointorder':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '兑换商品';
				}
				break;
			case 'extract_cash':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '提现';
				}
				break;
			case 'sign':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = '签到';
				}
				break;
			case 'win_prize': //中奖
				break;
			case 'other':
				break;
		}
		$save_sign = true;
		if ($if_repeat == false){
			//检测是否有相关信息存在，如果没有，入库
			$condition['pl_memberid'] = $insertarr['pl_memberid'];
			$condition['pl_stage'] = $stage;
			$log_array = self::getPointsInfo($condition);
			if (!empty($log_array)){
				$save_sign = false;
			}
		}
		if ($save_sign == false){
			return true;
		}
		//新增日志
		$value_array = array();
		$value_array['pl_memberid'] = $insertarr['pl_memberid'];
		$value_array['pl_membermobile'] = $insertarr['pl_membermobile'];
		if ($insertarr['pl_adminid']){
			$value_array['pl_adminid'] = $insertarr['pl_adminid'];
		}
		if ($insertarr['pl_adminname']){
			$value_array['pl_adminname'] = $insertarr['pl_adminname'];
		}
		$value_array['pl_points'] = $insertarr['pl_points'];
		$value_array['pl_addtime'] = time();
		$value_array['pl_desc'] = $insertarr['pl_desc'];
		$value_array['pl_stage'] = $stage;
		$result = false;
		if($value_array['pl_points'] != '0'){
			$result = self::addPointsLog($value_array);
		}
		if ($result){
			//更新member内容
			$obj_member = Model('member');
			$upmember_array = array();
			$upmember_array['member_points'] = array('exp','member_points+'.$insertarr['pl_points']);
			if($insertarr['pl_points'] > 0){
				$upmember_array['total_points'] = array('exp','total_points+'.$insertarr['pl_points']);
			}
			$obj_member->editMember(array('member_id'=>$insertarr['pl_memberid']),$upmember_array);
			$obj_message = Model('message');
			$message_content = $message_content ? $message_content : ("你的积分于". date('Y-m-d H:i:s')." 有变化，描述：".$insertarr['pl_desc']."，积分变化 ：".$insertarr['pl_points']);
			$obj_message->saveMessage(array('to_member_id'=>$insertarr['pl_memberid'],'message_content'=>$message_content,'message_state'=>1));
			return true;
		}else {
			return false;
		}

	}
	/**
	 * 添加积分日志信息
	 *
	 * @param array $param 添加信息数组
	 */
	public function addPointsLog($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('points_log',$param);
		return $result;
	}
	/**
	 * 积分日志列表
	 *
	 * @param array $condition 条件数组
	 * @param array $page   分页
	 * @param array $field   查询字段
	 * @param array $page   分页
	 */
	public function getPointsLogList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'points_log';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'points_log.pl_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	/**
	 * 统计积分
	 *
	 */
	public function countPoints($condition_str){
//		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'points_log';
		$param['where']	= $condition_str;
		$param['field'] = 'sum(pl_points) points_sum';
		return Db::select($param)[0]['points_sum'];
	}
	/**
	 * 积分日志详细信息
	 *
	 * @param array $condition 条件数组
	 * @param array $field   查询字段
	 */
	public function getPointsInfo($condition,$field='*'){
		//得到条件语句
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'points_log';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$list		= Db::select($array);
		return $list[0];
	}
	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $condition_array
	 * @return	string
	 */
	private function getCondition($condition_array){
		$condition_sql = '';
		//积分日志会员编号
		if ($condition_array['pl_memberid']) {
			$condition_sql	.= " and `points_log`.pl_memberid = '{$condition_array['pl_memberid']}'";
		}
		//操作阶段
		if ($condition_array['pl_stage']) {
			$condition_sql	.= " and `points_log`.pl_stage = '{$condition_array['pl_stage']}'";
		}
		//会员手机号
		if ($condition_array['pl_membermobile_like']) {
			$condition_sql	.= " and `points_log`.pl_membermobile like '%{$condition_array['pl_membermobile_like']}%'";
		}
		//管理员名称
		if ($condition_array['pl_adminname_like']) {
			$condition_sql	.= " and `points_log`.pl_adminname like '%{$condition_array['pl_adminname_like']}%'";
		}
		//添加时间
		if ($condition_array['saddtime']){
			$condition_sql	.= " and `points_log`.pl_addtime >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and `points_log`.pl_addtime <= '{$condition_array['eaddtime']}'";
		}
		//描述
		if ($condition_array['pl_desc_like']){
			$condition_sql	.= " and `points_log`.pl_desc like '%{$condition_array['pl_desc_like']}%'";
		}
		return $condition_sql;
	}
}
