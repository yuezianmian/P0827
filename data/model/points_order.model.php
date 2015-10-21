
<?php
/**
 * points_order模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class points_orderModel extends Model {

	public function __construct(){
		parent::__construct('points_order');
	}

	private $product_sn;	//订单编号
	/**
	 * 生成积分兑换订单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
	 * 长度 =2位 + 10位 + 3位 + 3位  = 18位
	 * 1000个会员同一微秒提订单，重复机率为1/100
	 * @return string
	 */
	public function point_snOrder($member_id) {
		return $this->product_sn =  mt_rand(10,99)
			. sprintf('%010d',time() - 946656000)
			. sprintf('%03d', (float) microtime() * 1000)
			. sprintf('%03d', (int) $member_id % 1000);
	}

	/**
	 * 取points_order列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getPointsOrderList($condition = array(),$field = '*', $pagesize = '', $limit = '', $order = 'point_orderid desc') {
		return $this->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->select();
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getPointsOrderInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 删除points_order
	 * @param unknown $condition
	 */
	public function delPointsOrder($condition = array()) {
		return $this->where($condition)->delete();
	}

	/**
	 * 创建兑换订单
	 * @param unknown $data
	 * @return boolean
	 */
	public function createPointsOrder($data) {
		//新增兑换订单
		$order_array		= array();
		$order_array['point_ordersn']		= $this->point_snOrder($data['member_id']);
		$order_array['point_buyerid']		= $data['member_id'];
		$order_array['point_buyermobile']		= $data['member_mobile'];
		$order_array['point_buyermobiletrue']	= $data['member_mobile_true'];
		$order_array['pg_id']	= $data['pg_id'];
		$order_array['pg_name']	= $data['pg_name'];
		$order_array['pg_number']	= $data['pg_number'];
		$order_array['point_allpoint']	= $data['point_allpoint'];
		$order_array['point_addtime']		= time();
		$order_array['point_orderstate']	= 1;
		return $this->addPointsOrder($order_array);
	}
	/**
	 * 新增兑换礼品订单
	 * @param array	$param
	 * @return bool
	 */
	public function addPointsOrder($param) {
		if (!$param){
			return false;
		}
		$result = $this->table('points_order')->insert($param);
		return $result;
	}

	/**
	 * 更新points_order
	 * @param unknown $data
	 * @param unknown $condition
	 */
	public function editPointsOrder($data = array(),$condition = array()) {
		return $this->where($condition)->update($data);
	}
}