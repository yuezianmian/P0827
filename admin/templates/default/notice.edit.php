<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>系统公告</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=notice&op=notice"><span><?php echo $lang['nc_manage'];?></span></a></li>
				<li><a href="index.php?act=notice&op=notice_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="notice_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
		<input type="hidden" name="notice_id" value="<?php echo $output['notice']['notice_id'];?>" />
        <table class="table tb-type2">
            <tbody>
                <tr>
                    <td colspan="2" class="required"><label class="validation" for="notice_title">标题:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform"><input type="text" id="notice_title" name="notice_title" class="txt" value="<?php echo $output['notice']['notice_title'];?>"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td colspan="2" class="required"><label for="notice_img">公告图:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
						<span class="type-file-show">
							<img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png" />
							<div class="type-file-preview"><img src="<?php echo SITE_URL.$output['notice']['notice_img'];?>" onload="javascript:DrawImage(this,500,500);"></div>
						</span>
						<span class="type-file-box">
							<input type="file" class="type-file-file" id="notice_img" name="notice_img" size="30" hidefocus="true">
						</span>
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation" for="notice_abstract">概要:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform" colspan="2" >
                        <textarea style="width: 500px;height: 50px" id="notice_abstract" name="notice_abstract"><?php echo $output['notice']['notice_abstract'];?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation" >内容:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform" colspan="2"><?php showEditor('notice_content',$output['notice']['notice_content']);?></td>
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
            if($("#notice_form").valid()){
                $("#notice_form").submit();
            }
        });
    });

    $(document).ready(function(){
        $('#notice_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },

            rules : {
                notice_title: {
                    required: true,
                    maxlength: 25
                },
                notice_abstract: {
                    required: true,
                    maxlength: 80
                },
                notice_img: {
                    accept : 'png|jpe?g|gif'
                },
                notice_content: {
                    required : true
                }
            },
            messages : {
                notice_title: {
                    required : '标题不能为空',
                    maxlength : '标题字符长度不能大于25'
                },
                notice_abstract: {
                    required : '概要不能为空',
                    maxlength : '概要字符长度不能大于80'
                },
                notice_img: {
                    accept   : '公告图限于png,gif,jpeg,jpg格式'
                },
                notice_content: {
                    required : '内容不能为空'
                }
            }
        });
    });
    $(function(){
// 模拟活动页面横幅Banner上传input type='file'样式
        var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />"
        $(textButton).insertBefore("#notice_img");
        $("#notice_img").change(function(){
            $("#textfield1").val($("#notice_img").val());
        });
    });
</script>