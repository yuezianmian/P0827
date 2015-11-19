<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>积分兑换订单</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=points_order&op=points_order"><span>管理</span></a></li>
                <li><a href="javascript:void(0);" class="current"><span>核销</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="edit_form" method="post" action="index.php?act=points_order&op=points_order_edit">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="point_orderid" id="point_orderid" value="<?php echo $output['points_order']['point_orderid'];?>" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >订单编号<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['points_order']['point_ordersn'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >会员手机号<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['points_order']['point_buyermobiletrue'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >兑换商品<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['points_order']['pg_name'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >兑换数量<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['points_order']['pg_number'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >订单积分<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['points_order']['point_allpoint'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >下单时间<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo date('Y-m-d H:i:s',$output['points_order']['point_addtime']); ?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >收货人<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['points_order']['receiver_name'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >收货电话<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['points_order']['receiver_mobile'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >收货地址<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['points_order']['address'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >订单状态<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php if($output['points_order']['point_orderstate'] == 1){ ?>
                        <?php echo "待完成"; ?>
                    <?php }else if($output['points_order']['point_orderstate'] == 2) { ?>
                        <?php echo "已完成"; ?>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label class="validation" for="point_orderdesc" class="">商品详细信息<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr>
                <td class="vatop rowform" colspan="2" >
                    <textarea style="width: 500px;height: 50px" id="point_orderdesc" name="point_orderdesc"></textarea>
                </td>
            </tr>

            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">
                    <a id="submit" href="javascript:void(0)" class="btn"><span>核销</span></a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript">
    $(function(){
        $('#submit').click(function(){
            $('#edit_form').submit();
        });

        $('#edit_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            success: function(label){
                label.addClass('valid');
            },
            rules : {
                point_orderdesc: {
                    required : true,
                    maxlength : 80
                },
            },
            messages : {
                point_orderdesc: {
                    required : "商品详细信息不能为空",
                    maxlength : "商品详细信息长度最多80个字符"
                }
            }
        });
    });
</script>
