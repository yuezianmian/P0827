
<?php
/**
 * banner模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class bannerModel extends Model {

	public function __construct(){
		parent::__construct('banner');
	}

	/**
	 * 取banner列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getBannerList($condition = array(),$field = '*', $pagesize = '', $limit = '', $order = 'banner_order asc') {
		return $this->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->select();
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getBannerInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 删除banner
	 * @param unknown $condition
	 */
	public function delBanner($condition = array()) {
		return $this->where($condition)->delete();
	}

	/**
	 * 增加banner
	 * @param unknown $data
	 * @return boolean
	 */
	public function addBanner($data) {
		return $this->insert($data);
	}

	/**
	 * 更新banner
	 * @param unknown $data
	 * @param unknown $condition
	 */
	public function editBanner($data = array(),$condition = array()) {
		return $this->where($condition)->update($data);
	}
}