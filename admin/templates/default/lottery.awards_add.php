<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>抽奖管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=lottery&op=index"><span>管理</span></a></li>
        <li><a href="javascript:;" class="current"><span>新增奖项</span></a></li>
         <li><a href="index.php?act=lottery&op=win_list"><span>中奖列表</span></a></li>
         <li><a href="index.php?act=lottery&op=participant_list"><span>参与记录</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=lottery&op=add_awards">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" class="" for="awards_name">奖项名称<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              <input id="awards_name" name="awards_name" type="text" class="txt" value=""/>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="prize_name" class="">奖品名称<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              <input id="prize_name" name="prize_name" type="text" class="txt" value=""/>
          </td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation" for="prize_type" class="">奖品类型<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <select name="prize_type" id="prize_type" >
                    <option value="">请选择</option>
                    <option  value="1">积分</option>
                    <option  value="2">其它</option>
                </select>
            </td>
        </tr>
        <tr id="tr_prize_points_title" class="noborder" style="display: none">
            <td colspan="2" class="required"><label class="" for="prize_points">积分值<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr id="tr_prize_points_input" class="noborder" style="display: none">
            <td class="vatop rowform">
                <input id="prize_points" name="prize_points" type="text" class="txt" value=""/>
            </td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label class="validation" for="win_rate" class="">中奖率<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop">
			<input id="win_rate" name="win_rate" type="text" class="txt" value="" /><span>格式：0.01</span>
		  </td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label class="validation" for="prize_amount" class="">奖品数量<?php echo $lang['nc_colon']; ?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop ">
              <input id="prize_amount" name="prize_amount" type="text" class="txt" value="" /></td>
        </tr>

      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">
            <a id="submit" href="javascript:void(0)" class="btn"><span>提交</span></a>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">
$("#prize_type").on("change", function(){
    if($("#prize_type").val() == 1){
        $("#tr_prize_points_title").show();
        $("#tr_prize_points_input").show();
    }else{
        $("#tr_prize_points_title").hide();
        $("#tr_prize_points_input").hide();
    }
});
$(function(){
    $('#submit').click(function(){
        $('#add_form').submit();
    });

    $('#add_form').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        success: function(label){
            label.addClass('valid');
        },
        rules : {
            awards_name: {
                required : true,
                maxlength : 20
            },
            prize_name: {
                required : true,
                maxlength : 20
            },
            prize_type: {
                required : true
            },
            prize_points: {
                required : $("#prize_type")==1,
                digits: true
            },
            win_rate: {
                required : true,
                range:[0,1]
            },
			prize_amount: {
                required : true,
                digits: true
            }
        },
        messages : {
            awards_name: {
                required : "奖项名称不能为空",
                maxlength : "奖项名称长度最多20个字符"
            },
            prize_name: {
                required : "奖品名称不能为空",
                maxlength : "奖品名称长度最多20个字符"
            },
            prize_type: {
                required : "奖品类型不能为空"
            },
            prize_points: {
                required : "积分值不能为空",
                digits: "积分值必须是整数"
            },
			win_rate: {
                required : "中奖率不能为空",
                range : "中奖率为0到1之间的小数"
            },
            prize_amount: {
                required : "奖品数量不能为空",
                digits: "奖品数量必须是整数"
            }
        }
    });
});
</script>
