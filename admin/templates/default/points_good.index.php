<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>积分商品</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
        <li><a href="index.php?act=points_good&op=points_good_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <form method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="space">
          <th colspan="15" class="nobg"><?php echo $lang['nc_list'];?></th>
        </tr>
        <tr class="thead">
          <th class="w36"><input type="checkbox" class="checkall" id="checkall_1"></th>
          <th>商品名称</th>
          <th>商品图片</th>
          <th>兑换积分值</th>
          <th>库存</th>
          <th>状态</th>
          <th>创建时间</th>
          <th class=""><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['points_good_list']) && is_array($output['points_good_list'])){ ?>
        <?php foreach($output['points_good_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td class="w36"><input type="checkbox" name='check_pg_id[]' value="<?php echo $v['pg_id'];?>" class="checkitem"></td>
          <td class="">
            <span><?php echo $v['pg_name'];?></span>
          </td>
          <td class="">
            <span>
              <a href="<?php if ($v['pg_img'] != ''){ echo SITE_URL.$v['pg_img'];}else { echo UPLOAD_SITE_URL.DS.img.DS.'default.jpg';}?>" data-lightbox="box-<?php echo $v['pg_id'];?>;?>">
                <img style="width: 50px;height: 50px;" src="<?php if ($v['pg_img'] != ''){ echo SITE_URL.$v['pg_img'];}?>"  onerror="this.error=null;this.src='<?php echo UPLOAD_SITE_URL.DS.img.DS?>default.jpg'"/>
              </a>
            </span>
          </td>
          <td class="">
            <span><?php echo $v['pg_points'];?></span>
          </td>
          <td>
            <span><?php echo $v['pg_stock'];?></span>
          </td>
          <td>
            <span>
              <?php if($v['pg_state'] == 1){ ?>
                <?php echo "已上架"; ?>
              <?php }else if($v['pg_state'] == 2) { ?>
                <?php echo "已下架"; ?>
              <?php } ?>
            </span>
          </td>
          <td class="">
            <span><?php echo date('Y-m-d H:i',$v['create_time']);?></span>
          </td>
          <td class=" w84">
            <span>
              <a href="index.php?act=points_good&op=points_good_edit&pg_id=<?php echo $v['pg_id'];?>"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:if(confirm('是否确认删除'))window.location = 'index.php?act=points_good&op=points_good_del&pg_id=<?php echo $v['pg_id'];?>';"><?php echo $lang['nc_del'];?></a>
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
        <?php if(!empty($output['points_good_list']) && is_array($output['points_good_list'])){ ?>
        <tr id="batchAction" >
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16" id="dataFuncs"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('是否确认删除')){$('form:first').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
            </td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/lightbox/css/lightbox.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo ADMIN_TEMPLATES_URL;?>/lightbox/js/lightbox.min.js"></script>