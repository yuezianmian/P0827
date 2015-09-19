<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>扫描记录</h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="qrcode_record" name="act">
    <input type="hidden" value="record_list" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td><select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'member_mobile'){ ?>selected='selected'<?php } ?> value="member_mobile">手机号</option>
            </select></td>
          <td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
<!--            <td>产品名称</td>-->
          <td> <select name="search_product_id" id="search_product_id">
                  <option value="" <?php if ($output['search_product_id'] == ""){echo 'selected=true';}?>>产品名称</option>
                  <?php if (!empty($output['product_list'])){?>
                      <?php foreach ($output['product_list'] as $k=>$v){?>
                          <option value="<?php echo $v['product_id'];?>" <?php if ($output['search_product_id'] == $v['product_id']){echo 'selected=true';}?>> <?php echo $v['product_name'];?> </option>
                      <?php }?>
                  <?php }?>
              </select></td>
          <td></td>

          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
        </tr>
      </tbody>
    </table>
  </form>
  <form method="post" id="form_qrcode_record">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th class="align-center">会员手机号</th>
          <th class="align-center">扫描的产品</th>
          <th class="align-center">二维码编码</th>
          <th class="align-center">扫描时间</th>
        </tr>
      <tbody>
        <?php if(!empty($output['qrcode_record_list']) && is_array($output['qrcode_record_list'])){ ?>
        <?php foreach($output['qrcode_record_list'] as $k => $v){ ?>
        <tr class="hover member">
          <td class="align-center"><?php echo $v['member_mobile'];?></td>
          <td class="align-center"><?php echo $v['product_name'];?></td>
          <td class="align-center"><?php echo $v['qrcode'];?></td>
          <td class="align-center"><?php echo $v['create_time'];?></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"><?php echo $lang['nc_no_record']?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot class="tfoot">
        <?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
        <tr>
          <td colspan="16">
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('record_list');$('#formSearch').submit();
    });	
});
</script>
