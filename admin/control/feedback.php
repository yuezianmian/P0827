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
		$lang	= Language::getLangContent();
		$model_feedback = Model('feedback');
		//删除
		if (chksubmit()){
			if (!empty($_POST['check_feedback_id']) && is_array($_POST['check_feedback_id']) ){
			    $result = $model_feedback->delFeedback(array('feedback_id'=>array('in',$_POST['check_feedback_id'])));
				if ($result) {
			        $this->log('删除feedback'.'[ID:'.implode(',',$_POST['check_feedback_id']).']',1);
				    showMessage("删除成功");
				}
			}
		    showMessage($lang['nc_common_del_fail']);
		}
		$condition_arr = array();
		$condition_arr['member_mobile_like'] = trim($_GET['search_member_mobile']);
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
		
		$feedback_list = $model_feedback->getFeedbackList($condition_arr,$page,'*');
		//信息输出
		Tpl::output('show_page',$page->show());
		Tpl::output('feedback_list',$feedback_list);
		Tpl::showpage('feedback.index');
	}
	
	/**
	 * 删除用户反馈
	 */
	public function feedback_delOp(){
		$lang	= Language::getLangContent();
		$model_feedback = Model('feedback');
		if (intval($_GET['feedback_id']) > 0){
			$result = $model_feedback->delFeedback(array('feedback_id'=>intval($_GET['feedback_id'])));
			if ($result) {
			     $this->log('删除Banner'.'[ID:'.$_GET['feedback_id'].']',1);
			     showMessage($lang['nc_common_del_succ'],getReferer());
			}
		}
		showMessage($lang['nc_common_del_fail'],'index.php?act=feedback&op=feedback');
	}

	
}
