<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['member_index_manage']?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=member&op=member" ><span><?php echo $lang['nc_manage']?></span></a></li>
        <li><a href="index.php?act=member&op=member_add" ><span>新增代理</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>新增店面</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>

        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="member_mobile_true">手机号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="member_mobile_true" id="member_mobile_true" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="member_passwd">密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="member_passwd" name="member_passwd" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="member_truename">姓名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" id="member_truename" name="member_truename" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="required"><label class="validation" for="shop_name">店铺名称:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input type="text" value="" name="shop_name" id="shop_name" class="txt"></td>
            <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="required"><label class="validation" for="shop_img">店铺照片:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform type-file-box">
                <input type="file" class="type-file-file" id="shop_img" name="shop_img" size="30" hidefocus="true"  >
            </td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation" for="sub_area_id">店铺区域:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop">
                <input type="hidden" name="area_id" id="area_id" value="" />
                <input type="hidden" name="area_name" id="area_name" value="" />
                <select id="province_id" name="province_id" onchange="changeCityOption()">
                    <option value=''>-请选择-</option>
                </select>
                <select id="city_id" name="city_id" onchange="changeAreaOption()">
                    <option></option>
                </select>
                <select id="sub_area_id" name="sub_area_id" onchange="changeAreaInfo()">
                    <option></option>
                </select>
            </td>
            <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="required"><label class="validation" for="shop_address">店铺地址:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input type="text" value="" name="shop_address" id="shop_address" class="txt"></td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation" for="parent_code">所属代理商编号:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input type="text"  id="parent_code" name="parent_code" class="txt"></td>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
var area_json = <?php echo  $output['area_json'];?>;
//裁剪图片后返回接收函数
function call_back(picname){
	$('#member_avatar').val(picname);
	$('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname);
}
function initProvinceSelect(){
    for(var i=0; i<area_json[0].length; i++){
        var optionStr = "<option value='" + area_json[0][i][0] + "'>" + area_json[0][i][1] + "</option>";
        $("#province_id").append(optionStr);
    }
}
function changeCityOption(){
    $("#city_id").empty();
    $("#city_id").append("<option value=''>-请选择-</option>");
    var province_id = $("#province_id").val();
    if(province_id != ''){
        for(var i=0; i<area_json[province_id].length; i++){
            var optionStr = "<option value='" + area_json[province_id][i][0] + "'>" + area_json[province_id][i][1] + "</option>";
            $("#city_id").append(optionStr);
        }
    }
    changeAreaOption();
}
function changeAreaOption(){
    $("#sub_area_id").empty();
    $("#sub_area_id").append("<option value=''>-请选择-</option>");
    var city_id = $("#city_id").val();
    if(city_id != ''){
        for(var i=0; i<area_json[city_id].length; i++){
            var optionStr = "<option value='" + area_json[city_id][i][0] + "'>" + area_json[city_id][i][1] + "</option>";
            $("#sub_area_id").append(optionStr);
        }
    }
}
function changeAreaInfo(){
    var sub_area_id = $("#sub_area_id").val();
    if(sub_area_id == ''){
        $("#area_id").val("");
        $("#area_name").val("");
    }else{
        $("#area_id").val(sub_area_id);
        var sub_area_name = $("#sub_area_id").find("option:selected").text();
        var city_name = $("#city_id").find("option:selected").text();
        $("#area_name").val(city_name+sub_area_name);
    }
}
$(function(){
    initProvinceSelect();
	//按钮先执行验证再提交表单
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
            shop_name: {
                required : true,
                maxlength: 25
            },
            shop_img: {
                required: true,
                accept : 'png|jpe?g|gif'
            },
            shop_address: {
                required : true,
                maxlength: 80
            },
            member_mobile_true: {
				required : true,
                digits : true,
                maxlength: 11,
                minlength: 11,
				remote   : {
                    url :'index.php?act=member&op=ajax&branch=check_member_mobile_true',
                    type:'get',
                    data:{
                        member_mobile : function(){
                            return $('#member_mobile_true').val();
                        },
                        member_id : ''
                    }
                }
			},
            member_passwd: {
				required : true,
                maxlength: 20,
                minlength: 6
            },
            member_truename: {
				required : true,
                maxlength: 20
            },
            sub_area_id: {
				required : true
            },
            parent_code: {
                required : true,
                maxlength: 4,
                minlength: 4,
                remote   : {
                    url :'index.php?act=member&op=ajax&branch=check_parent_code',
                    type:'get',
                    data:{
                        member_code : function(){
                            return $('#member_code').val();
                        },
                        member_id : ''
                    }
                }
            }
        },
        messages : {
            shop_name  : {
                required : '店铺名称不能为空',
                maxlength : '公司名称不能超过25位字符'
            },
            shop_img: {
                required : '图片不能为空',
                accept   : '图片限于png,gif,jpeg,jpg格式'
            },
            shop_address: {
                required : '地址不能为空',
                maxlength: '地址长度不能超过80位'
            },
            member_mobile_true: {
				required : '手机号不能为空',
                digits: '手机号格式不正确',
                minlength: '手机号格式不正确',
                maxlength: '手机号格式不正确',
				remote   : '该手机号已被注册'
			},
            member_passwd : {
				required : '密码不能为空',
                minlength: '密码不能少于6位',
                maxlength: '密码不能多于20位'
            },
            member_truename  : {
                required : '姓名不能为空',
                maxlength : '姓名不能超过20位字符'
            },
            sub_area_id : {
                required: '店铺区域必须精确到区或县'
			},
            parent_code : {
                required: '所属代理商编码不能为空',
                minlength: '所属代理商编码为4位',
                maxlength: '所属代理商编码为4位',
                remote   : '所属该代理商编码不存在'
            }
        }
    });
});
$(function(){
// 模拟活动页面横幅Banner上传input type='file'样式
    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />"
    $(textButton).insertBefore("#shop_img");
    $("#shop_img").change(function(){
        $("#textfield1").val($("#shop_img").val());
    });
});
</script>
