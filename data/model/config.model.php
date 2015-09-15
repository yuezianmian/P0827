<?php
/**
 * 配置项模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class configModel extends Model {

    public function __construct(){
        parent::__construct('config');
    }


	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getConfigInfo($condition = array()) {
		return $this->where($condition)->find();
	}


	/**
	 * 更新配置项
	 * @param unknown $data
	 * @param unknown $condition
	 */
	public function editConfig($data = array(),$condition = array()) {
		return $this->where($condition)->update($data);
	}
}
