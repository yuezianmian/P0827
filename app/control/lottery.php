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

	public function win_listOp(){
		$model_lottery = Model('lottery');
		$condition	= array();
		$condition["is_win"] = 1;
		$condition['member_id'] = $this->member_info['member_id'];
		$win_list = $model_lottery->getParticipantList($condition, '' , 'id,participant_time,awards_name,is_get,prize_name,prize_desc');
		$result = array();
		$result['win_list'] = $win_list;
//		$result['member_points'] = $this->member_info['member_points'];
//		$result['total_points'] = $this->member_info['total_points'];
		echoJson(SUCCESS, '获取中奖列表成功', $result, $this->token);
	}

	public function participateOp(){
		$result = array();
		$member_id  = $this->member_info['member_id'];
		$member_mobile  = $this->member_info['member_mobile'];

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
					$winPrizeType = $awards_list[$i]["prize_type"];
					if($winPrizeType == 1){ //如果中取的奖项是积分，则获取对应的积分值
						$winPrizePoints = $awards_list[$i]["prize_points"];
					}
				}
				break;
			}
		}


		if(isset($winAwardsId)){
			$params['is_win'] = 1;
			$params['awards_id'] = $winAwardsId;
			$params['awards_name'] = $winAwardsName;
			$params['prize_name'] = $winPrizeName;
			$params['prize_type'] = $winPrizeType;
			$params['is_get'] = 0;
			if($winPrizeType == 1){ //如果中取的奖项是积分，则获取对应的积分值
				$params['prize_points'] = $winPrizePoints;
				$params['is_get'] = 1;
				$params['get_time'] = time();
			}
		}else{
			$params['is_win'] = 0;
		}

		$params['member_id'] = $member_id;
		$params['member_mobile'] = $member_mobile;
		$params['participant_time'] = time();
		$params['activity_id'] = 1;

		$participant_id = $model_lottery->insertParticipant($params);

		if(isset($winAwardsId)){ //如果中奖，则修改奖项对应的已中奖数量
			$input = array();
			$input["win_amount"] = array('sign'=>'increase', 'value'=>1);
			$model_lottery->updateAwards($input,$winAwardsId);

			if($winPrizeType == 1){ //如果中取的奖项是积分,则直接增加用户的积分记录
				$points_model = Model('points');
				$points_model->savePointsLog('win_prize',array('pl_memberid'=>$member_id,'pl_membermobile'=>$member_mobile,'pl_points'=>$winPrizePoints,'pl_desc'=>'抽中'.$winAwardsName),true);
			}
		}

		if(isset($winAwardsId)){ //中奖
			$result["is_win"] = 1;
			$result["awards_name"] = $winAwardsName;
			$result['prize_name'] = $winPrizeName;
		}else{
			$result["is_win"] = 0;
		}
		echoJson(SUCCESS, '参与成功', $result, $this->token);
	}



}
