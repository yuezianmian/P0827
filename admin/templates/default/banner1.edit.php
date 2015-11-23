<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>banner管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=banner1&op=banner1"><span><?php echo $lang['nc_manage'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="banner1_form" method="post" enctype="multipart/form-data">
	<input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="banner1_id" value="<?php echo $output['banner1']['banner1_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="banner1_img">图片:</label></td>
        </tr>
		<tr class="noborder">
          <td class="vatop rowform">
          	<span class="type-file-show">
          		<img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png" />
          		<div class="type-file-preview"><img src="<?php echo SITE_URL.$output['banner1']['banner1_img'];?>" onload="javascript:DrawImage(this,500,500);"></div>
            </span>
            <span class="type-file-box">
            	<input type="file" class="type-file-file" id="banner1_img" name="banner1_img" size="30" hidefocus="true">
            </span>
          </td>
          <td class="vatop tips"></td>
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
        if($("#banner1_form").valid()){
         $("#banner1_form").submit();
        }
	});
});

$(document).ready(function(){
	$('#banner1_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
            banner1_img: {
				accept : 'png|jpe?g|gif'	
			}
        },
        messages : {
            banner1_img: {
				accept   : '图片限于png,gif,jpeg,jpg格式'	
			}
        }
    });
});
$(function(){
// 模拟活动页面横幅Banner上传input type='file'样式
    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />"
    $(textButton).insertBefore("#banner1_img");
    $("#banner1_img").change(function(){
        $("#textfield1").val($("#banner1_img").val());
    });
});
</script>