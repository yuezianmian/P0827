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
	public function getQrcodeRecordList($condition = array(),$field = '*', $pagesize = '', $limit = '', $order = 'record_id desc') {
		return $this->field($field)->where($condition)->order($order)->page($pagesize)->limit($limit)->select();
	}

	/**
	 * 扫描统计
	 */
	public function countQrcodeRecord($condition) {
		$count	= $this->where($condition)->count();
		return $count;
	}

	public function getJoinList($condition,$page='',$field = 'qrcode_record.*,member.member_mobile'){
		$param	= array();
		$param['field'] = $field;
		$param['table']	= 'qrcode_record,member';
		$param['join_type']	= 'inner join';
		$param['join_on']	= array('qrcode_record.member_id=member.member_id');
		$param['where']	= $this->getCondition($condition);
		$param['order']	= $condition['order'] ? $condition['order'] : 'qrcode_record.record_id desc';;
		return Db::select($param,$page);
	}

	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $condition_array
	 * @return	string
	 */
	private function getCondition($condition_array){
		$condition_sql = '';
		//手机号
		if ($condition_array['member_mobile']) {
			$condition_sql	.= " and member.member_mobile like '%{$condition_array['member_mobile']}%'";
		}
		//产品id
		if ($condition_array['product_id']) {
			$condition_sql	.= " and qrcode_record.product_id = '{$condition_array['product_id']}'";
		}

		return $condition_sql;
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
