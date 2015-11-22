<?php
/**
 * 提现模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class extract_cashModel extends Model {

    public function __construct(){
        parent::__construct('extract_cash');
    }

	
	/**
	 * 取提现列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getExtractCashList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'extract_cash';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'extract_cash.cash_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}

	/**
	 * 取提现列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getExtractCashListWithMemberInfo($condition,$page=''){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'extract_cash,member';
		$param['field'] = 'extract_cash.*,member.alipay_number,member.bank_number,member.bank_username,member.bank_name,member.bank_branch,member.member_mobile_true';
		$param['where']	= $condition_str;
		$param['join_type']	= 'inner join';
		$param['join_on']	= array('extract_cash.member_id=member.member_id');
		$param['order'] = $condition['order'] ? $condition['order'] : 'extract_cash.cash_id desc';
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}

	/**
	 * 提现总数
	 */
	public function counExtractCash($condition) {
		$cash_list	= $this->field('count(1) as countnum')->where($condition)->select();
		return $cash_list[0]['countnum'];
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getExtractCashInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 删除提现
	 * @param unknown $condition
	 */
	public function delExtractCash($condition = array()) {
		return $this->where($condition)->delete();
	}

	/**
	 * 增加提现
	 * @param unknown $data
	 * @return boolean
	 */
	public function addExtractCash($data) {
		return $this->insert($data);
	}

	/**
	 * 更新提现
	 * @param unknown $data
	 * @param unknown $condition
	 */
	public function editExtractCash($data = array(),$condition = array()) {
		return $this->where($condition)->update($data);
	}
	
	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $condition_array
	 * @return	string
	 */
	private function getCondition($condition_array){
		$condition_sql = '';
		
		//会员手机号
		if ($condition_array['member_mobile_like']) {
			$condition_sql	.= " and `extract_cash`.member_mobile like '%{$condition_array['member_mobile_like']}%'";
		}
		//申请时间
		if ($condition_array['saddtime']){
			$condition_sql	.= " and `extract_cash`.create_time >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and `extract_cash`.create_time <= '{$condition_array['eaddtime']}'";
		}
		//描述
		if ($condition_array['cash_state']){
			$condition_sql	.= " and `extract_cash`.cash_state = '{$condition_array['cash_state']}'";
		}
		return $condition_sql;
	}
}
