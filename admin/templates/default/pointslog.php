<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>积分明细</h3>
      <ul class="tab-base">
<!--        <li><a href="index.php?act=points&op=addpoints"><span>--><?php ///*echo $lang['nc_manage']*/?><!--</span></a></li>-->
<!--        <li><a href="JavaScript:void(0);" class="current"><span>--><?php ///*echo $lang['admin_points_log_title'];*/?><!--</span></a></li>-->
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="points">
    <input type="hidden" name="op" value="pointslog">
    <table class="tb-type1 noborder search">
      <tbody>
      <tr>
        <th><label>会员手机号</label></th>
        <td><input type="text" name="mmobile" class="txt" value='<?php echo $_GET['mmobile'];?>'></td>
        <th>添加时间</th>
        <td><input type="text" id="stime" name="stime" class="txt date" value="<?php echo $_GET['stime'];?>" >
          <label>~</label>
          <input type="text" id="etime" name="etime" class="txt date" value="<?php echo $_GET['etime'];?>" ></td>
        <td><select name="stage">
            <option value="" <?php if (!$_GET['stage']){echo 'selected=selected';}?>>操作阶段</option>
            <option value="recomm_regist" <?php if ($_GET['stage'] == 'recomm_regist'){echo 'selected=selected';}?>>推荐会员注册</option>
            <option value="recommed_regist" <?php if ($_GET['stage'] == 'recommed_regist'){echo 'selected=selected';}?>>被推荐注册</option>
            <option value="scan_qrcode" <?php if ($_GET['stage'] == 'scan_qrcode'){echo 'selected=selected';}?>>扫描产品</option>
            <option value="shop_scan_qrcode" <?php if ($_GET['stage'] == 'shop_scan_qrcode'){echo 'selected=selected';}?>>店面扫描产品</option>
            <option value="extract_cash" <?php if ($_GET['stage'] == 'extract_cash'){echo 'selected=selected';}?>>提现</option>
            <option value="sign" <?php if ($_GET['stage'] == 'sign'){echo 'selected=selected';}?>>签到</option>
            <option value="pointorder" <?php if ($_GET['stage'] == 'pointorder'){echo 'selected=selected';}?>>兑换礼品</option>
            <option value="pointorder" <?php if ($_GET['stage'] == 'win_prize'){echo 'selected=selected';}?>>抽奖</option>
          </select></td>
        <!--        </tr>-->
        <!--        <tr>-->

        <th>描述</th>
        <td><input type="text" id="description" name="description" class="txt2" value="<?php echo $_GET['description'];?>" ></td>
        <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>

      </tr>
      </tbody>
    </table>
  </form>
  <!--  <div style="text-align:right;"><a class="btns" href="javascript:void(0);" id="ncexport"><span>--><?php //echo $lang['nc_export'];?><!--Excel</span></a></div>-->
  <table class="table tb-type2">
    <thead>
    <tr class="thead">
      <th>会员手机号</th>
      <th class="align-center">积分值</th>
      <th class="align-center">添加时间</th>
      <th class="align-center">操作阶段</th>
      <th>描述</th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($output['list_log']) && is_array($output['list_log'])){ ?>
      <?php foreach($output['list_log'] as $k => $v){?>
        <tr class="hover">
          <td><?php echo $v['pl_membermobile'];?></td>
          <td class="align-center"><?php echo $v['pl_points'];?></td>
          <td class="nowrap align-center"><?php echo @date('Y-m-d H:i:s',$v['pl_addtime']);?></td>
          <td class="align-center"><?php
            switch ($v['pl_stage']){
              case 'recomm_regist':
                echo '推荐会员注册';
                break;
              case 'recommed_regist':
                echo '被推荐注册';
                break;
              case 'scan_qrcode':
                echo '扫描产品';
                break;
              case 'shop_scan_qrcode':
                echo '店面扫描产品';
                break;
              case 'extract_cash':
                echo '提现';
                break;
              case 'sign':
                echo '签到';
                break;
              case 'pointorder':
                echo '兑换礼品';
                break;
              case 'win_prize':
                echo '抽奖';
                break;
            }?></td>
          <td><?php echo $v['pl_desc'];?></td>
        </tr>
      <?php } ?>
    <?php }else { ?>
      <tr class="no_data">
        <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
      </tr>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr class="tfoot">
      <td colspan="15"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
    </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script language="javascript">
  $(function(){
    $('#stime').datepicker({dateFormat: 'yy-mm-dd'});
    $('#etime').datepicker({dateFormat: 'yy-mm-dd'});
//    $('#ncexport').click(function(){
//    	$('input[name="op"]').val('export_step1');
//    	$('#formSearch').submit();
//    });
    $('#ncsubmit').click(function(){
      $('input[name="op"]').val('pointslog');$('#formSearch').submit();
    });
  });
</script>
