<?php
/**
 * 上传图片
 */

defined('InShopNC') or exit('Access Invalid!');
class uploadControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function uploadOp(){
		if(empty($_POST["base64_image_content"])){
			echoJson(FAILED, "上传的图片编码不能为空");
		}

		$param = array();
		$param["path"] = empty($_POST['type']) ? 'default' : $_POST['type'];
		$param["base64_image_content"] =  $_POST["base64_image_content"];
		$this->_upload($param);
	}


	private function _upload($param){
		$base64_image_content = $param["base64_image_content"];
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
			$type = $result[2];
			$fileName = $this->member_info["member_id"]."-".microtime().".".$type;
			$filePath = "/data/upload/img/".$param["path"].DS.$fileName;
			$image_content = base64_decode(str_replace($result[1], '', $base64_image_content));
			if (file_put_contents($filePath, $image_content)){
				echoJson(SUCCESS, "上传成功，路径为$filePath", array('path'=>$filePath), $this->token);
			}else{
				echoJson(FAILED, "上传图片失败");
			}
		}else{
			echoJson(FAILED, "上传的图片编码不正确！");
		}
	}
}