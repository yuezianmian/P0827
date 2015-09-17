<?php
/**
 * 消息
 *
.*/

defined('InShopNC') or exit('Access Invalid!');
class messageControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function message_logOp(){
		$member_id = $this->member_info['member_id'];
		$page_size = $_POST['page_size'] ? $_POST['page_size'] : 10;
		$page_index = $_POST['page_index'] ? $_POST['page_index'] : 1;
		$message_state = $_POST['message_state'];
		$start = $page_size * ($page_index - 1);
		$model_message = Model('message');
		$condition	= array();
		$condition['limit'] = $start.','.$page_size;
		$condition['to_member_id'] = $member_id;
		if($message_state){
			$condition['message_state'] = $message_state;
		}
		$message_list = $model_message->listMessage($condition);
		//获取总数
		$condition	= array();
		$condition['to_member_id'] = $member_id;
		if($message_state){
			$condition['message_state'] = $message_state;
		}
		$message_amount = $model_message->countMessage($condition);
		$total_page = ($message_amount%$page_size==0)?intval($message_amount/$page_size):(intval($message_amount/$page_size)+1);
		$return_data = array();
		$return_data['message_list'] = $message_list;
		$return_data['total_page'] = $total_page;
		$return_data['amount'] = $message_amount;
		echoJson(SUCCESS, '获取会员的消息列表成功', $return_data, $this->token);
	}

}
