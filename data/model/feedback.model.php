<?php
/**
 * 用户反馈模型
 *
 */
defined('InShopNC') or exit('Access Invalid!');
class feedbackModel extends Model {

    public function __construct(){
        parent::__construct('feedback');
    }

	
	/**
	 * 取用户反馈列表
	 * @param unknown $condition
	 * @param string $pagesize
	 * @param string $order
	 */
	public function getFeedbackList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'feedback';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'feedback.feedback_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}

	/**
	 * 用户反馈总数
	 */
	public function countFeedback($condition) {
		$cash_list	= $this->field('count(1) as countnum')->where($condition)->select();
		return $cash_list[0]['countnum'];
	}

	/**
	 * 取得单条信息
	 * @param unknown $condition
	 */
	public function getFeedbackInfo($condition = array()) {
		return $this->where($condition)->find();
	}

	/**
	 * 删除用户反馈
	 * @param unknown $condition
	 */
	public function delFeedback($condition = array()) {
		return $this->where($condition)->delete();
	}

	/**
	 * 增加用户反馈
	 * @param unknown $data
	 * @return boolean
	 */
	public function addFeedback($data) {
		return $this->insert($data);
	}

	/**
	 * 更新用户反馈
	 * @param unknown $data
	 * @param unknown $condition
	 */
	public function editFeedback($data = array(),$condition = array()) {
		return $this->where($condition)->update($data);
	}
	
	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $condition_array
	 * @return	string
	 */
	private function getCondition($condition_array){
		$condition_sql = '';
		
		//会员手机号
		if ($condition_array['member_mobile_like']) {
			$condition_sql	.= " and `feedback`.member_mobile like '%{$condition_array['member_mobile_like']}%'";
		}
		//申请时间
		if ($condition_array['saddtime']){
			$condition_sql	.= " and `feedback`.create_time >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and `feedback`.create_time <= '{$condition_array['eaddtime']}'";
		}
		return $condition_sql;
	}
}
