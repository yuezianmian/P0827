<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>产品管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=product&op=product" ><span><?php echo $lang['nc_manage'];?></span></a></li>
        <li><a href="index.php?act=product&op=product_add" ><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="product_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="product_id" value="<?php echo $output['product_info']['product_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="product_name">产品名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['product_info']['product_name'];?>" name="product_name" id="product_name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="agent_points">代理商获得积分:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['product_info']['agent_points'];?>" name="agent_points" id="agent_points" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="shop_points">店面商获得积分:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['product_info']['shop_points'];?>" name="shop_points" id="shop_points" class="txt"></td>
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
$(function(){$("#submitBtn").click(function(){
    if($("#product_form").valid()){
     $("#product_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#product_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },

        rules : {
            product_name : {
                required : true,
                maxlength: 20,
                remote   : {
                    url :'index.php?act=product&op=ajax&branch=check_product_name',
                    type:'get',
                    data:{
                        product_name : function(){
                            return $('#product_name').val();
                        },
                        product_id : '<?php echo $output['product_info']['product_id'];?>'
                    }
                }
            },
            agent_points : {
                required : true,
                digits   : true
            },
            shop_points : {
                required : true,
                digits   : true
            }
        },
        messages : {
            product_name : {
                required : '产品名称不能为空',
                maxlength   : '产品名称长度最大20',
                remote   : '该产品名称已被占用'
            },
            agent_points  : {
                required : '代理商获得积分不能为空',
                digits   : '代理商获得积分必须为整数'
            },
            shop_points  : {
                required : '店面获得积分不能为空',
                digits   : '店面获得积分必须为整数'
            }
        }
    });
});
</script>