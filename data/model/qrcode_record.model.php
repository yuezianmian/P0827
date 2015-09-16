<?php
/**
 * 产品模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class qrcode_recordModel extends Model {

    public function __construct(){
        parent::__construct('qrcode_record');
    }

	/**
	 * 取二维码扫描记录列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getQrcodeRecordList($condition = array(), $pagesize = '', $limit = '', $order = 'record_id desc') {
		return $this->where($condition)->order($order)->page($pagesize)->limit($limit)->select();
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getQrcodeRecordInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 删除二维码扫描记录
	 * @param unknown $condition
	 */
//	public function delQrcodeRecord($condition = array()) {
//		return $this->where($condition)->delete();
//	}

	/**
	 * 增加二维码扫描记录
	 * @param unknown $data
	 * @return boolean
	 */
	public function addQrcodeRecord($data) {
		return $this->insert($data);
	}

}
