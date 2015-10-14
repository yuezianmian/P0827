<?php
/**
 * 会员模型
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc.
 */
defined('InShopNC') or exit('Access Invalid!');
class memberModel extends Model {

    public function __construct(){
        parent::__construct('member');
    }

    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false) {
        return $this->table('member')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberInfoByID($member_id, $fields = '*') {
        $member_info = rcache($member_id, 'member', $fields);
        if (empty($member_info)) {
            $member_info = $this->getMemberInfo(array('member_id'=>$member_id),'*',true);
            wcache($member_id, $member_info, 'member');
        }
        return $member_info;
    }

    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMemberList($condition = array(), $field = '*', $page = 0, $order = 'member_id desc', $limit = '') {
       return $this->table('member')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    /**
     * 会员数量
     * @param array $condition
     * @return int
     */
    public function getMemberCount($condition) {
        return $this->table('member')->where($condition)->count();
    }

    /**
     * 编辑会员
     * @param array $condition
     * @param array $data
     */
    public function editMember($condition, $data) {
        $update = $this->table('member')->where($condition)->update($data);
        if ($update && $condition['member_id']) {
            dcache($condition['member_id'], 'member');
        }
        return $update;
    }

    /**
     * 注册
     */
    public function register($register_info) {
        // 验证用户手机号是否重复
		$check_member_mobile	= $this->getMemberInfo(array('member_mobile_true'=>$register_info['member_mobile_true']));
		if(is_array($check_member_mobile) and count($check_member_mobile) > 0) {
           echoJson(FAILED, "该手机号已存在");
		}

		// 校验其所属的代理商编号是否存在，如果不存在则值为空
		if($register_info['parent_code']){
			$check_parent_code	= $this->getMemberInfo(array('member_code'=>$register_info['parent_code']));
			if(!is_array($check_parent_code) ||  count($check_parent_code) < 1) {
				$register_info['parent_code'] = null;
			}
		}


		// 会员添加
		$member_info	= array();
//		$member_info['member_name']		= $register_info['member_name'];
		$member_info['member_mobile'] = $register_info['member_mobile'];
		$member_info['member_mobile_true'] = $register_info['member_mobile_true'];
		$member_info['member_passwd'] = $register_info['member_passwd'];
		$member_info['parent_code'] = $register_info['parent_code'];
		$member_info['member_type'] = MEMBER_TYPE_STORE;
		$member_info['member_state'] = MEMBER_STATE_REGISTED;
		if(!empty($register_info['recomm_member_id'])){
			$member_info['recomm_member_id'] = $register_info['recomm_member_id'];
		}
		$insert_id	= $this->addMember($member_info);
		return $insert_id;

    }

	/**
	 * 注册商城会员
	 *
	 * @param	array $param 会员信息
	 * @return	array 数组格式的返回结果
	 */
	public function addMember($param) {
		if(empty($param)) {
			return false;
		}
		try {
		    $this->beginTransaction();
		    $member_info	= array();
		    $member_info['member_id']			= $param['member_id'];
		    $member_info['member_name']			= $param['member_name'];
		    $member_info['member_passwd']		= md5(trim($param['member_passwd']));
		    $member_info['member_mobile']		= $param['member_mobile'];
		    $member_info['member_mobile_true']		= $param['member_mobile_true'];
		    $member_info['member_state']		= $param['member_state'];
		    $member_info['member_type']		    = $param['member_type'];
		    $member_info['parent_code']		    = $param['parent_code'];
		    $member_info['member_code']		    = $param['member_code'];
			$member_info['area_id']		        = $param['area_id'];
			$member_info['area_name']		    = $param['area_name'];
		    $member_info['create_time']			= TIMESTAMP;

		    $insert_id	= $this->table('member')->insert($member_info);
		    if (!$insert_id) {
		        throw new Exception();
		    }
		    $this->commit();
		    return $insert_id;
		} catch (Exception $e) {
		    $this->rollback();
		    return false;
		}
	}

	/**
	 * 会员登录检查
	 *
	 */
	public function checkloginMember() {
		if($_SESSION['is_login'] == '1') {
			@header("Location: index.php");
			exit();
		}
	}


	/**
	 * 获取代理商下属搜有店面列表及本月单数
	 * @param string $parent_code
	 * @param string $month 201509
	 * @return int
	 */
	public function getShopListWithOrderAmount($parent_code, $month) {
		$sql = "SELECT a.member_id,a.member_mobile,a.member_code,a.shop_name,a.create_time, IFNULL(c.amount,0) monthAmount "
		        ."FROM sysc_member a "
				."LEFT JOIN ("
				."SELECT COUNT(1) amount,b.member_id "
				."FROM sysc_qrcode_record b "
				."WHERE FROM_UNIXTIME(b.create_time,'%Y%m') = '$month' "
				."GROUP BY b.member_id) c ON a.member_id=c.member_id "
				."WHERE a.parent_code='$parent_code' AND a.member_state=".MEMBER_STATE_NORMAL
				." ORDER BY a.member_id DESC";
		return Db::getAll($sql);
	}


	/**
	 * 添加会员积分
	 * @param unknown $member_info
	 */
	public function addPoint($member_info) {
	    if (!C('points_isuse') || empty($member_info)) return;
	
	    //一天内只有第一次登录赠送积分
	    if(trim(@date('Y-m-d',$member_info['member_login_time'])) == trim(date('Y-m-d'))) return;

	    //加入队列
	    $queue_content = array();
	    $queue_content['member_id'] = $member_info['member_id'];
	    $queue_content['member_name'] = $member_info['member_name'];
	    QueueClient::push('addPoint',$queue_content);
	}


	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $conditon_array
	 * @return	string
	 */
	private function getCondition($conditon_array){
		$condition_sql = '';
		if($conditon_array['member_id'] != '') {
			$condition_sql	.= " and member_id= '" .intval($conditon_array['member_id']). "'";
		}
		if($conditon_array['member_name'] != '') {
			$condition_sql	.= " and member_name='".$conditon_array['member_name']."'";
		}
        if($conditon_array['member_identity'] != '') {
            $condition_sql	.= " and member_identity='".$conditon_array['member_identity']."'";
        }
		if($conditon_array['member_passwd'] != '') {
			$condition_sql	.= " and member_passwd='".$conditon_array['member_passwd']."'";
		}
		//是否允许举报
		if($conditon_array['inform_allow'] != '') {
			$condition_sql	.= " and inform_allow='{$conditon_array['inform_allow']}'";
		}
		//是否允许购买
		if($conditon_array['is_buy'] != '') {
			$condition_sql	.= " and is_buy='{$conditon_array['is_buy']}'";
		}
		//是否允许发言
		if($conditon_array['is_allowtalk'] != '') {
			$condition_sql	.= " and is_allowtalk='{$conditon_array['is_allowtalk']}'";
		}
		//是否允许登录
		if($conditon_array['member_state'] != '') {
			$condition_sql	.= " and member_state='{$conditon_array['member_state']}'";
		}
		if($conditon_array['friend_list'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['friend_list'].")";
		}
		if($conditon_array['member_email'] != '') {
			$condition_sql	.= " and member_email='".$conditon_array['member_email']."'";
		}
		if($conditon_array['no_member_id'] != '') {
			$condition_sql	.= " and member_id != '".$conditon_array['no_member_id']."'";
		}
		if($conditon_array['like_member_name'] != '') {
			$condition_sql	.= " and member_name like '%".$conditon_array['like_member_name']."%'";
		}
		if($conditon_array['like_member_email'] != '') {
			$condition_sql	.= " and member_email like '%".$conditon_array['like_member_email']."%'";
		}
		if($conditon_array['like_member_truename'] != '') {
			$condition_sql	.= " and member_truename like '%".$conditon_array['like_member_truename']."%'";
		}
		if($conditon_array['in_member_id'] != '') {
			$condition_sql	.= " and member_id IN (".$conditon_array['in_member_id'].")";
		}
		if($conditon_array['in_member_name'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['in_member_name'].")";
		}
		if($conditon_array['member_qqopenid'] != '') {
			$condition_sql	.= " and member_qqopenid = '{$conditon_array['member_qqopenid']}'";
		}
		if($conditon_array['member_sinaopenid'] != '') {
			$condition_sql	.= " and member_sinaopenid = '{$conditon_array['member_sinaopenid']}'";
		}
		
		return $condition_sql;
	}

	/**
	 * 获得某一会员等级
	 * @param int $exppoints
	 * @param bool $show_progress 是否计算其当前等级进度
	 * @param array $member_grade 会员等级
	 */
	public function getOneMemberGrade($exppoints,$show_progress = false,$member_grade = array()){
	    if (!$member_grade){
	        $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
	    }
	    if (empty($member_grade)){//如果会员等级设置为空
	        $grade_arr['level'] = -1;
	        $grade_arr['level_name'] = '暂无等级';
	        return $grade_arr;
	    }
	    
	    $exppoints = intval($exppoints);
	    
	    $grade_arr = array();
	    if ($member_grade){
		    foreach ($member_grade as $k=>$v){
		        if($exppoints >= $v['exppoints']){
		            $grade_arr = $v;
		        }
			}
		}
		//计算提升进度
		if ($show_progress == true){
		    if (intval($grade_arr['level']) >= (count($member_grade) - 1)){//如果已达到顶级会员
		        $grade_arr['downgrade'] = $grade_arr['level'] - 1;//下一级会员等级
		        $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
		        $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
		        $grade_arr['upgrade'] = $grade_arr['level'];//上一级会员等级
		        $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
		        $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
		        $grade_arr['less_exppoints'] = 0;
		        $grade_arr['exppoints_rate'] = 100;
		    } else {
		        $grade_arr['downgrade'] = $grade_arr['level'];//下一级会员等级
		        $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
		        $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
		        $grade_arr['upgrade'] = $member_grade[$grade_arr['level']+1]['level'];//上一级会员等级
		        $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
		        $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
		        $grade_arr['less_exppoints'] = $grade_arr['upgrade_exppoints'] - $exppoints;
		        $grade_arr['exppoints_rate'] = round(($exppoints - $member_grade[$grade_arr['level']]['exppoints'])/($grade_arr['upgrade_exppoints'] - $member_grade[$grade_arr['level']]['exppoints'])*100,2);
		    }
		}
		return $grade_arr;
	}
}
