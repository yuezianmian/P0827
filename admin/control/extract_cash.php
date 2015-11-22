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
		$extract_cash_list = $model_extract_cash->getExtractCashListWithMemberInfo($condition_arr,$page);
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

	/**
	 * 导出提现列表
	 */
	public function export_cash_listOp(){
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
		//查询
		$model_extract_cash = Model('extract_cash');
		$extract_cash_list = $model_extract_cash->getExtractCashListWithMemberInfo($condition_arr);
		$this->createExcel($extract_cash_list);
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'手机号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'支付宝');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'银行卡号');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'开户姓名');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'银行名称');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'开户网点');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'提现积分');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'申请时间');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'状态');
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>$v['member_mobile_true']);
			$tmp[] = array('data'=>$v['alipay_number']);
			$tmp[] = array('data'=>$v['bank_number']);
			$tmp[] = array('data'=>$v['bank_username']);
			$tmp[] = array('data'=>$v['bank_name']);
			$tmp[] = array('data'=>$v['bank_branch']);
			$tmp[] = array('data'=>$v['cash_points']);
			$tmp[] = array('data'=>date('Y-m-d H:i',$v['create_time']));
			$tmp[] = array('data'=>($v['is_get']==1?'申请中' : '已提现'));
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset('提现列表',CHARSET));
		$excel_obj->generateXML($excel_obj->charset('提现列表',CHARSET).'-'.date('Y-m-d-H',time()));
	}

	
}
