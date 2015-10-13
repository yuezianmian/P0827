<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>提现记录</h3>
      <ul class="tab-base">

      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="extract_cash">
    <input type="hidden" name="op" value="extract_cash">
    <table class="tb-type1 noborder search">
      <tbody>
      <tr>
        <th><label>用户名</label></th>
        <td><input type="text" name="search_member_mobile" class="txt" value='<?php echo $_GET['search_member_mobile'];?>'></td>
        <th>申请时间</th>
        <td><input type="text" id="stime" name="stime" class="txt date" value="<?php echo $_GET['stime'];?>" >
          <label>~</label>
          <input type="text" id="etime" name="etime" class="txt date" value="<?php echo $_GET['etime'];?>" ></td>
        <td><select name="search_cash_state">
            <option value="" <?php if (!$_GET['search_cash_state']){echo 'selected=selected';}?>>状态</option>
            <option value="1" <?php if ($_GET['search_cash_state'] == 1){echo 'selected=selected';}?>>申请中</option>
            <option value="2" <?php if ($_GET['search_cash_state'] == 2){echo 'selected=selected';}?>>已提现</option>
          </select>
		</td>
        <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>

      </tr>
      </tbody>
    </table>
  </form>
  <!--  <div style="text-align:right;"><a class="btns" href="javascript:void(0);" id="ncexport"><span>--><?php //echo $lang['nc_export'];?><!--Excel</span></a></div>-->
  <table class="table tb-type2">
    <thead>
    <tr class="thead">
      <th>会员用户名</th>
      <th class="align-center">提现积分</th>
      <th class="align-center">申请时间</th>
      <th class="align-center">状态</th>
      <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($output['extract_cash_list']) && is_array($output['extract_cash_list'])){ ?>
      <?php foreach($output['extract_cash_list'] as $k => $v){?>
        <tr class="hover">
          <td><?php echo $v['member_mobile'];?></td>
          <td class="align-center"><?php echo $v['cash_points'];?></td>
          <td class="nowrap align-center"><?php echo @date('Y-m-d H:i:s',$v['create_time']);?></td>
          <td class="align-center"><?php
            switch ($v['cash_state']){
              case '1':
                echo '申请中';
                break;
              case '2':
                echo '已提现';
                break;
             
            }?></td>
          <td class="w84">
            <span>
			<?php if($v['cash_state'] == 1){ ?>
              <a href="index.php?act=extract_cash&op=cashed&cash_id=<?php echo $v['cash_id'];?>">核销</a> 
		   <?php } ?>
  		    </span>
          </td>
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
      $('input[name="op"]').val('extract_cash');$('#formSearch').submit();
    });
  });
</script>
