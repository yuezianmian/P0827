
<?php
/**
 * points_good模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class points_goodModel extends Model {

	public function __construct(){
		parent::__construct('points_good');
	}

	/**
	 * 取points_good列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getPointsGoodList($condition = array(),$field = '*', $pagesize = '', $limit = '', $order = 'pg_id desc') {
		return $this->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->select();
	}

	/**
	 * 取points_good列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function countPointsGoodList($condition = array()) {
		return $this->where($condition)->count();
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getPointsGoodInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 删除points_good
	 * @param unknown $condition
	 */
	public function delPointsGood($condition = array()) {
		return $this->where($condition)->delete();
	}

	/**
	 * 新增兑换礼品订单
	 * @param array	$param
	 * @return bool
	 */
	public function addPointsGood($param) {
		if (!$param){
			return false;
		}
		$result = $this->table('points_good')->insert($param);
		return $result;
	}

	/**
	 * 更新points_good
	 * @param unknown $data
	 * @param unknown $condition
	 */
	public function editPointsGood($data = array(),$condition = array()) {
		return $this->where($condition)->update($data);
	}
}