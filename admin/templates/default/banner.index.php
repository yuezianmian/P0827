<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>banner管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
        <li><a href="index.php?act=banner&op=banner_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
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
          <th><input type="checkbox" class="checkall" id="checkall_1"></th>
          <th>排序</th>
          <th>banner图片</th>
          <th>创建时间</th>
          <th><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['banner_list']) && is_array($output['banner_list'])){ ?>
        <?php foreach($output['banner_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td class="w36"><input type="checkbox" name='check_banner_id[]' value="<?php echo $v['banner_id'];?>" class="checkitem"></td>
          <td class="">
            <span><?php echo $v['banner_order'];?></span>
          </td>
          <td class="">
            <span>
              <a href="<?php if ($v['banner_img'] != ''){ echo SITE_URL.$v['banner_img'];}else { echo UPLOAD_SITE_URL.DS.img.DS.'default.jpg';}?>?<?php echo microtime();?>" data-lightbox="box-<?php echo $v['banner_id'];?>;?>">
                <img style="width: 100px;height: 55px;" src="<?php if ($v['banner_img'] != ''){ echo SITE_URL.$v['banner_img'];}?>?<?php echo microtime();?>"  onerror="this.error=null;this.src='<?php echo UPLOAD_SITE_URL.DS.img.DS?>default.jpg'"/>
              </a>
            </span>
          </td>
          <td class="">
            <span><?php echo date('Y-m-d H:i:s',$v['create_time']);?></span>
          </td>
          <td class="w84">
            <span>
              <a href="index.php?act=banner&op=banner_edit&banner_id=<?php echo $v['banner_id'];?>"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:if(confirm('是否确认删除'))window.location = 'index.php?act=banner&op=banner_del&banner_id=<?php echo $v['banner_id'];?>';"><?php echo $lang['nc_del'];?></a>
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
        <?php if(!empty($output['banner_list']) && is_array($output['banner_list'])){ ?>
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