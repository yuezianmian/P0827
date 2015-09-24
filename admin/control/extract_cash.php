<?php
/**
 * 提现管理
 *
 *
 */

defined('InShopNC') or exit('Access Invalid!');

class extract_cashControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 提现列表
	 */
	public function extract_cashOp(){
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
		$model_extract_cash = Model('extract_cash');
		$extract_cash_list = $model_extract_cash->getExtractCashList($condition_arr,$page,'*');
		//信息输出
		Tpl::output('show_page',$page->show());
		Tpl::output('extract_cash_list',$extract_cash_list);
		Tpl::showpage('extract_cash.index');
	}
	
	/**
	 * 修改状态为已提现
	 */
	public function cashedOp(){
		$lang = Language::getLangContent();
		$model_extract_cash = Model('extract_cash');
		$result = $model_extract_cash->editExtractCash(array('cash_state'=>2,'update_time'=>time()),array('cash_id'=>intval($_GET['cash_id'])));
		if ($result){
			$url = array(
				array(
					'url'=>'index.php?act=extract_cash&op=extract_cash',
					'msg'=>'返回提现详细列表',
				),
			);
			$this->log('核销提现申请'.'[ID:'.$_GET['cash_id'].']',1);
			showMessage("核销成功",$url);
		}else {
			showMessage("核销失败");
		}
	}

	
}
