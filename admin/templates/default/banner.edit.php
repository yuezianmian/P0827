<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>banner管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=banner&op=banner"><span><?php echo $lang['nc_manage'];?></span></a></li>
		<li><a href="index.php?act=banner&op=banner_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="banner_form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="banner_id" value="<?php echo $output['banner']['banner_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="banner_img">图片:</label></td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform">
          	<span class="type-file-show">
          		<img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png" />
          		<div class="type-file-preview"><img src="<?php echo SITE_URL.$output['banner']['banner_img'];?>" onload="javascript:DrawImage(this,500,500);"></div>
            </span>
            <span class="type-file-box">
            	<input type="file" class="type-file-file" id="banner_img" name="banner_img" size="30" hidefocus="true">
            </span>
          </td>
          <td class="vatop tips"><?php echo $lang['activity_new_banner_tip'];?></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="banner_order">排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="banner_order" name="banner_order" class="txt" value="<?php echo $output['banner']['banner_order'];?>"></td>
          <td class="vatop tips">数字范围为0~255，数字越小越靠前</td>
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
<script>
//按钮先执行验证再提交表单
$(function(){
    $("#submitBtn").click(function(){
        if($("#banner_form").valid()){
         $("#banner_form").submit();
        }
	});
});

$(document).ready(function(){
	$('#banner_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
            banner_img: {
				accept : 'png|jpe?g|gif'	
			},
            banner_order: {
        		required : true,
        		min:0,
        		max:255
        	}
        },
        messages : {
            banner_img: {
				accept   : '图片限于png,gif,jpeg,jpg格式'	
			},
            banner_sort: {
        		required : '排序不能为空',
        		min:'数字范围为0~255',
        		max:'数字范围为0~255'
        	}
        }
    });
});
$(function(){
// 模拟活动页面横幅Banner上传input type='file'样式
    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />"
    $(textButton).insertBefore("#banner_img");
    $("#banner_img").change(function(){
        $("#textfield1").val($("#banner_img").val());
    });
});
</script>