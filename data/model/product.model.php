<?php
/**
 * 产品模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class productModel extends Model {

    public function __construct(){
        parent::__construct('product');
    }

	/**
	 * 取产品列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getProductList($condition = array(), $pagesize = '', $limit = '', $order = 'product_id asc') {
		return $this->where($condition)->order($order)->page($pagesize)->limit($limit)->select();
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getProductInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 删除类别
	 * @param unknown $condition
	 */
	public function delProduct($condition = array()) {
		return $this->where($condition)->delete();
	}

	/**
	 * 增加店铺分类
	 * @param unknown $data
	 * @return boolean
	 */
	public function addProduct($data) {
		return $this->insert($data);
	}

	/**
	 * 更新产品
	 * @param unknown $data
	 * @param unknown $condition
	 */
	public function editProduct($data = array(),$condition = array()) {
		return $this->where($condition)->update($data);
	}
}
