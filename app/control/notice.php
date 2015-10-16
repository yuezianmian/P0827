<?php
/**
 * notice
.*/

defined('InShopNC') or exit('Access Invalid!');
class noticeControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function notice_listOp(){
		$model_notice = Model('notice');
		$condition	= array();
		$condition['create_time'] = array('egt', $this->member_info['create_time']);
		$notice_list = $model_notice->getNoticeList($condition, 'notice_id,notice_title,notice_img,notice_abstract', null);
		echoJson(SUCCESS, '获取系统公告列表成功', $notice_list, $this->token);
	}

	public function notice_infoOp(){
		$model_notice = Model('notice');
		$notice_id = $_POST['notice_id'];
		$condition	= array();
		$condition['notice_id'] = $notice_id;
		$notice_info = $model_notice->getNoticeInfo($condition);
		echoJson(SUCCESS, '获取系统公告详情成功', $notice_info, $this->token);
	}


}
