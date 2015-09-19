<?php
/**
 * 二维码扫描记录
 */

defined('InShopNC') or exit('Access Invalid!');

class qrcode_recordControl extends SystemControl{
	const EXPORT_SIZE = 1000;
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 二维码扫描记录
	 */
	public function record_listOp(){
		$lang	= Language::getLangContent();
		//会员级别
		$model_qrcode_record = Model('qrcode_record');
		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'member_mobile':
    				$condition['member_mobile'] = trim($_GET['search_field_value']);
    				break;
    		}
		}
		if($_GET['search_product_id']){
			$condition['product_id'] = intval($_GET['search_product_id']);
		}

		$qrcode_record_list = $model_qrcode_record->getJoinList($condition);
		//整理会员信息
		if (is_array($qrcode_record_list)){
			foreach ($qrcode_record_list as $k=> $v){
				$qrcode_record_list[$k]['create_time'] = $v['create_time']?date('Y-m-d H:i:s',$v['create_time']):'';
			}
		}
		$model_product = Model('product');
		$product_list = $model_product->getProductList(array());
		Tpl::output('product_list',$product_list);
		Tpl::output('search_product_id',$_GET['search_product_id']);
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('qrcode_record_list',$qrcode_record_list);
		Tpl::output('page',$model_qrcode_record->showpage());
		Tpl::showpage('qrcode_record.index');
	}



}
