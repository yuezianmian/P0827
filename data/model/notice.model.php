
<?php
/**
 * notice模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class noticeModel extends Model {

	public function __construct(){
		parent::__construct('notice');
	}

	/**
	 * 取notice列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getNoticeList($condition = array(),$field = '*', $pagesize = '', $limit = '', $order = 'notice_id asc') {
		return $this->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->select();
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getNoticeInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 删除notice
	 * @param unknown $condition
	 */
	public function delNotice($condition = array()) {
		return $this->where($condition)->delete();
	}

	/**
	 * 增加notice
	 * @param unknown $data
	 * @return boolean
	 */
	public function addNotice($data) {
		return $this->insert($data);
	}

	/**
	 * 更新notice
	 * @param unknown $data
	 * @param unknown $condition
	 */
	public function editNotice($data = array(),$condition = array()) {
		return $this->where($condition)->update($data);
	}
}