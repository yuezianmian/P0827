<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>产品管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
        <li><a href="index.php?act=product&op=product_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>删除产品时，请确认该产品生成的二维码没有投放出去；</li>
            <li>产品若生成二维码且投放出去，则不要删除该产品。</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th><input type="checkbox" class="checkall" id="checkall_1"></th>
          <th>产品名称</th>
          <th>代理商获得积分</th>
          <th>店面获得积分</th>
          <th>创建时间</th>
          <th><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['product_list']) && is_array($output['product_list'])){ ?>
        <?php foreach($output['product_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td class="w36"><input type="checkbox" name='check_product_id[]' value="<?php echo $v['product_id'];?>" class="checkitem"></td>
          <td class="">
            <span><?php echo $v['product_name'];?></span>
          </td>
          <td class="">
            <span><?php echo $v['agent_points'];?></span>
          </td>
          <td class="">
            <span><?php echo $v['shop_points'];?></span>
          </td>
          <td class="">
            <span><?php echo date('Y-m-d H:i:s',$v['create_time']);?></span>
          </td>
          <td class="w84">
            <span>
              <a href="index.php?act=product&op=product_edit&product_id=<?php echo $v['product_id'];?>"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:if(confirm('删除后投放的二维码将失效，确认删除吗'))window.location = 'index.php?act=product&op=product_del&product_id=<?php echo $v['product_id'];?>';"><?php echo $lang['nc_del'];?></a>
            </span>
          </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['product_list']) && is_array($output['product_list'])){ ?>
        <tr id="batchAction" >
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16" id="dataFuncs"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('删除后投放的二维码将失效，确认删除吗')){$('form:first').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
            </td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<!--<script type="text/javascript" src="--><?php //echo RESOURCE_SITE_URL;?><!--/js/jquery.store_class.js" charset="utf-8"></script> -->