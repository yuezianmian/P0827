<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['member_index_manage']?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=member&op=member" ><span><?php echo $lang['nc_manage']?></span></a></li>
        <li><a href="index.php?act=member&op=member_add" ><span><?php echo $lang['nc_new']?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>指定代理商</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="member_id" value="<?php echo $output['member_array']['member_id'];?>" />
    <table class="table tb-type2">
      <tbody>
          <tr class="noborder">
              <td colspan="2" class="required"><label>手机号:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform"><?php echo $output['member_array']['member_mobile'];?></td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>姓名:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform"><?php echo $output['member_array']['member_truename'];?></td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>会员类型:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform">
                  <?php if($output['member_array']['member_type'] == 1){ ?>
                      <?php echo "代理商"; ?>
                  <?php }else if($output['member_array']['member_type'] == 2) { ?>
                      <?php echo "店铺"; ?>
                  <?php } ?>
              </td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>可用积分:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform">
                  可用积分 <strong class="red"><?php echo $output['member_array']['member_points']; ?></strong>
              </td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>性别:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform">
                  <?php if($output['member_array']['member_sex'] == 0){ ?>
                      <?php echo "保密"; ?>
                  <?php }else if($output['member_array']['member_sex'] == 1) { ?>
                      <?php echo "男"; ?>
                  <?php }else if($output['member_array']['member_sex'] == 2) { ?>
                      <?php echo "女"; ?>
                  <?php } ?>
              </td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>生日:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform"><?php echo $output['member_array']['member_birthday'];?></td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>创建时间:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform">
                  <?php echo $output['member_array']['create_time'] ? date('Y-m-d H:i:s',$output['member_array']['create_time']) : '';?>
              </td>
          </tr>
          <?php if($output['member_array']['member_type'] == MEMBER_TYPE_AGENT){ ?>
              <tr>
                  <td colspan="2" class="required"><label>代理商编号:</label></td>
              </tr>
              <tr class="noborder">
                  <td class="vatop rowform"><?php echo $output['member_array']['member_code'];?></td>
              </tr>
              <tr>
                  <td colspan="2" class="required"><label>所属区域:</label></td>
              </tr>
              <tr class="noborder">
                  <td class="vatop rowform"><?php echo $output['member_array']['area_name'];?></td>
              </tr>
          <?php }else if($output['member_array']['member_type'] == MEMBER_TYPE_STORE) { ?>
              <tr>
                  <td colspan="2" class="required"><label>所属代理商编号:</label></td>
              </tr>
              <tr class="noborder">
                  <td class="vatop rowform">
                      <input type="text" value="<?php echo $output['member_array']['parent_code'];?>" id="parent_code" name="parent_code" class="txt">
                  </td>
              </tr>
              <tr>
                  <td colspan="2" class="required"><label>店铺名称:</label></td>
              </tr>
              <tr class="noborder">
                  <td class="vatop rowform"><?php echo $output['member_array']['shop_name'];?></td>
              </tr>
              <tr>
                  <td colspan="2" class="required"><label>店铺照片:</label></td>
              </tr>
              <tr class="noborder">
                  <td class="vatop rowform">
                      <?php if($output['member_array']['shop_img']){ ?>
                          <img src="<?php  echo SITE_URL.$output['member_array']['shop_img'];?>"  onload="javascript:DrawImage(this,44,44);"/>
                      <?php } ?>
                  </td>
              </tr>
              <tr>
                  <td colspan="2" class="required"><label>店铺区域:</label></td>
              </tr>
              <tr class="noborder">
                  <td class="vatop rowform"><?php echo $output['member_array']['area_name'];?></td>
              </tr>
              <tr>
                  <td colspan="2" class="required"><label>店铺地址:</label></td>
              </tr>
              <tr class="noborder">
                  <td class="vatop rowform"><?php echo $output['member_array']['shop_address'];?></td>
              </tr>
          <?php } ?>
          <tr>
              <td colspan="2" class="required"><label>支付宝:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform"><?php echo $output['member_array']['alipay_number'];?></td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>银行卡号:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform"><?php echo $output['member_array']['bank_number'];?></td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>开户姓名:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform"><?php echo $output['member_array']['bank_username'];?></td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>银行名称:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform"><?php echo $output['member_array']['bank_name'];?></td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>开户网点:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform"><?php echo $output['member_array']['bank_branch'];?></td>
          </tr>
          <tr>
              <td colspan="2" class="required"><label>状态:</label></td>
          </tr>
          <tr class="noborder">
              <td class="vatop rowform">
                  <?php if($output['member_array']['member_state'] == MEMBER_STATE_REGISTED){ ?>
                      <?php echo "已注册"; ?>
                  <?php }else if($output['member_array']['member_state'] == MEMBER_STATE_NOCHECK) { ?>
                      <?php echo "待审核"; ?>
                  <?php }else if($output['member_array']['member_state'] == MEMBER_STATE_NORMAL) { ?>
                      <?php echo "正常"; ?>
                  <?php }else if($output['member_array']['member_state'] == MEMBER_STATE_NOPASS) { ?>
                      <?php echo "失效"; ?>
                  <?php } ?>
              </td>
          </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
//裁剪图片后返回接收函数
function call_back(picname){
	$('#member_avatar').val(picname);
	$('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname+'?'+Math.random());
}
$(function(){
	$('input[class="type-file-file"]').change(uploadChange);
	function uploadChange(){
		var filepatd=$(this).val();
		var extStart=filepatd.lastIndexOf(".");
		var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url:'index.php?act=common&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_AVATAR;?>',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','index.php?act=common&op=pic_cut&type=member&x=120&y=120&resize=1&ratio=1&filename=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/avatar_<?php echo $_GET['member_id'];?>.jpg&url='+data.url,690);
					}else{
						alert(data.msg);
					}
					$('input[class="type-file-file"]').bind('change',uploadChange);
				},
				error: function (data, status, e)
				{
					alert('上传失败');$('input[class="type-file-file"]').bind('change',uploadChange);
				}
			}
		)
	};
$("#submitBtn").click(function(){
    if($("#user_form").valid()){
     $("#user_form").submit();
	}
	});
    $('#user_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            parent_code: {
                required : true,
                maxlength: 5,
                minlength: 5,
                remote   : {
                    url :'index.php?act=member&op=ajax&branch=check_parent_code',
                    type:'get',
                    data:{
                        parent_code : function(){
                            return $('#parent_code').val();
                        }
                    }
                }
            }
        },
        messages : {
            parent_code : {
                required: '所属代理商编码不能为空',
                minlength: '所属代理商编码为5位',
                maxlength: '所属代理商编码为5位',
                remote   : '该所属代理商不存在'
            }
        }
    });
});
</script> 
