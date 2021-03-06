<?php
/**
 * 站内信管理
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc.
 */
defined('InShopNC') or exit('Access Invalid!');
class messageModel extends Model{

	public function __construct(){
		parent::__construct('message');
	}
	/**
	 * 站内信列表
	 * @param	array $param	条件数组
	 * @param	object $page	分页对象调用
	 */
	public function listMessage($condition,$page='') {
		//得到条件语句
		$condition_str = $this->getCondition($condition);
		$param	= array();
		$param['table']		= 'message';
		$param['where']		= $condition_str;
		$param['order']		= 'message.message_id DESC';
		$param['limit']     = $condition['limit'];
		$message_list		= Db::select($param,$page);
		return $message_list;
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getMessageInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 更新消息状态
	 * @param unknown $data
	 * @param unknown $condition
	 */
	public function editMessage($data = array(),$condition = array()) {
		return $this->where($condition)->update($data);
	}
	/**
	 * 站内信总数
	 */
	public function countMessage($condition) {
		//得到条件语句
		$condition_str = $this->getCondition($condition);
		$param	= array();
		$param['table']		= 'message';
		$param['where']		= $condition_str;
		$param['field']		= ' count(message_id) as countnum ';
		$message_list		= Db::select($param);
		return $message_list[0]['countnum'];
	}
	/**
	 * 获取未读信息数量
	 */
	public function countNewMessage($member_id){
		$condition_arr = array();
		$condition_arr['to_member_id'] = "$member_id";
		$condition_arr['message_state'] = '1';
		$countnum = $this->countMessage($condition_arr);
		return $countnum;
	}
	/**
	 * 站内信单条信息
	 * @param	array $param	条件数组
	 * @param	object $page	分页对象调用
	 */
	public function getRowMessage($condition) {
		//得到条件语句
		$condition_str = $this->getCondition($condition);
		$param	= array();
		$param['table']		= 'message';
		$param['where']		= $condition_str;
		$message_list		= Db::select($param);
		return $message_list[0];
	}
	/**
	 * 站内信保存
	 *
	 * @param	array $param	条件数组
	 */
	public function saveMessage($param) {
		if($param['to_member_id'] == '') {
			return false;
		}
		$array	= array();
		$array['from_member_id']	= $param['from_member_id'] ? $param['from_member_id'] : '0' ;
		$array['to_member_id']	    = $param['to_member_id'];
		$array['message_content']	= $param['message_content'];
		$array['message_state']	= $param['message_state'];
		$array['create_time']		= time();
		return Db::insert('message',$array);
	}

	/**
	 * 删除消息
	 * @param unknown $condition
	 */
	public function delMessage($condition = array()) {
		return $this->where($condition)->delete();
	}

	private function getCondition($condition_array){
		$condition_sql = '';
		//站内信编号
		if($condition_array['message_id'] != ''){
			$condition_sql	.= " and message.message_id = '{$condition_array['message_id']}'";
		}
		//是否已读
		if($condition_array['message_state'] != ''){
			$condition_sql	.= " and message.message_state = '{$condition_array['message_state']}'";
		}
		//普通信件接收到的会员查询条件为
		if($condition_array['to_member_id'] != ''){
			$condition_sql	.= " and message.to_member_id='{$condition_array['to_member_id']}' ";
		}
		//发信人
		if($condition_array['from_member_id'] != '') {
			$condition_sql	.= " and message.from_member_id='{$condition_array['from_member_id']}' ";
		}
		return $condition_sql;
	}
}