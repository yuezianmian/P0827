<?php
/**
 * 代理商编码等生成器模型
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc.
 */
defined('InShopNC') or exit('Access Invalid!');
class key_generatorModel extends Model {

    public function __construct(){
        parent::__construct('key_generator');
    }

    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function generatorNextValue($type) {
        $data = $this->table('key_generator')->field('*')->where(array('generator_type'=>$type))->find();
		$value = $data['value'] + 1;
		$this->table('key_generator')->where(array('generator_type'=>$type))->update(array('value'=>$value));
		return $value;
    }


}
