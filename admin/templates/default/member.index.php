<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['member_index_manage']?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage']?></span></a></li>
        <li><a href="index.php?act=member&op=member_add" ><span>新增代理</span></a></li>
        <li><a href="index.php?act=member&op=member_add_shop" ><span>新增店面</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="member" name="act">
    <input type="hidden" value="member" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td><select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'member_mobile'){ ?>selected='selected'<?php } ?> value="member_mobile">用户名</option>
              <option <?php if($output['search_field_name'] == 'member_mobile_true'){ ?>selected='selected'<?php } ?> value="member_mobile_true">手机号</option>
              <option <?php if($output['search_field_name'] == 'member_truename'){ ?>selected='selected'<?php } ?> value="member_truename">会员姓名</option>
              <option <?php if($output['search_field_name'] == 'member_code'){ ?>selected='selected'<?php } ?> value="member_code">代理商编号</option>
              <option <?php if($output['search_field_name'] == 'parent_code'){ ?>selected='selected'<?php } ?> value="parent_code">所属代理商编号</option>
            </select></td>
          <td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
          <td><select name="search_type" >
              <option <?php if($_GET['search_type'] == ''){ ?>selected='selected'<?php } ?> value="">会员类型</option>
              <option <?php if($_GET['search_type'] == '1'){ ?>selected='selected'<?php } ?> value="1">代理商</option>
              <option <?php if($_GET['search_type'] == '2'){ ?>selected='selected'<?php } ?> value="2">店面</option>
            </select></td>
          <td><select name="search_state" >
              <option <?php if($_GET['search_state'] == ''){ ?>selected='selected'<?php } ?> value="">会员状态</option>
              <option <?php if($_GET['search_state'] == '1'){ ?>selected='selected'<?php } ?> value="1">待审核</option>
              <option <?php if($_GET['search_state'] == '2'){ ?>selected='selected'<?php } ?> value="2">正常</option>
              <option <?php if($_GET['search_state'] == '3'){ ?>selected='selected'<?php } ?> value="3">失效</option>
            </select></td>

          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a></td>
        </tr>
      </tbody>
    </table>
  </form>
  <form method="post" id="form_member">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2"><?php echo $lang['member_index_name']?></th>
