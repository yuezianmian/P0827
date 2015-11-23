<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>banner1管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
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

          <th>序号</th>
          <th>banner1图片</th>
          <th>创建时间</th>
          <th><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['banner1_list']) && is_array($output['banner1_list'])){ ?>
        <?php foreach($output['banner1_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td>1</td>
          <td class="">
            <span>
              <a href="<?php if ($v['banner1_img'] != ''){ echo SITE_URL.$v['banner1_img'];}else { echo UPLOAD_SITE_URL.DS.img.DS.'default.jpg';}?>" data-lightbox="box-<?php echo $v['banner1_id'];?>;?>">
                <img style="width: 100px;height: 55px;" src="<?php if ($v['banner1_img'] != ''){ echo SITE_URL.$v['banner1_img'];}?>"  onerror="this.error=null;this.src='<?php echo UPLOAD_SITE_URL.DS.img.DS?>default.jpg'"/>
              </a>
            </span>
          </td>
          <td class="">
            <span><?php echo date('Y-m-d H:i:s',$v['create_time']);?></span>
          </td>
          <td class="w84">
            <span>
              <a href="index.php?act=banner1&op=banner1_edit&banner1_id=<?php echo $v['banner1_id'];?>"><?php echo $lang['nc_edit'];?></a>
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
        <?php if(!empty($output['banner1_list']) && is_array($output['banner1_list'])){ ?>
        <tr id="batchAction" >
          <td></td>
          <td colspan="16" id="dataFuncs">
            &nbsp;&nbsp;
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