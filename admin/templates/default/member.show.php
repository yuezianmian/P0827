<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['member_index_manage']?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=member&op=member" ><span><?php echo $lang['nc_manage']?></span></a></li>
        <li><a href="index.php?act=member&op=member_add" ><span>新增代理</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>查看</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>用户名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['member_array']['member_mobile'];?></td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="required"><label>手机号:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['member_array']['member_mobile_true'];?></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>姓名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['member_array']['member_truename'];?></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>会员类型:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <?php if($output['member_array']['member_type'] == 1){ ?>
                    <?php echo "代理商"; ?>
                <?php }else if($output['member_array']['member_type'] == 2) { ?>
                    <?php echo "店铺"; ?>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>可用积分:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                可用积分 <strong class="red"><?php echo $output['member_array']['member_points']; ?></strong>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>性别:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <?php if($output['member_array']['member_sex'] == 0){ ?>
                    <?php echo "保密"; ?>
                <?php }else if($output['member_array']['member_sex'] == 1) { ?>
                    <?php echo "男"; ?>
                <?php }else if($output['member_array']['member_sex'] == 2) { ?>
                    <?php echo "女"; ?>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>生日:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['member_array']['member_birthday'];?></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>创建时间:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
             <?php echo $output['member_array']['create_time'] ? date('Y-m-d H:i:s',$output['member_array']['create_time']) : '';?>
            </td>
        </tr>
        <?php if($output['member_array']['member_type'] == MEMBER_TYPE_AGENT){ ?>
            <tr>
                <td colspan="2" class="required"><label>代理商编号:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><?php echo $output['member_array']['member_code'];?></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label>所属区域:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><?php echo $output['member_array']['area_name'];?></td>
            </tr>
        <?php }else if($output['member_array']['member_type'] == MEMBER_TYPE_STORE) { ?>
            <tr>
                <td colspan="2" class="required"><label>所属代理商编号:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><?php echo $output['member_array']['parent_code'];?></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label>店铺名称:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><?php echo $output['member_array']['shop_name'];?></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label>店铺照片:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php if($output['member_array']['shop_img']){ ?>
                        <img src="<?php  echo SITE_URL.$output['member_array']['shop_img'];?>"  onload="javascript:DrawImage(this,44,44);"/>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label>店铺区域:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><?php echo $output['member_array']['area_name'];?></td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label>店铺地址:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><?php echo $output['member_array']['shop_address'];?></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="2" class="required"><label>支付宝:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['member_array']['alipay_number'];?></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>银行卡号:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['member_array']['bank_number'];?></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>开户姓名:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['member_array']['bank_username'];?></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>银行名称:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['member_array']['bank_name'];?></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>开户网点:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php echo $output['member_array']['bank_branch'];?></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label>状态:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <?php if($output['member_array']['member_state'] == MEMBER_STATE_REGISTED){ ?>
                    <?php echo "已注册"; ?>
                <?php }else if($output['member_array']['member_state'] == MEMBER_STATE_NOCHECK) { ?>
                    <?php echo "待审核"; ?>
                <?php }else if($output['member_array']['member_state'] == MEMBER_STATE_NORMAL) { ?>
                    <?php echo "正常"; ?>
                <?php }else if($output['member_array']['member_state'] == MEMBER_STATE_NOPASS) { ?>
                    <?php echo "失效"; ?>
                <?php } ?>
            </td>
        </tr>
      </tbody>
      <?php if($output['member_array']['member_state'] == MEMBER_STATE_NOCHECK){ ?>
          <tfoot>
            <tr class="tfoot">
                <td colspan="15">
                    <a class="btn" href="index.php?act=member&op=pass&member_id=<?php echo $output['member_array']['member_id']; ?>"><span>通过</span></a>
                    <a class="btn" href="index.php?act=member&op=nopass&member_id=<?php echo $output['member_array']['member_id']; ?>"><span>拒绝</span></a>
                </td>
            </tr>
          </tfoot>
      <?php } ?>
    </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">

</script> 
