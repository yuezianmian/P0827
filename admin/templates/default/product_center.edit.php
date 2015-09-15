
<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>产品中心地址</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="product_form" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="product_center_url">产品中心地址:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" value="<?php echo $output['product_center_info']['value'];?>" name="product_center_url" id="product_center_url" class="txt"></td>
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
                product_center_url : {
                    required : true,
                    url:true,
                    maxlength:100
                }
            },
            messages : {
                product_center_url : {
                    required : '产品中心地址不能为空',
                    url : '产品中心url格式不正确',
                    maxlength   : '产品中心地址长度最大100'
                }
            }
        });
    });
</script>