<?php
/**
 * 权限管理
 *
 *
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');

class adminControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 生成二维码
	 */
	public function generate_codesOp(){
		$product_id = $_POST['product_id'];
		$number = $_POST['number'];
		if(empty($_POST['product_id'])){
			showMessage("产品不能为空，请选择生成二维码的产品");
		}
		if(empty($_POST['number'])){
			showMessage("生成的二维码个数不能为空");
		}
		$param = array();
		$param["product_id"] = $_POST['product_id'];
		$dir = BASE_UPLOAD_PATH.DS."imgcode".DS.uniqid(mt_rand(), true);
		if(!is_dir($dir)){
			mkdir($dir, 0777, true);
		}
		$param["dir"] = $dir;
		require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
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
	}

	private function _generateCode($param){
		$uid = uniqid(mt_rand(), true);
		$data = encrypt(serialize(array('id'=>$param['product_id'], 'uid'=>$param['dir'])),MD5_KEY);
		$img_name = $uid.".png";
		$PhpQRCode = new PhpQRCode();
		$PhpQRCode->set('pngTempDir',$param['dir']);
		$PhpQRCode->set('date',$data);
		$PhpQRCode->set('pngTempName', $img_name);
		$PhpQRCode->init();
	}
}
