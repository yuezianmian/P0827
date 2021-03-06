<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>抽奖管理</h3>
      <ul class="tab-base">
          <li><a href="index.php?act=lottery&op=index"><span>管理</span></a></li>
          <li><a href="javascript:void(0);" class="current"><span>中奖列表</span></a></li>
          <li><a href="index.php?act=lottery&op=participant_list"><span>参与记录</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="lottery">
        <input type="hidden" name="op" value="win_list">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label>是否已领奖</label></th>
                <td><select name="is_get">
                        <option value="" <?php if (empty($output['is_get'])){echo 'selected=selected';}?>><?php echo $lang['nc_please_choose'];?></option>
                        <option value="1" <?php if ($output['is_get'] == '1'){echo 'selected=selected';}?>>已领奖</option>
                        <option value="0" <?php if ($output['is_get'] == '0'){echo 'selected=selected';}?>>未领奖</option>
                    </select>
                </td>
                <td><a  id="ncsubmit" href="javascript:void(0);" class="btn-search " title="<?php echo $lang['nc_query']; ?>">&nbsp;</a></td>
            </tr>
            </tbody>
        </table>
    </form>
    <div style="text-align:right;"><a class="btns" href="javascript:void(0);" id="ncexport"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th>编号</th>
        <th>手机号</th>
        <th>中奖时间</th>
        <th>奖项名称</th>
        <th>奖品名称</th>
        <th>区域</th>
        <th>详细地址</th>
        <th>是否已领奖</th>
        <th>领奖时间</th>
        <th style="width:250px;">奖品信息</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['win_list']) && is_array($output['win_list'])){ ?>
      <?php foreach($output['win_list'] as $k => $v){ ?>
      <tr class="hover">
        <td ><?php echo $v['id']; ?></td>
        <td ><?php echo $v['member_mobile_true']; ?></td>
        <td ><?php echo date('Y-m-d H:i',$v['participant_time']); ?></td>
        <td ><?php echo $v['awards_name']; ?></td>
        <td ><?php echo $v['prize_name']; ?></td>
        <td ><?php echo $v['address_area_name']; ?></td>
        <td ><?php echo $v['address_detail']; ?></td>
        <td >
            <?php
            switch ($v['is_get']){
                case '1':
                    echo '已领奖';
                    break;
                case '0':
                    echo '未领奖';
                    break;
            }
            ?>
        </td>
        <td >
          <?php if(!empty($v['get_time'])){ ?>
              <?php echo date('Y-m-d H:i',$v['get_time']); ?>
          <?php } ?>
        </td>
        <td ><?php echo $v['prize_desc']; ?></td>
        <td class="w72">
          <?php  if ($v['is_get'] == 0) { ?>
            <a href="index.php?act=lottery&op=get_prize&id=<?php echo $v['id']; ?>">核销</a>
          <?php } ?>
        </td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="6"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php } ?>
    </tbody>
      <tfoot>
      <?php  if (count($output['win_list'])>0) { ?>
          <tr>
              <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
          </tr>
      <?php } ?>
      </tfoot>
  </table>
</div>
<script type="application/javascript">
    $(".get_prize").click(function() {
        getPrize($(this));
    });
    $('#ncexport').click(function(){
        $('input[name="op"]').val('export_win_list');
        $('#formSearch').submit();
    });
    $('#ncsubmit').click(function(){
        $('input[name="op"]').val('win_list');
        $('#formSearch').submit();
    });
    function getPrize(obj){
        var participant_id = obj.attr("data_id");
        $.getJSON("index.php?act=lottery&op=get_prize&participant_id=" + participant_id,function(res){
            if(res.code == 200){
                alert("核销成功");
                obj.hide();
                obj.parent().prev().prev().text("已领奖");
                obj.parent().prev().text(res.get_time);
            }
        });
    }

</script>

