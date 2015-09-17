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
	public function getExtractCashList($condition = array(),$field = '*', $pagesize = '', $limit = '', $order = 'cash_id desc') {
		return $this->table('extract_cash')->field($field)->where($condition)->order($order)->page($pagesize)->limit($limit)->select();
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
}
