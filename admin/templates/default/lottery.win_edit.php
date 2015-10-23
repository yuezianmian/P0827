<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>抽奖管理</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=lottery&op=index"><span>管理</span></a></li>
                <li><a href="index.php?act=lottery&op=win_list"><span>中奖列表</span></a></li>
                <li><a href="javascript:void(0);" class="current"><span>核销</span></a></li>
                <li><a href="index.php?act=lottery&op=participant_list"><span>参与记录</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="edit_form" method="post" action="index.php?act=lottery&op=get_prize">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="submit_type" id="submit_type" value="" />
        <input type="hidden" name="id" id="id" value="<?php echo $output['win']['id'];?>" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >抽奖编号<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['win']['id'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >用户名<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['win']['member_mobile'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >中奖时间<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo date('Y-m-d H:i:s',$output['win']['participant_time']); ?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >奖项名称<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['win']['awards_name'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >奖品名称<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['win']['prize_name'];?>
                </td>
            </tr>
            <tr class="noborder">
                <td colspan="2" class="required"><label class="" >奖项名称<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr class="noborder">
                <td class="vatop rowform">
                    <?php echo $output['win']['awards_name'];?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="required"><label class="validation" for="prize_desc" class="">奖品详细信息<?php echo $lang['nc_colon']; ?></label></td>
            </tr>
            <tr>
                <td class="vatop rowform" colspan="2" >
                    <textarea style="width: 500px;height: 50px" id="prize_desc" name="prize_desc"></textarea>
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
                prize_desc: {
                    required : true,
                    maxlength : 80
                },
            },
            messages : {
                prize_desc: {
                    required : "奖品详细信息不能为空",
                    maxlength : "奖品详细信息长度最多80个字符"
                }
            }
        });
    });
</script>
