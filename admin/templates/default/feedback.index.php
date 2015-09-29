<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>反馈记录</h3>
      <ul class="tab-base">

      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="formSearch" id="formSearch">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="act" value="feedback">
    <input type="hidden" name="op" value="feedback">
    <table class="tb-type1 noborder search">
      <tbody>
      <tr>
        <th><label>会员手机号</label></th>
        <td><input type="text" name="search_member_mobile" class="txt" value='<?php echo $_GET['search_member_mobile'];?>'></td>
        <th>申请时间</th>
        <td><input type="text" id="stime" name="stime" class="txt date" value="<?php echo $_GET['stime'];?>" >
          <label>~</label>
          <input type="text" id="etime" name="etime" class="txt date" value="<?php echo $_GET['etime'];?>" ></td>
        
        <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>

      </tr>
      </tbody>
    </table>
  
  <!--  <div style="text-align:right;"><a class="btns" href="javascript:void(0);" id="ncexport"><span>--><?php //echo $lang['nc_export'];?><!--Excel</span></a></div>-->
  <table class="table tb-type2">
    <thead>
    <tr class="thead">
      <th>&nbsp;</th>
      <th>反馈会员手机</th>
      <th>反馈内容</th>
      <th >反馈图片</th>
	  <th >反馈时间</th>
      <th class="align-center">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($output['feedback_list']) && is_array($output['feedback_list'])){ ?>
      <?php foreach($output['feedback_list'] as $k => $v){?>
        <tr class="hover">
		  <td class="w24"><input type="checkbox" name="check_feedback_id[]" value="<?php echo $v['feedback_id'];?>" class="checkitem"></td>
          <td><?php echo $v['member_mobile'];?></td>
          <td  width="705px"><?php echo $v['feedback_content'];?></td>
		  <td >
           <img src="<?php if ($v['feedback_img'] != ''){ echo SITE_URL.$v['feedback_img'];}?>?<?php echo microtime();?>"  onload="javascript:DrawImage(this,50,50);"/>
		  </td>
		  <td><?php echo date('Y-m-d H:i:s', $v['create_time']);?></td>
		  <td class="w96 align-center">
			<a href="javascript:if(confirm('是否确认删除'))window.location='index.php?act=feedback&op=feedback_del&feedback_id=<?php echo $v['feedback_id'];?>';"><?php echo $lang['nc_del'];?></a>
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
		<?php if(!empty($output['feedback_list']) && is_array($output['feedback_list'])){ ?>
        <tr id="batchAction" >
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16" id="dataFuncs"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('是否确认删除')){$('form:first').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
            </td>
        </tr>
        <?php } ?>
    </tr>
    </tfoot>
  </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script language="javascript">
  $(function(){
    $('#stime').datepicker({dateFormat: 'yy-mm-dd'});
    $('#etime').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
      $('input[name="op"]').val('feedback');
	  $('input[name="form_submit"]').val('');
	  $('#formSearch').submit();
    });
  });
</script>
