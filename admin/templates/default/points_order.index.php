<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>积分兑换订单</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="points_order" name="act">
    <input type="hidden" value="points_order" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
      <tr>
        <td><select name="search_field_name" >
            <option <?php if($output['search_field_name'] == 'point_ordersn'){ ?>selected='selected'<?php } ?> value="point_ordersn">订单编号</option>
            <option <?php if($output['search_field_name'] == 'member_mobile_true'){ ?>selected='selected'<?php } ?> value="member_mobile_true">手机号</option>
          </select></td>
        <td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
        <td><select name="search_state" >
            <option <?php if($_GET['search_state'] == ''){ ?>selected='selected'<?php } ?> value="">订单状态</option>
            <option <?php if($_GET['search_state'] == '1'){ ?>selected='selected'<?php } ?> value="1">待完成</option>
            <option <?php if($_GET['search_state'] == '2'){ ?>selected='selected'<?php } ?> value="2">已完成</option>
          </select></td>

        <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
      </tr>
      </tbody>
    </table>
  </form>
    <div style="text-align:right;"><a class="btns" href="javascript:void(0);" id="ncexport"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>订单编号</th>
<!--          <th>手机号</th>-->
          <th width="140px">商品</th>
          <th>数量</th>
          <th>积分</th>
          <th width="76px">下单时间</th>
          <th>收货人</th>
          <th>收货手机号</th>
          <th width="200px">收货地址</th>
          <th width="76px">完成时间</th>
          <th width="40px">状态</th>
          <th width="150px">商品详细信息</th>
          <th class=""><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['points_order_list']) && is_array($output['points_order_list'])){ ?>
        <?php foreach($output['points_order_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td class="">
            <span><?php echo $v['point_ordersn'];?></span>
          </td>
         <!-- <td class="">
            <span><?php /*echo $v['point_buyermobiletrue'];*/?></span>
          </td>-->
          <td>
            <span><?php echo $v['pg_name'];?></span>
          </td>
          <td>
            <span><?php echo $v['pg_number'];?></span>
          </td>
          <td>
            <span><?php echo $v['point_allpoint'];?></span>
          </td>
          <td>
            <span><?php echo date('Y-m-d H:i',$v['point_addtime']);?></span>
          </td>
          <td>
            <span><?php echo $v['receiver_name'];?></span>
          </td>
          <td>
            <span><?php echo $v['receiver_mobile'];?></span>
          </td>
          <td>
            <span><?php echo $v['address'];?></span>
          </td>
          <td>
            <span>
            <?php if($v['point_finishedtime']){ ?>
              <?php echo date('Y-m-d H:i',$v['point_finishedtime']);?>
            <?php } ?>
            </span>
          </td>
          <td>
            <span>
              <?php if($v['point_orderstate'] == 1){ ?>
                <?php echo "待完成"; ?>
              <?php }else if($v['point_orderstate'] == 2) { ?>
                <?php echo "已完成"; ?>
              <?php } ?>
            </span>
          </td>
          <td>
            <span><?php echo $v['point_orderdesc'];?></span>
          </td>
          <td class=" w84">
            <span>
            <?php if($v['point_orderstate'] == 1){ ?>
              <a href="index.php?act=points_order&op=points_order_edit&point_orderid=<?php echo $v['point_orderid'];?>">核销</a>
            <?php } ?>
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
        <?php if(!empty($output['points_order_list']) && is_array($output['points_order_list'])){ ?>
        <tr id="batchAction" >
          <td colspan="16">
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
            </td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script>
  $(function(){
    $('#ncsubmit').click(function(){
      $('input[name="op"]').val('points_order');$('#formSearch').submit();
    });
      $('#ncexport').click(function(){
          $('input[name="op"]').val('export_points_order');
          $('#formSearch').submit();
      });
  });
</script>