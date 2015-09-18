<?php
/**
 * 签到模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class signModel extends Model {

    public function __construct(){
        parent::__construct('sign');
    }

	/**
	 * 取签到列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getSignList($condition = array(),$page='',$field = '*') {
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'sign';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'sign.sign_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getSignInfo($condition, $field='*') {
		//得到条件语句
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'sign';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$list = Db::select($array);
		return $list[0];
	}

	/**
	 * 插入签到
	 * @param unknown $data
	 * @return boolean
	 */
	public function addSign($data) {
		return $this->insert($data);
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
		if ($condition_array['month']) {
			$condition_sql	.= " and FROM_UNIXTIME(sign.sign_time,'%Y%m') = '{$condition_array['month']}'";
		}
		//操作阶段
		if ($condition_array['day']) {
			$condition_sql	.= " and FROM_UNIXTIME(sign.sign_time,'%Y%m%d') = '{$condition_array['day']}'";
		}
		//会员名称
		if ($condition_array['member_id']) {
			$condition_sql	.= " and sign.member_id = '{$condition_array['member_id']}'";
		}

		return $condition_sql;
	}
}
