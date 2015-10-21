<?php
/**
 * 二维码
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class qrcodeControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function scanOp(){
		if($this->member_info['member_type'] != MEMBER_TYPE_STORE){
			echoJson(10, "非服务店面会员，不可扫描二维码");
		}
		$qrvalue = $_POST['qrcode'];
		if(empty($qrvalue)){
			echoJson(FAILED, "二维码内容不能为空");
		}
		$data = unserialize(decrypt($qrvalue, MD5_KEY));
		$product_id = intval($data['id']);
		$qrcode = $data['uid'];
		$model_product = Model('product');
		$product_info = $model_product->getProductInfo(array('product_id'=>$product_id));
		if(empty($product_info)){
			echoJson(11,"该产品不存在");
		}
		$model_qrcode_record = Model('qrcode_record');
		$qrcode_record_info = $model_qrcode_record->getQrcodeRecordInfo(array('product_id'=>$product_id,'qrcode'=>$qrcode));
		if(!empty($qrcode_record_info)){
			echoJson(12,"该二维码已被扫描，不可重复扫描");
		}
		$insert_array = array();
		$insert_array['product_id']	= $product_id;
		$insert_array['product_name'] = $product_info['product_name'];
		$insert_array['shop_points'] = $product_info['shop_points'];
		$insert_array['agent_points'] = $product_info['agent_points'];
		$insert_array['qrcode']	= $qrcode;
		$insert_array['member_id']= $this->member_info['member_id'];
		$insert_array['create_time'] 	= TIMESTAMP;

		$result = $model_qrcode_record->addQrcodeRecord($insert_array);
		if(!$result){
			echoJson(FAILED, "保存二维码扫描记录失败");
		}
		//添加积分记录
		$points_model = Model('points');
		$points_model->savePointsLog('scan_qrcode',array('pl_memberid'=>$this->member_info['member_id'],'pl_membermobile'=>$this->member_info['member_mobile'],'pl_points'=>$product_info['shop_points'],'pl_desc'=>('扫描产品'),'product_name'=>$product_info['product_name']),true);
		if(!empty($this->member_info['parent_code'])){ //存在所属代理商
			$model_member = Model('member');
			$parent_member_info = $model_member->getMemberInfo(array('member_code'=>$this->member_info['parent_code']));
			$points_model->savePointsLog('shop_scan_qrcode',array('pl_memberid'=>$parent_member_info['member_id'],'pl_membermobile'=>$parent_member_info['member_mobile'],'pl_points'=>$product_info['agent_points'],'pl_desc'=>('店面扫描产品'),'product_name'=>$product_info['product_name'],'shop_name'=>$this->member_info['shop_name']),true);
		}
		echoJson(SUCCESS, "扫描成功，获取".$product_info['shop_points']."积分", array('points'=>$product_info['shop_points'],'product_name'=>$product_info['product_name']), $this->token);
	}



}