<!--          <th class="align-center"><span fieldname="logins" nc_type="order_by">--><?php //echo $lang['member_index_login_time']?><!--</span></th>-->
<!--          <th class="align-center"><span fieldname="last_login" nc_type="order_by">--><?php //echo $lang['member_index_last_login']?><!--</span></th>-->
          <th class="align-center">手机号</th>
          <th class="align-center">可用积分</th>
          <th class="align-center">会员类型</th>
          <th class="align-center">代理商编号</th>
          <th class="align-center">所属代理商</th>
          <th colspan="2">店铺信息/公司名称</th>
          <th class="align-center">区域</th>
          <th class="align-center">状态</th>
          <th class="align-center">操作</th>
        </tr>
      <tbody>
        <?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
        <?php foreach($output['member_list'] as $k => $v){ ?>
        <tr class="hover member">
          <td class="w24"></td>
          <td class="w48 picture">
              <div class="size-44x44">
                  <span class="thumb size-44x44"><i></i>
                      <a href="<?php if ($v['member_avatar'] != ''){ echo SITE_URL.$v['member_avatar'];}else { echo UPLOAD_SITE_URL.DS.img.DS.C('default_user_portrait');}?>" data-lightbox="box-userimg-<?php echo $v['member_id'];?>">
                         <img style="width: 44px;height: 44px;" src="<?php if ($v['member_avatar'] != ''){ echo SITE_URL.$v['member_avatar'];}else { echo UPLOAD_SITE_URL.DS.img.DS.C('default_user_portrait');}?>"  onerror="this.error=null;this.src='<?php echo UPLOAD_SITE_URL.DS.img.DS.C('default_user_portrait')?>'"/>
                      </a>
                  </span>
              </div>
          </td>
          <td>
              <p class="name">
                  <strong><a href="index.php?act=member&op=member_show&member_id=<?php echo $v['member_id']; ?>"><?php echo $v['member_mobile']; ?></a></strong>
                  <a href="index.php?act=member&op=member_show&member_id=<?php echo $v['member_id']; ?>">(姓名: <?php echo $v['member_truename']; ?>)</a>
              </p>
              <p class="smallfont"><?php echo $lang['member_index_reg_time']?>:&nbsp;<?php echo $v['create_time']; ?></p>
          </td>
            <td class="align-center"><?php echo $v['member_mobile_true'];?></td>
          <td class="align-center"><?php echo $v['member_points'];?></td>
          <td class="align-center">
              <?php if($v['member_type'] == MEMBER_TYPE_AGENT){ ?>
                  <?php echo "代理商"; ?>
              <?php }else if($v['member_type'] == MEMBER_TYPE_STORE) { ?>
                  <?php echo "店铺"; ?>
              <?php } ?>
          </td>
          <td class="align-center"><?php echo $v['member_type'] == 1?$v['member_code']:"--"; ?></td>
          <td class="align-center"><?php echo $v['member_type'] == 1?"--":$v['parent_code']; ?></td>
          <td class="w48 picture">
            <div class="size-44x44">
              <span class="thumb size-44x44"><i></i>
               <?php if($v['member_type'] == 2 && $v['shop_img']){ ?>
                  <a href="<?php if ($v['shop_img'] != ''){ echo SITE_URL.$v['shop_img'];}else { echo UPLOAD_SITE_URL.DS.img.DS.'default.jpg';}?>" data-lightbox="box-shopimg-<?php echo $v['member_id'];?>;?>">
                    <img style="width: 44px;height: 44px;" src="<?php  echo SITE_URL.$v['shop_img'];?>"  onerror="this.error=null;this.src='<?php echo UPLOAD_SITE_URL.DS.img.DS?>default.jpg'"/>
                  </a>
               <?php } ?>
              </span>
            </div>
          </td>
          <td>
              <p class="name"><strong><?php echo $v['shop_name']; ?></strong></p>
            <?php if($v['member_type'] == 2){ ?>
              <p class="smallfont"><?php echo $v['area_name']; ?></p>
            <?php } ?>
          </td>
          <td class="align-center"><?php echo $v['member_type'] == 1?$v['area_name']:"--"; ?></td>
          <td class="align-center">
            <?php if($v['member_state'] == MEMBER_STATE_REGISTED){ ?>
              <?php echo "已注册"; ?>
            <?php }else if($v['member_state'] == MEMBER_STATE_NOCHECK) { ?>
                <?php echo "待审核"; ?>
            <?php }else if($v['member_state'] == MEMBER_STATE_NORMAL) { ?>
                <?php echo "正常"; ?>
            <?php }else if($v['member_state'] == MEMBER_STATE_NOPASS) { ?>
              <?php echo "失效"; ?>
            <?php } ?>
          </td>
          <td class="align-center">
              <a href="index.php?act=member&op=member_show&member_id=<?php echo $v['member_id']; ?>">查看</a>
                  | <a href="index.php?act=member&op=member_edit_new&member_id=<?php echo $v['member_id']; ?>">编辑</a>
              <?php if($v['member_type'] == MEMBER_TYPE_STORE && $v['member_state'] == MEMBER_STATE_NOCHECK && empty($v['parent_code'])){ ?>
                  | <a href="index.php?act=member&op=member_edit&member_id=<?php echo $v['member_id']; ?>">指定代理商</a>
              <?php } ?>
              <?php if($v['member_state'] == MEMBER_STATE_NOPASS){ ?>
                  | <a href="index.php?act=member&op=member_del&member_id=<?php echo $v['member_id']; ?>">删除</a>
              <?php } ?>

<!--              --><?php //if($v['member_state'] == MEMBER_STATE_NOCHECK){ ?>
<!--                | <a href="index.php?act=member&op=pass&member_id=--><?php //echo $v['member_id']; ?><!--">通过</a>-->
<!--                | <a href="index.php?act=member&op=nopass&member_id=--><?php //echo $v['member_id']; ?><!--">拒绝</a>-->
<!--              --><?php //} ?>
          </td>
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
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/lightbox/css/lightbox.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo ADMIN_TEMPLATES_URL;?>/lightbox/js/lightbox.min.js"></script>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('member');$('#formSearch').submit();
    });	
});
</script>
