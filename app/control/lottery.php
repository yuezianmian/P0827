<?php
/**
 * lottery
.*/

defined('InShopNC') or exit('Access Invalid!');
class lotteryControl extends BaseMemberControl {
	public function __construct(){
		parent::__construct();
	}

	public function awards_listOp(){
		$model_lottery = Model('lottery');
		$activity_id = 1;
		$awards_list = $model_lottery->getAwardsList($activity_id);
		echoJson(SUCCESS, '获取奖项列表成功', $awards_list, $this->token);
	}

	public function participateOp(){
		$result = array();
		$member_id  = $this->member_info['member_id'];
		$member_name  = $this->member_info['member_name'];

		$model_lottery	= Model('lottery');
		//获取今日参与抽奖次数,一天只能抽奖一次
		$participate_count = $model_lottery->countParticipateCountToday($member_id);
		if($participate_count >= 1){
			echoJson(10, '今日已参与，不可重复参与');
		}

		$activity_id = 1;
		$awards_list = $model_lottery->getAwardsList($activity_id);

		$range=1000000;
		$end = 0;
		//计算每个奖项的取值范围（1 - 1000000）
		for($i = 0; $i < count($awards_list); $i++){
			$end = bcmul($awards_list[$i]["win_rate"],$range) + $end;
			$awards_list[$i]["end"] = $end;
		}
		$random = rand(1, $range);
		//判断是否在范围内
		for($i = 0; $i < count($awards_list); $i++){
			$end = $awards_list[$i]["end"];
			if($random <= $end ){ //中奖了
				if($awards_list[$i]["prize_amount"] > $awards_list[$i]["win_amount"]){
					$winAwardsId = $awards_list[$i]["awards_id"];
					$winAwardsName = $awards_list[$i]["awards_name"];
					$winPrizeName = $awards_list[$i]["prize_name"];
				}
				break;
			}
		}


		if(isset($winAwardsId)){
			$params['is_win'] = 1;
			$params['awards_id'] = $winAwardsId;
			$params['awards_name'] = $winAwardsName;
			$params['prize_name'] = $winPrizeName;
		}else{
			$params['is_win'] = 0;
		}

		$params['member_id'] = $member_id;
		$params['member_name'] = $member_name;
		$params['participant_time'] = date("Y-m-d H:i:s");;
		$params['activity_id'] = 1;
		$params['is_get'] = 0;

		$participant_id = $model_lottery->insertParticipant($params);

		if(isset($winAwardsId)){ //如果中奖，则修改奖项对于的已中奖数量
			$input = array();
			$input["win_amount"] = array('sign'=>'increase', 'value'=>1);
			$model_lottery->updateAwards($input,$winAwardsId);
		}


		$points_model = Model('points');
		$points_model->savePointsLog('other',array('pl_memberid'=>$member_id,'pl_membername'=>$member_name,'pl_points'=>intval(20/10),'pl_desc'=>('抽奖送积分，抽奖号：'.$participant_id)),true);

		if(isset($winAwardsId)){ //中奖
			$result["is_win"] = 1;
			$result["awards_name"] = $winAwardsName;
			$result['prize_name'] = $winPrizeName;
		}else{
			$result["is_win"] = 0;
		}
		$result["code"] = 200;
//		echo json_encode($result);
		echoJson(SUCCESS, '获取奖项列表成功', $result, $this->token);
	}



}
