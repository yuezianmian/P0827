<?php
/**
 * 登录
 *
 * 包括 登录 验证 退出 操作
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');
class UploadControl extends BaseMemberControl {

	public function uploadUserImg(){
		if(empty($_POST["base64_image_content"])){
			echoJson(FAILED, "上传的图片编码不能为空");
		}
		$param = array();
		$param["path"] = "user";
		$param["base64_image_content"] =  $_POST["base64_image_content"];
		$this->uploadOp($param);
	}

	public function uploadShopImg(){
		if(empty($_POST["base64_image_content"])){
			echoJson(FAILED, "上传的图片编码不能为空");
		}
		$param = array();
		$param["path"] = "shop";
		$param["base64_image_content"] =  $_POST["base64_image_content"];
		$this->uploadOp($param);
	}


	private function uploadOp($param){
		$base64_image_content = $param["base64_image_content"];
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
    		$type = $result[2];
			$fileName = $this->member_info["member_id"]."-".time().".".$type;
			$filePath = "/data/upload/img/".$param["path"].DS.$fileName;
			$image_content = base64_decode(str_replace($result[1], '', $base64_image_content));
			if (file_put_contents($filePath, $image_content)){
				echoJson(SUCCESS, "上传成功，路径为$filePath", array('path'=>$filePath));
			}else{
				echoJson(FAILED, "上传图片失败");
			}
		}else{
			echoJson(FAILED, "上传的图片编码不正确");
		}
	}
}