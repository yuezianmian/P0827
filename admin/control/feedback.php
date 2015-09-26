<?php
/**
 * 用户反馈管理
 *
 *
 */

defined('InShopNC') or exit('Access Invalid!');

class feedbackControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 用户反馈列表
	 */
	public function feedbackOp(){
		$condition_arr = array();
		$condition_arr['member_mobile_like'] = trim($_GET['search_member_mobile']);
		if ($_GET['search_cash_state']){
			$condition_arr['cash_state'] = trim($_GET['search_cash_state']);
		}
		$condition_arr['saddtime'] = strtotime($_GET['stime']);
		$condition_arr['eaddtime'] = strtotime($_GET['etime']);
        if($condition_arr['eaddtime'] > 0) {
            $condition_arr['eaddtime'] += 86400;
        }
		//分页
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		//查询积分日志列表
		$model_feedback = Model('feedback');
		$feedback_list = $model_feedback->getExtractCashList($condition_arr,$page,'*');
		//信息输出
		Tpl::output('show_page',$page->show());
		Tpl::output('feedback_list',$feedback_list);
		Tpl::showpage('feedback.index');
	}
	


	
}
