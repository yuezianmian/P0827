<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>系统公告</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
        <li><a href="index.php?act=notice&op=notice_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
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
          <th width="200px">标题</th>
          <th width="110px">公告图</th>
          <th width="630px">摘要</th>
          <th width="110px">创建时间</th>
          <th class=""><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['notice_list']) && is_array($output['notice_list'])){ ?>
        <?php foreach($output['notice_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td class="w36"><input type="checkbox" name='check_notice_id[]' value="<?php echo $v['notice_id'];?>" class="checkitem"></td>
          <td class="">
            <span><?php echo $v['notice_title'];?></span>
          </td>
          <td class="">
            <span><img src="<?php if ($v['notice_img'] != ''){ echo SITE_URL.$v['notice_img'];}?>?<?php echo microtime();?>"  onload="javascript:DrawImage(this,100,50);"/></span>
          </td>
          <td>
            <span><?php echo $v['notice_abstract'];?></span>
          </td>
          <td class="">
            <span><?php echo date('Y-m-d H:i',$v['create_time']);?></span>
          </td>
          <td class=" w84">
            <span>
              <a href="index.php?act=notice&op=notice_edit&notice_id=<?php echo $v['notice_id'];?>"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:if(confirm('是否确认删除'))window.location = 'index.php?act=notice&op=notice_del&notice_id=<?php echo $v['notice_id'];?>';"><?php echo $lang['nc_del'];?></a>
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
        <?php if(!empty($output['notice_list']) && is_array($output['notice_list'])){ ?>
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