
<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>生成二维码</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12" class="nobg"><div class="title">
                    <h5><?php echo $lang['nc_prompts'];?></h5>
                    <span class="arrow"></span></div></th>
        </tr>
        <tr>
            <td><ul>
                    <li>该操作耗时较久，一次最多只允许生成100个；</li>
                    <li>若想再次生成二维码，需刷新页面。</li>
                </ul></td>
        </tr>
        </tbody>
    </table>
    <form id="generate_form" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="product_id">选择二维码的产品:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <select name="product_id" id="product_id">
                        <?php if (!empty($output['product_list'])){?>
                            <?php foreach ($output['product_list'] as $k=>$v){?>
                                <option value="<?php echo $v['product_id'];?>" <?php if ($output['default_product_id'] == $v['product_id']){echo 'selected=true';}?>> <?php echo $v['product_name'];?> </option>
                            <?php }?>
                        <?php }?>
                    </select></td>
                </td>
                <td class="vatop tips">.</td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="validation" for="code_number">生成二维码个数:</label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform"><input type="text" value="" name="code_number" id="code_number" class="txt"></td>
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
    var isSubmit = false;
    //按钮先执行验证再提交表单
    $(function(){
        $("#submitBtn").click(function(){
           /* if(isSubmit){
                alert("处理中，不可重复提交");
                return;
            }*/
            if($("#generate_form").valid()){
                $("#generate_form").submit();
                isSubmit = true;
            }
//            $("#submitBtn").attr('disabled',"true")
//            $("#submitBtn > span").text("处理中...");
        });
    });
    //
    $(document).ready(function(){
        $('#generate_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },

            rules : {
                product_id : {
                    required : true
                },
                code_number : {
                    required : true,
                    digits   : true,
                    max : 5000,
                    min : 1
                }
            },
            messages : {
                product_id : {
                    required : '产品不能为空'
                },
                code_number : {
                    required : '二维码数量不能为空',
                    digits : '二维码数量必须为整数',
                    max : '二维码一次最多生成5000个',
                    min : '二维码一次最少生成1个'
                }
            }
        });
    });
</script>