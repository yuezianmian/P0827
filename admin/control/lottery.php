<?php
/**
 * 抽奖管理
 *
 *
 *
 **@copyright  Copyright (c) 2007-2013 ShopNC Inc.*/

defined('InShopNC') or exit('Access Invalid!');

class lotteryControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
	/**
	 * 抽奖基本信息
	 */
	public function indexOp(){
        $model_lottery	= Model('lottery');
		$activity_id = '1';//活动id
        $awards_list = $model_lottery->getAwardsList($activity_id);
		$participantAmount = $model_lottery->getParticipantAmount($activity_id);
		$winAmount = $model_lottery->getWinAmount($activity_id);
        Tpl::output('awards_list',$awards_list);
		Tpl::output('participantAmount',$participantAmount);
		Tpl::output('winAmount',$winAmount);
        Tpl::showpage('lottery.index');
	}

	/**
	 * 编辑奖项
	 */
	public function add_awardsOp(){
		if (chksubmit()) {

            $params = array();
            $params['awards_name'] = trim($_POST['awards_name']);
            $params['prize_name'] = trim($_POST['prize_name']);
            $params['prize_type'] = $_POST['prize_type'];
            $params['win_rate'] = floatval($_POST['win_rate']);
			$params['prize_amount'] = intval($_POST['prize_amount']);
			$params['create_time'] = time();
			$params['activity_id'] = 1;
			if($params['prize_type'] == 1){ //积分类型奖项，需对应的积分值
				$params['prize_points'] = intval($_POST['prize_points']);
			}


            $model_lottery	= Model('lottery');

            $res = $model_lottery->insertAwards($params, intval($_POST['awards_id']));



            if ($res) {
				$url = array(
					array(
						'url'=>'index.php?act=lottery&op=add_awards',
						'msg'=>'继续添加奖项',
					),
					array(
						'url'=>'index.php?act=lottery&op=index',
						'msg'=>'返回奖项列表',
					)
				);
				$this->log('添加奖项'.'['.$params['awards_name'].']',1);
				showMessage("新增奖项成功",$url,'html','succ',1,5000);
            } else {
                showMessage('新增奖项失败', 'index.php?act=lottery&op=index', '', 'error');
            }

        }
        Tpl::showpage('lottery.awards_add');
	}

	/**
	 * 编辑奖项
	 */
	public function edit_awardsOp(){
		if (chksubmit()) {

			$params = array();
			$params['prize_name'] = trim($_POST['prize_name']);
			$params['win_rate'] = floatval($_POST['win_rate']);
			$params['prize_amount'] = intval($_POST['prize_amount']);


			$model_lottery	= Model('lottery');

			$res = $model_lottery->updateAwards($params, intval($_POST['awards_id']));



			if ($res) {
				showMessage('编辑成功', 'index.php?act=lottery&op=index', '', 'succ');
			} else {
				showMessage('编辑失败', 'index.php?act=lottery&op=index', '', 'error');
			}

		}
		$model_lottery	= Model('lottery');
		$awards_info = $model_lottery->getAwardsInfo(intval($_GET['awards_id']));
		Tpl::output('awards_info',$awards_info[0]);
		Tpl::showpage('lottery.awards_edit');
	}

    /**
     * 中奖列表
     */
    public function win_listOp(){
        $condition_arr = array();
        $condition_arr['is_win'] = 1;
        $condition_arr['is_get'] = trim($_GET['is_get']);
        //分页
        $page	= new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        //查询积分日志列表
        $lottery_model = Model('lottery');
        $win_list = $lottery_model->getParticipantList($condition_arr,$page,'*','');

        Tpl::output('show_page',$page->show());
        if($_GET['is_get'] != ""){
            Tpl::output('is_get', trim($_GET['is_get']));
        }

        Tpl::output('win_list',$win_list);
        Tpl::showpage('lottery.win_list');
    }

    /**
     * 中奖列表
     */
    public function participant_listOp(){
        $condition_arr = array();
        $condition_arr['is_win'] = trim($_GET['is_win']);
        //分页
        $page	= new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        //查询积分日志列表
        $lottery_model = Model('lottery');
        $win_list = $lottery_model->getParticipantList($condition_arr,$page,'*','');

        Tpl::output('show_page',$page->show());
        if($_GET['is_win'] != ""){
            Tpl::output('is_win', trim($_GET['is_win']));
        }

        Tpl::output('participant_list',$win_list);
        Tpl::showpage('lottery.participant_list');
    }

    /**
     * 领奖
     */
    public function get_prizeOp(){
		if (chksubmit()) {
			$params = array();
			$params['id'] = intval($_POST['id']);
			$params['prize_desc'] = trim($_POST['prize_desc']);
			$params['is_get'] = 1;
			$params['get_time'] = time();


			$model_lottery	= Model('lottery');

			$res = $model_lottery->getPrize($params, intval($_POST['id']));

			if ($res) {
				showMessage('核销成功', 'index.php?act=lottery&op=win_list', '', 'succ');
			} else {
				showMessage('核销失败', 'index.php?act=lottery&op=win_list', '', 'error');
			}
		}
		$model_lottery	= Model('lottery');
		$win_info = $model_lottery->getParticipantInfo(intval($_GET['id']));
		Tpl::output('win',$win_info[0]);
		Tpl::showpage('lottery.win_edit');
    }



}
