<?php
/**
 * 权限管理
 *
 *
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');

class generate_codeControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	private $phpQRCode;
	/**
	 * 生成二维码
	 */
	public function generate_codeOp(){
		if (chksubmit()){
			$product_id = $_POST['product_id'];
			$number = $_POST['code_number'];
			if(empty($_POST['product_id'])){
				showMessage("产品不能为空，请选择生成二维码的产品");
			}
			if(empty($_POST['code_number'])){
				showMessage("生成的二维码个数不能为空");
			}
			$param = array();
			$param["product_id"] = $_POST['product_id'];
			$dir = BASE_UPLOAD_PATH.DS."imgcode".DS.uniqid(mt_rand(), true).DS;
			if(!is_dir($dir)){
				mkdir($dir, 0777, true);
			}
			$param["dir"] = $dir;
			require_once(BASE_RESOURCE_PATH.'/phpqrcode/index.php');
			for($i=0; $i < $number; $i++){
				$this->_generateCode($param);
			}
			require_once(BASE_CORE_PATH.'/framework/libraries/phpzip.php');
			//压缩并下载
			$phpzip = new phpzip();
			$phpzip->ZipAndDownload($dir);

			require_once(BASE_CORE_PATH.'/framework/libraries/FileUtil.php');
			//删除目录
			$fileUtil = new FileUtil();
			$fileUtil -> unlinkDir($dir);
			//生成成功后重新跳转到该页面
		}
		$model_product = Model('product');
		$product_list = $model_product->getProductList(array(),20);
		$default_product_id = $_GET['default_product_id'];
		Tpl::output('product_list',$product_list);
		Tpl::output('default_product_id',$default_product_id);
		Tpl::showpage('generate_code.index');

	}

	private function _generateCode($param){
		if(!isset($phpQRCode)){
			$phpQRCode = new PhpQRCode();
		}
		$uid = uniqid(mt_rand(), true);
		$data = encrypt(serialize(array('id'=>$param['product_id'], 'uid'=>$uid)),MD5_KEY);
		$img_name = $uid.".png";
		$phpQRCode->set('pngTempDir',$param['dir']);
		$phpQRCode->set('date',$data);
		$phpQRCode->set('pngTempName', $img_name);
		$phpQRCode->init();
	}
}
