<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>积分商品</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=points_good&op=points_good"><span><?php echo $lang['nc_manage'];?></span></a></li>
        <li><a href="index.php?act=points_good&op=points_good_add" class="current"><span><?php echo $lang['nc_new'];?></span></a></li>
          <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="points_good_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="notice_id" value="<?php echo $output['points_good']['pg_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="pg_name">商品名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              <input type="text" id="pg_name" name="pg_name" class="txt" value="<?php echo $output['points_good']['pg_name'];?>">
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="pg_points">兑换积分:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="pg_points" name="pg_points" class="txt" value="<?php echo $output['points_good']['pg_points'];?>"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation" for="pg_stock">库存:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input type="text" id="pg_stock" name="pg_stock" class="txt" value="<?php echo $output['points_good']['pg_stock'];?>"></td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation" for="pg_state">状态:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <select id="pg_state" name="pg_state">
                    <option <?php if($output['points_good']['pg_state'] == '1'){ ?>selected='selected'<?php } ?> value="1">上架</option>
                    <option <?php if($output['points_good']['pg_state'] == '2'){ ?>selected='selected'<?php } ?> value="2">下架</option>
                </select>
            </td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation" >商品描述:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><?php showEditor('pg_desc',$output['points_good']['pg_desc']);?></td>
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
        if($("#points_good_form").valid()){
         $("#points_good_form").submit();
        }
	});
});

$(document).ready(function(){
	$('#points_good_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
            pg_name: {
        		required: true,
                maxlength: 25
			},
            pg_points: {
        		required: true,
                digits : true
			},
            pg_stock: {
                required: true,
                digits : true
            },
            pg_state: {
                required: true
            },
            pg_desc: {
                required : true
            }
        },
        messages : {
            pg_name: {
        		required : '商品名称不能为空',
                maxlength : '商品名称最大长度不能超过25'
			},
            pg_points: {
                required : '兑换积分值不能为空',
                digits : '兑换积分值必须为正整数'
            },
            pg_stock: {
                required : '库存不能为空',
                digits : '库存必须为正整数'
            },
            pg_state: {
                required : '状态不能为空'
            },
            pg_desc: {
                required : '描述不能为空'
            }
        }
    });
});
</script>