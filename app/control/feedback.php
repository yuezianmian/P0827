<?php
/**
 * 用户反馈
 *
.*/

defined('InShopNC') or exit('Access Invalid!');
class feedbackControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}


	public function add_feedbackOp(){
		$feedback_content = $_POST['feedback_content'];
		$feedback_img = $_POST['feedback_img'];
		if(empty($feedback_content)){
			echoJson(FAILED, '反馈内容不能为空');
		}
		if(empty($feedback_img)){
			echoJson(FAILED, '反馈的图片不能为空');
		}
		$member_id = $this->member_info['member_id'];
		$member_mobile = $this->member_info['member_mobile'];
		$feedback_model = Model('feedback');
		$insert_arr	= array();
		$insert_arr['member_id'] = $member_id;
		$insert_arr['member_mobile'] = $member_mobile;
		$insert_arr['feedback_content'] = $feedback_content;
		$insert_arr['feedback_img'] = $feedback_img;
		$insert_arr['create_time'] = time();
		$feedback_id = $feedback_model->addFeedback($insert_arr);
		if($feedback_id){
			echoJson(SUCCESS, '提交反馈信息成功', array('feedback_id'=>$feedback_id), $this->token);
		}else{
			echoJson(FAILED, '提交反馈信息失败');
		}
	}



}
